<?php
/**
 * taxonomy-loker_perusahaan.php — Halaman Arsip Perusahaan & Riwayat Lowongan
 */
get_header();

$term       = get_queried_object();
$term_id    = $term ? $term->term_id : 0;
$co_name    = $term ? $term->name : '';
$co_desc    = $term ? $term->description : '';
$website    = $term_id ? get_term_meta( $term_id, '_perusahaan_website', true ) : '';
$lokasi     = $term_id ? get_term_meta( $term_id, '_perusahaan_lokasi', true ) : '';
$logo_id    = $term_id ? absint( get_term_meta( $term_id, '_perusahaan_logo_id', true ) ) : 0;
$initial    = $co_name ? strtoupper( mb_substr( $co_name, 0, 1 ) ) : '?';

$status_tab = sanitize_key( $_GET['status'] ?? 'all' );
$today      = current_time( 'Y-m-d' );

// Hitung statistik riwayat lowongan perusahaan ini
$all_ids_q = new WP_Query( [
    'post_type'      => 'loker',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
    'tax_query'      => [ [ 'taxonomy' => 'loker_perusahaan', 'field' => 'term_id', 'terms' => $term_id ] ],
] );

$total_published = count( $all_ids_q->posts );
$active_count    = 0;
$closed_count    = 0;

foreach ( $all_ids_q->posts as $pid ) {
    if ( hakajobspro_is_expired( $pid ) ) {
        $closed_count++;
    } else {
        $active_count++;
    }
}

// Query daftar riwayat lowongan dengan filter status (all | open | closed)
$per_page   = absint( get_theme_mod( 'hakajobspro_loker_per_page', 12 ) );
$paged      = max( 1, get_query_var( 'paged' ) );
$meta_query = [];

if ( $status_tab === 'open' ) {
    $meta_query = [
        'relation' => 'OR',
        [ 'key' => '_loker_deadline', 'compare' => 'NOT EXISTS' ],
        [ 'key' => '_loker_deadline', 'value' => '', 'compare' => '=' ],
        [ 'key' => '_loker_deadline', 'value' => $today, 'compare' => '>=', 'type' => 'DATE' ],
    ];
} elseif ( $status_tab === 'closed' ) {
    $meta_query = [
        'relation' => 'AND',
        [ 'key' => '_loker_deadline', 'value' => '', 'compare' => '!=' ],
        [ 'key' => '_loker_deadline', 'value' => $today, 'compare' => '<', 'type' => 'DATE' ],
    ];
}

$history_query = new WP_Query( [
    'post_type'      => 'loker',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'tax_query'      => [ [ 'taxonomy' => 'loker_perusahaan', 'field' => 'term_id', 'terms' => $term_id ] ],
    'meta_query'     => $meta_query,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

$base_url = get_term_link( $term );
?>

<div class="container">
<div class="layout">

  <main role="main">

    <!-- PROFIL PERUSAHAAN & STATISTIK RIWAYAT -->
    <section class="company-profile-card" aria-labelledby="company-heading">
      <div class="company-profile-card__top">
        <?php if ( $logo_id ) : ?>
          <?php echo wp_get_attachment_image( $logo_id, 'company-logo', false, [
              'class' => 'single-loker__logo',
              'alt'   => esc_attr( $co_name ),
          ] ); ?>
        <?php else : ?>
          <div class="single-loker__logo-placeholder" aria-hidden="true">
            <?php echo esc_html( $initial ); ?>
          </div>
        <?php endif; ?>

        <div class="company-profile-card__info">
          <span class="badge badge--tipe" style="margin-bottom:6px;"><?php esc_html_e( 'Arsip Perusahaan', 'hakajobspro' ); ?></span>
          <h1 id="company-heading" class="company-profile-card__title"><?php echo esc_html( $co_name ); ?></h1>
          <div class="company-profile-card__meta">
            <?php if ( $lokasi ) : ?>
              <span><?php echo esc_html( $lokasi ); ?></span>
            <?php endif; ?>
            <?php if ( $website ) : ?>
              <?php if ( $lokasi ) echo ' &bull; '; ?>
              <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener">
                <?php echo esc_html( parse_url( $website, PHP_URL_HOST ) ?: $website ); ?>
              </a>
            <?php endif; ?>
          </div>
          <?php if ( $co_desc ) : ?>
            <p class="company-profile-card__desc"><?php echo esc_html( $co_desc ); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="company-stats-grid">
        <div class="company-stat-box">
          <span class="company-stat-box__num"><?php echo number_format_i18n( $total_published ); ?></span>
          <span class="company-stat-box__label"><?php esc_html_e( 'Total Riwayat Dipublish', 'hakajobspro' ); ?></span>
        </div>
        <div class="company-stat-box company-stat-box--open">
          <span class="company-stat-box__num"><?php echo number_format_i18n( $active_count ); ?></span>
          <span class="company-stat-box__label"><?php esc_html_e( 'Lowongan Masih Buka', 'hakajobspro' ); ?></span>
        </div>
        <div class="company-stat-box company-stat-box--closed">
          <span class="company-stat-box__num"><?php echo number_format_i18n( $closed_count ); ?></span>
          <span class="company-stat-box__label"><?php esc_html_e( 'Sudah Ditutup (Arsip)', 'hakajobspro' ); ?></span>
        </div>
      </div>
    </section>

    <!-- FILTER TAB RIWAYAT LOWONGAN -->
    <div class="section-head" style="margin-top:24px;">
      <h2><?php esc_html_e( 'Riwayat Lowongan yang Pernah Dipublish', 'hakajobspro' ); ?></h2>
    </div>

    <div class="category-filter" role="navigation" aria-label="<?php esc_attr_e( 'Filter status riwayat lowongan', 'hakajobspro' ); ?>">
      <a href="<?php echo esc_url( $base_url ); ?>"
         class="cat-chip <?php echo $status_tab === 'all' ? 'active' : ''; ?>">
        <?php printf( esc_html__( 'Semua Riwayat (%d)', 'hakajobspro' ), $total_published ); ?>
      </a>
      <a href="<?php echo esc_url( add_query_arg( 'status', 'open', $base_url ) ); ?>"
         class="cat-chip <?php echo $status_tab === 'open' ? 'active' : ''; ?>">
        <?php printf( esc_html__( 'Masih Buka (%d)', 'hakajobspro' ), $active_count ); ?>
      </a>
      <a href="<?php echo esc_url( add_query_arg( 'status', 'closed', $base_url ) ); ?>"
         class="cat-chip <?php echo $status_tab === 'closed' ? 'active' : ''; ?>">
        <?php printf( esc_html__( 'Sudah Ditutup (%d)', 'hakajobspro' ), $closed_count ); ?>
      </a>
    </div>

    <?php if ( $history_query->have_posts() ) : ?>
      <div class="loker-list">
        <?php while ( $history_query->have_posts() ) : $history_query->the_post();
          set_query_var( 'hakajobspro_view', 'list' );
          get_template_part( 'template-parts/content', 'loker' );
        endwhile; wp_reset_postdata(); ?>
      </div>

      <?php hakajobspro_pagination( $history_query ); ?>
    <?php else : ?>
      <div class="not-found">
        <h2><?php esc_html_e( 'Tidak ada riwayat lowongan pada filter ini', 'hakajobspro' ); ?></h2>
        <p><?php esc_html_e( 'Silakan pilih tab Semua Riwayat untuk melihat seluruh lowongan yang pernah dipublish perusahaan ini.', 'hakajobspro' ); ?></p>
        <a href="<?php echo esc_url( $base_url ); ?>" class="btn btn--primary">
          <?php esc_html_e( 'Lihat Semua Riwayat', 'hakajobspro' ); ?>
        </a>
      </div>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>


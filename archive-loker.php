<?php
/**
 * archive-loker.php — Halaman arsip semua lowongan kerja + filter
 */
get_header();

$per_page = absint( get_theme_mod( 'hakajobspro_loker_per_page', 12 ) );
$paged    = max( 1, get_query_var( 'paged' ) );
$search   = sanitize_text_field( get_query_var( 's' ) );

// Filter dari URL (atau otomatis dari halaman taksonomi yang sedang dibuka)
$sel_lokasi     = sanitize_key( $_GET['loker_lokasi']     ?? '' );
$sel_kategori   = sanitize_key( $_GET['loker_kategori']   ?? '' );
$sel_pendidikan = sanitize_key( $_GET['loker_pendidikan'] ?? '' );
$sel_tipe       = sanitize_key( $_GET['loker_tipe']       ?? '' );

$queried_term = is_tax( [ 'loker_kategori', 'loker_pendidikan', 'loker_lokasi', 'loker_tipe' ] ) ? get_queried_object() : null;
if ( $queried_term && ! empty( $queried_term->slug ) ) {
    if ( 'loker_kategori' === $queried_term->taxonomy && ! $sel_kategori ) {
        $sel_kategori = $queried_term->slug;
    } elseif ( 'loker_pendidikan' === $queried_term->taxonomy && ! $sel_pendidikan ) {
        $sel_pendidikan = $queried_term->slug;
    } elseif ( 'loker_lokasi' === $queried_term->taxonomy && ! $sel_lokasi ) {
        $sel_lokasi = $queried_term->slug;
    } elseif ( 'loker_tipe' === $queried_term->taxonomy && ! $sel_tipe ) {
        $sel_tipe = $queried_term->slug;
    }
}

// Bangun tax_query dari filter (mendukung taksonomi loker_* maupun kategori/tag Pos lama)
$tax_query = [ 'relation' => 'AND' ];
if ( $sel_lokasi ) {
    $tax_query[] = [
        'relation' => 'OR',
        [ 'taxonomy' => 'loker_lokasi', 'field' => 'slug', 'terms' => $sel_lokasi ],
        [ 'taxonomy' => 'category',     'field' => 'slug', 'terms' => $sel_lokasi ],
        [ 'taxonomy' => 'post_tag',     'field' => 'slug', 'terms' => $sel_lokasi ],
    ];
}
if ( $sel_kategori ) {
    $tax_query[] = [
        'relation' => 'OR',
        [ 'taxonomy' => 'loker_kategori', 'field' => 'slug', 'terms' => $sel_kategori ],
        [ 'taxonomy' => 'category',       'field' => 'slug', 'terms' => $sel_kategori ],
        [ 'taxonomy' => 'post_tag',       'field' => 'slug', 'terms' => $sel_kategori ],
    ];
}
if ( $sel_pendidikan ) {
    $tax_query[] = [
        'relation' => 'OR',
        [ 'taxonomy' => 'loker_pendidikan', 'field' => 'slug', 'terms' => $sel_pendidikan ],
        [ 'taxonomy' => 'category',         'field' => 'slug', 'terms' => $sel_pendidikan ],
        [ 'taxonomy' => 'post_tag',         'field' => 'slug', 'terms' => $sel_pendidikan ],
    ];
}
if ( $sel_tipe ) {
    $tax_query[] = [
        'relation' => 'OR',
        [ 'taxonomy' => 'loker_tipe', 'field' => 'slug', 'terms' => $sel_tipe ],
        [ 'taxonomy' => 'category',   'field' => 'slug', 'terms' => $sel_tipe ],
        [ 'taxonomy' => 'post_tag',   'field' => 'slug', 'terms' => $sel_tipe ],
    ];
}

$loker_query = new WP_Query( [
    'post_type'           => hakajobspro_get_loker_post_types(),
    'posts_per_page'      => $per_page,
    'paged'               => $paged,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'tax_query'           => count( $tax_query ) > 1 ? $tax_query : [],
    's'                   => $search,
    'orderby'             => 'date',
    'order'               => 'DESC',
] );

$archive_heading = get_theme_mod( 'hakajobspro_archive_title', __( 'Semua Lowongan Kerja', 'hakajobspro' ) );
if ( $queried_term && ! empty( $queried_term->name ) ) {
    $archive_heading = sprintf( __( 'Lowongan Kerja: %s', 'hakajobspro' ), $queried_term->name );
}
?>

<div class="container">
<div class="layout">

  <!-- KONTEN UTAMA -->
  <main role="main">

    <!-- Archive header -->
    <div class="archive-header">
      <h1><?php echo esc_html( $archive_heading ); ?></h1>
      <p>
        <?php
        $desc = get_theme_mod( 'hakajobspro_archive_desc', '' );
        echo $desc ? esc_html( $desc ) : '';
        ?>
        <?php if ( $loker_query->found_posts ) : ?>
          <strong><?php echo number_format_i18n( $loker_query->found_posts ); ?></strong>
          <?php esc_html_e( 'lowongan ditemukan.', 'hakajobspro' ); ?>
        <?php endif; ?>
      </p>
    </div>

    <!-- Filter bar -->
    <form class="filter-bar filter-bar--form" method="get"
          action="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>"
          aria-label="<?php esc_attr_e( 'Filter lowongan', 'hakajobspro' ); ?>">

      <input type="hidden" name="post_type" value="loker">

      <!-- Cari kata kunci -->
      <input type="search" name="s"
             value="<?php echo esc_attr( $search ); ?>"
             placeholder="<?php esc_attr_e( 'Kata kunci...', 'hakajobspro' ); ?>"
             aria-label="<?php esc_attr_e( 'Cari kata kunci', 'hakajobspro' ); ?>">

      <?php
      // Filter Kategori Pekerjaan
      $kat_terms = get_terms( [ 'taxonomy' => 'loker_kategori', 'hide_empty' => false ] );
      ?>
      <select name="loker_kategori" aria-label="<?php esc_attr_e( 'Pilih Kategori Pekerjaan', 'hakajobspro' ); ?>">
        <option value=""><?php esc_html_e( 'Semua Kategori', 'hakajobspro' ); ?></option>
        <?php if ( $kat_terms && ! is_wp_error( $kat_terms ) ) : ?>
          <?php foreach ( $kat_terms as $t ) : ?>
            <option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $sel_kategori, $t->slug ); ?>>
              <?php echo esc_html( $t->name ); ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>

      <?php
      // Filter Pendidikan
      $edu_terms = get_terms( [ 'taxonomy' => 'loker_pendidikan', 'hide_empty' => false ] );
      ?>
      <select name="loker_pendidikan" aria-label="<?php esc_attr_e( 'Pilih Pendidikan', 'hakajobspro' ); ?>">
        <option value=""><?php esc_html_e( 'Semua Pendidikan', 'hakajobspro' ); ?></option>
        <?php if ( $edu_terms && ! is_wp_error( $edu_terms ) ) : ?>
          <?php foreach ( $edu_terms as $t ) : ?>
            <option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $sel_pendidikan, $t->slug ); ?>>
              <?php echo esc_html( $t->name ); ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>

      <?php
      // Filter Lokasi
      $lok_terms = get_terms( [ 'taxonomy' => 'loker_lokasi', 'hide_empty' => false ] );
      ?>
      <select name="loker_lokasi" aria-label="<?php esc_attr_e( 'Pilih Kota', 'hakajobspro' ); ?>">
        <option value=""><?php esc_html_e( 'Semua Lokasi', 'hakajobspro' ); ?></option>
        <?php if ( $lok_terms && ! is_wp_error( $lok_terms ) ) : ?>
          <?php foreach ( $lok_terms as $t ) : ?>
            <option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $sel_lokasi, $t->slug ); ?>>
              <?php echo esc_html( $t->name ); ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>

      <?php
      // Filter Tipe Pekerjaan
      $tipe_terms = get_terms( [ 'taxonomy' => 'loker_tipe', 'hide_empty' => false ] );
      ?>
      <select name="loker_tipe" aria-label="<?php esc_attr_e( 'Tipe Pekerjaan', 'hakajobspro' ); ?>">
        <option value=""><?php esc_html_e( 'Semua Tipe', 'hakajobspro' ); ?></option>
        <?php if ( $tipe_terms && ! is_wp_error( $tipe_terms ) ) : ?>
          <?php foreach ( $tipe_terms as $t ) : ?>
            <option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $sel_tipe, $t->slug ); ?>>
              <?php echo esc_html( $t->name ); ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>

      <button type="submit" class="btn btn--primary btn--sm">
        <?php esc_html_e( 'Terapkan Filter', 'hakajobspro' ); ?>
      </button>

      <?php if ( $sel_lokasi || $sel_kategori || $sel_pendidikan || $sel_tipe || $search ) : ?>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="btn btn--outline btn--sm">
        <?php esc_html_e( 'Reset', 'hakajobspro' ); ?>
      </a>
      <?php endif; ?>
    </form>

    <!-- Daftar lowongan -->
    <?php if ( $loker_query->have_posts() ) : ?>
    <div class="loker-list">
      <?php
      $infeed_after = max( 1, absint( get_theme_mod( 'hakajobspro_ad_infeed_after', 3 ) ) );
      $loop_i       = 0;
      while ( $loker_query->have_posts() ) : $loker_query->the_post();
        $loop_i++;
        set_query_var( 'hakajobspro_view', 'list' );
        get_template_part( 'template-parts/content', 'loker' );
        if ( $loop_i === $infeed_after && function_exists( 'hakajobspro_render_ad_slot' ) ) {
            hakajobspro_render_ad_slot( 'infeed' );
        }
      endwhile; wp_reset_postdata(); ?>
    </div>

    <?php hakajobspro_pagination( $loker_query ); ?>

    <?php else : ?>
    <div class="not-found">
      <h2><?php esc_html_e( 'Lowongan tidak ditemukan', 'hakajobspro' ); ?></h2>
      <p><?php esc_html_e( 'Coba ubah filter pencarian atau gunakan kata kunci yang berbeda.', 'hakajobspro' ); ?></p>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="btn btn--primary">
        <?php esc_html_e( 'Lihat Semua Lowongan', 'hakajobspro' ); ?>
      </a>
    </div>
    <?php endif; ?>

  </main>

  <!-- SIDEBAR -->
  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>


<?php
/**
 * Template Name: Direktori Perusahaan
 * page-perusahaan.php — Halaman daftar seluruh perusahaan beserta jumlah riwayat lowongan
 */
get_header();

$search_co = sanitize_text_field( $_GET['cari_perusahaan'] ?? '' );

$terms_args = [
    'taxonomy'   => 'loker_perusahaan',
    'hide_empty' => false,
    'orderby'    => 'count',
    'order'      => 'DESC',
];
if ( $search_co ) {
    $terms_args['search'] = $search_co;
}

$companies = get_terms( $terms_args );
?>

<div class="container">
<div class="layout">

  <main role="main">

    <div class="archive-header">
      <h1><?php the_title(); ?></h1>
      <p><?php esc_html_e( 'Daftar perusahaan beserta arsip dan riwayat seluruh lowongan kerja yang pernah dipublish.', 'hakajobspro' ); ?></p>
    </div>

    <form class="filter-bar" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
      <input type="search"
             name="cari_perusahaan"
             value="<?php echo esc_attr( $search_co ); ?>"
             placeholder="<?php esc_attr_e( 'Cari nama perusahaan...', 'hakajobspro' ); ?>"
             style="flex:1;padding:8px 12px;border:1px solid var(--color-border);border-radius:var(--radius-sm);font-family:var(--font-main);">
      <button type="submit" class="btn btn--primary btn--sm"><?php esc_html_e( 'Cari Perusahaan', 'hakajobspro' ); ?></button>
      <?php if ( $search_co ) : ?>
        <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn--outline btn--sm"><?php esc_html_e( 'Reset', 'hakajobspro' ); ?></a>
      <?php endif; ?>
    </form>

    <?php if ( $companies && ! is_wp_error( $companies ) ) : ?>
      <div class="loker-grid">
        <?php foreach ( $companies as $co ) :
          $lokasi  = get_term_meta( $co->term_id, '_perusahaan_lokasi', true );
          $logo_id = absint( get_term_meta( $co->term_id, '_perusahaan_logo_id', true ) );
          $initial = strtoupper( mb_substr( $co->name, 0, 1 ) );
          $co_url  = get_term_link( $co );
        ?>
        <article class="loker-card">
          <div class="loker-card__top">
            <?php if ( $logo_id ) : ?>
              <?php echo wp_get_attachment_image( $logo_id, 'company-logo', false, [ 'class' => 'loker-card__logo', 'alt' => esc_attr( $co->name ) ] ); ?>
            <?php else : ?>
              <div class="loker-card__logo-placeholder" aria-hidden="true"><?php echo esc_html( $initial ); ?></div>
            <?php endif; ?>
            <div class="loker-card__info">
              <h2 class="loker-card__title">
                <a href="<?php echo esc_url( $co_url ); ?>"><?php echo esc_html( $co->name ); ?></a>
              </h2>
              <?php if ( $lokasi ) : ?>
                <div class="loker-card__company"><?php echo esc_html( $lokasi ); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="loker-card__footer">
            <span class="loker-card__deadline">
              <strong><?php echo number_format_i18n( $co->count ); ?></strong> <?php esc_html_e( 'Riwayat Lowongan', 'hakajobspro' ); ?>
            </span>
            <a href="<?php echo esc_url( $co_url ); ?>" class="btn btn--outline btn--sm">
              <?php esc_html_e( 'Lihat Riwayat', 'hakajobspro' ); ?>
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="not-found">
        <h2><?php esc_html_e( 'Perusahaan belum ditemukan', 'hakajobspro' ); ?></h2>
        <p><?php esc_html_e( 'Belum ada arsip perusahaan yang sesuai dengan pencarian Anda.', 'hakajobspro' ); ?></p>
      </div>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>


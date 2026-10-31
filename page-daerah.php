<?php
/**
 * Template Name: Direktori Daerah & Lokasi
 * page-daerah.php — Halaman Direktori Daftar Daerah / Lokasi Lowongan (/daerah/ atau /lokasi/)
 */
get_header();

$search_lok = sanitize_text_field( $_GET['q'] ?? '' );

$term_args = [
    'taxonomy'   => 'loker_lokasi',
    'hide_empty' => false,
];
if ( $search_lok ) {
    $term_args['name__like'] = $search_lok;
}

$locations = get_terms( $term_args );
if ( is_wp_error( $locations ) ) {
    $locations = [];
}

// Urutkan daerah yang memiliki lowongan terbanyak di paling atas, lalu urut abjad
usort( $locations, function( $a, $b ) {
    if ( (int) $b->count !== (int) $a->count ) {
        return (int) $b->count - (int) $a->count;
    }
    return strcasecmp( $a->name, $b->name );
} );
?>

<div class="container">
<div class="layout">

  <main role="main">

    <div class="archive-header">
      <h1><?php esc_html_e( 'Lowongan Kerja Berdasarkan Daerah / Lokasi', 'hakajobspro' ); ?></h1>
      <p>
        <?php esc_html_e( 'Pilih kota, kabupaten, atau wilayah penempatan kerja untuk melihat daftar lowongan terbaru di daerah tersebut.', 'hakajobspro' ); ?>
        <?php if ( ! empty( $locations ) ) : ?>
          <strong>(<?php echo number_format_i18n( count( $locations ) ); ?> <?php esc_html_e( 'Wilayah', 'hakajobspro' ); ?>)</strong>
        <?php endif; ?>
      </p>
    </div>

    <!-- Form Pencarian Daerah -->
    <form class="filter-bar filter-bar--form" method="get" action="<?php echo esc_url( home_url( '/daerah/' ) ); ?>" role="search" style="grid-template-columns: 1fr auto;">
      <input
        type="search"
        name="q"
        value="<?php echo esc_attr( $search_lok ); ?>"
        placeholder="<?php esc_attr_e( 'Cari nama kota atau daerah (contoh: Bekasi, Jakarta, Bandar Lampung)...', 'hakajobspro' ); ?>"
        aria-label="<?php esc_attr_e( 'Cari nama daerah', 'hakajobspro' ); ?>"
      >
      <div class="filter-bar__actions">
        <button type="submit" class="btn btn--primary">
          <?php esc_html_e( 'Cari Daerah', 'hakajobspro' ); ?>
        </button>
        <?php if ( $search_lok ) : ?>
        <a href="<?php echo esc_url( home_url( '/daerah/' ) ); ?>" class="btn btn--outline">
          <?php esc_html_e( 'Reset', 'hakajobspro' ); ?>
        </a>
        <?php endif; ?>
      </div>
    </form>

    <?php if ( ! empty( $locations ) ) : ?>
      <div class="loker-grid">
        <?php foreach ( $locations as $lok_term ) :
          $lok_name = $lok_term->name;
          $lok_url  = get_term_link( $lok_term );
          $count    = (int) $lok_term->count;
        ?>
        <article class="loker-card" aria-label="<?php echo esc_attr( $lok_name ); ?>">
          <div class="loker-card__top">
            <div class="loker-card__logo-placeholder" style="background:rgba(230,57,70,0.08);color:var(--color-primary);border-color:rgba(230,57,70,0.2);" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>

            <div class="loker-card__meta">
              <div class="loker-card__badges">
                <span class="badge <?php echo $count > 0 ? 'badge--new' : 'badge--tipe'; ?>">
                  <?php printf( esc_html__( '%d Lowongan', 'hakajobspro' ), $count ); ?>
                </span>
              </div>
              <h2 class="loker-card__title">
                <a href="<?php echo esc_url( $lok_url ); ?>"><?php echo esc_html( $lok_name ); ?></a>
              </h2>
              <div class="loker-card__company">
                <?php printf( esc_html__( 'Area Penempatan %s', 'hakajobspro' ), esc_html( $lok_name ) ); ?>
              </div>
            </div>
          </div>

          <div class="loker-card__footer">
            <span class="loker-card__deadline">
              <?php echo $count > 0 ? esc_html__( 'Tersedia Lowongan Aktif', 'hakajobspro' ) : esc_html__( 'Arsip Wilayah', 'hakajobspro' ); ?>
            </span>
            <a href="<?php echo esc_url( $lok_url ); ?>" class="btn btn--primary btn--sm">
              <?php esc_html_e( 'Lihat Loker', 'hakajobspro' ); ?>
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="not-found">
        <h2><?php esc_html_e( 'Daerah Tidak Ditemukan', 'hakajobspro' ); ?></h2>
        <p><?php esc_html_e( 'Belum ada wilayah yang cocok dengan kata kunci pencarian Anda.', 'hakajobspro' ); ?></p>
      </div>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>


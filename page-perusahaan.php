<?php
/**
 * Template Name: Direktori Perusahaan
 * page-perusahaan.php — Halaman Direktori Daftar Semua Perusahaan (/perusahaan/)
 */
get_header();

$search_co = sanitize_text_field( $_GET['q'] ?? '' );

$term_args = [
    'taxonomy'   => 'loker_perusahaan',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
];
if ( $search_co ) {
    $term_args['name__like'] = $search_co;
}

$companies = get_terms( $term_args );
if ( is_wp_error( $companies ) ) {
    $companies = [];
}
?>

<div class="container">
<div class="layout">

  <main role="main">

    <div class="archive-header">
      <h1><?php esc_html_e( 'Daftar Perusahaan', 'hakajobspro' ); ?></h1>
      <p>
        <?php esc_html_e( 'Telusuri daftar perusahaan resmi beserta riwayat lowongan kerja yang pernah dipublikasikan.', 'hakajobspro' ); ?>
        <?php if ( ! empty( $companies ) ) : ?>
          <strong>(<?php echo number_format_i18n( count( $companies ) ); ?> <?php esc_html_e( 'Perusahaan', 'hakajobspro' ); ?>)</strong>
        <?php endif; ?>
      </p>
    </div>

    <!-- Form Pencarian Perusahaan -->
    <form class="filter-bar filter-bar--form" method="get" action="<?php echo esc_url( home_url( '/perusahaan/' ) ); ?>" role="search" style="grid-template-columns: 1fr auto;">
      <input
        type="search"
        name="q"
        value="<?php echo esc_attr( $search_co ); ?>"
        placeholder="<?php esc_attr_e( 'Cari nama perusahaan (contoh: PT Prakarsa Alam Segar)...', 'hakajobspro' ); ?>"
        aria-label="<?php esc_attr_e( 'Cari nama perusahaan', 'hakajobspro' ); ?>"
      >
      <div class="filter-bar__actions">
        <button type="submit" class="btn btn--primary">
          <?php esc_html_e( 'Cari Perusahaan', 'hakajobspro' ); ?>
        </button>
        <?php if ( $search_co ) : ?>
        <a href="<?php echo esc_url( home_url( '/perusahaan/' ) ); ?>" class="btn btn--outline">
          <?php esc_html_e( 'Reset', 'hakajobspro' ); ?>
        </a>
        <?php endif; ?>
      </div>
    </form>

    <?php if ( ! empty( $companies ) ) : ?>
      <div class="loker-grid">
        <?php foreach ( $companies as $co_term ) :
          $term_id  = $co_term->term_id;
          $co_name  = $co_term->name;
          $co_url   = get_term_link( $co_term );
          $lokasi   = get_term_meta( $term_id, '_perusahaan_lokasi', true );
          $logo_id  = absint( get_term_meta( $term_id, '_perusahaan_logo_id', true ) );
          $initial  = $co_name ? strtoupper( mb_substr( $co_name, 0, 1 ) ) : 'P';

          // Fallback otomatis: ambil logo (Featured Image) & lokasi dari post loker terbaru milik perusahaan ini
          if ( ! $logo_id || ! $lokasi ) {
              $latest_loker = get_posts( [
                  'post_type'      => 'loker',
                  'posts_per_page' => 1,
                  'post_status'    => 'publish',
                  'fields'         => 'ids',
                  'tax_query'      => [
                      [
                          'taxonomy' => 'loker_perusahaan',
                          'field'    => 'term_id',
                          'terms'    => $term_id,
                      ],
                  ],
              ] );
              if ( ! empty( $latest_loker ) ) {
                  $loker_id = $latest_loker[0];
                  if ( ! $logo_id && has_post_thumbnail( $loker_id ) ) {
                      $logo_id = get_post_thumbnail_id( $loker_id );
                  }
                  if ( ! $lokasi ) {
                      $lokasi = hakajobspro_get_loker_lokasi( $loker_id );
                  }
              }
          }
        ?>
        <article class="loker-card" aria-label="<?php echo esc_attr( $co_name ); ?>">
          <div class="loker-card__top">
            <?php if ( $logo_id ) : ?>
              <?php echo wp_get_attachment_image( $logo_id, 'company-logo', false, [
                  'class' => 'loker-card__logo',
                  'alt'   => esc_attr( $co_name ),
              ] ); ?>
            <?php else : ?>
              <div class="loker-card__logo-placeholder" aria-hidden="true">
                <?php echo esc_html( $initial ); ?>
              </div>
            <?php endif; ?>

            <div class="loker-card__meta">
              <div class="loker-card__badges">
                <span class="badge badge--tipe">
                  <?php printf( esc_html__( '%d Lowongan', 'hakajobspro' ), (int) $co_term->count ); ?>
                </span>
              </div>
              <h2 class="loker-card__title">
                <a href="<?php echo esc_url( $co_url ); ?>"><?php echo esc_html( $co_name ); ?></a>
              </h2>
              <?php if ( $lokasi ) : ?>
              <div class="loker-card__company">
                <?php echo esc_html( $lokasi ); ?>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="loker-card__footer">
            <span class="loker-card__deadline">
              <?php esc_html_e( 'Arsip & Riwayat Loker', 'hakajobspro' ); ?>
            </span>
            <a href="<?php echo esc_url( $co_url ); ?>" class="btn btn--primary btn--sm">
              <?php esc_html_e( 'Lihat Perusahaan', 'hakajobspro' ); ?>
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="not-found">
        <h2><?php esc_html_e( 'Perusahaan Tidak Ditemukan', 'hakajobspro' ); ?></h2>
        <p><?php esc_html_e( 'Belum ada perusahaan yang cocok dengan pencarian Anda.', 'hakajobspro' ); ?></p>
      </div>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>

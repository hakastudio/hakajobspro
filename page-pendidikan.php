<?php
/**
 * Template Name: Direktori Pendidikan
 * page-pendidikan.php — Halaman Direktori Jenjang Pendidikan Lowongan (/pendidikan/)
 */
get_header();

$edu_terms = get_terms( [
    'taxonomy'   => 'loker_pendidikan',
    'hide_empty' => false,
] );
if ( is_wp_error( $edu_terms ) ) {
    $edu_terms = [];
}

// Urutkan jenjang yang memiliki lowongan terbanyak di paling atas
usort( $edu_terms, function( $a, $b ) {
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
      <h1><?php esc_html_e( 'Lowongan Kerja Berdasarkan Pendidikan', 'hakajobspro' ); ?></h1>
      <p>
        <?php esc_html_e( 'Pilih kualifikasi jenjang pendidikan terakhir Anda (SMA/SMK, D3, S1/D4, SMP, hingga S2) untuk menemukan lowongan yang sesuai.', 'hakajobspro' ); ?>
      </p>
    </div>

    <?php if ( ! empty( $edu_terms ) ) : ?>
      <div class="loker-grid">
        <?php foreach ( $edu_terms as $edu_term ) :
          $edu_name = $edu_term->name;
          $edu_url  = get_term_link( $edu_term );
          $count    = (int) $edu_term->count;
        ?>
        <article class="loker-card" aria-label="<?php echo esc_attr( $edu_name ); ?>">
          <div class="loker-card__top">
            <div class="loker-card__logo-placeholder" style="background:rgba(29,53,87,0.08);color:var(--color-dark);border-color:rgba(29,53,87,0.2);" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>

            <div class="loker-card__meta">
              <div class="loker-card__badges">
                <span class="badge <?php echo $count > 0 ? 'badge--new' : 'badge--tipe'; ?>">
                  <?php printf( esc_html__( '%d Lowongan', 'hakajobspro' ), $count ); ?>
                </span>
              </div>
              <h2 class="loker-card__title">
                <a href="<?php echo esc_url( $edu_url ); ?>"><?php printf( esc_html__( 'Loker %s', 'hakajobspro' ), esc_html( $edu_name ) ); ?></a>
              </h2>
              <div class="loker-card__company">
                <?php printf( esc_html__( 'Kualifikasi Lulusan %s', 'hakajobspro' ), esc_html( $edu_name ) ); ?>
              </div>
            </div>
          </div>

          <div class="loker-card__footer">
            <span class="loker-card__deadline">
              <?php echo $count > 0 ? esc_html__( 'Tersedia Lowongan Aktif', 'hakajobspro' ) : esc_html__( 'Kategori Pendidikan', 'hakajobspro' ); ?>
            </span>
            <a href="<?php echo esc_url( $edu_url ); ?>" class="btn btn--primary btn--sm">
              <?php esc_html_e( 'Lihat Loker', 'hakajobspro' ); ?>
            </a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="not-found">
        <h2><?php esc_html_e( 'Belum Ada Kategori Pendidikan', 'hakajobspro' ); ?></h2>
      </div>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>


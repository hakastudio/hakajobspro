<?php
/**
 * template-parts/content-loker.php
 * Kartu loker — digunakan di beranda, arsip, dan hasil pencarian.
 * @param string $hakajobspro_view 'grid' (default) | 'list'
 */

$post_id  = get_the_ID();
$view     = isset( $hakajobspro_view ) ? $hakajobspro_view : 'grid';
$featured = get_post_meta( $post_id, '_loker_featured', true ) === 'Ya';
$expired  = hakajobspro_is_expired( $post_id );

$perusahaan     = get_post_meta( $post_id, '_loker_perusahaan', true );
$perusahaan_url = hakajobspro_get_perusahaan_url( $post_id );
$tipe_kerja     = hakajobspro_get_loker_tipe( $post_id );
$lokasi_det     = hakajobspro_get_loker_lokasi( $post_id );
$pendidikan     = hakajobspro_get_loker_pendidikan( $post_id );
$gaji_min       = get_post_meta( $post_id, '_loker_gaji_min', true );
$gaji_max       = get_post_meta( $post_id, '_loker_gaji_max', true );
$deadline       = get_post_meta( $post_id, '_loker_deadline', true );

$show_salary   = get_theme_mod( 'hakajobspro_show_salary', true );
$show_deadline = get_theme_mod( 'hakajobspro_show_deadline', true );

// Logo perusahaan (thumbnail)
$has_thumb = has_post_thumbnail( $post_id );

// Inisial perusahaan untuk placeholder logo
$initial = $perusahaan ? strtoupper( mb_substr( $perusahaan, 0, 1 ) ) : '?';

// Format gaji
$gaji_str = $show_salary ? hakajobspro_format_gaji( $gaji_min, $gaji_max ) : '';

// Format deadline
$deadline_str = '';
if ( $show_deadline && $deadline ) {
    $deadline_str = date_i18n( 'd M Y', strtotime( $deadline ) );
}

$card_class = 'loker-card';
if ( $featured && ! $expired ) $card_class .= ' loker-card--featured';
if ( $expired )                $card_class .= ' loker-card--expired';
if ( $view === 'list' ) {
    $card_class = 'loker-list-item';
    if ( $featured && ! $expired ) $card_class .= ' loker-list-item--featured';
    if ( $expired )                $card_class .= ' loker-list-item--expired';
}
?>

<?php if ( $view === 'list' ) : ?>
<article <?php post_class( $card_class ); ?> aria-label="<?php echo esc_attr( get_the_title() ); ?>">

  <!-- Logo placeholder atau thumbnail -->
  <?php if ( $has_thumb ) : ?>
    <?php the_post_thumbnail( 'company-logo', [ 'class' => 'loker-card__logo', 'alt' => esc_attr( $perusahaan ) ] ); ?>
  <?php else : ?>
    <div class="loker-card__logo-placeholder" aria-hidden="true"><?php echo esc_html( $initial ); ?></div>
  <?php endif; ?>

  <div class="loker-list-item__body">
    <div class="loker-list-item__main">
      <div class="loker-card__badges" style="margin-bottom:5px;">
        <?php echo hakajobspro_badges( $post_id ); ?>
        <?php if ( $tipe_kerja ) : ?>
          <span class="badge badge--tipe"><?php echo esc_html( $tipe_kerja ); ?></span>
        <?php endif; ?>
      </div>
      <h2 class="loker-list-item__title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
      <div class="loker-list-item__company">
        <?php if ( $perusahaan && $perusahaan_url ) : ?>
          <a href="<?php echo esc_url( $perusahaan_url ); ?>" style="color:inherit;font-weight:600;"><?php echo esc_html( $perusahaan ); ?></a>
        <?php else : ?>
          <?php echo esc_html( $perusahaan ); ?>
        <?php endif; ?>
        &bull; <span><?php printf( esc_html__( 'Tayang %s', 'hakajobspro' ), esc_html( get_the_date( 'd M Y' ) ) ); ?></span>
      </div>
      <div class="loker-list-item__tags">
        <?php if ( $lokasi_det ) : ?>
          <span class="cat-chip" style="pointer-events:none;border-radius:4px;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <?php echo esc_html( $lokasi_det ); ?>
          </span>
        <?php endif; ?>
        <?php if ( $gaji_str ) : ?>
          <span class="loker-card__salary"><?php echo esc_html( $gaji_str ); ?></span>
        <?php endif; ?>
      </div>
    </div>

    <div class="loker-list-item__right">
      <?php if ( $deadline_str ) : ?>
        <div class="loker-card__deadline">
          <?php if ( $expired ) : ?>
            <span style="color:#c62828;font-weight:600;"><?php esc_html_e( 'Ditutup:', 'hakajobspro' ); ?> <?php echo esc_html( $deadline_str ); ?></span>
          <?php else : ?>
            <?php esc_html_e( 'Batas:', 'hakajobspro' ); ?> <strong><?php echo esc_html( $deadline_str ); ?></strong>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if ( ! $expired ) : ?>
        <a href="<?php the_permalink(); ?>" class="btn btn--primary btn--sm">
          <?php echo esc_html( get_theme_mod( 'hakajobspro_cta_apply_text', __( 'Lihat Loker', 'hakajobspro' ) ) ); ?>
        </a>
      <?php else : ?>
        <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
          <?php esc_html_e( 'Lihat Arsip', 'hakajobspro' ); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>

</article>

<?php else : // Grid view ?>

<article <?php post_class( $card_class ); ?> aria-label="<?php echo esc_attr( get_the_title() ); ?>">

  <div class="loker-card__top">
    <?php if ( $has_thumb ) : ?>
      <?php the_post_thumbnail( 'company-logo', [ 'class' => 'loker-card__logo', 'alt' => esc_attr( $perusahaan ) ] ); ?>
    <?php else : ?>
      <div class="loker-card__logo-placeholder" aria-hidden="true"><?php echo esc_html( $initial ); ?></div>
    <?php endif; ?>

    <div class="loker-card__info">
      <h2 class="loker-card__title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>
      <?php if ( $perusahaan ) : ?>
        <div class="loker-card__company">
          <?php if ( $perusahaan_url ) : ?>
            <a href="<?php echo esc_url( $perusahaan_url ); ?>" style="color:inherit;"><?php echo esc_html( $perusahaan ); ?></a>
          <?php else : ?>
            <?php echo esc_html( $perusahaan ); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="loker-card__badges">
    <?php echo hakajobspro_badges( $post_id ); ?>
    <?php if ( $tipe_kerja ) : ?>
      <span class="badge badge--tipe"><?php echo esc_html( $tipe_kerja ); ?></span>
    <?php endif; ?>
  </div>

  <div class="loker-card__meta">
    <?php if ( $lokasi_det ) : ?>
    <div class="loker-card__meta-row">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      <span><?php echo esc_html( $lokasi_det ); ?></span>
    </div>
    <?php endif; ?>

    <?php if ( $gaji_str ) : ?>
    <div class="loker-card__meta-row">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      <span class="loker-card__salary"><?php echo esc_html( $gaji_str ); ?></span>
    </div>
    <?php endif; ?>
  </div>

  <div class="loker-card__footer">
    <?php if ( $deadline_str ) : ?>
      <span class="loker-card__deadline">
        <?php esc_html_e( 'Tutup:', 'hakajobspro' ); ?> <strong><?php echo esc_html( $deadline_str ); ?></strong>
      </span>
    <?php else : ?>
      <span class="loker-card__deadline">
        <?php echo esc_html( get_the_date() ); ?>
      </span>
    <?php endif; ?>

    <?php if ( ! $expired ) : ?>
      <a href="<?php the_permalink(); ?>" class="btn btn--primary btn--sm">
        <?php echo esc_html( get_theme_mod( 'hakajobspro_cta_apply_text', __( 'Lihat Loker', 'hakajobspro' ) ) ); ?>
      </a>
    <?php else : ?>
      <span class="badge badge--expired"><?php esc_html_e( 'Ditutup', 'hakajobspro' ); ?></span>
    <?php endif; ?>
  </div>

</article>
<?php endif; ?>


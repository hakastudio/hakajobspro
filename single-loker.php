<?php
/**
 * single-loker.php — Halaman detail lowongan kerja
 */
get_header();

while ( have_posts() ) : the_post();
    $post_id    = get_the_ID();
    $perusahaan = get_post_meta( $post_id, '_loker_perusahaan', true );
    $website    = get_post_meta( $post_id, '_loker_website', true );
    $lokasi     = get_post_meta( $post_id, '_loker_lokasi_detail', true );
    $tipe       = get_post_meta( $post_id, '_loker_tipe_kerja', true );
    $gaji_min   = get_post_meta( $post_id, '_loker_gaji_min', true );
    $gaji_max   = get_post_meta( $post_id, '_loker_gaji_max', true );
    $pendidikan = get_post_meta( $post_id, '_loker_pendidikan', true );
    $pengalaman = get_post_meta( $post_id, '_loker_pengalaman', true );
    $deadline   = get_post_meta( $post_id, '_loker_deadline', true );
    $link_lamar = get_post_meta( $post_id, '_loker_link_lamar', true );
    $cara       = get_post_meta( $post_id, '_loker_cara_melamar', true );

    $expired        = hakajobspro_is_expired( $post_id );
    $gaji_str       = hakajobspro_format_gaji( $gaji_min, $gaji_max );
    $initial        = $perusahaan ? strtoupper( mb_substr( $perusahaan, 0, 1 ) ) : '?';
    $perusahaan_url = hakajobspro_get_perusahaan_url( $post_id );
?>

<div class="container">
<div class="layout">
<main role="main">

  <?php hakajobspro_render_admin_wa_bar( $post_id ); ?>

  <article class="single-loker<?php echo $expired ? ' single-loker--expired' : ''; ?>">

    <?php if ( $expired ) : ?>
    <!-- BANNER PENUTUPAN OTOMATIS -->
    <div class="closed-alert" role="alert">
      <strong><?php esc_html_e( 'Pendaftaran Lowongan Telah Ditutup', 'hakajobspro' ); ?></strong>
      <p>
        <?php echo esc_html( get_theme_mod( 'hakajobspro_closed_notice', __( 'Pendaftaran untuk lowongan ini telah ditutup secara otomatis karena sudah melewati batas waktu pendaftaran. Artikel ini disimpan sebagai arsip riwayat lowongan perusahaan.', 'hakajobspro' ) ) ); ?>
      </p>
    </div>
    <?php endif; ?>

    <!-- HEADER DETAIL -->
    <header class="single-loker__header">

      <?php if ( has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'company-logo', [
            'class' => 'single-loker__logo',
            'alt'   => esc_attr( $perusahaan ),
        ] ); ?>
      <?php else : ?>
        <div class="single-loker__logo-placeholder" aria-hidden="true">
          <?php echo esc_html( $initial ); ?>
        </div>
      <?php endif; ?>

      <div class="single-loker__title-area">
        <div class="single-loker__badges" style="margin-bottom:8px;">
          <?php echo hakajobspro_badges( $post_id ); ?>
          <?php if ( $tipe ) : ?>
            <span class="badge badge--tipe"><?php echo esc_html( $tipe ); ?></span>
          <?php endif; ?>
        </div>

        <h1 class="single-loker__title"><?php the_title(); ?></h1>

        <?php if ( $perusahaan ) : ?>
        <div class="single-loker__company">
          <?php if ( $perusahaan_url ) : ?>
            <a href="<?php echo esc_url( $perusahaan_url ); ?>" style="color:var(--color-dark);font-weight:700;">
              <?php echo esc_html( $perusahaan ); ?>
            </a>
            &nbsp;&bull;&nbsp;
            <a href="<?php echo esc_url( $perusahaan_url ); ?>">
              <?php esc_html_e( 'Lihat Riwayat Lowongan Perusahaan', 'hakajobspro' ); ?>
            </a>
          <?php else : ?>
            <span><?php echo esc_html( $perusahaan ); ?></span>
          <?php endif; ?>
          <?php if ( $website ) : ?>
            &nbsp;&mdash;&nbsp;
            <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener">
              <?php esc_html_e( 'Website Resmi', 'hakajobspro' ); ?>
            </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Share buttons sederhana -->
        <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
          <a href="https://wa.me/?text=<?php echo urlencode( get_the_title() . ' - ' . get_permalink() ); ?>"
             target="_blank" rel="noopener"
             class="btn btn--outline btn--sm"
             aria-label="<?php esc_attr_e( 'Bagikan via WhatsApp', 'hakajobspro' ); ?>">
            WhatsApp
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
             target="_blank" rel="noopener"
             class="btn btn--outline btn--sm"
             aria-label="<?php esc_attr_e( 'Bagikan di Facebook', 'hakajobspro' ); ?>">
            Facebook
          </a>
        </div>
      </div>
    </header>

    <?php if ( function_exists( 'hakajobspro_render_ad_slot' ) ) hakajobspro_render_ad_slot( 'single_top' ); ?>

    <!-- GRID INFO DETAIL -->
    <div class="loker-detail-grid">

      <?php if ( $lokasi ) : ?>
      <div class="loker-detail-item">
        <span class="loker-detail-item__label"><?php esc_html_e( 'Lokasi', 'hakajobspro' ); ?></span>
        <span class="loker-detail-item__value"><?php echo esc_html( $lokasi ); ?></span>
      </div>
      <?php endif; ?>

      <?php if ( $tipe ) : ?>
      <div class="loker-detail-item">
        <span class="loker-detail-item__label"><?php esc_html_e( 'Tipe Pekerjaan', 'hakajobspro' ); ?></span>
        <span class="loker-detail-item__value"><?php echo esc_html( $tipe ); ?></span>
      </div>
      <?php endif; ?>

      <?php if ( $gaji_str ) : ?>
      <div class="loker-detail-item">
        <span class="loker-detail-item__label"><?php esc_html_e( 'Kisaran Gaji', 'hakajobspro' ); ?></span>
        <span class="loker-detail-item__value loker-detail-item__value--salary"><?php echo esc_html( $gaji_str ); ?></span>
      </div>
      <?php endif; ?>

      <?php if ( $pendidikan ) : ?>
      <div class="loker-detail-item">
        <span class="loker-detail-item__label"><?php esc_html_e( 'Pendidikan Min.', 'hakajobspro' ); ?></span>
        <span class="loker-detail-item__value"><?php echo esc_html( $pendidikan ); ?></span>
      </div>
      <?php endif; ?>

      <?php if ( $pengalaman ) : ?>
      <div class="loker-detail-item">
        <span class="loker-detail-item__label"><?php esc_html_e( 'Pengalaman', 'hakajobspro' ); ?></span>
        <span class="loker-detail-item__value"><?php echo esc_html( $pengalaman ); ?></span>
      </div>
      <?php endif; ?>

      <?php if ( $deadline ) : ?>
      <div class="loker-detail-item">
        <span class="loker-detail-item__label"><?php esc_html_e( 'Batas Pendaftaran', 'hakajobspro' ); ?></span>
        <span class="loker-detail-item__value" style="<?php echo $expired ? 'color:#c62828;' : ''; ?>">
          <?php echo esc_html( date_i18n( 'd F Y', strtotime( $deadline ) ) ); ?>
          <?php if ( $expired ) echo ' (' . esc_html__( 'Sudah ditutup', 'hakajobspro' ) . ')'; ?>
        </span>
      </div>
      <?php endif; ?>

    </div><!-- /loker-detail-grid -->

    <!-- DESKRIPSI LOKER -->
    <div class="single-loker__body">
      <?php
      the_content();

      // Cara melamar dari meta box
      if ( $cara ) :
      ?>
      <h2><?php esc_html_e( 'Cara Melamar', 'hakajobspro' ); ?></h2>
      <p><?php echo nl2br( esc_html( $cara ) ); ?></p>
      <?php endif; ?>

      <!-- AJAKAN GABUNG SALURAN WHATSAPP DI AKHIR ARTIKEL -->
      <?php hakajobspro_render_wa_channel_cta(); ?>
    </div>

    <?php if ( function_exists( 'hakajobspro_render_ad_slot' ) ) hakajobspro_render_ad_slot( 'single_bottom' ); ?>

    <!-- TOMBOL LAMAR -->
    <?php if ( ! $expired && $link_lamar ) : ?>
    <div class="single-loker__apply">
      <div>
        <strong style="display:block;color:var(--color-dark);font-size:15px;">
          <?php echo esc_html( get_theme_mod( 'hakajobspro_cta_apply_text', __( 'Lamar Sekarang', 'hakajobspro' ) ) ); ?>
        </strong>
        <?php if ( $deadline ) : ?>
        <span class="apply-deadline">
          <?php esc_html_e( 'Batas:', 'hakajobspro' ); ?>
          <strong><?php echo esc_html( date_i18n( 'd F Y', strtotime( $deadline ) ) ); ?></strong>
        </span>
        <?php endif; ?>
      </div>
      <a href="<?php echo esc_url( $link_lamar ); ?>"
         class="btn btn--primary btn--lg"
         target="<?php echo strpos( $link_lamar, 'mailto:' ) === 0 ? '_self' : '_blank'; ?>"
         rel="noopener">
        <?php echo esc_html( get_theme_mod( 'hakajobspro_cta_apply_text', __( 'Lamar Sekarang', 'hakajobspro' ) ) ); ?>
      </a>
    </div>
    <?php elseif ( $expired ) : ?>
    <div class="single-loker__apply single-loker__apply--closed">
      <div>
        <strong style="display:block;color:#c62828;font-size:15px;">
          <?php esc_html_e( 'Pendaftaran Sudah Ditutup', 'hakajobspro' ); ?>
        </strong>
        <?php if ( $deadline ) : ?>
        <span class="apply-deadline">
          <?php esc_html_e( 'Batas pendaftaran berakhir pada:', 'hakajobspro' ); ?>
          <strong><?php echo esc_html( date_i18n( 'd F Y', strtotime( $deadline ) ) ); ?></strong>
        </span>
        <?php endif; ?>
      </div>
      <?php if ( $perusahaan_url ) : ?>
      <a href="<?php echo esc_url( $perusahaan_url ); ?>" class="btn btn--dark">
        <?php esc_html_e( 'Lihat Riwayat & Loker Perusahaan Ini', 'hakajobspro' ); ?>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </article>

  <!-- LOKER TERKAIT -->
  <?php
  $kat_ids = wp_list_pluck( get_the_terms( $post_id, 'loker_kategori' ) ?: [], 'term_id' );
  $related = new WP_Query( [
      'post_type'      => 'loker',
      'posts_per_page' => 4,
      'post_status'    => 'publish',
      'post__not_in'   => [ $post_id ],
      'tax_query'      => $kat_ids ? [ [ 'taxonomy' => 'loker_kategori', 'terms' => $kat_ids ] ] : [],
      'orderby'        => 'date',
      'order'          => 'DESC',
  ] );
  if ( $related->have_posts() ) :
  ?>
  <section style="margin-top:28px;" aria-labelledby="related-heading">
    <div class="section-head">
      <h2 id="related-heading"><?php esc_html_e( 'Lowongan Serupa', 'hakajobspro' ); ?></h2>
    </div>
    <div class="loker-list">
      <?php while ( $related->have_posts() ) : $related->the_post();
        set_query_var( 'hakajobspro_view', 'list' );
        get_template_part( 'template-parts/content', 'loker' );
      endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; ?>

</main>

<?php get_sidebar(); ?>

</div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>


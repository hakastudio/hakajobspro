<?php
/**
 * 404.php — Halaman tidak ditemukan
 */
get_header(); ?>
<div class="container" style="padding:40px 20px;">
  <div class="not-found">
    <h1>404</h1>
    <h2><?php esc_html_e( 'Halaman Tidak Ditemukan', 'hakajobspro' ); ?></h2>
    <p><?php esc_html_e( 'Halaman yang Anda cari tidak ada atau telah dipindahkan. Silakan kembali ke beranda atau cari lowongan kerja di bawah ini.', 'hakajobspro' ); ?></p>
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:20px;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary btn--lg">
        <?php esc_html_e( 'Ke Beranda', 'hakajobspro' ); ?>
      </a>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="btn btn--outline btn--lg">
        <?php esc_html_e( 'Lihat Semua Lowongan', 'hakajobspro' ); ?>
      </a>
    </div>
  </div>
</div>
<?php get_footer(); ?>


<?php
/**
 * sidebar.php — Sidebar kanan (Slot Iklan Atas, Loker Populer, Kategori Loker, Widget WP, & Slot Iklan Bawah Sticky)
 */
?>
<aside class="sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'hakajobspro' ); ?>">

  <?php
  // SLOT IKLAN 6: SIDEBAR KANAN ATAS
  if ( function_exists( 'hakajobspro_render_ad_slot' ) ) {
      hakajobspro_render_ad_slot( 'sidebar' );
  }

  // Tampilkan Widget Loker Populer otomatis
  if ( function_exists( 'hakajobspro_render_popular_loker_widget' ) ) {
      hakajobspro_render_popular_loker_widget( 5 );
  }

  // Tampilkan daftar Kategori Loker
  $kat_list = get_terms( [ 'taxonomy' => 'loker_kategori', 'hide_empty' => false ] );
  if ( $kat_list && ! is_wp_error( $kat_list ) ) :
  ?>
  <div class="widget">
    <h3 class="widget-title"><?php esc_html_e( 'Kategori Lowongan', 'hakajobspro' ); ?></h3>
    <div class="widget-content">
      <ul class="widget-loker-list">
        <?php foreach ( $kat_list as $kat ) : ?>
        <li>
          <a href="<?php echo esc_url( get_term_link( $kat ) ); ?>">
            <span><?php echo esc_html( $kat->name ); ?></span>
            <span class="count"><?php echo (int) $kat->count; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>

  <?php
  // Widget tambahan dari WP Admin > Tampilan > Widget (termasuk Widget "Hakajobspro: Slot Iklan AdSense")
  if ( is_active_sidebar( 'sidebar-1' ) ) {
      dynamic_sidebar( 'sidebar-1' );
  }

  // SLOT IKLAN 7: SIDEBAR KANAN BAWAH (STICKY)
  if ( function_exists( 'hakajobspro_render_ad_slot' ) ) {
      hakajobspro_render_ad_slot( 'sidebar_bottom' );
  }
  ?>

</aside>

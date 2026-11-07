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

  // Tampilkan Widget Loker Populer otomatis jika diaktifkan
  if ( get_theme_mod( 'hakajobspro_sidebar_show_popular', true ) && function_exists( 'hakajobspro_render_popular_loker_widget' ) ) {
      $pop_count = absint( get_theme_mod( 'hakajobspro_popular_widget_count', 5 ) ) ?: 5;
      hakajobspro_render_popular_loker_widget( $pop_count );
  }

  // Tampilkan daftar Kategori Loker jika diaktifkan
  if ( get_theme_mod( 'hakajobspro_sidebar_show_categories', true ) ) :
      $hide_empty = (bool) get_theme_mod( 'hakajobspro_sidebar_categories_hide_empty', false );
      $kat_title  = get_theme_mod( 'hakajobspro_sidebar_categories_title', __( 'Kategori Lowongan', 'hakajobspro' ) );
      $show_count = (bool) get_theme_mod( 'hakajobspro_sidebar_categories_show_count', true );
      $kat_max    = absint( get_theme_mod( 'hakajobspro_sidebar_categories_max', 0 ) );

      $term_args = [
          'taxonomy'   => 'loker_kategori',
          'hide_empty' => $hide_empty,
      ];
      if ( $kat_max > 0 ) {
          $term_args['number'] = $kat_max;
      }

      $kat_list = get_terms( $term_args );
      if ( $kat_list && ! is_wp_error( $kat_list ) ) :
      ?>
      <div class="widget">
        <h3 class="widget-title"><?php echo esc_html( $kat_title ); ?></h3>
        <div class="widget-content">
          <ul class="widget-loker-list">
            <?php foreach ( $kat_list as $kat ) : ?>
            <li>
              <a href="<?php echo esc_url( get_term_link( $kat ) ); ?>">
                <span><?php echo esc_html( $kat->name ); ?></span>
                <?php if ( $show_count ) : ?>
                  <?php if ( $kat->count > 0 ) : ?>
                    <span class="count"><?php echo (int) $kat->count; ?></span>
                  <?php else : ?>
                    <span class="count" aria-hidden="true">&rsaquo;</span>
                  <?php endif; ?>
                <?php endif; ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <?php endif; ?>
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

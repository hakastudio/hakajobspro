<?php
/**
 * footer.php — Footer global tema Hakajobspro
 */
?>
</div><!-- #main-content -->

<footer class="site-footer" role="contentinfo" aria-label="<?php esc_attr_e( 'Footer', 'hakajobspro' ); ?>">

  <!-- WIDGET AREA FOOTER -->
  <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
  <div class="footer-widgets">
    <div class="container">
      <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:28px;" class="footer-widgets-grid">

        <!-- Kolom Tentang + Sosial -->
        <div>
          <?php if ( has_custom_logo() ) : ?>
            <div style="margin-bottom:14px;"><?php the_custom_logo(); ?></div>
          <?php else : ?>
            <div style="font-size:20px;font-weight:800;color:white;margin-bottom:10px;">
              <?php bloginfo( 'name' ); ?>
            </div>
          <?php endif; ?>

          <?php $about = get_theme_mod( 'hakajobspro_footer_about', '' ); ?>
          <?php if ( $about ) : ?>
            <p class="footer-about"><?php echo esc_html( $about ); ?></p>
          <?php endif; ?>

          <!-- Media Sosial -->
          <?php
          $socials = [
              'facebook'  => 'Facebook',
              'instagram' => 'Instagram',
              'twitter'   => 'Twitter',
              'youtube'   => 'YouTube',
              'tiktok'    => 'TikTok',
              'telegram'  => 'Telegram',
              'whatsapp'  => 'WhatsApp',
              'linkedin'  => 'LinkedIn',
          ];
          $has_social = false;
          foreach ( $socials as $key => $label ) {
              if ( get_theme_mod( 'hakajobspro_social_' . $key, '' ) ) { $has_social = true; break; }
          }
          if ( $has_social ) :
          ?>
          <div class="footer-social" aria-label="<?php esc_attr_e( 'Media sosial kami', 'hakajobspro' ); ?>">
            <?php foreach ( $socials as $key => $label ) :
                $url = get_theme_mod( 'hakajobspro_social_' . $key, '' );
                if ( ! $url ) continue;
            ?>
            <a href="<?php echo esc_url( $url ); ?>"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php echo esc_attr( $label ); ?>">
              <?php echo hakajobspro_social_icon( $key ); ?>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Widget 1 -->
        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
        <div class="footer-widget-area">
          <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
        <?php endif; ?>

        <!-- Widget 2 -->
        <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
        <div class="footer-widget-area">
          <?php dynamic_sidebar( 'footer-2' ); ?>
        </div>
        <?php endif; ?>

        <!-- Widget 3 + Kontak -->
        <div>
          <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
          <div class="footer-widget-area">
            <?php dynamic_sidebar( 'footer-3' ); ?>
          </div>
          <?php else : ?>
          <h4><?php esc_html_e( 'Hubungi Kami', 'hakajobspro' ); ?></h4>
          <div class="footer-contact">
            <?php $phone = get_theme_mod( 'hakajobspro_phone', '' ); ?>
            <?php if ( $phone ) : ?>
            <div class="footer-contact-item">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>">
                <?php echo esc_html( $phone ); ?>
              </a>
            </div>
            <?php endif; ?>

            <?php $email = get_theme_mod( 'hakajobspro_email', '' ); ?>
            <?php if ( $email ) : ?>
            <div class="footer-contact-item">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <a href="mailto:<?php echo esc_attr( $email ); ?>">
                <?php echo esc_html( $email ); ?>
              </a>
            </div>
            <?php endif; ?>

            <?php $address = get_theme_mod( 'hakajobspro_address', '' ); ?>
            <?php if ( $address ) : ?>
            <div class="footer-contact-item">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span><?php echo nl2br( esc_html( $address ) ); ?></span>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- FOOTER BOTTOM -->
  <div class="footer-bottom">
    <div class="container">
      <?php
      $copyright = get_theme_mod(
          'hakajobspro_footer_copyright',
          sprintf( '&copy; %d %s. Semua hak cipta dilindungi.', date( 'Y' ), get_bloginfo( 'name' ) )
      );
      echo wp_kses_post( $copyright );
      ?>
      &nbsp;&nbsp;|&nbsp;&nbsp;
      <?php
      wp_nav_menu( [
          'theme_location' => 'footer',
          'container'      => false,
          'menu_class'     => '',
          'depth'          => 1,
          'items_wrap'     => '%3$s',
          'walker'         => new Hakajobspro_Inline_Walker(),
          'fallback_cb'    => false,
      ] );
      ?>
    </div>
  </div>

</footer>

<?php if ( get_theme_mod( 'hakajobspro_show_back_to_top', true ) ) : ?>
<button type="button"
        class="scroll-top"
        title="<?php esc_attr_e( 'Kembali ke atas', 'hakajobspro' ); ?>"
        aria-label="<?php esc_attr_e( 'Kembali ke atas', 'hakajobspro' ); ?>">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="18 15 12 9 6 15"/></svg>
</button>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>

<?php
// Walker untuk footer menu inline (pisahkan dengan spasi + karakter pemisah)
class Hakajobspro_Inline_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $output .= '<a href="' . esc_url( $data_object->url ) . '">'
                 . esc_html( $data_object->title ) . '</a> &nbsp;';
    }
}


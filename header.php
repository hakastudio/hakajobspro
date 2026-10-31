<?php
/**
 * header.php — Header global tema Hakajobspro
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- STICKY HEADER WRAPPER (DIAM MENEMPEL DI ATAS SAAT DI-SCROLL) -->
<div class="site-header-sticky-wrap">

  <!-- HEADER -->
  <header class="site-header" role="banner">
    <div class="container">

      <!-- Logo / Branding -->
      <?php if ( has_custom_logo() ) : ?>
        <div class="site-branding">
          <?php the_custom_logo(); ?>
        </div>
      <?php else : ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding" rel="home">
          <span class="site-title-text">
            <?php bloginfo( 'name' ); ?>
          </span>
          <?php
          $tagline_header = get_theme_mod( 'hakajobspro_header_tagline', '' );
          $description    = $tagline_header ?: get_bloginfo( 'description' );
          if ( $description ) :
          ?>
          <span class="site-description-text"><?php echo esc_html( $description ); ?></span>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <?php if ( get_theme_mod( 'hakajobspro_show_search_header', true ) ) : ?>
      <div class="header-search" role="search">
        <form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>">
          <input
            type="search"
            name="s"
            placeholder="<?php esc_attr_e( 'Cari posisi atau perusahaan...', 'hakajobspro' ); ?>"
            value="<?php echo esc_attr( get_search_query() ); ?>"
            aria-label="<?php esc_attr_e( 'Cari lowongan', 'hakajobspro' ); ?>"
          >
          <input type="hidden" name="post_type" value="loker">
          <button type="submit"><?php esc_html_e( 'Cari', 'hakajobspro' ); ?></button>
        </form>
      </div>
      <?php endif; ?>

      <button class="nav-toggle"
              aria-controls="site-navigation"
              aria-expanded="false"
              aria-label="<?php esc_attr_e( 'Buka menu navigasi', 'hakajobspro' ); ?>">
        &#9776;
      </button>

    </div>
  </header>

  <!-- NAVIGATION -->
  <nav class="main-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Menu utama', 'hakajobspro' ); ?>">
    <div class="container">
      <?php
      if ( function_exists( 'hakajobspro_render_header_menu' ) ) {
          hakajobspro_render_header_menu();
      }
      ?>
    </div>
  </nav>

</div><!-- /.site-header-sticky-wrap -->

<!-- Breadcrumb (tersembunyi di halaman utama) -->
<?php if ( ! is_front_page() ) : ?>
<div class="breadcrumb-bar">
  <div class="container">
    <?php hakajobspro_breadcrumb(); ?>
  </div>
</div>
<?php endif; ?>

<div id="main-content">

<?php
// SLOT IKLAN 1: BAWAH HEADER
if ( function_exists( 'hakajobspro_get_ad_slot_html' ) ) :
    $header_ad = hakajobspro_get_ad_slot_html( 'header' );
    if ( $header_ad ) :
?>
<div class="container" style="padding-top:16px;">
  <?php echo $header_ad; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<?php endif; endif; ?>

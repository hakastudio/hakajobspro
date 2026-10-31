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

<a class="skip-link sr-only" href="#main-content">
  <?php esc_html_e( 'Langsung ke konten', 'hakajobspro' ); ?>
</a>

<?php
// --- TOP BAR dengan ticker loker terbaru ---
$show_ticker = get_theme_mod( 'hakajobspro_show_ticker', true );
if ( $show_ticker ) :
    $recent_loker = new WP_Query( [
        'post_type'      => 'loker',
        'posts_per_page' => 5,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ] );
    if ( $recent_loker->have_posts() ) :
        $ticker_label = get_theme_mod( 'hakajobspro_ticker_label', __( 'Terbaru', 'hakajobspro' ) );
?>
<div class="topbar" role="complementary" aria-label="<?php esc_attr_e( 'Informasi terkini', 'hakajobspro' ); ?>">
  <div class="container">
    <div class="topbar-ticker" aria-live="polite" aria-atomic="false">
      <span class="ticker-label"><?php echo esc_html( $ticker_label ); ?></span>
      <div class="ticker-track">
        <span>
          <?php
          $posts_data = get_posts( [
              'post_type'      => 'loker',
              'posts_per_page' => 6,
              'post_status'    => 'publish',
          ] );
          $items = array_map( function( $p ) {
              return '<a href="' . esc_url( get_permalink( $p->ID ) ) . '">'
                   . esc_html( $p->post_title ) . '</a>';
          }, $posts_data );
          echo implode( ' &nbsp;|&nbsp; ', $items );
          ?>
        </span>
      </div>
    </div>
    <div class="topbar-right">
      <?php
      $phone = get_theme_mod( 'hakajobspro_phone', '' );
      if ( $phone ) :
          echo '<a href="tel:' . esc_attr( preg_replace( '/\D/', '', $phone ) ) . '">'
               . esc_html( $phone ) . '</a>';
      endif;
      ?>
    </div>
  </div>
</div>
<?php endif; wp_reset_postdata(); endif; ?>

<!-- HEADER -->
<header class="site-header" role="banner">
  <div class="container">

    <!-- Logo / Branding -->
    <?php if ( has_custom_logo() ) : ?>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding" rel="home"
         aria-label="<?php bloginfo( 'name' ); ?>">
        <?php the_custom_logo(); ?>
      </a>
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
    wp_nav_menu( [
        'theme_location' => 'primary',
        'menu_class'     => 'nav-menu',
        'container'      => false,
        'fallback_cb'    => 'hakajobspro_fallback_nav',
    ] );
    ?>
  </div>
</nav>

<?php
// Fallback menu jika belum ada menu yang di-assign
function hakajobspro_fallback_nav() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Beranda', 'hakajobspro' ) . '</a></li>';
    echo '<li><a href="' . esc_url( get_post_type_archive_link( 'loker' ) ) . '">' . esc_html__( 'Lowongan Kerja', 'hakajobspro' ) . '</a></li>';
    echo '</ul>';
}
?>

<!-- Breadcrumb (tersembunyi di halaman utama) -->
<?php if ( ! is_front_page() ) : ?>
<div class="breadcrumb">
  <div class="container">
    <?php hakajobspro_breadcrumb(); ?>
  </div>
</div>
<?php endif; ?>

<div id="main-content">


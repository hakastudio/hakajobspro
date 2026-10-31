<?php
/**
 * Hakajobspro — functions.php
 * Setup tema, CPT, Customizer, widget areas, menus, enqueue.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'HAKAJOBSPRO_VERSION', wp_get_theme( get_template() )->get( 'Version' ) ?: '1.0.0' );
define( 'HAKAJOBSPRO_DIR',     get_template_directory() );
define( 'HAKAJOBSPRO_URI',     get_template_directory_uri() );

/* ==========================================================================
   THEME SETUP
   ========================================================================== */

function hakajobspro_setup() {
    load_theme_textdomain( 'hakajobspro', HAKAJOBSPRO_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 260,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );

    // Ukuran thumbnail tambahan
    add_image_size( 'loker-card',    400, 280, true );
    add_image_size( 'loker-single',  900, 400, true );
    add_image_size( 'company-logo',  120, 60,  false );

    register_nav_menus( [
        'primary'      => __( 'Menu Header Utama', 'hakajobspro' ),
        'footer_col_1' => __( 'Menu Footer Kolom 1 (Kategori / Rubrik)', 'hakajobspro' ),
        'footer_col_2' => __( 'Menu Footer Kolom 2 (Lokasi / Daerah)', 'hakajobspro' ),
        'footer_col_3' => __( 'Menu Footer Kolom 3 (Informasi)', 'hakajobspro' ),
        'footer'       => __( 'Menu Baris Bawah Footer (Sebelah Copyright)', 'hakajobspro' ),
    ] );
}
add_action( 'after_setup_theme', 'hakajobspro_setup' );

/* ==========================================================================
   CONTENT WIDTH
   ========================================================================== */

function hakajobspro_content_width() {
    $GLOBALS['content_width'] = 900;
}
add_action( 'after_setup_theme', 'hakajobspro_content_width', 0 );

/* ==========================================================================
   WIDGET AREAS
   ========================================================================== */

function hakajobspro_widgets_init() {
    $shared = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar( array_merge( $shared, [
        'name'        => __( 'Sidebar Kanan', 'hakajobspro' ),
        'id'          => 'sidebar-1',
        'description' => __( 'Sidebar halaman utama dan arsip.', 'hakajobspro' ),
    ] ) );

    register_sidebar( array_merge( $shared, [
        'name'        => __( 'Footer Kolom 1', 'hakajobspro' ),
        'id'          => 'footer-1',
        'description' => __( 'Widget kolom pertama footer.', 'hakajobspro' ),
    ] ) );

    register_sidebar( array_merge( $shared, [
        'name'        => __( 'Footer Kolom 2', 'hakajobspro' ),
        'id'          => 'footer-2',
        'description' => __( 'Widget kolom kedua footer.', 'hakajobspro' ),
    ] ) );

    register_sidebar( array_merge( $shared, [
        'name'        => __( 'Footer Kolom 3', 'hakajobspro' ),
        'id'          => 'footer-3',
        'description' => __( 'Widget kolom ketiga footer.', 'hakajobspro' ),
    ] ) );
}
add_action( 'widgets_init', 'hakajobspro_widgets_init' );

/* ==========================================================================
   ENQUEUE SCRIPTS & STYLES
   ========================================================================== */

function hakajobspro_scripts() {
    // Enqueue Google Font jika dipilih di Customizer
    $font_choice = get_theme_mod( 'hakajobspro_font_family', 'system' );
    $google_fonts = [
        'inter'   => 'Inter:wght@400;500;600;700;800',
        'jakarta' => 'Plus+Jakarta+Sans:wght@400;500;600;700;800',
        'poppins' => 'Poppins:wght@400;500;600;700;800',
        'roboto'  => 'Roboto:wght@400;500;700;900',
        'nunito'  => 'Nunito+Sans:wght@400;600;700;800',
    ];
    if ( isset( $google_fonts[ $font_choice ] ) ) {
        wp_enqueue_style(
            'hakajobspro-google-font',
            'https://fonts.googleapis.com/css2?family=' . $google_fonts[ $font_choice ] . '&display=swap',
            [],
            null
        );
    }

    $css_ver = HAKAJOBSPRO_VERSION . '.' . (int) @filemtime( HAKAJOBSPRO_DIR . '/assets/css/main.css' );
    $js_ver  = HAKAJOBSPRO_VERSION . '.' . (int) @filemtime( HAKAJOBSPRO_DIR . '/assets/js/main.js' );

    wp_enqueue_style(
        'hakajobspro-main',
        HAKAJOBSPRO_URI . '/assets/css/main.css',
        [],
        $css_ver
    );

    wp_enqueue_script(
        'hakajobspro-main',
        HAKAJOBSPRO_URI . '/assets/js/main.js',
        [],
        $js_ver,
        true
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Status Anti-Copas
    $anticopas_active = (bool) get_theme_mod( 'hakajobspro_enable_anticopas', true );
    if ( $anticopas_active && get_theme_mod( 'hakajobspro_anticopas_bypass_admin', true ) && current_user_can( 'edit_posts' ) ) {
        $anticopas_active = false;
    }

    // Kirim data PHP ke JS
    wp_localize_script( 'hakajobspro-main', 'hakajobspro', [
        'ajaxurl'      => admin_url( 'admin-ajax.php' ),
        'nonce'        => wp_create_nonce( 'hakajobspro_nonce' ),
        'anticopas'    => $anticopas_active,
        'anticopasMsg' => get_theme_mod( 'hakajobspro_anticopas_msg', __( 'Konten dilindungi. Dilarang menyalin informasi lowongan kerja tanpa izin.', 'hakajobspro' ) ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'hakajobspro_scripts' );

function hakajobspro_body_classes( $classes ) {
    // Hapus class 'single-loker' dari <body> agar tidak bentrok dengan selector kartu <article class="single-loker">
    $classes = array_values( array_diff( $classes, [ 'single-loker' ] ) );

    $anticopas_active = (bool) get_theme_mod( 'hakajobspro_enable_anticopas', true );
    if ( $anticopas_active && get_theme_mod( 'hakajobspro_anticopas_bypass_admin', true ) && current_user_can( 'edit_posts' ) ) {
        $anticopas_active = false;
    }
    if ( $anticopas_active ) {
        $classes[] = 'hakajobspro-anticopas';
    }
    return $classes;
}
add_filter( 'body_class', 'hakajobspro_body_classes' );

/* ==========================================================================
   OUTPUT CSS VARIABEL DARI CUSTOMIZER
   ========================================================================== */

function hakajobspro_customizer_css() {
    $primary     = sanitize_hex_color( get_theme_mod( 'hakajobspro_color_primary',     '#e63946' ) ) ?: '#e63946';
    $dark        = sanitize_hex_color( get_theme_mod( 'hakajobspro_color_dark',        '#1d3557' ) ) ?: '#1d3557';
    $body_bg     = sanitize_hex_color( get_theme_mod( 'hakajobspro_body_bg',           '#f4f6f9' ) ) ?: '#f4f6f9';
    $header_bg   = sanitize_hex_color( get_theme_mod( 'hakajobspro_header_bg',         '#ffffff' ) ) ?: '#ffffff';
    $nav_bg      = sanitize_hex_color( get_theme_mod( 'hakajobspro_nav_bg',            '#1d3557' ) ) ?: '#1d3557';
    $nav_text    = sanitize_hex_color( get_theme_mod( 'hakajobspro_nav_text',          '#ffffff' ) ) ?: '#ffffff';
    $nav_hover   = sanitize_hex_color( get_theme_mod( 'hakajobspro_nav_hover_bg',      '#e63946' ) ) ?: '#e63946';
    $footer_bg   = sanitize_hex_color( get_theme_mod( 'hakajobspro_footer_bg',         '#1d3557' ) ) ?: '#1d3557';
    $footer_text = sanitize_hex_color( get_theme_mod( 'hakajobspro_footer_text_color', '#dce3ed' ) ) ?: '#dce3ed';

    $font_choice = get_theme_mod( 'hakajobspro_font_family', 'system' );
    $font_stacks = [
        'system'  => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif",
        'inter'   => "'Inter', -apple-system, BlinkMacSystemFont, sans-serif",
        'jakarta' => "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif",
        'poppins' => "'Poppins', -apple-system, BlinkMacSystemFont, sans-serif",
        'roboto'  => "'Roboto', -apple-system, BlinkMacSystemFont, sans-serif",
        'nunito'  => "'Nunito Sans', -apple-system, BlinkMacSystemFont, sans-serif",
    ];
    $font_main = isset( $font_stacks[ $font_choice ] ) ? $font_stacks[ $font_choice ] : $font_stacks['system'];

    $font_size       = absint( get_theme_mod( 'hakajobspro_font_size_base', 16 ) ) ?: 16;
    $container_width = absint( get_theme_mod( 'hakajobspro_container_width', 1200 ) ) ?: 1200;
    $logo_h          = absint( get_theme_mod( 'hakajobspro_logo_height', 48 ) ) ?: 48;
    $logo_h_mob      = absint( get_theme_mod( 'hakajobspro_logo_height_mobile', 36 ) ) ?: 36;
    $title_size      = absint( get_theme_mod( 'hakajobspro_site_title_size', 22 ) ) ?: 22;

    ?>
    <style id="hakajobspro-customizer-css">
    :root {
        --color-primary:     <?php echo esc_attr( $primary ); ?>;
        --color-dark:        <?php echo esc_attr( $dark ); ?>;
        --color-bg:          <?php echo esc_attr( $body_bg ); ?>;
        --color-header-bg:   <?php echo esc_attr( $header_bg ); ?>;
        --color-footer-bg:   <?php echo esc_attr( $footer_bg ); ?>;
        --color-footer-text: <?php echo esc_attr( $footer_text ); ?>;
        --font-main:         <?php echo $font_main; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
        --container:         <?php echo (int) $container_width; ?>px;
    }
    html { font-size: <?php echo (int) $font_size; ?>px; overflow-y: auto !important; }
    body, body.single-loker { overflow-x: hidden; overflow-y: visible !important; background: <?php echo esc_attr( $body_bg ); ?>; border: 0; border-radius: 0; }
    article.single-loker { background: #ffffff; border: 1px solid #dee2e6; border-radius: 8px; overflow: visible !important; }
    .single-loker__body, .entry-content { display: block !important; visibility: visible !important; opacity: 1 !important; }
    .main-nav { background: <?php echo esc_attr( $nav_bg ); ?>; }
    .nav-menu > li > a { color: <?php echo esc_attr( $nav_text ); ?>; }
    .nav-menu > li > a:hover,
    .nav-menu > li.current-menu-item > a,
    .nav-menu > li.current-menu-parent > a { background: <?php echo esc_attr( $nav_hover ); ?>; color: #ffffff; }
    .custom-logo { height: <?php echo (int) $logo_h; ?>px; width: auto; }
    .site-title-text { font-size: <?php echo (int) $title_size; ?>px; }
    @media (max-width: 768px) {
        .custom-logo { height: <?php echo (int) $logo_h_mob; ?>px; }
    }
    </style>
    <?php
}
add_action( 'wp_head', 'hakajobspro_customizer_css' );

/* ==========================================================================
   INCLUDES
   ========================================================================== */

require HAKAJOBSPRO_DIR . '/inc/custom-post-types.php';
require HAKAJOBSPRO_DIR . '/inc/menu-manager.php';
require HAKAJOBSPRO_DIR . '/inc/adsense.php';
require HAKAJOBSPRO_DIR . '/inc/customizer.php';
require HAKAJOBSPRO_DIR . '/inc/template-tags.php';
require HAKAJOBSPRO_DIR . '/inc/meta-boxes.php';
require HAKAJOBSPRO_DIR . '/inc/theme-updater.php';


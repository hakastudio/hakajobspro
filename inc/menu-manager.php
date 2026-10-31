<?php
/**
 * Hakajobspro — inc/menu-manager.php
 * Sistem Pengaturan Langsung Menu Header & Menu Footer (4 Kolom + Baris Bawah)
 * Dapat diatur langsung melalui:
 * 1. WP Admin -> Tampilan (Appearance) -> Menu Header & Footer
 * 2. WP Admin -> Tampilan -> Sesuaikan (Customize) -> Menu Header & Footer
 * 3. WP Admin -> Tampilan -> Menu (Bawaan WordPress)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Nilai default teks menu (Format: Nama Menu | URL per baris)
 */
function hakajobspro_get_default_header_menu_text() {
    $home  = home_url( '/' );
    $loker = get_post_type_archive_link( 'loker' ) ?: home_url( '/lowongan/' );
    return implode( "\n", [
        'HOME | ' . $home,
        'LOWONGAN KERJA | ' . $loker,
        'DAERAH / LOKASI | ' . $loker,
        'D3 / S1 | ' . add_query_arg( 'loker_pendidikan', 'd3-s1', $loker ),
        'SMA / SMK | ' . add_query_arg( 'loker_pendidikan', 'sma-smk', $loker ),
    ] );
}

function hakajobspro_get_default_footer_col1_text() {
    $loker = get_post_type_archive_link( 'loker' ) ?: home_url( '/lowongan/' );
    return implode( "\n", [
        'Loker SMA / SMK | ' . add_query_arg( 'loker_pendidikan', 'sma-smk', $loker ),
        'Loker D3 / S1 | ' . add_query_arg( 'loker_pendidikan', 'd3-s1', $loker ),
        'Fresh Graduate | ' . add_query_arg( 'loker_kategori', 'fresh-graduate', $loker ),
        'BUMN & Instansi | ' . add_query_arg( 'loker_kategori', 'bumn-instansi', $loker ),
        'IT & Teknologi | ' . add_query_arg( 'loker_kategori', 'it-teknologi', $loker ),
    ] );
}

function hakajobspro_get_default_footer_col2_text() {
    $loker = get_post_type_archive_link( 'loker' ) ?: home_url( '/lowongan/' );
    return implode( "\n", [
        'Bandar Lampung | ' . add_query_arg( 'loker_lokasi', 'bandar-lampung', $loker ),
        'Kota Metro | ' . add_query_arg( 'loker_lokasi', 'metro', $loker ),
        'Lampung Selatan | ' . add_query_arg( 'loker_lokasi', 'lampung-selatan', $loker ),
        'Lampung Tengah | ' . add_query_arg( 'loker_lokasi', 'lampung-tengah', $loker ),
        'Jakarta & Sekitarnya | ' . add_query_arg( 'loker_lokasi', 'jakarta', $loker ),
    ] );
}

function hakajobspro_get_default_footer_col3_text() {
    $home = home_url( '/' );
    return implode( "\n", [
        'Tentang Kami | ' . $home,
        'Pasang Lowongan | ' . $home,
        'Kebijakan Privasi | ' . $home,
        'Syarat & Ketentuan | ' . $home,
        'Hubungi Kami | ' . $home,
    ] );
}

function hakajobspro_get_default_footer_bottom_text() {
    $home = home_url( '/' );
    return implode( "\n", [
        'Tentang Kami | ' . $home,
        'Kebijakan Privasi | ' . $home,
        'Disclaimer | ' . $home,
        'Kontak | ' . $home,
    ] );
}

/**
 * Parse teks baris "Nama Menu | URL" menjadi array item
 */
function hakajobspro_parse_menu_lines( $raw_text ) {
    $lines = preg_split( '/\r\n|\r|\n/', trim( (string) $raw_text ) );
    $items = [];
    foreach ( $lines as $line ) {
        $line = trim( $line );
        if ( '' === $line ) {
            continue;
        }
        if ( strpos( $line, '|' ) !== false ) {
            list( $label, $url ) = array_map( 'trim', explode( '|', $line, 2 ) );
        } else {
            $label = $line;
            $url   = '#';
        }
        if ( '' !== $label ) {
            $items[] = [
                'label' => $label,
                'url'   => $url ?: '#',
            ];
        }
    }
    return $items;
}

/**
 * Render Menu Header Utama
 */
function hakajobspro_render_header_menu() {
    $use_direct = (bool) get_theme_mod( 'hakajobspro_use_direct_header_menu', false );
    $raw_direct = trim( (string) get_theme_mod( 'hakajobspro_header_menu_items', '' ) );

    // Jika admin mengaktifkan pengaturan menu langsung di Customizer / Menu Header & Footer
    if ( $use_direct && '' !== $raw_direct ) {
        $items = hakajobspro_parse_menu_lines( $raw_direct );
        if ( ! empty( $items ) ) {
            $current_url = trailingslashit( home_url( add_query_arg( [], $GLOBALS['wp']->request ?? '' ) ) );
            echo '<ul class="nav-menu">';
            foreach ( $items as $idx => $item ) {
                $item_url = esc_url( $item['url'] );
                $is_cur   = ( trailingslashit( $item['url'] ) === $current_url && 0 === $idx && is_front_page() )
                            || ( ! is_front_page() && trailingslashit( $item['url'] ) === $current_url );
                $cls      = $is_cur ? ' class="current-menu-item"' : '';
                echo '<li' . $cls . '><a href="' . $item_url . '">' . esc_html( $item['label'] ) . '</a></li>';
            }
            echo '</ul>';
            return;
        }
    }

    // Jika ada menu WordPress yang di-assign ke lokasi 'primary'
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( [
            'theme_location' => 'primary',
            'menu_class'     => 'nav-menu',
            'container'      => false,
            'fallback_cb'    => false,
        ] );
        return;
    }

    // Fallback ke daftar menu langsung (atau default)
    $fallback_raw = '' !== $raw_direct ? $raw_direct : hakajobspro_get_default_header_menu_text();
    $items        = hakajobspro_parse_menu_lines( $fallback_raw );
    echo '<ul class="nav-menu">';
    foreach ( $items as $idx => $item ) {
        $cls = ( 0 === $idx && is_front_page() ) ? ' class="current-menu-item"' : '';
        echo '<li' . $cls . '><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Render daftar link untuk kolom Footer (1, 2, 3)
 */
function hakajobspro_render_footer_column_links( $col_num ) {
    $mod_key = 'hakajobspro_footer_col' . $col_num . '_links';
    $loc_key = 'footer_col_' . $col_num;

    $raw = trim( (string) get_theme_mod( $mod_key, '' ) );

    // 1. Jika diisi melalui Pengaturan Langsung
    if ( '' !== $raw ) {
        $items = hakajobspro_parse_menu_lines( $raw );
        if ( ! empty( $items ) ) {
            echo '<ul class="footer-menu-links">';
            foreach ( $items as $item ) {
                echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a></li>';
            }
            echo '</ul>';
            return;
        }
    }

    // 2. Jika diatur melalui Appearance -> Menus pada lokasi footer_col_1/2/3
    if ( has_nav_menu( $loc_key ) ) {
        wp_nav_menu( [
            'theme_location' => $loc_key,
            'menu_class'     => 'footer-menu-links',
            'container'      => false,
            'depth'          => 1,
            'fallback_cb'    => false,
        ] );
        return;
    }

    // 3. Fallback otomatis yang rapi (Kategori / Lokasi / Informasi)
    if ( 1 === (int) $col_num ) {
        $cats = get_terms( [ 'taxonomy' => 'loker_kategori', 'hide_empty' => false, 'number' => 6 ] );
        if ( $cats && ! is_wp_error( $cats ) ) {
            echo '<ul class="footer-menu-links">';
            foreach ( $cats as $c ) {
                echo '<li><a href="' . esc_url( get_term_link( $c ) ) . '">' . esc_html( $c->name ) . '</a></li>';
            }
            echo '</ul>';
            return;
        }
        $items = hakajobspro_parse_menu_lines( hakajobspro_get_default_footer_col1_text() );
    } elseif ( 2 === (int) $col_num ) {
        $loks = get_terms( [ 'taxonomy' => 'loker_lokasi', 'hide_empty' => false, 'number' => 6 ] );
        if ( $loks && ! is_wp_error( $loks ) ) {
            echo '<ul class="footer-menu-links">';
            foreach ( $loks as $l ) {
                echo '<li><a href="' . esc_url( get_term_link( $l ) ) . '">' . esc_html( $l->name ) . '</a></li>';
            }
            echo '</ul>';
            return;
        }
        $items = hakajobspro_parse_menu_lines( hakajobspro_get_default_footer_col2_text() );
    } else {
        $items = hakajobspro_parse_menu_lines( hakajobspro_get_default_footer_col3_text() );
    }

    echo '<ul class="footer-menu-links">';
    foreach ( $items as $item ) {
        echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Render Menu Baris Bawah Footer (sebelah Copyright)
 */
function hakajobspro_render_footer_bottom_links() {
    $raw = trim( (string) get_theme_mod( 'hakajobspro_footer_bottom_links', '' ) );

    if ( '' !== $raw ) {
        $items = hakajobspro_parse_menu_lines( $raw );
        if ( ! empty( $items ) ) {
            $links = [];
            foreach ( $items as $item ) {
                $links[] = '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
            }
            echo implode( ' &nbsp;&bull;&nbsp; ', $links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            return;
        }
    }

    if ( has_nav_menu( 'footer' ) ) {
        wp_nav_menu( [
            'theme_location' => 'footer',
            'container'      => false,
            'menu_class'     => '',
            'depth'          => 1,
            'items_wrap'     => '%3$s',
            'walker'         => new Hakajobspro_Inline_Walker(),
            'fallback_cb'    => false,
        ] );
        return;
    }

    $items = hakajobspro_parse_menu_lines( hakajobspro_get_default_footer_bottom_text() );
    $links = [];
    foreach ( $items as $item ) {
        $links[] = '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
    }
    echo implode( ' &nbsp;&bull;&nbsp; ', $links ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/* ==========================================================================
   HALAMAN PANEL ADMIN: APPEARANCE -> MENU HEADER & FOOTER
   ========================================================================== */

function hakajobspro_register_menus_admin_page() {
    add_theme_page(
        __( 'Pengaturan Langsung Menu Header & Footer', 'hakajobspro' ),
        __( 'Menu Header & Footer', 'hakajobspro' ),
        'edit_theme_options',
        'hakajobspro-menus',
        'hakajobspro_render_menus_admin_page'
    );
}
add_action( 'admin_menu', 'hakajobspro_register_menus_admin_page' );

function hakajobspro_handle_menus_admin_save() {
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    if ( isset( $_POST['hakajobspro_save_menus'] ) && check_admin_referer( 'hakajobspro_menus_settings_nonce' ) ) {
        set_theme_mod( 'hakajobspro_use_direct_header_menu', isset( $_POST['hakajobspro_use_direct_header_menu'] ) );

        $fields_textarea = [
            'hakajobspro_header_menu_items',
            'hakajobspro_footer_col1_links',
            'hakajobspro_footer_col2_links',
            'hakajobspro_footer_col3_links',
            'hakajobspro_footer_bottom_links',
            'hakajobspro_footer_about',
        ];
        foreach ( $fields_textarea as $f ) {
            if ( isset( $_POST[ $f ] ) ) {
                set_theme_mod( $f, sanitize_textarea_field( wp_unslash( $_POST[ $f ] ) ) );
            }
        }

        $fields_text = [
            'hakajobspro_footer_col1_title',
            'hakajobspro_footer_col2_title',
            'hakajobspro_footer_col3_title',
            'hakajobspro_footer_copyright',
        ];
        foreach ( $fields_text as $f ) {
            if ( isset( $_POST[ $f ] ) ) {
                set_theme_mod( $f, sanitize_text_field( wp_unslash( $_POST[ $f ] ) ) );
            }
        }

        wp_safe_redirect( add_query_arg( [
            'page'  => 'hakajobspro-menus',
            'saved' => '1',
        ], admin_url( 'themes.php' ) ) );
        exit;
    }
}
add_action( 'admin_init', 'hakajobspro_handle_menus_admin_save' );

function hakajobspro_render_menus_admin_page() {
    $use_direct_header = (bool) get_theme_mod( 'hakajobspro_use_direct_header_menu', false );
    $header_items      = (string) get_theme_mod( 'hakajobspro_header_menu_items', hakajobspro_get_default_header_menu_text() );
    $col1_title        = (string) get_theme_mod( 'hakajobspro_footer_col1_title', __( 'Kategori Lowongan', 'hakajobspro' ) );
    $col1_links        = (string) get_theme_mod( 'hakajobspro_footer_col1_links', hakajobspro_get_default_footer_col1_text() );
    $col2_title        = (string) get_theme_mod( 'hakajobspro_footer_col2_title', __( 'Lokasi Populer', 'hakajobspro' ) );
    $col2_links        = (string) get_theme_mod( 'hakajobspro_footer_col2_links', hakajobspro_get_default_footer_col2_text() );
    $col3_title        = (string) get_theme_mod( 'hakajobspro_footer_col3_title', __( 'Informasi & Kontak', 'hakajobspro' ) );
    $col3_links        = (string) get_theme_mod( 'hakajobspro_footer_col3_links', hakajobspro_get_default_footer_col3_text() );
    $bottom_links      = (string) get_theme_mod( 'hakajobspro_footer_bottom_links', hakajobspro_get_default_footer_bottom_text() );
    $footer_about      = (string) get_theme_mod( 'hakajobspro_footer_about', __( 'Portal lowongan kerja terpercaya yang menghubungkan pencari kerja dengan perusahaan terbaik di Indonesia.', 'hakajobspro' ) );
    $footer_copy       = (string) get_theme_mod( 'hakajobspro_footer_copyright', sprintf( '&copy; %d %s. Semua hak cipta dilindungi.', date( 'Y' ), get_bloginfo( 'name' ) ) );
    ?>
    <style>
        .hjp-menu-wrap { max-width: 960px; margin-top: 20px; }
        .hjp-menu-card { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 22px 24px; margin-bottom: 18px; box-shadow: 0 1px 2px rgba(0,0,0,.03); }
        .hjp-menu-card h2 { margin-top: 0; color: #1d3557; font-size: 16px; border-bottom: 1px solid #f0f0f1; padding-bottom: 10px; }
        .hjp-menu-textarea { width: 100%; font-family: Consolas, Monaco, monospace; font-size: 13px; line-height: 1.6; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 12px; }
        .hjp-menu-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 782px) { .hjp-menu-grid { grid-template-columns: 1fr; } }
    </style>

    <div class="wrap hjp-menu-wrap">
        <h1>Pengaturan Langsung Menu Header &amp; Footer — Hakajobspro</h1>
        <p style="font-size:14px;color:#50575e;">
            Atur daftar link <strong>Menu Navigasi Header</strong> dan <strong>4 Kolom Footer</strong> secara langsung dari satu halaman ini tanpa harus menyusun widget satu per satu. Format penulisan: satu menu per baris dengan pola <code>Nama Menu | https://link-tujuan.com</code>.
        </p>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p><strong>Pengaturan Menu Header &amp; Footer berhasil disimpan!</strong></p></div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field( 'hakajobspro_menus_settings_nonce' ); ?>

            <!-- 1. MENU HEADER UTAMA -->
            <div class="hjp-menu-card" style="border-left:4px solid #e63946;">
                <h2>1. Pengaturan Menu Header Utama (Bar Navigasi Atas)</h2>
                <p style="margin-bottom:12px;">
                    <label style="font-weight:600;font-size:13.5px;">
                        <input type="checkbox" name="hakajobspro_use_direct_header_menu" value="1" <?php checked( $use_direct_header ); ?>>
                        Gunakan Daftar Menu Header Langsung di Bawah Ini (Timpa menu bawaan WP <em>Appearance &rarr; Menus</em>)
                    </label>
                </p>
                <p class="description" style="margin-bottom:8px;">
                    Tulis 1 item menu per baris dengan format: <code>Nama Menu | URL Tujuan</code>.
                    (Jika centang di atas dimatikan, tema akan memprioritaskan menu dari <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">Appearance &rarr; Menus</a>).
                </p>
                <textarea name="hakajobspro_header_menu_items" rows="6" class="hjp-menu-textarea"><?php echo esc_textarea( $header_items ); ?></textarea>
            </div>

            <!-- 2. MENU 3 KOLOM FOOTER -->
            <div class="hjp-menu-card" style="border-left:4px solid #1d3557;">
                <h2>2. Pengaturan Menu Kolom Footer (Kategori, Lokasi, &amp; Informasi)</h2>
                <p class="description" style="margin-bottom:14px;">
                    Footer menampilkan 4 kolom: <strong>Kolom Kiri (Tentang Situs &amp; Sosial Media)</strong> serta <strong>3 Kolom Menu di bawah ini</strong>. Tulis 1 link per baris dengan format <code>Nama Link | URL</code>.
                </p>

                <div class="hjp-menu-grid">
                    <!-- Kolom 1 -->
                    <div>
                        <label style="font-weight:700;display:block;margin-bottom:6px;">Judul Footer Kolom 1:</label>
                        <input type="text" name="hakajobspro_footer_col1_title" value="<?php echo esc_attr( $col1_title ); ?>" class="widefat" style="margin-bottom:10px;">
                        <label style="font-weight:600;display:block;margin-bottom:4px;font-size:12px;">Daftar Link Kolom 1:</label>
                        <textarea name="hakajobspro_footer_col1_links" rows="7" class="hjp-menu-textarea"><?php echo esc_textarea( $col1_links ); ?></textarea>
                    </div>

                    <!-- Kolom 2 -->
                    <div>
                        <label style="font-weight:700;display:block;margin-bottom:6px;">Judul Footer Kolom 2:</label>
                        <input type="text" name="hakajobspro_footer_col2_title" value="<?php echo esc_attr( $col2_title ); ?>" class="widefat" style="margin-bottom:10px;">
                        <label style="font-weight:600;display:block;margin-bottom:4px;font-size:12px;">Daftar Link Kolom 2:</label>
                        <textarea name="hakajobspro_footer_col2_links" rows="7" class="hjp-menu-textarea"><?php echo esc_textarea( $col2_links ); ?></textarea>
                    </div>

                    <!-- Kolom 3 -->
                    <div>
                        <label style="font-weight:700;display:block;margin-bottom:6px;">Judul Footer Kolom 3:</label>
                        <input type="text" name="hakajobspro_footer_col3_title" value="<?php echo esc_attr( $col3_title ); ?>" class="widefat" style="margin-bottom:10px;">
                        <label style="font-weight:600;display:block;margin-bottom:4px;font-size:12px;">Daftar Link Kolom 3:</label>
                        <textarea name="hakajobspro_footer_col3_links" rows="7" class="hjp-menu-textarea"><?php echo esc_textarea( $col3_links ); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 3. TENTANG SITUS, COPYRIGHT, & LINK BARIS BAWAH FOOTER -->
            <div class="hjp-menu-card">
                <h2>3. Deskripsi Footer, Copyright, &amp; Link Baris Bawah</h2>
                <table class="form-table" role="presentation" style="margin:0;">
                    <tr>
                        <th scope="row"><label for="hakajobspro_footer_about">Deskripsi Singkat Situs (Kolom Kiri Footer)</label></th>
                        <td>
                            <textarea id="hakajobspro_footer_about" name="hakajobspro_footer_about" rows="3" class="large-text"><?php echo esc_textarea( $footer_about ); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hakajobspro_footer_copyright">Teks Hak Cipta (Copyright)</label></th>
                        <td>
                            <input type="text" id="hakajobspro_footer_copyright" name="hakajobspro_footer_copyright" value="<?php echo esc_attr( $footer_copy ); ?>" class="large-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hakajobspro_footer_bottom_links">Menu Baris Paling Bawah (Sebelah Copyright)</label></th>
                        <td>
                            <textarea id="hakajobspro_footer_bottom_links" name="hakajobspro_footer_bottom_links" rows="4" class="hjp-menu-textarea"><?php echo esc_textarea( $bottom_links ); ?></textarea>
                            <p class="description">Format: <code>Nama Link | URL</code> (satu per baris).</p>
                        </td>
                    </tr>
                </table>
            </div>

            <p class="submit">
                <button type="submit" name="hakajobspro_save_menus" value="1" class="button button-primary button-hero" style="background:#e63946;border-color:#c92a37;">
                    Simpan Pengaturan Menu Header &amp; Footer
                </button>
            </p>
        </form>
    </div>
    <?php
}


<?php
/**
 * Hakajobspro — inc/adsense.php
 * Sistem Manajemen Slot Iklan Google AdSense & Banner HTML
 * Dilengkapi fitur ON/OFF per slot di WP Customizer & Halaman Khusus WP Admin (Appearance -> Iklan AdSense).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Daftar definisi seluruh slot iklan tema Hakajobspro
 */
function hakajobspro_get_ad_slots_config() {
    return [
        'header' => [
            'title'       => __( 'Slot 1: Bawah Header (Leaderboard Atas)', 'hakajobspro' ),
            'desc'        => __( 'Tampil di bawah menu navigasi utama pada seluruh halaman (Ukuran ideal: 728x90 / Responsive).', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_header_enable',
            'code_mod'    => 'hakajobspro_ad_header_code',
            'size_hint'   => '728x90 / Responsive Display Ad',
        ],
        'infeed' => [
            'title'       => __( 'Slot 2: Di Tengah Daftar Lowongan (In-Feed Loker)', 'hakajobspro' ),
            'desc'        => __( 'Tampil otomatis di sela-sela daftar lowongan pada halaman Beranda & Arsip Lowongan.', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_infeed_enable',
            'code_mod'    => 'hakajobspro_ad_infeed_code',
            'extra_mod'   => 'hakajobspro_ad_infeed_after',
            'extra_label' => __( 'Muncul setelah urutan loker ke-', 'hakajobspro' ),
            'extra_def'   => 3,
            'size_hint'   => 'In-Feed Ad / Responsive Banner',
        ],
        'single_top' => [
            'title'       => __( 'Slot 3: Atas Artikel Detail Lowongan', 'hakajobspro' ),
            'desc'        => __( 'Tampil di halaman detail lowongan tepat di atas tabel informasi pekerjaan.', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_single_top_enable',
            'code_mod'    => 'hakajobspro_ad_single_top_code',
            'size_hint'   => 'Responsive Display / 336x280 / 728x90',
        ],
        'single_mid' => [
            'title'       => __( 'Slot 4: Tengah Deskripsi Lowongan (In-Article)', 'hakajobspro' ),
            'desc'        => __( 'Disisipkan otomatis di tengah paragraf deskripsi kualifikasi lowongan kerja.', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_single_mid_enable',
            'code_mod'    => 'hakajobspro_ad_single_mid_code',
            'extra_mod'   => 'hakajobspro_ad_single_mid_p',
            'extra_label' => __( 'Sisipkan setelah paragraf ke-', 'hakajobspro' ),
            'extra_def'   => 2,
            'size_hint'   => 'In-Article Ad / Responsive Display',
        ],
        'single_bottom' => [
            'title'       => __( 'Slot 5: Bawah Artikel / Sebelum Tombol Lamar', 'hakajobspro' ),
            'desc'        => __( 'Tampil tepat di atas area Tombol Lamar Sekarang (area dengan rasio klik tertinggi).', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_single_bottom_enable',
            'code_mod'    => 'hakajobspro_ad_single_bottom_code',
            'size_hint'   => 'Responsive Display / 336x280 / 300x250',
        ],
        'sidebar' => [
            'title'       => __( 'Slot 6: Sidebar Kanan Atas', 'hakajobspro' ),
            'desc'        => __( 'Tampil di kotak paling atas Sidebar Kanan (di atas widget Loker Populer).', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_sidebar_enable',
            'code_mod'    => 'hakajobspro_ad_sidebar_code',
            'size_hint'   => '300x250 Medium Rectangle / Responsive',
        ],
        'sidebar_bottom' => [
            'title'       => __( 'Slot 7: Sidebar Kanan Bawah (Sticky)', 'hakajobspro' ),
            'desc'        => __( 'Tampil di bagian bawah Sidebar Kanan (di bawah Kategori Lowongan) dan mengikuti scroll layar.', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_sidebar_bottom_enable',
            'code_mod'    => 'hakajobspro_ad_sidebar_bottom_code',
            'size_hint'   => '300x600 Large Skyscraper / 300x250',
        ],
        'footer' => [
            'title'       => __( 'Slot 8: Atas Footer', 'hakajobspro' ),
            'desc'        => __( 'Tampil di bagian bawah halaman tepat sebelum area Footer.', 'hakajobspro' ),
            'enable_mod'  => 'hakajobspro_ad_footer_enable',
            'code_mod'    => 'hakajobspro_ad_footer_code',
            'size_hint'   => '728x90 / Responsive Multiplex Ad',
        ],
    ];
}

/**
 * Sanitasi kode iklan AdSense / HTML (mengizinkan tag <script>, <ins>, <iframe> bagi Admin)
 */
function hakajobspro_sanitize_ad_code( $input ) {
    if ( current_user_can( 'unfiltered_html' ) || current_user_can( 'manage_options' ) ) {
        return $input;
    }
    return wp_kses_post( $input );
}

/**
 * Output Script Google AdSense Auto Ads di dalam tag <head> jika diaktifkan
 */
function hakajobspro_output_head_adsense_script() {
    if ( ! get_theme_mod( 'hakajobspro_ads_enable_all', true ) ) {
        return;
    }
    if ( ! get_theme_mod( 'hakajobspro_ads_head_enable', false ) ) {
        return;
    }
    $head_code = trim( (string) get_theme_mod( 'hakajobspro_ads_head_code', '' ) );
    if ( ! empty( $head_code ) ) {
        echo "\n<!-- Hakajobspro Google AdSense Head Script -->\n";
        echo $head_code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'hakajobspro_output_head_adsense_script', 20 );

/**
 * Render slot iklan berdasarkan kunci lokasi ('header', 'infeed', 'single_top', 'single_mid', 'single_bottom', 'sidebar', 'footer')
 */
function hakajobspro_get_ad_slot_html( $slot_key ) {
    // Cek saklar utama semua iklan
    if ( ! get_theme_mod( 'hakajobspro_ads_enable_all', true ) ) {
        return '';
    }

    $slots = hakajobspro_get_ad_slots_config();
    if ( ! isset( $slots[ $slot_key ] ) ) {
        return '';
    }

    $slot       = $slots[ $slot_key ];
    $is_enabled = (bool) get_theme_mod( $slot['enable_mod'], false );

    if ( ! $is_enabled ) {
        return '';
    }

    $code              = trim( (string) get_theme_mod( $slot['code_mod'], '' ) );
    $show_label        = (bool) get_theme_mod( 'hakajobspro_ads_show_label', true );
    $admin_placeholder = (bool) get_theme_mod( 'hakajobspro_ads_admin_placeholder', true );
    $is_sidebar_slot   = in_array( $slot_key, [ 'sidebar', 'sidebar_bottom' ], true );
    $sticky_class      = ( 'sidebar_bottom' === $slot_key ) ? ' widget-ad-slot--sticky' : '';

    // Jika kode kosong dan fitur pratinjau placeholder admin aktif (hanya terlihat oleh Admin)
    if ( empty( $code ) ) {
        if ( $admin_placeholder && current_user_can( 'manage_options' ) ) {
            $edit_url = admin_url( 'themes.php?page=hakajobspro-adsense' );
            ob_start();
            if ( $is_sidebar_slot ) :
            ?>
            <div class="widget widget-ad-slot<?php echo esc_attr( $sticky_class ); ?>" role="region" aria-label="<?php esc_attr_e( 'Slot Iklan Sidebar', 'hakajobspro' ); ?>">
                <?php if ( $show_label ) : ?>
                    <h3 class="widget-title"><?php esc_html_e( 'Iklan Sponsor', 'hakajobspro' ); ?></h3>
                <?php endif; ?>
                <div class="widget-content">
                    <div class="hjp-ad-placeholder-box">
                        <strong><?php echo esc_html( $slot['title'] ); ?> (AKTIF)</strong>
                        <span><?php echo esc_html( $slot['size_hint'] ); ?> &mdash; <?php esc_html_e( 'Kode AdSense belum diisi (Khusus Admin).', 'hakajobspro' ); ?></span>
                        <a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Isi Kode Iklan di Pengaturan WP', 'hakajobspro' ); ?> &rarr;</a>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div class="hjp-ad-slot hjp-ad-slot--<?php echo esc_attr( $slot_key ); ?> hjp-ad-slot--placeholder" role="region" aria-label="<?php esc_attr_e( 'Slot Iklan', 'hakajobspro' ); ?>">
                <?php if ( $show_label ) : ?>
                    <span class="hjp-ad-slot__label"><?php esc_html_e( 'IKLAN / ADVERTISEMENT', 'hakajobspro' ); ?></span>
                <?php endif; ?>
                <div class="hjp-ad-placeholder-box">
                    <strong><?php echo esc_html( $slot['title'] ); ?> (AKTIF)</strong>
                    <span><?php echo esc_html( $slot['size_hint'] ); ?> &mdash; <?php esc_html_e( 'Kode AdSense belum diisi (Kotak ini hanya terlihat oleh Admin).', 'hakajobspro' ); ?></span>
                    <a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Isi Kode Iklan di Pengaturan WP', 'hakajobspro' ); ?> &rarr;</a>
                </div>
            </div>
            <?php
            endif;
            return ob_get_clean();
        }
        return '';
    }

    ob_start();
    if ( $is_sidebar_slot ) :
    ?>
    <div class="widget widget-ad-slot<?php echo esc_attr( $sticky_class ); ?>" role="region" aria-label="<?php esc_attr_e( 'Iklan Sponsor Sidebar', 'hakajobspro' ); ?>">
        <?php if ( $show_label ) : ?>
            <h3 class="widget-title"><?php esc_html_e( 'Iklan Sponsor', 'hakajobspro' ); ?></h3>
        <?php endif; ?>
        <div class="widget-content">
            <div class="hjp-ad-slot__inner">
                <?php echo do_shortcode( $code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="hjp-ad-slot hjp-ad-slot--<?php echo esc_attr( $slot_key ); ?>" role="region" aria-label="<?php esc_attr_e( 'Iklan Sponsor', 'hakajobspro' ); ?>">
        <?php if ( $show_label ) : ?>
            <span class="hjp-ad-slot__label"><?php esc_html_e( 'IKLAN / ADVERTISEMENT', 'hakajobspro' ); ?></span>
        <?php endif; ?>
        <div class="hjp-ad-slot__inner">
            <?php echo do_shortcode( $code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </div>
    <?php
    endif;
    return ob_get_clean();
}

/**
 * Cetak langsung HTML slot iklan
 */
function hakajobspro_render_ad_slot( $slot_key ) {
    echo hakajobspro_get_ad_slot_html( $slot_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Filter otomatis untuk menyisipkan Slot 4 (Tengah Deskripsi Lowongan) ke dalam the_content()
 */
function hakajobspro_inject_single_middle_ad( $content ) {
    if ( is_admin() || ! is_singular( [ 'loker', 'post' ] ) || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }

    $ad_html = hakajobspro_get_ad_slot_html( 'single_mid' );
    if ( empty( $ad_html ) ) {
        return $content;
    }

    $after_p    = max( 1, absint( get_theme_mod( 'hakajobspro_ad_single_mid_p', 2 ) ) );
    $paragraphs = explode( '</p>', $content );

    if ( count( $paragraphs ) <= $after_p ) {
        return $content . $ad_html;
    }

    foreach ( $paragraphs as $index => $paragraph ) {
        if ( trim( $paragraph ) ) {
            $paragraphs[ $index ] .= '</p>';
        }
        if ( ( $index + 1 ) === $after_p ) {
            $paragraphs[ $index ] .= $ad_html;
        }
    }

    return implode( '', $paragraphs );
}
add_filter( 'the_content', 'hakajobspro_inject_single_middle_ad', 20 );

/* ==========================================================================
   HALAMAN PANEL ADMIN: APPEARANCE -> IKLAN ADSENSE
   ========================================================================== */

function hakajobspro_register_adsense_admin_menu() {
    add_theme_page(
        __( 'Pengaturan Slot Iklan AdSense', 'hakajobspro' ),
        __( 'Iklan AdSense', 'hakajobspro' ),
        'manage_options',
        'hakajobspro-adsense',
        'hakajobspro_render_adsense_admin_page'
    );
}
add_action( 'admin_menu', 'hakajobspro_register_adsense_admin_menu' );

function hakajobspro_handle_adsense_admin_save() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['hakajobspro_save_adsense'] ) && check_admin_referer( 'hakajobspro_adsense_settings_nonce' ) ) {
        set_theme_mod( 'hakajobspro_ads_enable_all', isset( $_POST['hakajobspro_ads_enable_all'] ) );
        set_theme_mod( 'hakajobspro_ads_show_label', isset( $_POST['hakajobspro_ads_show_label'] ) );
        set_theme_mod( 'hakajobspro_ads_admin_placeholder', isset( $_POST['hakajobspro_ads_admin_placeholder'] ) );
        set_theme_mod( 'hakajobspro_ads_head_enable', isset( $_POST['hakajobspro_ads_head_enable'] ) );

        if ( isset( $_POST['hakajobspro_ads_head_code'] ) ) {
            set_theme_mod( 'hakajobspro_ads_head_code', hakajobspro_sanitize_ad_code( wp_unslash( $_POST['hakajobspro_ads_head_code'] ) ) );
        }

        $slots = hakajobspro_get_ad_slots_config();
        foreach ( $slots as $key => $slot ) {
            $enable_mod = $slot['enable_mod'];
            $code_mod   = $slot['code_mod'];

            set_theme_mod( $enable_mod, isset( $_POST[ $enable_mod ] ) );

            if ( isset( $_POST[ $code_mod ] ) ) {
                set_theme_mod( $code_mod, hakajobspro_sanitize_ad_code( wp_unslash( $_POST[ $code_mod ] ) ) );
            }

            if ( ! empty( $slot['extra_mod'] ) && isset( $_POST[ $slot['extra_mod'] ] ) ) {
                set_theme_mod( $slot['extra_mod'], max( 1, absint( $_POST[ $slot['extra_mod'] ] ) ) );
            }
        }

        wp_safe_redirect( add_query_arg( [
            'page'  => 'hakajobspro-adsense',
            'saved' => '1',
        ], admin_url( 'themes.php' ) ) );
        exit;
    }
}
add_action( 'admin_init', 'hakajobspro_handle_adsense_admin_save' );

function hakajobspro_render_adsense_admin_page() {
    $enable_all        = (bool) get_theme_mod( 'hakajobspro_ads_enable_all', true );
    $show_label        = (bool) get_theme_mod( 'hakajobspro_ads_show_label', true );
    $admin_placeholder = (bool) get_theme_mod( 'hakajobspro_ads_admin_placeholder', true );
    $head_enable       = (bool) get_theme_mod( 'hakajobspro_ads_head_enable', false );
    $head_code         = (string) get_theme_mod( 'hakajobspro_ads_head_code', '' );
    $slots             = hakajobspro_get_ad_slots_config();
    ?>
    <style>
        .hjp-ads-admin-wrap { max-width: 960px; margin-top: 20px; }
        .hjp-ads-card { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 22px 24px; margin-bottom: 18px; box-shadow: 0 1px 2px rgba(0,0,0,.03); }
        .hjp-ads-card__head { display: flex; justify-content: space-between; align-items: center; gap: 16px; border-bottom: 1px solid #f0f0f1; padding-bottom: 14px; margin-bottom: 14px; }
        .hjp-ads-card__title { margin: 0; font-size: 16px; font-weight: 700; color: #1d3557; }
        .hjp-ads-card__desc { margin: 4px 0 0; color: #646970; font-size: 13px; }
        .hjp-switch { position: relative; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; font-size: 13px; user-select: none; }
        .hjp-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
        .hjp-switch-slider { width: 44px; height: 24px; background-color: #cbd5e1; border-radius: 24px; position: relative; transition: .2s; display: inline-block; }
        .hjp-switch-slider::before { content: ""; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #fff; border-radius: 50%; transition: .2s; box-shadow: 0 1px 2px rgba(0,0,0,.2); }
        .hjp-switch input:checked + .hjp-switch-slider { background-color: #16a34a; }
        .hjp-switch input:checked + .hjp-switch-slider::before { transform: translateX(20px); }
        .hjp-ads-code-area { width: 100%; font-family: Consolas, Monaco, monospace; font-size: 12.5px; line-height: 1.5; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 12px; }
        .hjp-ads-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; background: #eef2ff; color: #1d3557; margin-top: 6px; }
    </style>

    <div class="wrap hjp-ads-admin-wrap">
        <h1>Manajemen Slot Iklan Google AdSense &amp; Banner — Hakajobspro</h1>
        <p style="font-size:14px;color:#50575e;">
            Atur pemasangan kode iklan Google AdSense atau banner HTML di setiap posisi strategis website. Setiap slot memiliki tombol <strong>ON / OFF</strong> mandiri dan juga tersinkronisasi dengan menu <strong>Appearance &rarr; Customize &rarr; Slot Iklan AdSense</strong>.
        </p>

        <?php if ( isset( $_GET['saved'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p><strong>Pengaturan slot iklan AdSense berhasil disimpan!</strong></p></div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field( 'hakajobspro_adsense_settings_nonce' ); ?>

            <!-- PENGATURAN UMUM & AUTO ADS HEAD -->
            <div class="hjp-ads-card" style="border-left:4px solid #1d3557;">
                <div class="hjp-ads-card__head">
                    <div>
                        <h2 class="hjp-ads-card__title">Saklar Utama &amp; Script Google AdSense &lt;head&gt;</h2>
                        <p class="hjp-ads-card__desc">Kontrol global seluruh penayangan iklan dan penyisipan kode verifikasi / Auto Ads di dalam tag <code>&lt;head&gt;</code>.</p>
                    </div>
                    <label class="hjp-switch">
                        <input type="checkbox" name="hakajobspro_ads_enable_all" value="1" <?php checked( $enable_all ); ?>>
                        <span class="hjp-switch-slider"></span>
                        <span>Aktifkan Sistem Iklan</span>
                    </label>
                </div>

                <table class="form-table" role="presentation" style="margin:0;">
                    <tr>
                        <th scope="row">Label &amp; Pratinjau Slot</th>
                        <td>
                            <label style="display:block;margin-bottom:8px;">
                                <input type="checkbox" name="hakajobspro_ads_show_label" value="1" <?php checked( $show_label ); ?>>
                                Tampilkan tulisan kecil <strong>"IKLAN / ADVERTISEMENT"</strong> di atas setiap slot iklan
                            </label>
                            <label style="display:block;">
                                <input type="checkbox" name="hakajobspro_ads_admin_placeholder" value="1" <?php checked( $admin_placeholder ); ?>>
                                Tampilkan kotak pratinjau lokasi slot (khusus Admin) saat slot diaktifkan tetapi kode AdSense masih kosong
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Script <code>&lt;head&gt;</code> AdSense<br>
                            <small style="font-weight:400;color:#646970;">(Auto Ads / Verifikasi Situs)</small>
                        </th>
                        <td>
                            <div style="margin-bottom:8px;">
                                <label class="hjp-switch">
                                    <input type="checkbox" name="hakajobspro_ads_head_enable" value="1" <?php checked( $head_enable ); ?>>
                                    <span class="hjp-switch-slider"></span>
                                    <span>ON / OFF Script &lt;head&gt;</span>
                                </label>
                            </div>
                            <textarea name="hakajobspro_ads_head_code" rows="4" class="hjp-ads-code-area" placeholder='<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX" crossorigin="anonymous"></script>'><?php echo esc_textarea( $head_code ); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- DAFTAR 7 SLOT IKLAN STRATEGIS -->
            <?php foreach ( $slots as $key => $slot ) :
                $is_on    = (bool) get_theme_mod( $slot['enable_mod'], false );
                $code_val = (string) get_theme_mod( $slot['code_mod'], '' );
            ?>
            <div class="hjp-ads-card">
                <div class="hjp-ads-card__head">
                    <div>
                        <h3 class="hjp-ads-card__title"><?php echo esc_html( $slot['title'] ); ?></h3>
                        <p class="hjp-ads-card__desc"><?php echo esc_html( $slot['desc'] ); ?></p>
                        <span class="hjp-ads-badge">Rekomendasi Ukuran: <?php echo esc_html( $slot['size_hint'] ); ?></span>
                    </div>
                    <label class="hjp-switch">
                        <input type="checkbox" name="<?php echo esc_attr( $slot['enable_mod'] ); ?>" value="1" <?php checked( $is_on ); ?>>
                        <span class="hjp-switch-slider"></span>
                        <span><?php echo $is_on ? 'ON (Aktif)' : 'OFF (Nonaktif)'; ?></span>
                    </label>
                </div>

                <?php if ( ! empty( $slot['extra_mod'] ) ) :
                    $extra_val = absint( get_theme_mod( $slot['extra_mod'], $slot['extra_def'] ) ) ?: $slot['extra_def'];
                ?>
                <div style="margin-bottom:12px;display:flex;align-items:center;gap:10px;">
                    <label for="<?php echo esc_attr( $slot['extra_mod'] ); ?>" style="font-weight:600;font-size:13px;">
                        <?php echo esc_html( $slot['extra_label'] ); ?>
                    </label>
                    <input type="number" id="<?php echo esc_attr( $slot['extra_mod'] ); ?>" name="<?php echo esc_attr( $slot['extra_mod'] ); ?>" value="<?php echo (int) $extra_val; ?>" min="1" max="20" style="width:70px;">
                </div>
                <?php endif; ?>

                <textarea name="<?php echo esc_attr( $slot['code_mod'] ); ?>" rows="4" class="hjp-ads-code-area" placeholder="Tempel kode unit iklan Google AdSense (<ins class=&quot;adsbygoogle&quot;...></ins>) atau kode HTML banner di sini..."><?php echo esc_textarea( $code_val ); ?></textarea>
            </div>
            <?php endforeach; ?>

            <p class="submit" style="position:sticky;bottom:0;background:rgba(255,255,255,.95);padding:14px 20px;border:1px solid #dcdcde;border-radius:8px;box-shadow:0 -2px 10px rgba(0,0,0,.06);z-index:10;display:flex;align-items:center;justify-content:space-between;">
                <span style="color:#50575e;font-size:13px;">Pastikan klik simpan setelah mengaktifkan saklar ON/OFF atau menempelkan kode AdSense.</span>
                <button type="submit" name="hakajobspro_save_adsense" value="1" class="button button-primary button-hero" style="background:#e63946;border-color:#c92a37;">
                    Simpan Semua Pengaturan Iklan
                </button>
            </p>
        </form>
    </div>
    <?php
}

/* ==========================================================================
   WIDGET WORDPRESS KHUSUS SIDEBAR KANAN: HAKAJOBSPRO SLOT IKLAN ADSENSE
   ========================================================================== */

class Hakajobspro_Adsense_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'hakajobspro_adsense_widget',
            __( 'Hakajobspro: Slot Iklan AdSense', 'hakajobspro' ),
            [ 'description' => __( 'Menampilkan unit iklan Google AdSense atau kode HTML banner di Sidebar Kanan.', 'hakajobspro' ) ]
        );
    }

    public function widget( $args, $instance ) {
        if ( ! get_theme_mod( 'hakajobspro_ads_enable_all', true ) ) {
            return;
        }
        $enabled = ! isset( $instance['enabled'] ) || ! empty( $instance['enabled'] );
        if ( ! $enabled ) {
            return;
        }
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Iklan Sponsor', 'hakajobspro' );
        $code  = ! empty( $instance['code'] ) ? trim( $instance['code'] ) : '';

        if ( empty( $code ) ) {
            return;
        }

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        echo '<div class="widget-content"><div class="hjp-ad-slot__inner">' . do_shortcode( $code ) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public function form( $instance ) {
        $title   = isset( $instance['title'] ) ? $instance['title'] : __( 'Iklan Sponsor', 'hakajobspro' );
        $code    = isset( $instance['code'] ) ? $instance['code'] : '';
        $enabled = ! isset( $instance['enabled'] ) || ! empty( $instance['enabled'] );
        ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $enabled ); ?> id="<?php echo esc_attr( $this->get_field_id( 'enabled' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'enabled' ) ); ?>" value="1">
            <label for="<?php echo esc_attr( $this->get_field_id( 'enabled' ) ); ?>"><strong><?php esc_html_e( 'ON / Aktifkan Widget Iklan Ini', 'hakajobspro' ); ?></strong></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Judul Kotak Iklan:', 'hakajobspro' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>"><?php esc_html_e( 'Kode Script AdSense / HTML Banner:', 'hakajobspro' ); ?></label>
            <textarea class="widefat" rows="5" id="<?php echo esc_attr( $this->get_field_id( 'code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'code' ) ); ?>"><?php echo esc_textarea( $code ); ?></textarea>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return [
            'title'   => sanitize_text_field( $new_instance['title'] ?? '' ),
            'code'    => hakajobspro_sanitize_ad_code( $new_instance['code'] ?? '' ),
            'enabled' => ! empty( $new_instance['enabled'] ) ? 1 : 0,
        ];
    }
}

add_action( 'widgets_init', function() {
    register_widget( 'Hakajobspro_Adsense_Widget' );
} );


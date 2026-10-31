<?php
/**
 * WP Customizer — semua pengaturan tema Hakajobspro.
 * Sections: Warna, Header, Beranda, Loker, Footer, Kontak, Media Sosial.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function hakajobspro_customize_register( WP_Customize_Manager $wp_customize ) {

    /* ===== PANEL UTAMA ===== */
    $wp_customize->add_panel( 'hakajobspro_panel', [
        'title'    => __( 'Pengaturan Tema Hakajobspro', 'hakajobspro' ),
        'priority' => 30,
    ] );

    /* ======================================================================
       SECTION 1: WARNA TEMA (LENGKAP: AKSEN, HEADER, MENU, TOPBAR, FOOTER, BODY)
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_colors', [
        'title'    => __( 'Warna Tema & Menu', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 10,
    ] );

    $color_settings = [
        'hakajobspro_color_primary' => [
            'label'   => __( 'Warna Utama / Aksen (Tombol & Highlight)', 'hakajobspro' ),
            'default' => '#e63946',
        ],
        'hakajobspro_color_dark' => [
            'label'   => __( 'Warna Judul & Elemen Gelap', 'hakajobspro' ),
            'default' => '#1d3557',
        ],
        'hakajobspro_body_bg' => [
            'label'   => __( 'Warna Latar Halaman (Body)', 'hakajobspro' ),
            'default' => '#f4f6f9',
        ],
        'hakajobspro_header_bg' => [
            'label'   => __( 'Warna Latar Header', 'hakajobspro' ),
            'default' => '#ffffff',
        ],
        'hakajobspro_nav_bg' => [
            'label'   => __( 'Warna Latar Baris Menu Navigasi', 'hakajobspro' ),
            'default' => '#1d3557',
        ],
        'hakajobspro_nav_text' => [
            'label'   => __( 'Warna Teks Menu Navigasi', 'hakajobspro' ),
            'default' => '#ffffff',
        ],
        'hakajobspro_nav_hover_bg' => [
            'label'   => __( 'Warna Hover / Menu Aktif', 'hakajobspro' ),
            'default' => '#e63946',
        ],
        'hakajobspro_footer_bg' => [
            'label'   => __( 'Warna Latar Footer', 'hakajobspro' ),
            'default' => '#1d3557',
        ],
        'hakajobspro_footer_text_color' => [
            'label'   => __( 'Warna Teks Footer', 'hakajobspro' ),
            'default' => '#dce3ed',
        ],
    ];

    foreach ( $color_settings as $id => $args ) {
        $wp_customize->add_setting( $id, [
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ] );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, [
            'label'   => $args['label'],
            'section' => 'hakajobspro_colors',
        ] ) );
    }

    /* ======================================================================
       SECTION 1B: TIPOGRAFI / FONT & UKURAN LAYOUT
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_typography', [
        'title'    => __( 'Font & Ukuran Layout', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 15,
    ] );

    $wp_customize->add_setting( 'hakajobspro_font_family', [
        'default'           => 'system',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'hakajobspro_font_family', [
        'label'   => __( 'Pilihan Jenis Font Utama', 'hakajobspro' ),
        'section' => 'hakajobspro_typography',
        'type'    => 'select',
        'choices' => [
            'system'  => __( 'System UI (Tercepat, Bawaan Perangkat)', 'hakajobspro' ),
            'inter'   => 'Inter (Google Font)',
            'jakarta' => 'Plus Jakarta Sans (Google Font)',
            'poppins' => 'Poppins (Google Font)',
            'roboto'  => 'Roboto (Google Font)',
            'nunito'  => 'Nunito Sans (Google Font)',
        ],
    ] );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_font_size_base',
        __( 'Ukuran Font Dasar Teks (px)', 'hakajobspro' ),
        16, 'hakajobspro_typography', 13, 20
    );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_container_width',
        __( 'Lebar Maksimal Container Layout (px)', 'hakajobspro' ),
        1200, 'hakajobspro_typography', 960, 1440
    );

    /* ======================================================================
       SECTION 2: PENGATURAN HEADER & UKURAN LOGO
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_header', [
        'title'    => __( 'Header & Ukuran Logo', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 20,
    ] );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_logo_height',
        __( 'Tinggi Logo di Desktop (px)', 'hakajobspro' ),
        48, 'hakajobspro_header', 24, 120
    );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_logo_height_mobile',
        __( 'Tinggi Logo di Mobile (px)', 'hakajobspro' ),
        36, 'hakajobspro_header', 20, 80
    );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_site_title_size',
        __( 'Ukuran Font Nama Situs Jika Tanpa Gambar Logo (px)', 'hakajobspro' ),
        22, 'hakajobspro_header', 16, 36
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_header_tagline',
        __( 'Tagline di Header', 'hakajobspro' ),
        __( 'Portal Lowongan Kerja Terpercaya', 'hakajobspro' ),
        'hakajobspro_header'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_search_header',
        __( 'Tampilkan Form Pencarian di Header', 'hakajobspro' ),
        true, 'hakajobspro_header'
    );

    /* ======================================================================
       SECTION 3: HALAMAN BERANDA
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_homepage', [
        'title'    => __( 'Halaman Beranda', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 30,
    ] );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_hero_title',
        __( 'Judul Hero', 'hakajobspro' ),
        __( 'Temukan Pekerjaan Impian Anda', 'hakajobspro' ),
        'hakajobspro_homepage'
    );

    hakajobspro_add_textarea_setting( $wp_customize, 'hakajobspro_hero_subtitle',
        __( 'Subjudul Hero', 'hakajobspro' ),
        __( 'Ribuan lowongan kerja dari perusahaan terpercaya menanti Anda. Daftar gratis dan lamar sekarang.', 'hakajobspro' ),
        'hakajobspro_homepage'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_hero_cta_text',
        __( 'Teks Tombol CTA Hero', 'hakajobspro' ),
        __( 'Lihat Semua Lowongan', 'hakajobspro' ),
        'hakajobspro_homepage'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_hero_cta_url',
        __( 'URL Tombol CTA Hero', 'hakajobspro' ),
        home_url( '/lowongan' ),
        'hakajobspro_homepage',
        'url'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_hero_bg',
        __( 'Warna Latar Hero (hex)', 'hakajobspro' ),
        '#f0f4f8',
        'hakajobspro_homepage'
    );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_featured_count',
        __( 'Jumlah Loker Unggulan di Beranda', 'hakajobspro' ),
        6, 'hakajobspro_homepage', 1, 12
    );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_latest_count',
        __( 'Jumlah Loker Terbaru di Beranda', 'hakajobspro' ),
        9, 'hakajobspro_homepage', 3, 24
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_section_featured_title',
        __( 'Judul Seksi Loker Unggulan', 'hakajobspro' ),
        __( 'Lowongan Unggulan', 'hakajobspro' ),
        'hakajobspro_homepage'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_section_latest_title',
        __( 'Judul Seksi Loker Terbaru', 'hakajobspro' ),
        __( 'Lowongan Terbaru', 'hakajobspro' ),
        'hakajobspro_homepage'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_category_filter',
        __( 'Tampilkan Filter Kategori di Beranda', 'hakajobspro' ),
        true, 'hakajobspro_homepage'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_tips_home',
        __( 'Tampilkan Seksi Tips Karier (Pos Artikel) di Bawah Beranda', 'hakajobspro' ),
        true, 'hakajobspro_homepage'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_section_tips_title',
        __( 'Judul Seksi Tips Karier di Beranda', 'hakajobspro' ),
        __( 'Tips Karier & Artikel Terbaru', 'hakajobspro' ),
        'hakajobspro_homepage'
    );

    /* ======================================================================
       SECTION 4: PENGATURAN LOWONGAN
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_loker_settings', [
        'title'    => __( 'Pengaturan Lowongan', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 40,
    ] );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_loker_per_page',
        __( 'Jumlah Loker per Halaman (Arsip)', 'hakajobspro' ),
        12, 'hakajobspro_loker_settings', 6, 48
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_badge_featured',
        __( 'Label Badge Unggulan', 'hakajobspro' ),
        __( 'Unggulan', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_badge_new',
        __( 'Label Badge Baru (hari)', 'hakajobspro' ),
        __( 'Baru', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_new_days',
        __( 'Loker dianggap baru jika diterbitkan dalam (hari)', 'hakajobspro' ),
        7, 'hakajobspro_loker_settings', 1, 30
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_salary',
        __( 'Tampilkan Range Gaji di Kartu', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_deadline',
        __( 'Tampilkan Deadline di Kartu', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_auto_close_deadline',
        __( 'Otomatis Tutup Lowongan Saat Melewati Batas Pendaftaran', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_badge_closed',
        __( 'Label Badge Lowongan Ditutup', 'hakajobspro' ),
        __( 'Ditutup', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_textarea_setting( $wp_customize, 'hakajobspro_closed_notice',
        __( 'Pesan Informasi Saat Lowongan Sudah Ditutup', 'hakajobspro' ),
        __( 'Pendaftaran untuk lowongan ini telah ditutup secara otomatis karena sudah melewati batas waktu pendaftaran. Artikel ini disimpan sebagai arsip riwayat lowongan perusahaan.', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_enable_job_schema',
        __( 'Aktifkan Schema JobPosting JSON-LD Otomatis (Google Jobs)', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_wa_channel_url',
        __( 'Link Saluran (Channel) WhatsApp', 'hakajobspro' ),
        'https://whatsapp.com/channel/',
        'hakajobspro_loker_settings',
        'url'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_wa_channel_box',
        __( 'Tampilkan Ajakan Gabung Saluran WA di Akhir Artikel Loker', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_wa_channel_title',
        __( 'Judul Box Ajakan Saluran WhatsApp', 'hakajobspro' ),
        __( 'Jangan Ketinggalan Info Loker Terbaru!', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_textarea_setting( $wp_customize, 'hakajobspro_wa_channel_desc',
        __( 'Deskripsi Box Ajakan Saluran WhatsApp', 'hakajobspro' ),
        __( 'Gabung ke Saluran WhatsApp resmi kami untuk mendapatkan notifikasi lowongan kerja terverifikasi setiap hari langsung di WhatsApp Anda.', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_wa_channel_btn',
        __( 'Teks Tombol Box Saluran WhatsApp', 'hakajobspro' ),
        __( 'Gabung Saluran WhatsApp', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_enable_anticopas',
        __( 'Aktifkan Proteksi Anti-Copas (Blokir Klik Kanan & Salin Konten)', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_anticopas_bypass_admin',
        __( 'Nonaktifkan Anti-Copas Saat Login Sebagai Admin', 'hakajobspro' ),
        true, 'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_anticopas_msg',
        __( 'Pesan Peringatan Anti-Copas', 'hakajobspro' ),
        __( 'Konten dilindungi. Dilarang menyalin informasi lowongan kerja tanpa izin.', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_cta_apply_text',
        __( 'Teks Tombol Lamar', 'hakajobspro' ),
        __( 'Lamar Sekarang', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_archive_title',
        __( 'Judul Halaman Arsip Lowongan', 'hakajobspro' ),
        __( 'Semua Lowongan Kerja', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    hakajobspro_add_textarea_setting( $wp_customize, 'hakajobspro_archive_desc',
        __( 'Deskripsi Halaman Arsip', 'hakajobspro' ),
        __( 'Temukan lowongan pekerjaan sesuai keahlian dan kota Anda.', 'hakajobspro' ),
        'hakajobspro_loker_settings'
    );

    /* ======================================================================
       SECTION 5: INFORMASI KONTAK
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_contact', [
        'title'    => __( 'Informasi Kontak', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 50,
    ] );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_phone',
        __( 'Nomor Telepon / WhatsApp', 'hakajobspro' ),
        '', 'hakajobspro_contact'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_email',
        __( 'Alamat Email Kontak', 'hakajobspro' ),
        '', 'hakajobspro_contact', 'email'
    );

    hakajobspro_add_textarea_setting( $wp_customize, 'hakajobspro_address',
        __( 'Alamat Lengkap', 'hakajobspro' ),
        '', 'hakajobspro_contact'
    );

    /* ======================================================================
       SECTION 6: MEDIA SOSIAL
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_social', [
        'title'    => __( 'Media Sosial', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 60,
    ] );

    $socials = [
        'facebook'  => 'Facebook',
        'instagram' => 'Instagram',
        'twitter'   => 'Twitter / X',
        'youtube'   => 'YouTube',
        'tiktok'    => 'TikTok',
        'telegram'  => 'Telegram',
        'whatsapp'  => 'WhatsApp',
        'linkedin'  => 'LinkedIn',
    ];

    foreach ( $socials as $key => $label ) {
        hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_social_' . $key,
            $label, '', 'hakajobspro_social', 'url'
        );
    }

    /* ======================================================================
       SECTION 7: FOOTER
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_footer', [
        'title'    => __( 'Pengaturan Footer', 'hakajobspro' ),
        'panel'    => 'hakajobspro_panel',
        'priority' => 70,
    ] );

    hakajobspro_add_textarea_setting( $wp_customize, 'hakajobspro_footer_about',
        __( 'Tentang Situs (kolom footer kiri)', 'hakajobspro' ),
        __( 'Portal lowongan kerja terpercaya yang menghubungkan pencari kerja dengan perusahaan terbaik di Indonesia.', 'hakajobspro' ),
        'hakajobspro_footer'
    );

    hakajobspro_add_text_setting( $wp_customize, 'hakajobspro_footer_copyright',
        __( 'Teks Hak Cipta', 'hakajobspro' ),
        sprintf( '&copy; %d %s. Semua hak cipta dilindungi.', date( 'Y' ), get_bloginfo( 'name' ) ),
        'hakajobspro_footer'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_show_back_to_top',
        __( 'Tampilkan Tombol Kembali ke Atas', 'hakajobspro' ),
        true, 'hakajobspro_footer'
    );

    /* ======================================================================
       SECTION 8: SLOT PEMASANGAN IKLAN ADSENSE (ON/OFF & KODE SCRIPT)
       ====================================================================== */
    $wp_customize->add_section( 'hakajobspro_adsense', [
        'title'       => __( 'Slot Iklan AdSense (On/Off)', 'hakajobspro' ),
        'description' => __( 'Aktifkan/nonaktifkan (ON/OFF) setiap slot iklan dan tempelkan kode Google AdSense atau banner HTML Anda. Tersedia juga tampilan layar penuh di menu WP Admin: Appearance -> Iklan AdSense.', 'hakajobspro' ),
        'panel'       => 'hakajobspro_panel',
        'priority'    => 80,
    ] );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ads_enable_all',
        __( 'Saklar Utama: Aktifkan Sistem Iklan di Website', 'hakajobspro' ),
        true, 'hakajobspro_adsense'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ads_show_label',
        __( 'Tampilkan Label "IKLAN / ADVERTISEMENT" di Atas Slot', 'hakajobspro' ),
        true, 'hakajobspro_adsense'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ads_admin_placeholder',
        __( 'Tampilkan Pratinjau Kotak Slot Kosong (Khusus Admin)', 'hakajobspro' ),
        true, 'hakajobspro_adsense'
    );

    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ads_head_enable',
        __( 'ON/OFF: Script <head> Google AdSense (Auto Ads)', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );

    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ads_head_code',
        __( 'Kode Script <head> Google AdSense', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 1: Header
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_header_enable',
        __( 'ON/OFF Slot 1: Bawah Header (Atas Halaman)', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_header_code',
        __( 'Kode Iklan Slot 1 (Bawah Header)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 2: In-Feed Daftar Loker
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_infeed_enable',
        __( 'ON/OFF Slot 2: Di Tengah Daftar Loker (In-Feed)', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_ad_infeed_after',
        __( 'Slot 2 Muncul Setelah Loker Ke-', 'hakajobspro' ),
        3, 'hakajobspro_adsense', 1, 20
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_infeed_code',
        __( 'Kode Iklan Slot 2 (In-Feed Daftar Loker)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 3: Atas Artikel Detail Loker
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_single_top_enable',
        __( 'ON/OFF Slot 3: Atas Artikel Detail Lowongan', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_single_top_code',
        __( 'Kode Iklan Slot 3 (Atas Detail Loker)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 4: Tengah Deskripsi Loker
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_single_mid_enable',
        __( 'ON/OFF Slot 4: Tengah Deskripsi Lowongan', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_number_setting( $wp_customize, 'hakajobspro_ad_single_mid_p',
        __( 'Slot 4 Muncul Setelah Paragraf Ke-', 'hakajobspro' ),
        2, 'hakajobspro_adsense', 1, 15
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_single_mid_code',
        __( 'Kode Iklan Slot 4 (Tengah Deskripsi Loker)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 5: Bawah Artikel / Sebelum Tombol Lamar
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_single_bottom_enable',
        __( 'ON/OFF Slot 5: Bawah Artikel / Sebelum Tombol Lamar', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_single_bottom_code',
        __( 'Kode Iklan Slot 5 (Bawah Artikel Loker)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 6: Sidebar Kanan
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_sidebar_enable',
        __( 'ON/OFF Slot 6: Sidebar Kanan', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_sidebar_code',
        __( 'Kode Iklan Slot 6 (Sidebar Kanan)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );

    // Slot 7: Atas Footer
    hakajobspro_add_checkbox_setting( $wp_customize, 'hakajobspro_ad_footer_enable',
        __( 'ON/OFF Slot 7: Atas Footer', 'hakajobspro' ),
        false, 'hakajobspro_adsense'
    );
    hakajobspro_add_ad_code_setting( $wp_customize, 'hakajobspro_ad_footer_code',
        __( 'Kode Iklan Slot 7 (Atas Footer)', 'hakajobspro' ),
        '', 'hakajobspro_adsense'
    );
}
add_action( 'customize_register', 'hakajobspro_customize_register' );

/* ==========================================================================
   HELPER FUNCTIONS UNTUK MENAMBAH SETTING
   ========================================================================== */

function hakajobspro_add_text_setting( $wpc, $id, $label, $default, $section, $input_type = 'text' ) {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => $input_type === 'url' ? 'esc_url_raw' :
                               ( $input_type === 'email' ? 'sanitize_email' : 'sanitize_text_field' ),
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'   => $label,
        'section' => $section,
        'type'    => 'text',
    ] );
}

function hakajobspro_add_textarea_setting( $wpc, $id, $label, $default, $section ) {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'   => $label,
        'section' => $section,
        'type'    => 'textarea',
    ] );
}

function hakajobspro_add_checkbox_setting( $wpc, $id, $label, $default, $section ) {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => function( $v ) { return (bool) $v; },
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'   => $label,
        'section' => $section,
        'type'    => 'checkbox',
    ] );
}

function hakajobspro_add_number_setting( $wpc, $id, $label, $default, $section, $min = 1, $max = 100 ) {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'       => $label,
        'section'     => $section,
        'type'        => 'number',
        'input_attrs' => [ 'min' => $min, 'max' => $max ],
    ] );
}

function hakajobspro_add_ad_code_setting( $wpc, $id, $label, $default, $section ) {
    $wpc->add_setting( $id, [
        'default'           => $default,
        'sanitize_callback' => 'hakajobspro_sanitize_ad_code',
        'transport'         => 'refresh',
    ] );
    $wpc->add_control( $id, [
        'label'       => $label,
        'section'     => $section,
        'type'        => 'textarea',
        'input_attrs' => [
            'placeholder' => 'Tempel kode AdSense (<ins class="adsbygoogle"...></ins>) atau HTML banner di sini...',
        ],
    ] );
}


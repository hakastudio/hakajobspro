<?php
/**
 * Custom Post Type: Lowongan Kerja (loker)
 * Taksonomi: Kategori Loker, Lokasi, Tipe Pekerjaan
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ==========================================================================
   CPT: LOKER
   ========================================================================== */

function hakajobspro_register_cpt_loker() {
    $labels = [
        'name'               => _x( 'Lowongan Kerja', 'post type general name', 'hakajobspro' ),
        'singular_name'      => _x( 'Lowongan Kerja', 'post type singular name', 'hakajobspro' ),
        'menu_name'          => __( 'Lowongan Kerja', 'hakajobspro' ),
        'name_admin_bar'     => __( 'Lowongan', 'hakajobspro' ),
        'add_new'            => __( 'Tambah Lowongan', 'hakajobspro' ),
        'add_new_item'       => __( 'Tambah Lowongan Baru', 'hakajobspro' ),
        'new_item'           => __( 'Lowongan Baru', 'hakajobspro' ),
        'edit_item'          => __( 'Edit Lowongan', 'hakajobspro' ),
        'view_item'          => __( 'Lihat Lowongan', 'hakajobspro' ),
        'all_items'          => __( 'Semua Lowongan', 'hakajobspro' ),
        'search_items'       => __( 'Cari Lowongan', 'hakajobspro' ),
        'not_found'          => __( 'Lowongan tidak ditemukan.', 'hakajobspro' ),
        'not_found_in_trash' => __( 'Tidak ada lowongan di sampah.', 'hakajobspro' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => [ 'slug' => 'lowongan', 'with_front' => false ],
        'capability_type'    => 'post',
        'has_archive'        => 'lowongan',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-id-alt',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'show_in_rest'       => true,
    ];

    register_post_type( 'loker', $args );
}
add_action( 'init', 'hakajobspro_register_cpt_loker' );

/* ==========================================================================
   TAKSONOMI: KATEGORI LOKER
   ========================================================================== */

function hakajobspro_register_taxonomies() {

    // Kategori Lowongan
    register_taxonomy( 'loker_kategori', 'loker', [
        'labels' => [
            'name'              => __( 'Kategori Loker', 'hakajobspro' ),
            'singular_name'     => __( 'Kategori', 'hakajobspro' ),
            'search_items'      => __( 'Cari Kategori', 'hakajobspro' ),
            'all_items'         => __( 'Semua Kategori', 'hakajobspro' ),
            'edit_item'         => __( 'Edit Kategori', 'hakajobspro' ),
            'update_item'       => __( 'Perbarui Kategori', 'hakajobspro' ),
            'add_new_item'      => __( 'Tambah Kategori Baru', 'hakajobspro' ),
            'new_item_name'     => __( 'Nama Kategori Baru', 'hakajobspro' ),
            'menu_name'         => __( 'Kategori', 'hakajobspro' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'kategori-loker' ],
        'show_in_rest'      => true,
    ] );

    // Lokasi
    register_taxonomy( 'loker_lokasi', 'loker', [
        'labels' => [
            'name'          => __( 'Lokasi', 'hakajobspro' ),
            'singular_name' => __( 'Lokasi', 'hakajobspro' ),
            'add_new_item'  => __( 'Tambah Lokasi Baru', 'hakajobspro' ),
            'edit_item'     => __( 'Edit Lokasi', 'hakajobspro' ),
            'all_items'     => __( 'Semua Lokasi', 'hakajobspro' ),
            'menu_name'     => __( 'Lokasi', 'hakajobspro' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'lokasi-loker' ],
        'show_in_rest'      => true,
    ] );

    // Tipe Pekerjaan
    register_taxonomy( 'loker_tipe', 'loker', [
        'labels' => [
            'name'          => __( 'Tipe Pekerjaan', 'hakajobspro' ),
            'singular_name' => __( 'Tipe', 'hakajobspro' ),
            'add_new_item'  => __( 'Tambah Tipe Baru', 'hakajobspro' ),
            'edit_item'     => __( 'Edit Tipe', 'hakajobspro' ),
            'all_items'     => __( 'Semua Tipe', 'hakajobspro' ),
            'menu_name'     => __( 'Tipe Pekerjaan', 'hakajobspro' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'tipe-loker' ],
        'show_in_rest'      => true,
    ] );

    // Perusahaan (Arsip Perusahaan & Riwayat Lowongan)
    register_taxonomy( 'loker_perusahaan', 'loker', [
        'labels' => [
            'name'          => __( 'Perusahaan', 'hakajobspro' ),
            'singular_name' => __( 'Perusahaan', 'hakajobspro' ),
            'search_items'  => __( 'Cari Perusahaan', 'hakajobspro' ),
            'all_items'     => __( 'Semua Perusahaan', 'hakajobspro' ),
            'edit_item'     => __( 'Edit Perusahaan', 'hakajobspro' ),
            'update_item'   => __( 'Perbarui Perusahaan', 'hakajobspro' ),
            'add_new_item'  => __( 'Tambah Perusahaan Baru', 'hakajobspro' ),
            'new_item_name' => __( 'Nama Perusahaan Baru', 'hakajobspro' ),
            'menu_name'     => __( 'Perusahaan', 'hakajobspro' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'perusahaan', 'with_front' => false ],
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'hakajobspro_register_taxonomies' );

/* ==========================================================================
   JADWAL OTOMATIS TUTUP LOWONGAN (AUTO-CLOSE LEWAT BATAS PENDAFTARAN)
   ========================================================================== */

function hakajobspro_schedule_auto_close() {
    if ( ! wp_next_scheduled( 'hakajobspro_cron_close_expired_loker' ) ) {
        wp_schedule_event( time(), 'hourly', 'hakajobspro_cron_close_expired_loker' );
    }
}
add_action( 'wp', 'hakajobspro_schedule_auto_close' );

function hakajobspro_run_auto_close_expired_loker() {
    if ( ! get_theme_mod( 'hakajobspro_auto_close_deadline', true ) ) {
        return;
    }

    $today = current_time( 'Y-m-d' );

    $expired_Main = new WP_Query( [
        'post_type'      => 'loker',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => '_loker_deadline',
                'value'   => '',
                'compare' => '!=',
            ],
            [
                'key'     => '_loker_deadline',
                'value'   => $today,
                'compare' => '<',
                'type'    => 'DATE',
            ],
            [
                'relation' => 'OR',
                [
                    'key'     => '_loker_status_close',
                    'compare' => 'NOT EXISTS',
                ],
                [
                    'key'     => '_loker_status_close',
                    'value'   => 'closed',
                    'compare' => '!=',
                ],
            ],
        ],
    ] );

    if ( ! empty( $expired_Main->posts ) ) {
        foreach ( $expired_Main->posts as $post_id ) {
            update_post_meta( $post_id, '_loker_status_close', 'closed' );
            update_post_meta( $post_id, '_loker_closed_at', $today );
        }
    }
}
add_action( 'hakajobspro_cron_close_expired_loker', 'hakajobspro_run_auto_close_expired_loker' );

/* Fallback ringan via transient setiap 1 jam agar tetap jalan meski WP-Cron tertunda */
function hakajobspro_maybe_close_expired_loker() {
    if ( false === get_transient( 'hakajobspro_auto_close_check' ) ) {
        hakajobspro_run_auto_close_expired_loker();
        set_transient( 'hakajobspro_auto_close_check', 1, HOUR_IN_SECONDS );
    }
}
add_action( 'init', 'hakajobspro_maybe_close_expired_loker', 20 );

/* Buat daftar term default otomatis (Lokasi, Kategori, Tipe) jika masih kosong agar dropdown filter langsung muncul */
function hakajobspro_seed_default_terms() {
    if ( ! get_option( 'hakajobspro_default_terms_seeded_v1' ) ) {
        $existing_lokasi = get_terms( [ 'taxonomy' => 'loker_lokasi', 'hide_empty' => false, 'fields' => 'ids' ] );
        if ( empty( $existing_lokasi ) || is_wp_error( $existing_lokasi ) ) {
            $default_lokasi = [
                'Bandar Lampung', 'Metro', 'Lampung Selatan', 'Lampung Tengah',
                'Lampung Timur', 'Lampung Utara', 'Pringsewu', 'Pesawaran',
                'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan',
                'Lampung Barat', 'Pesisir Barat', 'Mesuji', 'Jakarta',
                'Bandung', 'Surabaya', 'Bekasi', 'Tangerang', 'Palembang',
                'Remote / WFH', 'Seluruh Indonesia',
            ];
            foreach ( $default_lokasi as $lok ) {
                if ( ! term_exists( $lok, 'loker_lokasi' ) ) {
                    wp_insert_term( $lok, 'loker_lokasi' );
                }
            }
        }

        $existing_kat = get_terms( [ 'taxonomy' => 'loker_kategori', 'hide_empty' => false, 'fields' => 'ids' ] );
        if ( empty( $existing_kat ) || is_wp_error( $existing_kat ) ) {
            $default_kat = [
                'SMA / SMK', 'D3 / S1', 'Fresh Graduate', 'BUMN & Instansi',
                'Administrasi & Perkantoran', 'IT & Teknologi', 'Keuangan & Perbankan',
                'Pemasaran & Sales', 'Teknik & Industri', 'Kesehatan & Medis',
            ];
            foreach ( $default_kat as $kat ) {
                if ( ! term_exists( $kat, 'loker_kategori' ) ) {
                    wp_insert_term( $kat, 'loker_kategori' );
                }
            }
        }

        $existing_tipe = get_terms( [ 'taxonomy' => 'loker_tipe', 'hide_empty' => false, 'fields' => 'ids' ] );
        if ( empty( $existing_tipe ) || is_wp_error( $existing_tipe ) ) {
            $default_tipe = [
                'Full-time', 'Part-time', 'Kontrak', 'Magang', 'Freelance', 'Remote',
            ];
            foreach ( $default_tipe as $tp ) {
                if ( ! term_exists( $tp, 'loker_tipe' ) ) {
                    wp_insert_term( $tp, 'loker_tipe' );
                }
            }
        }

        update_option( 'hakajobspro_default_terms_seeded_v1', 1 );
    }
}
add_action( 'init', 'hakajobspro_seed_default_terms', 15 );

/* Flush rewrite rules sekali saat tema aktif */
function hakajobspro_flush_rewrite_rules() {
    hakajobspro_register_cpt_loker();
    hakajobspro_register_taxonomies();
    hakajobspro_seed_default_terms();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'hakajobspro_flush_rewrite_rules' );


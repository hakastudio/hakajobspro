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
   TAKSONOMI TERPISAH: KATEGORI PEKERJAAN, PENDIDIKAN, TIPE PEKERJAAN, LOKASI
   ========================================================================== */

function hakajobspro_register_taxonomies() {

    // 1. Kategori Pekerjaan (Bidang Profesi)
    register_taxonomy( 'loker_kategori', 'loker', [
        'labels' => [
            'name'              => __( 'Kategori Pekerjaan', 'hakajobspro' ),
            'singular_name'     => __( 'Kategori Pekerjaan', 'hakajobspro' ),
            'search_items'      => __( 'Cari Kategori Pekerjaan', 'hakajobspro' ),
            'all_items'         => __( 'Semua Kategori Pekerjaan', 'hakajobspro' ),
            'edit_item'         => __( 'Edit Kategori Pekerjaan', 'hakajobspro' ),
            'update_item'       => __( 'Perbarui Kategori Pekerjaan', 'hakajobspro' ),
            'add_new_item'      => __( 'Tambah Kategori Pekerjaan Baru', 'hakajobspro' ),
            'new_item_name'     => __( 'Nama Kategori Pekerjaan Baru', 'hakajobspro' ),
            'menu_name'         => __( 'Kategori Pekerjaan', 'hakajobspro' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'kategori-loker' ],
        'show_in_rest'      => true,
    ] );

    // 2. Pendidikan (Jenjang Pendidikan — Terpisah dari Kategori Pekerjaan)
    register_taxonomy( 'loker_pendidikan', 'loker', [
        'labels' => [
            'name'              => __( 'Pendidikan', 'hakajobspro' ),
            'singular_name'     => __( 'Pendidikan', 'hakajobspro' ),
            'search_items'      => __( 'Cari Jenjang Pendidikan', 'hakajobspro' ),
            'all_items'         => __( 'Semua Pendidikan', 'hakajobspro' ),
            'edit_item'         => __( 'Edit Pendidikan', 'hakajobspro' ),
            'update_item'       => __( 'Perbarui Pendidikan', 'hakajobspro' ),
            'add_new_item'      => __( 'Tambah Jenjang Pendidikan Baru', 'hakajobspro' ),
            'new_item_name'     => __( 'Nama Jenjang Pendidikan Baru', 'hakajobspro' ),
            'menu_name'         => __( 'Pendidikan', 'hakajobspro' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'pendidikan' ],
        'show_in_rest'      => true,
    ] );

    // 3. Tipe Pekerjaan (Checkbox di Sidebar Kanan — Tidak Double dengan Meta Box)
    register_taxonomy( 'loker_tipe', 'loker', [
        'labels' => [
            'name'          => __( 'Tipe Pekerjaan', 'hakajobspro' ),
            'singular_name' => __( 'Tipe Pekerjaan', 'hakajobspro' ),
            'search_items'  => __( 'Cari Tipe Pekerjaan', 'hakajobspro' ),
            'all_items'     => __( 'Semua Tipe Pekerjaan', 'hakajobspro' ),
            'edit_item'     => __( 'Edit Tipe Pekerjaan', 'hakajobspro' ),
            'update_item'   => __( 'Perbarui Tipe Pekerjaan', 'hakajobspro' ),
            'add_new_item'  => __( 'Tambah Tipe Pekerjaan Baru', 'hakajobspro' ),
            'new_item_name' => __( 'Nama Tipe Pekerjaan Baru', 'hakajobspro' ),
            'menu_name'     => __( 'Tipe Pekerjaan', 'hakajobspro' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'tipe-loker' ],
        'show_in_rest'      => true,
    ] );

    // 4. Lokasi / Kota (Checkbox di Sidebar Kanan)
    register_taxonomy( 'loker_lokasi', 'loker', [
        'labels' => [
            'name'          => __( 'Lokasi / Daerah', 'hakajobspro' ),
            'singular_name' => __( 'Lokasi', 'hakajobspro' ),
            'search_items'  => __( 'Cari Lokasi', 'hakajobspro' ),
            'all_items'     => __( 'Semua Lokasi', 'hakajobspro' ),
            'edit_item'     => __( 'Edit Lokasi', 'hakajobspro' ),
            'update_item'   => __( 'Perbarui Lokasi', 'hakajobspro' ),
            'add_new_item'  => __( 'Tambah Lokasi Baru', 'hakajobspro' ),
            'new_item_name' => __( 'Nama Lokasi Baru', 'hakajobspro' ),
            'menu_name'     => __( 'Lokasi', 'hakajobspro' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'lokasi-loker' ],
        'show_in_rest'      => true,
    ] );

    // 5. Perusahaan (Arsip Perusahaan & Riwayat Lowongan — Disembunyikan dari Sidebar Kanan agar tidak double dengan input Nama Perusahaan di Meta Box)
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
        'meta_box_cb'       => false,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'perusahaan', 'with_front' => false ],
        'show_in_rest'      => false,
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

/* Sembunyikan otomatis lowongan yang sudah tutup/melewati deadline pada kueri utama */
function hakajobspro_filter_expired_loker_queries( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( ! get_theme_mod( 'hakajobspro_hide_expired_loker', true ) ) {
        return;
    }

    $is_loker_query = $query->is_post_type_archive( 'loker' )
        || $query->is_tax( [ 'loker_kategori', 'loker_pendidikan', 'loker_lokasi', 'loker_tipe' ] )
        || ( $query->is_search() && $query->get( 'post_type' ) === 'loker' );

    if ( $is_loker_query && function_exists( 'hakajobspro_get_active_loker_meta_query' ) ) {
        $active_meta = hakajobspro_get_active_loker_meta_query();
        if ( ! empty( $active_meta ) ) {
            $meta_query   = $query->get( 'meta_query' ) ?: [];
            $meta_query[] = $active_meta;
            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'hakajobspro_filter_expired_loker_queries' );

/* Buat & pisahkan daftar term default otomatis (Kategori Pekerjaan, Pendidikan, Tipe Pekerjaan, Lokasi) */
function hakajobspro_seed_default_terms() {
    if ( ! get_option( 'hakajobspro_default_terms_seeded_v2' ) ) {

        // 1. Daftar default Jenjang Pendidikan (loker_pendidikan)
        $default_pendidikan = [
            'SMA / SMK',
            'D3',
            'S1 / D4',
            'D3 / S1',
            'SMP',
            'S2',
            'Semua Jenjang',
        ];
        foreach ( $default_pendidikan as $edu ) {
            if ( ! term_exists( $edu, 'loker_pendidikan' ) ) {
                wp_insert_term( $edu, 'loker_pendidikan' );
            }
        }

        // 2. Bersihkan term pendidikan ('SMA / SMK', 'D3 / S1') yang sebelumnya tercampur di loker_kategori
        $mixed_edu_slugs = [ 'sma-smk', 'd3-s1' ];
        foreach ( $mixed_edu_slugs as $edu_slug ) {
            $old_term = get_term_by( 'slug', $edu_slug, 'loker_kategori' );
            if ( $old_term && ! is_wp_error( $old_term ) ) {
                // Pindahkan post yang memakai term ini ke taksonomi loker_pendidikan
                $posts_with_old = get_posts( [
                    'post_type'      => 'loker',
                    'posts_per_page' => -1,
                    'post_status'    => 'any',
                    'fields'         => 'ids',
                    'tax_query'      => [
                        [
                            'taxonomy' => 'loker_kategori',
                            'field'    => 'term_id',
                            'terms'    => $old_term->term_id,
                        ],
                    ],
                ] );
                if ( ! empty( $posts_with_old ) ) {
                    foreach ( $posts_with_old as $pid ) {
                        wp_set_object_terms( $pid, $old_term->name, 'loker_pendidikan', true );
                    }
                }
                wp_delete_term( $old_term->term_id, 'loker_kategori' );
            }
        }

        // 3. Daftar default Kategori Pekerjaan murni (loker_kategori)
        $default_kat = [
            'Administrasi & Perkantoran',
            'IT & Teknologi',
            'Keuangan & Perbankan',
            'Pemasaran & Sales',
            'Teknik & Industri',
            'Logistik & Gudang',
            'Kesehatan & Medis',
            'Operator & Produksi',
            'BUMN & Instansi',
            'Pendidikan & Pelatihan',
            'Fresh Graduate',
        ];
        foreach ( $default_kat as $kat ) {
            if ( ! term_exists( $kat, 'loker_kategori' ) ) {
                wp_insert_term( $kat, 'loker_kategori' );
            }
        }

        // 4. Daftar default Tipe Pekerjaan (loker_tipe)
        $default_tipe = [
            'Full-time',
            'Part-time',
            'Kontrak',
            'Magang',
            'Freelance',
            'Remote',
        ];
        foreach ( $default_tipe as $tp ) {
            if ( ! term_exists( $tp, 'loker_tipe' ) ) {
                wp_insert_term( $tp, 'loker_tipe' );
            }
        }

        // 5. Daftar default Lokasi / Kota (loker_lokasi)
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

        // 6. Sinkronkan post loker lama yang sudah punya meta _loker_pendidikan & _loker_tipe_kerja ke taksonomi baru
        $all_loker_ids = get_posts( [
            'post_type'      => 'loker',
            'posts_per_page' => 200,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ] );
        if ( ! empty( $all_loker_ids ) ) {
            foreach ( $all_loker_ids as $pid ) {
                $meta_edu  = get_post_meta( $pid, '_loker_pendidikan', true );
                $meta_tipe = get_post_meta( $pid, '_loker_tipe_kerja', true );

                if ( $meta_edu ) {
                    $edu_label = ( $meta_edu === 'SMA/SMK' ) ? 'SMA / SMK' : $meta_edu;
                    $cur_edu   = wp_get_object_terms( $pid, 'loker_pendidikan', [ 'fields' => 'ids' ] );
                    if ( empty( $cur_edu ) || is_wp_error( $cur_edu ) ) {
                        wp_set_object_terms( $pid, $edu_label, 'loker_pendidikan', true );
                    }
                }
                if ( $meta_tipe ) {
                    $cur_tipe = wp_get_object_terms( $pid, 'loker_tipe', [ 'fields' => 'ids' ] );
                    if ( empty( $cur_tipe ) || is_wp_error( $cur_tipe ) ) {
                        wp_set_object_terms( $pid, $meta_tipe, 'loker_tipe', true );
                    }
                }
            }
        }

        update_option( 'hakajobspro_default_terms_seeded_v1', 1 );
        update_option( 'hakajobspro_default_terms_seeded_v2', 1 );
        flush_rewrite_rules();
    }
}
add_action( 'init', 'hakajobspro_seed_default_terms', 15 );

/* Gunakan template archive-loker.php untuk halaman arsip taksonomi Kategori, Pendidikan, Lokasi, & Tipe,
   serta aktifkan rute langsung /perusahaan/, /daerah/ (/lokasi/), dan /pendidikan/ untuk Halaman Direktori */
function hakajobspro_get_directory_route() {
    $req_uri   = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
    $req_path  = trim( (string) wp_parse_url( $req_uri, PHP_URL_PATH ), '/' );
    $home_path = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
    if ( $home_path && strpos( $req_path, $home_path ) === 0 ) {
        $req_path = trim( substr( $req_path, strlen( $home_path ) ), '/' );
    }

    if ( $req_path === 'perusahaan' ) {
        return 'perusahaan';
    }
    if ( in_array( $req_path, [ 'daerah', 'lokasi', 'lokasi-loker' ], true ) ) {
        return 'daerah';
    }
    if ( in_array( $req_path, [ 'pendidikan', 'pendidikan-loker' ], true ) ) {
        return 'pendidikan';
    }

    // Dukung juga sub-path langsung /daerah/{slug}, /lokasi-loker/{slug}, /pendidikan/{slug}, /pendidikan-loker/{slug}, /kategori-loker/{slug}, /tipe-loker/{slug}
    // agar tidak pernah 404 meskipun permalink belum di-flush
    if ( preg_match( '#^(daerah|lokasi|lokasi-loker)/([^/]+)$#i', $req_path, $m ) ) {
        $_GET['loker_lokasi'] = sanitize_key( $m[2] );
        return 'archive_loker_filtered';
    }
    if ( preg_match( '#^(pendidikan|pendidikan-loker)/([^/]+)$#i', $req_path, $m ) ) {
        $_GET['loker_pendidikan'] = sanitize_key( $m[2] );
        return 'archive_loker_filtered';
    }
    if ( preg_match( '#^(kategori|kategori-loker)/([^/]+)$#i', $req_path, $m ) ) {
        $_GET['loker_kategori'] = sanitize_key( $m[2] );
        return 'archive_loker_filtered';
    }
    if ( preg_match( '#^(tipe|tipe-loker)/([^/]+)$#i', $req_path, $m ) ) {
        $_GET['loker_tipe'] = sanitize_key( $m[2] );
        return 'archive_loker_filtered';
    }

    return '';
}

function hakajobspro_is_perusahaan_directory_request() {
    return hakajobspro_get_directory_route() === 'perusahaan';
}

function hakajobspro_prevent_perusahaan_dir_404( $preempt, $wp_query ) {
    $route = hakajobspro_get_directory_route();
    if ( $route ) {
        $wp_query->is_404     = false;
        $wp_query->is_archive = true;
        $wp_query->is_home    = false;
        status_header( 200 );
        return true;
    }
    return $preempt;
}
add_filter( 'pre_handle_404', 'hakajobspro_prevent_perusahaan_dir_404', 10, 2 );

function hakajobspro_perusahaan_dir_title( $title ) {
    $route = hakajobspro_get_directory_route();
    if ( 'perusahaan' === $route ) {
        $title['title'] = __( 'Daftar Perusahaan', 'hakajobspro' );
    } elseif ( 'daerah' === $route ) {
        $title['title'] = __( 'Lowongan Kerja Berdasarkan Daerah / Lokasi', 'hakajobspro' );
    } elseif ( 'pendidikan' === $route ) {
        $title['title'] = __( 'Lowongan Kerja Berdasarkan Pendidikan', 'hakajobspro' );
    }
    return $title;
}
add_filter( 'document_title_parts', 'hakajobspro_perusahaan_dir_title' );

function hakajobspro_taxonomy_template_include( $template ) {
    $route = hakajobspro_get_directory_route();
    if ( $route ) {
        global $wp_query;
        if ( $wp_query ) {
            $wp_query->is_404  = false;
            $wp_query->is_home = false;
        }
        status_header( 200 );

        if ( 'perusahaan' === $route ) {
            $tpl = locate_template( 'page-perusahaan.php' );
            if ( $tpl ) return $tpl;
        } elseif ( 'daerah' === $route ) {
            $tpl = locate_template( 'page-daerah.php' );
            if ( $tpl ) return $tpl;
        } elseif ( 'pendidikan' === $route ) {
            $tpl = locate_template( 'page-pendidikan.php' );
            if ( $tpl ) return $tpl;
        } elseif ( 'archive_loker_filtered' === $route ) {
            $tpl = locate_template( 'archive-loker.php' );
            if ( $tpl ) return $tpl;
        }
    }
    if ( is_tax( [ 'loker_kategori', 'loker_pendidikan', 'loker_lokasi', 'loker_tipe' ] ) ) {
        $archive_loker = locate_template( 'archive-loker.php' );
        if ( $archive_loker ) {
            return $archive_loker;
        }
    }
    return $template;
}
add_filter( 'template_include', 'hakajobspro_taxonomy_template_include', 99 );

/* Flush rewrite rules sekali saat tema aktif */
function hakajobspro_flush_rewrite_rules() {
    hakajobspro_register_cpt_loker();
    hakajobspro_register_taxonomies();
    hakajobspro_seed_default_terms();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'hakajobspro_flush_rewrite_rules' );


<?php
/**
 * Template tags dan helper functions untuk tema Hakajobspro.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ==========================================================================
   FORMAT GAJI
   ========================================================================== */

function hakajobspro_format_gaji( $min, $max ) {
    if ( ! $min && ! $max ) return '';

    $fmt = function( $n ) {
        if ( $n >= 1000000 ) {
            return 'Rp ' . number_format( $n / 1000000, 1, ',', '.' ) . ' Jt';
        }
        return 'Rp ' . number_format( $n, 0, ',', '.' );
    };

    if ( $min && $max ) {
        return $fmt( $min ) . ' - ' . $fmt( $max );
    }
    return $min ? $fmt( $min ) . ' +' : 'Hingga ' . $fmt( $max );
}

/* ==========================================================================
   BADGE LOKER BARU
   ========================================================================== */

function hakajobspro_is_new_loker( $post_id = null ) {
    $post_id  = $post_id ?: get_the_ID();
    $days     = absint( get_theme_mod( 'hakajobspro_new_days', 7 ) );
    $created  = get_post_datetime( $post_id );
    if ( ! $created ) return false;
    $diff = ( time() - $created->getTimestamp() ) / DAY_IN_SECONDS;
    return $diff <= $days;
}

/* ==========================================================================
   CEK APAKAH LOKER SUDAH KADALUARSA / DITUTUP OTOMATIS
   ========================================================================== */

function hakajobspro_is_expired( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    if ( ! get_theme_mod( 'hakajobspro_auto_close_deadline', true ) ) {
        return false;
    }

    $status_close = get_post_meta( $post_id, '_loker_status_close', true );
    $deadline     = get_post_meta( $post_id, '_loker_deadline', true );

    if ( $deadline ) {
        return $deadline < current_time( 'Y-m-d' );
    }

    return $status_close === 'closed';
}

/* ==========================================================================
   HELPER: AMBIL URL ARSIP PERUSAHAAN DARI POST LOKER
   ========================================================================== */

function hakajobspro_get_perusahaan_url( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $terms   = get_the_terms( $post_id, 'loker_perusahaan' );
    if ( $terms && ! is_wp_error( $terms ) ) {
        $link = get_term_link( $terms[0], 'loker_perusahaan' );
        if ( ! is_wp_error( $link ) ) {
            return $link;
        }
    }

    $perusahaan = get_post_meta( $post_id, '_loker_perusahaan', true );
    if ( $perusahaan ) {
        $term = get_term_by( 'name', $perusahaan, 'loker_perusahaan' );
        if ( $term && ! is_wp_error( $term ) ) {
            $link = get_term_link( $term, 'loker_perusahaan' );
            if ( ! is_wp_error( $link ) ) {
                return $link;
            }
        }
    }

    return '';
}

/* ==========================================================================
   RENDER BADGE LOKER
   ========================================================================== */

function hakajobspro_badges( $post_id = null ) {
    $post_id  = $post_id ?: get_the_ID();
    $featured = get_post_meta( $post_id, '_loker_featured', true );
    $expired  = hakajobspro_is_expired( $post_id );
    $output   = '';

    if ( $expired ) {
        $closed_label = esc_html( get_theme_mod( 'hakajobspro_badge_closed', __( 'Ditutup', 'hakajobspro' ) ) );
        $output .= '<span class="badge badge--expired">' . $closed_label . '</span>';
        return $output;
    }

    if ( $featured === 'Ya' ) {
        $label   = esc_html( get_theme_mod( 'hakajobspro_badge_featured', __( 'Unggulan', 'hakajobspro' ) ) );
        $output .= '<span class="badge badge--featured">' . $label . '</span>';
    }
    if ( hakajobspro_is_new_loker( $post_id ) ) {
        $label   = esc_html( get_theme_mod( 'hakajobspro_badge_new', __( 'Baru', 'hakajobspro' ) ) );
        $output .= '<span class="badge badge--new">' . $label . '</span>';
    }

    return $output;
}

/* ==========================================================================
   RENDER IKON MEDIA SOSIAL (SVG INLINE SEDERHANA)
   ========================================================================== */

function hakajobspro_social_icon( $platform ) {
    $icons = [
        'facebook'  => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        'instagram' => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        'twitter'   => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'youtube'   => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon fill="#fff" points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>',
        'tiktok'    => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.17 8.17 0 0 0 4.78 1.53V6.78a4.85 4.85 0 0 1-1.01-.09z"/></svg>',
        'telegram'  => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21.93 3.24L2.56 10.76c-1.28.51-1.27 1.22-.23 1.54l4.84 1.51 11.2-7.07c.53-.32 1.01-.15.61.2L9.55 15.11l-.42 4.98c.62 0 .89-.28 1.23-.61l2.96-2.87 4.91 3.63c.9.5 1.56.24 1.78-.83l3.22-15.2c.33-1.31-.5-1.9-1.3-1.57z"/></svg>',
        'whatsapp'  => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.093.535 4.065 1.476 5.783L0 24l6.387-1.454A11.93 11.93 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.72 9.72 0 0 1-5.028-1.396l-.36-.214-3.74.98.999-3.648-.235-.375A9.72 9.72 0 0 1 2.25 12c0-5.376 4.374-9.75 9.75-9.75S21.75 6.624 21.75 12s-4.374 9.75-9.75 9.75z"/></svg>',
        'linkedin'  => '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>',
    ];
    return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}

/* ==========================================================================
   PAGINATION
   ========================================================================== */

function hakajobspro_pagination( $query = null ) {
    global $wp_query;
    $q = $query ?: $wp_query;

    $total   = (int) $q->max_num_pages;
    $current = max( 1, get_query_var( 'paged' ) );

    if ( $total <= 1 ) return;

    echo '<nav class="pagination" aria-label="' . esc_attr__( 'Navigasi halaman', 'hakajobspro' ) . '">';
    echo paginate_links( [
        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => $total,
        'prev_text' => '&lsaquo; ' . __( 'Sebelumnya', 'hakajobspro' ),
        'next_text' => __( 'Berikutnya', 'hakajobspro' ) . ' &rsaquo;',
        'type'      => 'plain',
    ] );
    echo '</nav>';
}

/* ==========================================================================
   BREADCRUMB SEDERHANA
   ========================================================================== */

function hakajobspro_breadcrumb() {
    if ( is_front_page() ) return;

    echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'hakajobspro' ) . '">';
    echo '<ol class="breadcrumb__list">';
    echo '<li><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Beranda', 'hakajobspro' ) . '</a></li>';

    if ( is_singular( 'loker' ) ) {
        echo '<li><a href="' . esc_url( get_post_type_archive_link( 'loker' ) ) . '">'
            . esc_html__( 'Lowongan Kerja', 'hakajobspro' ) . '</a></li>';
        $co_name = get_post_meta( get_the_ID(), '_loker_perusahaan', true );
        $co_url  = hakajobspro_get_perusahaan_url( get_the_ID() );
        if ( $co_name && $co_url ) {
            echo '<li><a href="' . esc_url( $co_url ) . '">' . esc_html( $co_name ) . '</a></li>';
        }
        echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_tax( 'loker_perusahaan' ) ) {
        echo '<li aria-current="page">' . sprintf( esc_html__( 'Arsip Perusahaan: %s', 'hakajobspro' ), esc_html( single_term_title( '', false ) ) ) . '</li>';
    } elseif ( is_post_type_archive( 'loker' ) || is_tax( [ 'loker_kategori', 'loker_lokasi', 'loker_tipe' ] ) ) {
        echo '<li aria-current="page">' . esc_html( get_the_archive_title() ) . '</li>';
    } elseif ( is_page() ) {
        echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_search() ) {
        echo '<li aria-current="page">' . sprintf(
            esc_html__( 'Hasil Pencarian: %s', 'hakajobspro' ),
            esc_html( get_search_query() )
        ) . '</li>';
    }

    echo '</ol></nav>';
}

/* ==========================================================================
   HELPER: CEK ADA SIDEBAR AKTIF
   ========================================================================== */

function hakajobspro_has_sidebar() {
    return ! is_page_template( 'page-fullwidth.php' );
}

/* ==========================================================================
   PENGHITUNG TAYANGAN (VIEWS) & WIDGET LOKER POPULER
   ========================================================================== */

function hakajobspro_track_loker_views() {
    if ( ! is_singular( 'loker' ) || is_admin() ) {
        return;
    }
    $post_id = get_queried_object_id();
    if ( ! $post_id ) return;
    $views = (int) get_post_meta( $post_id, '_loker_views', true );
    update_post_meta( $post_id, '_loker_views', $views + 1 );
}
add_action( 'wp', 'hakajobspro_track_loker_views' );

function hakajobspro_render_popular_loker_widget( $count = 5 ) {
    $exclude_id = is_singular( 'loker' ) ? [ get_the_ID() ] : [];

    // Ambil loker berdasarkan jumlah tayangan (_loker_views) tertinggi
    $pop_query = new WP_Query( [
        'post_type'      => 'loker',
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'post__not_in'   => $exclude_id,
        'meta_key'       => '_loker_views',
        'orderby'        => [ 'meta_value_num' => 'DESC', 'date' => 'DESC' ],
    ] );

    // Fallback jika belum ada data views (situs baru): ambil loker terbaru/unggulan
    if ( ! $pop_query->have_posts() ) {
        $pop_query = new WP_Query( [
            'post_type'      => 'loker',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'post__not_in'   => $exclude_id,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );
    }

    if ( ! $pop_query->have_posts() ) {
        return;
    }
    ?>
    <div class="widget widget-popular-loker">
      <h3 class="widget-title"><?php echo esc_html( get_theme_mod( 'hakajobspro_popular_widget_title', __( 'Loker Populer', 'hakajobspro' ) ) ); ?></h3>
      <div class="widget-content">
        <ul class="widget-loker-recent">
          <?php while ( $pop_query->have_posts() ) : $pop_query->the_post();
            $pid     = get_the_ID();
            $co      = get_post_meta( $pid, '_loker_perusahaan', true );
            $lok     = get_post_meta( $pid, '_loker_lokasi_detail', true );
            $tipe    = get_post_meta( $pid, '_loker_tipe_kerja', true );
            $initial = $co ? strtoupper( mb_substr( $co, 0, 1 ) ) : 'L';
            if ( ! $lok ) {
                $locs = get_the_terms( $pid, 'loker_lokasi' );
                if ( $locs && ! is_wp_error( $locs ) ) $lok = $locs[0]->name;
            }
          ?>
          <li>
            <?php if ( has_post_thumbnail( $pid ) ) : ?>
              <?php the_post_thumbnail( 'company-logo', [ 'alt' => esc_attr( $co ) ] ); ?>
            <?php else : ?>
              <div class="loker-card__logo-placeholder" style="width:46px;height:46px;font-size:15px;" aria-hidden="true">
                <?php echo esc_html( $initial ); ?>
              </div>
            <?php endif; ?>
            <div style="flex:1;min-width:0;">
              <a href="<?php the_permalink(); ?>" class="title">
                <?php the_title(); ?>
              </a>
              <div class="meta">
                <?php echo esc_html( $co ); ?>
                <?php if ( $lok ) echo ' &bull; ' . esc_html( $lok ); ?>
                <?php if ( $tipe ) echo ' &bull; ' . esc_html( $tipe ); ?>
              </div>
            </div>
          </li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>
    </div>
    <?php
}

/* ==========================================================================
   SCHEMA JOBPOSTING JSON-LD (STANDAR GOOGLE JOBS + ANTI-BENTROK YOAST/RANKMATH)
   ========================================================================== */

function hakajobspro_output_jobposting_schema() {
    if ( ! is_singular( 'loker' ) ) {
        return;
    }

    // Bisa dimatikan dari WP Customizer jika ingin 100% menyerahkan ke plugin SEO
    if ( ! get_theme_mod( 'hakajobspro_enable_job_schema', true ) ) {
        return;
    }

    $post_id = get_the_ID();

    // Deteksi otomatis anti-bentrok dengan Rank Math & Yoast SEO:
    // Jika pada post ini user sudah mengaktifkan JobPosting schema dari Rank Math,
    // maka tema otomatis tidak mencetak duplikat JobPosting JSON-LD.
    $rm_snippet = get_post_meta( $post_id, 'rank_math_rich_snippet', true );
    $rm_schemas = get_post_meta( $post_id, 'rank_math_schema_JobPosting', true );
    if ( $rm_snippet === 'jobposting' || ! empty( $rm_schemas ) ) {
        return;
    }

    $perusahaan = get_post_meta( $post_id, '_loker_perusahaan', true ) ?: get_bloginfo( 'name' );
    $website    = get_post_meta( $post_id, '_loker_website', true ) ?: home_url();
    $lokasi     = get_post_meta( $post_id, '_loker_lokasi_detail', true );
    $tipe       = get_post_meta( $post_id, '_loker_tipe_kerja', true );
    $gaji_min   = absint( get_post_meta( $post_id, '_loker_gaji_min', true ) );
    $gaji_max   = absint( get_post_meta( $post_id, '_loker_gaji_max', true ) );
    $deadline   = get_post_meta( $post_id, '_loker_deadline', true );
    $link_lamar = get_post_meta( $post_id, '_loker_link_lamar', true );

    if ( ! $lokasi ) {
        $locs = get_the_terms( $post_id, 'loker_lokasi' );
        if ( $locs && ! is_wp_error( $locs ) ) {
            $lokasi = $locs[0]->name;
        }
    }

    // Petakan tipe pekerjaan ke enum resmi Google JobPosting
    $emp_map = [
        'Full-time'  => 'FULL_TIME',
        'Part-time'  => 'PART_TIME',
        'Kontrak'    => 'CONTRACTOR',
        'Freelance'  => 'FREE_LANCE',
        'Internship' => 'INTERN',
        'Remote'     => 'FULL_TIME',
    ];
    $employment_type = isset( $emp_map[ $tipe ] ) ? $emp_map[ $tipe ] : 'FULL_TIME';

    $org_data = [
        '@type'  => 'Organization',
        'name'   => $perusahaan,
        'sameAs' => esc_url( $website ),
    ];
    if ( has_post_thumbnail( $post_id ) ) {
        $logo_url = get_the_post_thumbnail_url( $post_id, 'company-logo' );
        if ( $logo_url ) {
            $org_data['logo'] = esc_url( $logo_url );
        }
    }

    $schema = [
        '@context'           => 'https://schema.org/',
        '@type'              => 'JobPosting',
        'title'              => get_the_title( $post_id ),
        'description'        => wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) ) ),
        'datePosted'         => get_the_date( 'c', $post_id ),
        'employmentType'     => $employment_type,
        'hiringOrganization' => $org_data,
        'jobLocation'        => [
            '@type'   => 'Place',
            'address' => [
                '@type'           => 'PostalAddress',
                'addressLocality' => $lokasi ?: 'Indonesia',
                'addressCountry'  => 'ID',
            ],
        ],
    ];

    if ( $tipe === 'Remote' ) {
        $schema['jobLocationType'] = 'TELECOMMUTE';
        $schema['applicantLocationRequirements'] = [
            '@type' => 'Country',
            'name'  => 'ID',
        ];
    }

    if ( $deadline ) {
        $schema['validThrough'] = $deadline . 'T23:59:59+07:00';
    }

    if ( $gaji_min || $gaji_max ) {
        $val_data = [
            '@type'    => 'QuantitativeValue',
            'unitText' => 'MONTH',
        ];
        if ( $gaji_min && $gaji_max ) {
            $val_data['minValue'] = $gaji_min;
            $val_data['maxValue'] = $gaji_max;
        } else {
            $val_data['value'] = $gaji_min ?: $gaji_max;
        }
        $schema['baseSalary'] = [
            '@type'    => 'MonetaryAmount',
            'currency' => 'IDR',
            'value'    => $val_data,
        ];
    }

    if ( $link_lamar ) {
        $schema['directApply'] = true;
    }

    echo "\n" . '<script type="application/ld+json" id="hakajobspro-jobposting-schema">'
        . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
        . '</script>' . "\n";
}
add_action( 'wp_head', 'hakajobspro_output_jobposting_schema', 20 );

/* ==========================================================================
   GENERATOR RANGKUMAN TEKS SIARAN SALURAN WHATSAPP (KHUSUS ADMIN)
   ========================================================================== */

function hakajobspro_get_wa_broadcast_text( $post_id = null ) {
    $post_id    = $post_id ?: get_the_ID();
    $title      = get_the_title( $post_id );
    $perusahaan = get_post_meta( $post_id, '_loker_perusahaan', true );
    $lokasi     = get_post_meta( $post_id, '_loker_lokasi_detail', true );
    $tipe       = get_post_meta( $post_id, '_loker_tipe_kerja', true );
    $gaji_min   = get_post_meta( $post_id, '_loker_gaji_min', true );
    $gaji_max   = get_post_meta( $post_id, '_loker_gaji_max', true );
    $pendidikan = get_post_meta( $post_id, '_loker_pendidikan', true );
    $pengalaman = get_post_meta( $post_id, '_loker_pengalaman', true );
    $deadline   = get_post_meta( $post_id, '_loker_deadline', true );
    $gaji_str   = hakajobspro_format_gaji( $gaji_min, $gaji_max );
    $permalink  = get_permalink( $post_id );
    $channel    = get_theme_mod( 'hakajobspro_wa_channel_url', 'https://whatsapp.com/channel/' );

    if ( ! $lokasi ) {
        $locs = get_the_terms( $post_id, 'loker_lokasi' );
        if ( $locs && ! is_wp_error( $locs ) ) {
            $lokasi = $locs[0]->name;
        }
    }

    $lines   = [];
    $lines[] = '*INFO LOWONGAN KERJA TERBARU*';
    $lines[] = '';
    $lines[] = '- Posisi: *' . $title . '*';
    if ( $perusahaan ) $lines[] = '- Perusahaan: *' . $perusahaan . '*';
    if ( $tipe )       $lines[] = '- Tipe Pekerjaan: ' . $tipe;
    if ( $lokasi )     $lines[] = '- Lokasi: ' . $lokasi;
    if ( $pendidikan ) $lines[] = '- Pendidikan: ' . $pendidikan;
    if ( $pengalaman ) $lines[] = '- Pengalaman: ' . $pengalaman;
    if ( $gaji_str )   $lines[] = '- Kisaran Gaji: ' . $gaji_str;
    if ( $deadline )   $lines[] = '- Deadline: ' . date_i18n( 'd F Y', strtotime( $deadline ) );

    $lines[] = '';
    $lines[] = '*Detail Kualifikasi & Cara Melamar:*';
    $lines[] = $permalink;

    if ( $channel ) {
        $lines[] = '';
        $lines[] = '*Gabung Saluran WhatsApp:*';
        $lines[] = $channel;
    }

    return implode( "\n", $lines );
}

/* ==========================================================================
   TOOLBAR ALAT ADMIN: SIARKAN LOKER KE SALURAN WHATSAPP
   (Hanya tampil jika login sebagai Admin / Editor)
   ========================================================================== */

function hakajobspro_render_admin_wa_bar( $post_id = null ) {
    if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
        return;
    }

    $post_id     = $post_id ?: get_the_ID();
    $summary_txt = hakajobspro_get_wa_broadcast_text( $post_id );
    $wa_send_url = 'https://api.whatsapp.com/send?text=' . rawurlencode( $summary_txt );
    $channel_url = get_theme_mod( 'hakajobspro_wa_channel_url', 'https://whatsapp.com/channel/' );
    $edit_url    = get_edit_post_link( $post_id );
    ?>
    <div class="admin-wa-bar" role="region" aria-label="<?php esc_attr_e( 'Alat Admin Siaran WhatsApp', 'hakajobspro' ); ?>">
      <div class="admin-wa-bar__left">
        <span class="admin-wa-badge">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <?php esc_html_e( 'ALAT ADMIN', 'hakajobspro' ); ?>
        </span>
        <div class="admin-wa-bar__text">
          <strong><?php esc_html_e( 'Siarkan Loker ke Saluran WhatsApp', 'hakajobspro' ); ?></strong>
          <span><?php esc_html_e( 'Format teks siap kirim lengkap dengan ringkasan detail lowongan dan link channel.', 'hakajobspro' ); ?></span>
        </div>
      </div>

      <div class="admin-wa-bar__actions">
        <a href="<?php echo esc_url( $wa_send_url ); ?>" target="_blank" rel="noopener" class="admin-wa-btn admin-wa-btn--green">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          <?php esc_html_e( 'Kirim ke WhatsApp', 'hakajobspro' ); ?>
        </a>

        <button type="button" class="admin-wa-btn admin-wa-btn--copy" data-wa-copy="<?php echo esc_attr( $summary_txt ); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          <span class="copy-label"><?php esc_html_e( 'Salin Teks', 'hakajobspro' ); ?></span>
        </button>

        <?php if ( $channel_url ) : ?>
        <a href="<?php echo esc_url( $channel_url ); ?>" target="_blank" rel="noopener" class="admin-wa-btn admin-wa-btn--blue">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          <?php esc_html_e( 'Buka Saluran', 'hakajobspro' ); ?>
        </a>
        <?php endif; ?>

        <?php if ( $edit_url ) : ?>
        <a href="<?php echo esc_url( $edit_url ); ?>" class="admin-wa-btn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          <?php esc_html_e( 'Edit Loker', 'hakajobspro' ); ?>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php
}

/* ==========================================================================
   BOX AJAKAN GABUNG SALURAN WHATSAPP DI AKHIR ARTIKEL LOKER
   ========================================================================== */

function hakajobspro_render_wa_channel_cta() {
    if ( ! get_theme_mod( 'hakajobspro_show_wa_channel_box', true ) ) {
        return;
    }

    $channel_url = get_theme_mod( 'hakajobspro_wa_channel_url', 'https://whatsapp.com/channel/' );
    if ( ! $channel_url ) {
        return;
    }

    $title = get_theme_mod( 'hakajobspro_wa_channel_title', __( 'Jangan Ketinggalan Info Loker Terbaru!', 'hakajobspro' ) );
    $desc  = get_theme_mod( 'hakajobspro_wa_channel_desc', __( 'Gabung ke Saluran WhatsApp resmi kami untuk mendapatkan notifikasi lowongan kerja terverifikasi setiap hari langsung di WhatsApp Anda.', 'hakajobspro' ) );
    $btn   = get_theme_mod( 'hakajobspro_wa_channel_btn', __( 'Gabung Saluran WhatsApp', 'hakajobspro' ) );
    ?>
    <div class="wa-channel-cta">
      <div class="wa-channel-cta__icon" aria-hidden="true">
        <?php echo hakajobspro_social_icon( 'whatsapp' ); ?>
      </div>
      <div class="wa-channel-cta__body">
        <strong><?php echo esc_html( $title ); ?></strong>
        <p><?php echo esc_html( $desc ); ?></p>
      </div>
      <a href="<?php echo esc_url( $channel_url ); ?>" target="_blank" rel="noopener" class="wa-channel-cta__btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        <?php echo esc_html( $btn ); ?>
      </a>
    </div>
    <?php
}


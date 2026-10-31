<?php
/**
 * Meta Boxes untuk data detail Lowongan Kerja
 * Field: perusahaan, gaji, lokasi detail, tipe, pendidikan,
 *        pengalaman, deadline, cara melamar, link melamar.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ==========================================================================
   DAFTARKAN META BOX
   ========================================================================== */

function hakajobspro_add_meta_boxes() {
    add_meta_box(
        'hakajobspro_detail_loker',
        __( 'Detail Lowongan', 'hakajobspro' ),
        'hakajobspro_render_meta_box',
        'loker',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'hakajobspro_add_meta_boxes' );

/* ==========================================================================
   RENDER META BOX
   ========================================================================== */

function hakajobspro_render_meta_box( $post ) {
    wp_nonce_field( 'hakajobspro_save_meta', 'hakajobspro_meta_nonce' );

    $fields = hakajobspro_meta_fields();
    $values = [];
    foreach ( $fields as $key => $field ) {
        $values[ $key ] = get_post_meta( $post->ID, '_' . $key, true );
    }
    ?>
    <style>
    .hakajobspro-meta-info{background:#f0f6fc;border-left:4px solid #1d3557;padding:10px 14px;margin-bottom:12px;border-radius:4px;font-size:12.5px;color:#1e293b;line-height:1.5;}
    .hakajobspro-meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px 20px;padding:6px 0 10px;}
    .hakajobspro-meta-grid label{display:block;font-weight:600;margin-bottom:4px;font-size:13px;}
    .hakajobspro-meta-grid input,
    .hakajobspro-meta-grid select,
    .hakajobspro-meta-grid textarea{width:100%;padding:6px 8px;border:1px solid #ddd;border-radius:4px;font-size:13px;}
    .hakajobspro-meta-grid .full{grid-column:1/-1;}
    .hakajobspro-meta-note{font-size:12px;color:#888;margin-top:3px;}
    </style>
    <div class="hakajobspro-meta-info">
        <strong>Panduan Pengisian:</strong> Untuk <strong>Kategori Pekerjaan</strong>, <strong>Pendidikan</strong>, <strong>Tipe Pekerjaan</strong>, dan <strong>Lokasi Kota/Kabupaten</strong> silakan centang langsung pada kotak masing-masing di <strong>Sidebar Kanan</strong> (sudah dipisahkan agar tidak ganda/double).
    </div>
    <div class="hakajobspro-meta-grid">
    <?php foreach ( $fields as $key => $field ) :
        $value = isset( $values[ $key ] ) ? $values[ $key ] : '';
        $full  = ! empty( $field['full'] ) ? 'full' : '';
        ?>
        <div class="<?php echo esc_attr( $full ); ?>">
            <label for="hakajobspro_<?php echo esc_attr( $key ); ?>">
                <?php echo esc_html( $field['label'] ); ?>
            </label>

            <?php if ( $field['type'] === 'select' ) : ?>
                <select id="hakajobspro_<?php echo esc_attr( $key ); ?>"
                        name="hakajobspro_<?php echo esc_attr( $key ); ?>">
                    <option value="">-- Pilih --</option>
                    <?php foreach ( $field['options'] as $opt ) : ?>
                        <option value="<?php echo esc_attr( $opt ); ?>"
                            <?php selected( $value, $opt ); ?>>
                            <?php echo esc_html( $opt ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            <?php elseif ( $field['type'] === 'textarea' ) : ?>
                <textarea id="hakajobspro_<?php echo esc_attr( $key ); ?>"
                          name="hakajobspro_<?php echo esc_attr( $key ); ?>"
                          rows="3"><?php echo esc_textarea( $value ); ?></textarea>

            <?php else : ?>
                <input type="<?php echo esc_attr( $field['type'] ); ?>"
                       id="hakajobspro_<?php echo esc_attr( $key ); ?>"
                       name="hakajobspro_<?php echo esc_attr( $key ); ?>"
                       value="<?php echo esc_attr( $value ); ?>">
            <?php endif; ?>

            <?php if ( ! empty( $field['note'] ) ) : ?>
                <p class="hakajobspro-meta-note"><?php echo esc_html( $field['note'] ); ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>

    <?php if ( $post->post_status === 'publish' && function_exists( 'hakajobspro_get_wa_broadcast_text' ) ) :
        $wa_txt  = hakajobspro_get_wa_broadcast_text( $post->ID );
        $wa_url  = 'https://api.whatsapp.com/send?text=' . rawurlencode( $wa_txt );
        $ch_url  = get_theme_mod( 'hakajobspro_wa_channel_url', 'https://whatsapp.com/channel/' );
    ?>
    <div style="margin-top:16px;background:#0f172a;color:#fff;padding:14px 18px;border-radius:10px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:12px;">
        <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;border:1px solid rgba(56,189,248,.4);background:rgba(56,189,248,.12);color:#38bdf8;font-size:11px;font-weight:700;letter-spacing:.5px;">ALAT ADMIN</span>
        <div>
          <strong style="display:block;font-size:14px;color:#fff;">Siarkan Loker ke Saluran WhatsApp</strong>
          <span style="font-size:12px;color:#94a3b8;">Format teks siap kirim lengkap dengan ringkasan detail lowongan dan link channel.</span>
        </div>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener" style="background:#16a34a;color:#fff;text-decoration:none;padding:8px 14px;border-radius:6px;font-size:12.5px;font-weight:600;">Kirim ke WhatsApp</a>
        <button type="button" onclick="navigator.clipboard.writeText(this.getAttribute('data-wa'));this.textContent='Tersalin!';setTimeout(()=>this.textContent='Salin Teks',2000)" data-wa="<?php echo esc_attr( $wa_txt ); ?>" style="background:#1e293b;color:#e2e8f0;border:1px solid #334155;padding:8px 14px;border-radius:6px;font-size:12.5px;font-weight:600;cursor:pointer;">Salin Teks</button>
        <?php if ( $ch_url ) : ?>
          <a href="<?php echo esc_url( $ch_url ); ?>" target="_blank" rel="noopener" style="background:#0f172a;color:#38bdf8;border:1px solid #1e3a8a;text-decoration:none;padding:8px 14px;border-radius:6px;font-size:12.5px;font-weight:600;">Buka Saluran</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php
}

/* ==========================================================================
   DEFINISI FIELD META (TANPA DUPLIKASI TIPE PEKERJAAN & PENDIDIKAN)
   ========================================================================== */

function hakajobspro_meta_fields() {
    return [
        'loker_perusahaan' => [
            'label' => __( 'Nama Perusahaan', 'hakajobspro' ),
            'type'  => 'text',
            'note'  => 'Contoh: PT Prakarsa Alam Segar',
        ],
        'loker_website' => [
            'label' => __( 'Website Perusahaan', 'hakajobspro' ),
            'type'  => 'url',
            'note'  => 'Contoh: https://perusahaan.com',
        ],
        'loker_lokasi_detail' => [
            'label' => __( 'Lokasi Detail / Alamat', 'hakajobspro' ),
            'type'  => 'text',
            'note'  => 'Contoh: Pondok Ungu, Bekasi Utara, Jawa Barat',
        ],
        'loker_pengalaman' => [
            'label' => __( 'Pengalaman Kerja', 'hakajobspro' ),
            'type'  => 'text',
            'note'  => 'Contoh: Fresh Graduate / Minimal 1 tahun',
        ],
        'loker_gaji_min' => [
            'label' => __( 'Gaji Minimum (Rp)', 'hakajobspro' ),
            'type'  => 'number',
            'note'  => 'Ketik angka tanpa titik/Rp (Contoh: 3200000 otomatis tampil Rp 3,2 Jt). Kosongkan jika dirahasiakan.',
        ],
        'loker_gaji_max' => [
            'label' => __( 'Gaji Maksimum (Rp)', 'hakajobspro' ),
            'type'  => 'number',
            'note'  => 'Ketik angka tanpa titik/Rp (Contoh: 4500000 otomatis tampil Rp 4,5 Jt). Kosongkan jika dirahasiakan.',
        ],
        'loker_deadline' => [
            'label' => __( 'Batas Pendaftaran', 'hakajobspro' ),
            'type'  => 'date',
            'note'  => 'Lowongan otomatis ditutup jika melewati tanggal ini.',
        ],
        'loker_link_lamar' => [
            'label' => __( 'Link Melamar / Email', 'hakajobspro' ),
            'type'  => 'url',
            'note'  => 'URL form lamaran atau mailto:email@perusahaan.com',
        ],
        'loker_cara_melamar' => [
            'label' => __( 'Cara Melamar', 'hakajobspro' ),
            'type'  => 'textarea',
            'full'  => true,
            'note'  => 'Jelaskan langkah-langkah melamar pekerjaan ini.',
        ],
        'loker_featured' => [
            'label'   => __( 'Tandai sebagai Unggulan', 'hakajobspro' ),
            'type'    => 'select',
            'options' => [ 'Ya', 'Tidak' ],
            'note'    => 'Pilih "Ya" agar tampil di seksi Lowongan Unggulan beranda.',
        ],
    ];
}

/* ==========================================================================
   SIMPAN META DATA
   ========================================================================== */

function hakajobspro_save_meta( $post_id ) {
    if ( ! isset( $_POST['hakajobspro_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['hakajobspro_meta_nonce'], 'hakajobspro_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    foreach ( hakajobspro_meta_fields() as $key => $field ) {
        if ( ! isset( $_POST[ 'hakajobspro_' . $key ] ) ) continue;

        $raw = $_POST[ 'hakajobspro_' . $key ];

        if ( $field['type'] === 'url' ) {
            $value = esc_url_raw( $raw );
        } elseif ( $field['type'] === 'number' ) {
            $value = absint( $raw );
        } else {
            $value = sanitize_text_field( $raw );
        }

        if ( $value !== '' ) {
            update_post_meta( $post_id, '_' . $key, $value );
        } else {
            delete_post_meta( $post_id, '_' . $key );
        }
    }

    // Sinkronkan pilihan taksonomi Pendidikan & Tipe Pekerjaan dari Sidebar Kanan ke post meta
    $edu_terms = wp_get_object_terms( $post_id, 'loker_pendidikan', [ 'fields' => 'names' ] );
    if ( ! is_wp_error( $edu_terms ) && ! empty( $edu_terms ) ) {
        update_post_meta( $post_id, '_loker_pendidikan', implode( ', ', $edu_terms ) );
    }

    $tipe_terms = wp_get_object_terms( $post_id, 'loker_tipe', [ 'fields' => 'names' ] );
    if ( ! is_wp_error( $tipe_terms ) && ! empty( $tipe_terms ) ) {
        update_post_meta( $post_id, '_loker_tipe_kerja', implode( ', ', $tipe_terms ) );
    }

    // Sinkronkan Nama Perusahaan ke taksonomi loker_perusahaan (untuk Arsip Perusahaan)
    $perusahaan = get_post_meta( $post_id, '_loker_perusahaan', true );
    if ( $perusahaan ) {
        $term_ids = wp_set_object_terms( $post_id, $perusahaan, 'loker_perusahaan', false );
        if ( ! is_wp_error( $term_ids ) && ! empty( $term_ids[0] ) ) {
            $term_id = (int) $term_ids[0];
            $website = get_post_meta( $post_id, '_loker_website', true );
            $lokasi  = get_post_meta( $post_id, '_loker_lokasi_detail', true );
            if ( $website ) update_term_meta( $term_id, '_perusahaan_website', $website );
            if ( $lokasi )  update_term_meta( $term_id, '_perusahaan_lokasi', $lokasi );
            if ( has_post_thumbnail( $post_id ) ) {
                update_term_meta( $term_id, '_perusahaan_logo_id', get_post_thumbnail_id( $post_id ) );
            }
        }
    }

    // Sinkronkan status jadwal penutupan otomatis berdasarkan batas pendaftaran
    $deadline = get_post_meta( $post_id, '_loker_deadline', true );
    $today    = current_time( 'Y-m-d' );
    if ( $deadline && $deadline < $today ) {
        update_post_meta( $post_id, '_loker_status_close', 'closed' );
    } else {
        update_post_meta( $post_id, '_loker_status_close', 'open' );
    }
}
add_action( 'save_post_loker', 'hakajobspro_save_meta' );

/* Sinkronkan otomatis juga saat term Pendidikan atau Tipe Pekerjaan diubah lewat Gutenberg REST API / Quick Edit */
function hakajobspro_sync_tax_terms_to_meta( $object_id, $terms, $tt_ids, $taxonomy ) {
    if ( get_post_type( $object_id ) !== 'loker' ) {
        return;
    }
    if ( 'loker_pendidikan' === $taxonomy ) {
        $names = wp_get_object_terms( $object_id, 'loker_pendidikan', [ 'fields' => 'names' ] );
        if ( ! is_wp_error( $names ) && ! empty( $names ) ) {
            update_post_meta( $object_id, '_loker_pendidikan', implode( ', ', $names ) );
        }
    } elseif ( 'loker_tipe' === $taxonomy ) {
        $names = wp_get_object_terms( $object_id, 'loker_tipe', [ 'fields' => 'names' ] );
        if ( ! is_wp_error( $names ) && ! empty( $names ) ) {
            update_post_meta( $object_id, '_loker_tipe_kerja', implode( ', ', $names ) );
        }
    }
}
add_action( 'set_object_terms', 'hakajobspro_sync_tax_terms_to_meta', 10, 4 );

/* ==========================================================================
   KOLOM TAMBAHAN DI ADMIN LIST LOKER
   ========================================================================== */

function hakajobspro_admin_columns( $columns ) {
    $new = [];
    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['loker_perusahaan'] = __( 'Perusahaan', 'hakajobspro' );
            $new['loker_deadline']   = __( 'Batas & Status', 'hakajobspro' );
            $new['loker_featured']   = __( 'Unggulan', 'hakajobspro' );
            $new['loker_wa_share']   = __( 'Siaran Saluran WA', 'hakajobspro' );
        }
    }
    return $new;
}
add_filter( 'manage_loker_posts_columns', 'hakajobspro_admin_columns' );

function hakajobspro_admin_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'loker_perusahaan':
            $co  = get_post_meta( $post_id, '_loker_perusahaan', true );
            $url = function_exists( 'hakajobspro_get_perusahaan_url' ) ? hakajobspro_get_perusahaan_url( $post_id ) : '';
            if ( $co && $url ) {
                echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $co ) . '</a>';
            } else {
                echo esc_html( $co ?: '-' );
            }
            break;
        case 'loker_deadline':
            $d = get_post_meta( $post_id, '_loker_deadline', true );
            if ( ! $d ) {
                echo '-';
                break;
            }
            $expired = function_exists( 'hakajobspro_is_expired' ) && hakajobspro_is_expired( $post_id );
            echo esc_html( date_i18n( 'd M Y', strtotime( $d ) ) ) . '<br>';
            if ( $expired ) {
                echo '<span style="color:#c62828;font-weight:700;font-size:11px;">Ditutup Otomatis</span>';
            } else {
                echo '<span style="color:#2e7d32;font-weight:700;font-size:11px;">Aktif</span>';
            }
            break;
        case 'loker_featured':
            $f = get_post_meta( $post_id, '_loker_featured', true );
            echo ( $f === 'Ya' ) ? '<span style="color:#e63946;font-weight:700;">Ya</span>' : 'Tidak';
            break;
        case 'loker_wa_share':
            if ( function_exists( 'hakajobspro_get_wa_broadcast_text' ) ) {
                $txt = hakajobspro_get_wa_broadcast_text( $post_id );
                $url = 'https://api.whatsapp.com/send?text=' . rawurlencode( $txt );
                echo '<button type="button" class="button button-small" onclick="navigator.clipboard.writeText(this.getAttribute(\'data-wa\'));this.textContent=\'Tersalin!\';setTimeout(()=>this.textContent=\'Salin Teks\',2000)" data-wa="' . esc_attr( $txt ) . '" style="margin-right:4px;">Salin Teks</button>';
                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="button button-small" style="background:#16a34a;color:#fff;border-color:#16a34a;">Kirim WA</a>';
            }
            break;
    }
}
add_action( 'manage_loker_posts_custom_column', 'hakajobspro_admin_column_content', 10, 2 );


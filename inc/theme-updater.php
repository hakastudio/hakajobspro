<?php
/**
 * Hakajobspro — inc/theme-updater.php
 * Sistem Pembaruan Otomatis (Auto-Updater) Tema WordPress terintegrasi GitHub:
 * https://github.com/hakastudio/hakajobspro
 *
 * Mendukung 2 metode deteksi otomatis sekaligus:
 * 1. Push langsung ke branch utama (main/master) dengan menaikkan angka "Version:" di style.css
 * 2. Pembuatan GitHub Release (Tag versi seperti v1.0.1 / 1.0.1)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Hakajobspro_Theme_Updater {

    /**
     * Pemilik & nama repositori GitHub
     */
    private $repo_owner = 'hakastudio';
    private $repo_name  = 'hakajobspro';

    /**
     * Slug folder tema yang sedang aktif
     */
    private $theme_slug = '';

    /**
     * Key cache transient untuk menyimpan hasil cek GitHub
     */
    private $transient_key = 'hakajobspro_gh_update_data';

    public function __construct() {
        $this->theme_slug = get_template();

        // Filter utama WordPress untuk cek update tema
        add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_for_update' ] );

        // Filter untuk memperbaiki nama folder hasil ekstrak ZIP dari GitHub (misal hakajobspro-main -> hakajobspro)
        add_filter( 'upgrader_source_selection', [ $this, 'fix_github_folder_name' ], 10, 4 );

        // Bersihkan cache setelah proses update selesai
        add_action( 'upgrader_process_complete', [ $this, 'purge_cache_after_update' ], 10, 2 );

        // Halaman status & kontrol pembaruan di WP Admin (Appearance -> Update Tema)
        add_action( 'admin_menu', [ $this, 'register_updater_menu' ] );
        add_action( 'admin_init', [ $this, 'handle_admin_actions' ] );
        add_action( 'admin_notices', [ $this, 'render_update_admin_notice' ] );
    }

    /**
     * Ambil branch GitHub yang digunakan (default: main)
     */
    private function get_branch() {
        $branch = get_option( 'hakajobspro_github_branch', 'main' );
        return $branch ? sanitize_text_field( $branch ) : 'main';
    }

    /**
     * Ambil Personal Access Token opsional (jika repositori diubah menjadi Private)
     */
    private function get_token() {
        return trim( (string) get_option( 'hakajobspro_github_token', '' ) );
    }

    /**
     * Header HTTP standar untuk request ke GitHub API / Raw
     */
    private function get_request_args() {
        $headers = [
            'Accept'     => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url( '/' ) . '; Hakajobspro-Updater',
        ];
        $token = $this->get_token();
        if ( ! empty( $token ) ) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
        return [
            'timeout' => 12,
            'headers' => $headers,
        ];
    }

    /**
     * Ambil versi tema yang sedang terpasang di WordPress
     */
    public function get_installed_version() {
        $theme = wp_get_theme( $this->theme_slug );
        return $theme->exists() ? $theme->get( 'Version' ) : HAKAJOBSPRO_VERSION;
    }

    /**
     * Ambil data versi terbaru dari GitHub (cek style.css di branch & GitHub Releases API)
     */
    public function get_remote_data( $force = false ) {
        if ( ! $force && isset( $_GET['force-check'] ) && '1' === $_GET['force-check'] ) {
            $force = true;
        }

        if ( ! $force ) {
            $cached = get_site_transient( $this->transient_key );
            if ( is_array( $cached ) && ! empty( $cached['version'] ) ) {
                return $cached;
            }
        }

        $branch      = $this->get_branch();
        $best_data   = null;
        $req_args    = $this->get_request_args();

        // 1. Cek langsung file style.css di branch utama (main / master)
        $branches_to_try = array_unique( [ $branch, 'main', 'master' ] );
        foreach ( $branches_to_try as $try_branch ) {
            $raw_url = sprintf(
                'https://raw.githubusercontent.com/%s/%s/%s/style.css?t=%d',
                $this->repo_owner,
                $this->repo_name,
                $try_branch,
                time()
            );

            $response = wp_remote_get( $raw_url, $req_args );
            if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                $css_body = wp_remote_retrieve_body( $response );
                if ( preg_match( '/^[ \t\/*#@]*Version:\s*([^\r\n]+)/mi', $css_body, $matches ) ) {
                    $branch_version = trim( $matches[1] );
                    $requires_wp    = '5.8';
                    $requires_php   = '7.4';

                    if ( preg_match( '/^[ \t\/*#@]*Requires at least:\s*([^\r\n]+)/mi', $css_body, $m_wp ) ) {
                        $requires_wp = trim( $m_wp[1] );
                    }
                    if ( preg_match( '/^[ \t\/*#@]*Requires PHP:\s*([^\r\n]+)/mi', $css_body, $m_php ) ) {
                        $requires_php = trim( $m_php[1] );
                    }

                    $best_data = [
                        'version'      => $branch_version,
                        'package'      => sprintf(
                            'https://github.com/%s/%s/archive/refs/heads/%s.zip',
                            $this->repo_owner,
                            $this->repo_name,
                            $try_branch
                        ),
                        'url'          => sprintf( 'https://github.com/%s/%s', $this->repo_owner, $this->repo_name ),
                        'branch'       => $try_branch,
                        'source_type'  => 'branch',
                        'requires'     => $requires_wp,
                        'requires_php' => $requires_php,
                        'checked_at'   => current_time( 'mysql' ),
                    ];
                    break;
                }
            }
        }

        // 2. Cek GitHub Releases API (jika ada rilis resmi dengan tag versi lebih baru)
        $release_api_url = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            $this->repo_owner,
            $this->repo_name
        );

        $rel_response = wp_remote_get( $release_api_url, $req_args );
        if ( ! is_wp_error( $rel_response ) && 200 === wp_remote_retrieve_response_code( $rel_response ) ) {
            $rel_body = json_decode( wp_remote_retrieve_body( $rel_response ), true );
            if ( is_array( $rel_body ) && ! empty( $rel_body['tag_name'] ) ) {
                $rel_version = ltrim( trim( $rel_body['tag_name'] ), 'vV' );
                $package_url = ! empty( $rel_body['zipball_url'] ) ? $rel_body['zipball_url'] : '';

                // Jika ada file .zip yang diunggah ke Release Assets, prioritaskan asset tersebut
                if ( ! empty( $rel_body['assets'] ) && is_array( $rel_body['assets'] ) ) {
                    foreach ( $rel_body['assets'] as $asset ) {
                        if ( ! empty( $asset['browser_download_url'] ) && substr( $asset['browser_download_url'], -4 ) === '.zip' ) {
                            $package_url = $asset['browser_download_url'];
                            break;
                        }
                    }
                }

                if ( $package_url && ( ! $best_data || version_compare( $rel_version, $best_data['version'], '>=' ) ) ) {
                    $best_data = [
                        'version'      => $rel_version,
                        'package'      => $package_url,
                        'url'          => ! empty( $rel_body['html_url'] ) ? $rel_body['html_url'] : sprintf( 'https://github.com/%s/%s/releases', $this->repo_owner, $this->repo_name ),
                        'branch'       => $branch,
                        'source_type'  => 'release',
                        'requires'     => isset( $best_data['requires'] ) ? $best_data['requires'] : '5.8',
                        'requires_php' => isset( $best_data['requires_php'] ) ? $best_data['requires_php'] : '7.4',
                        'checked_at'   => current_time( 'mysql' ),
                    ];
                }
            }
        }

        if ( $best_data ) {
            // Simpan di transient selama 3 jam
            set_site_transient( $this->transient_key, $best_data, 3 * HOUR_IN_SECONDS );
        }

        return $best_data;
    }

    /**
     * Suntikkan informasi update ke sistem Update Themes bawaan WordPress
     */
    public function check_for_update( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $installed_version = isset( $transient->checked[ $this->theme_slug ] )
            ? $transient->checked[ $this->theme_slug ]
            : $this->get_installed_version();

        $remote = $this->get_remote_data();
        if ( ! is_array( $remote ) || empty( $remote['version'] ) || empty( $remote['package'] ) ) {
            return $transient;
        }

        $item = [
            'theme'        => $this->theme_slug,
            'new_version'  => $remote['version'],
            'url'          => $remote['url'],
            'package'      => $remote['package'],
            'requires'     => $remote['requires'],
            'requires_php' => $remote['requires_php'],
        ];

        if ( version_compare( $remote['version'], $installed_version, '>' ) ) {
            $transient->response[ $this->theme_slug ] = $item;
        } else {
            $transient->no_update[ $this->theme_slug ] = $item;
        }

        return $transient;
    }

    /**
     * Pastikan folder ekstrak ZIP dari GitHub (misal: hakajobspro-main)
     * diubah namanya menjadi sama persis dengan folder tema aktif ($this->theme_slug)
     */
    public function fix_github_folder_name( $source, $remote_source, $upgrader, $hook_extra ) {
        global $wp_filesystem;

        if ( is_wp_error( $source ) || ! $wp_filesystem ) {
            return $source;
        }

        $is_target_theme = false;

        if ( isset( $hook_extra['theme'] ) && $hook_extra['theme'] === $this->theme_slug ) {
            $is_target_theme = true;
        } elseif ( basename( untrailingslashit( $source ) ) !== $this->theme_slug ) {
            // Cek apakah folder ekstrak merupakan milik Hakajobspro
            $folder_name = basename( untrailingslashit( $source ) );
            if ( strpos( strtolower( $folder_name ), 'hakajobspro' ) !== false ) {
                $is_target_theme = true;
            }
        }

        if ( ! $is_target_theme ) {
            return $source;
        }

        // Jika di dalam zip terdapat subfolder hakajobspro/style.css, gunakan subfolder tersebut
        $nested_style = trailingslashit( $source ) . 'hakajobspro/style.css';
        if ( ! $wp_filesystem->exists( trailingslashit( $source ) . 'style.css' ) && $wp_filesystem->exists( $nested_style ) ) {
            $source = trailingslashit( $source ) . 'hakajobspro/';
        }

        $corrected_source = trailingslashit( $remote_source ) . $this->theme_slug . '/';

        if ( untrailingslashit( $source ) === untrailingslashit( $corrected_source ) ) {
            return $source;
        }

        if ( $wp_filesystem->exists( $corrected_source ) ) {
            $wp_filesystem->delete( $corrected_source, true );
        }

        if ( $wp_filesystem->move( $source, $corrected_source, true ) ) {
            return $corrected_source;
        }

        return $source;
    }

    /**
     * Bersihkan cache setelah tema diperbarui
     */
    public function purge_cache_after_update( $upgrader, $options ) {
        if ( isset( $options['type'] ) && 'theme' === $options['type'] ) {
            delete_site_transient( $this->transient_key );
        }
    }

    /**
     * Tampilkan notifikasi di Dashboard WP jika ada versi baru di GitHub
     */
    public function render_update_admin_notice() {
        if ( ! current_user_can( 'update_themes' ) ) {
            return;
        }

        $screen = get_current_screen();
        if ( ! $screen || ! in_array( $screen->id, [ 'dashboard', 'themes', 'update-core', 'appearance_page_hakajobspro-updater' ], true ) ) {
            return;
        }

        $installed = $this->get_installed_version();
        $remote    = $this->get_remote_data();

        if ( ! is_array( $remote ) || empty( $remote['version'] ) ) {
            return;
        }

        if ( version_compare( $remote['version'], $installed, '>' ) ) {
            $update_url = wp_nonce_url(
                admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( $this->theme_slug ) ),
                'upgrade-theme_' . $this->theme_slug
            );
            $manage_url = admin_url( 'themes.php?page=hakajobspro-updater' );
            ?>
            <div class="notice notice-warning is-dismissible" style="border-left-color:#e63946;padding:12px 16px;">
                <p style="margin:0 0 8px;font-size:14px;">
                    <strong>Pembaruan Tema Hakajobspro Tersedia!</strong>
                    Versi terbaru <strong>v<?php echo esc_html( $remote['version'] ); ?></strong> telah dirilis di GitHub
                    (versi terpasang saat ini: <code>v<?php echo esc_html( $installed ); ?></code>).
                </p>
                <p style="margin:0;">
                    <a href="<?php echo esc_url( $update_url ); ?>" class="button button-primary" style="background:#e63946;border-color:#c92a37;">
                        Perbarui Tema Sekarang
                    </a>
                    <a href="<?php echo esc_url( $manage_url ); ?>" class="button" style="margin-left:6px;">
                        Detail &amp; Pengaturan Update
                    </a>
                    <a href="<?php echo esc_url( $remote['url'] ); ?>" target="_blank" rel="noopener" class="button-link" style="margin-left:12px;line-height:28px;">
                        Lihat Repositori GitHub &rarr;
                    </a>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Daftarkan submenu "Update Tema (GitHub)" di bawah Appearance (Tampilan)
     */
    public function register_updater_menu() {
        add_theme_page(
            __( 'Update Tema Hakajobspro', 'hakajobspro' ),
            __( 'Update Tema (GitHub)', 'hakajobspro' ),
            'update_themes',
            'hakajobspro-updater',
            [ $this, 'render_updater_page' ]
        );
    }

    /**
     * Tangani tombol "Cek Pembaruan Sekarang" dan penyimpanan pengaturan GitHub
     */
    public function handle_admin_actions() {
        if ( ! current_user_can( 'update_themes' ) ) {
            return;
        }

        // Cek pembaruan manual
        if ( isset( $_GET['hakajobspro_check_update'] ) && check_admin_referer( 'hakajobspro_check_update_nonce' ) ) {
            delete_site_transient( $this->transient_key );
            delete_site_transient( 'update_themes' );
            $this->get_remote_data( true );
            wp_update_themes();

            wp_safe_redirect( add_query_arg( [
                'page'    => 'hakajobspro-updater',
                'checked' => '1',
            ], admin_url( 'themes.php' ) ) );
            exit;
        }

        // Simpan pengaturan branch / token
        if ( isset( $_POST['hakajobspro_save_updater_settings'] ) && check_admin_referer( 'hakajobspro_updater_settings_nonce' ) ) {
            $branch = isset( $_POST['hakajobspro_github_branch'] ) ? sanitize_text_field( wp_unslash( $_POST['hakajobspro_github_branch'] ) ) : 'main';
            $token  = isset( $_POST['hakajobspro_github_token'] ) ? sanitize_text_field( wp_unslash( $_POST['hakajobspro_github_token'] ) ) : '';

            update_option( 'hakajobspro_github_branch', $branch ?: 'main' );
            update_option( 'hakajobspro_github_token', $token );

            delete_site_transient( $this->transient_key );
            delete_site_transient( 'update_themes' );
            $this->get_remote_data( true );
            wp_update_themes();

            wp_safe_redirect( add_query_arg( [
                'page'  => 'hakajobspro-updater',
                'saved' => '1',
            ], admin_url( 'themes.php' ) ) );
            exit;
        }
    }

    /**
     * Tampilan halaman Appearance -> Update Tema (GitHub)
     */
    public function render_updater_page() {
        $installed  = $this->get_installed_version();
        $remote     = $this->get_remote_data();
        $branch     = $this->get_branch();
        $token      = $this->get_token();
        $has_update = is_array( $remote ) && ! empty( $remote['version'] ) && version_compare( $remote['version'], $installed, '>' );

        $check_url  = wp_nonce_url(
            add_query_arg( [ 'page' => 'hakajobspro-updater', 'hakajobspro_check_update' => '1' ], admin_url( 'themes.php' ) ),
            'hakajobspro_check_update_nonce'
        );
        $update_url = wp_nonce_url(
            admin_url( 'update.php?action=upgrade-theme&theme=' . urlencode( $this->theme_slug ) ),
            'upgrade-theme_' . $this->theme_slug
        );
        ?>
        <div class="wrap" style="max-width:820px;">
            <h1>Sistem Pembaruan Otomatis — Hakajobspro</h1>
            <p style="color:#555;font-size:14px;">
                Tema ini terhubung langsung ke repositori resmi GitHub
                <a href="https://github.com/hakastudio/hakajobspro" target="_blank" rel="noopener"><strong>https://github.com/hakastudio/hakajobspro</strong></a>.
                Setiap kali ada perubahan versi di GitHub, notifikasi pembaruan akan otomatis muncul di Dasbor WordPress.
            </p>

            <?php if ( isset( $_GET['checked'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p>Pengecekan pembaruan ke server GitHub berhasil dilakukan.</p></div>
            <?php endif; ?>
            <?php if ( isset( $_GET['saved'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p>Pengaturan koneksi GitHub berhasil disimpan.</p></div>
            <?php endif; ?>

            <div style="background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:24px;margin-top:16px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
                <h2 style="margin-top:0;border-bottom:1px solid #eee;padding-bottom:12px;">Status Versi Tema</h2>
                <table class="form-table" role="presentation" style="margin-top:0;">
                    <tr>
                        <th scope="row">Nama Tema &amp; Folder</th>
                        <td><strong>Hakajobspro</strong> (<code><?php echo esc_html( $this->theme_slug ); ?></code>)</td>
                    </tr>
                    <tr>
                        <th scope="row">Versi Terpasang Saat Ini</th>
                        <td>
                            <span style="display:inline-block;background:#1d3557;color:#fff;padding:4px 12px;border-radius:4px;font-weight:700;">
                                v<?php echo esc_html( $installed ); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Versi Terbaru di GitHub</th>
                        <td>
                            <?php if ( is_array( $remote ) && ! empty( $remote['version'] ) ) : ?>
                                <span style="display:inline-block;background:<?php echo $has_update ? '#e63946' : '#16a34a'; ?>;color:#fff;padding:4px 12px;border-radius:4px;font-weight:700;">
                                    v<?php echo esc_html( $remote['version'] ); ?>
                                </span>
                                <span style="margin-left:8px;color:#555;">
                                    <?php if ( $has_update ) : ?>
                                        <strong>(Pembaruan Tersedia!)</strong>
                                    <?php else : ?>
                                        (Tema Anda sudah menggunakan versi paling baru)
                                    <?php endif; ?>
                                </span>
                                <p class="description" style="margin-top:6px;">
                                    Terakhir diperiksa: <?php echo esc_html( $remote['checked_at'] ); ?>
                                    | Sumber: <code><?php echo esc_html( strtoupper( $remote['source_type'] ) ); ?> (<?php echo esc_html( $remote['branch'] ); ?>)</code>
                                </p>
                            <?php else : ?>
                                <span style="color:#b91c1c;font-weight:600;">Belum dapat mengambil data dari GitHub. Pastikan repositori sudah di-push dan bersifat Public (atau isi Token di bawah).</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #eee;display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                    <a href="<?php echo esc_url( $check_url ); ?>" class="button button-secondary">
                        Cek Pembaruan dari GitHub Sekarang
                    </a>
                    <?php if ( $has_update ) : ?>
                        <a href="<?php echo esc_url( $update_url ); ?>" class="button button-primary" style="background:#e63946;border-color:#c92a37;">
                            Perbarui ke v<?php echo esc_html( $remote['version'] ); ?> Sekarang
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( admin_url( 'update-core.php?force-check=1' ) ); ?>" class="button-link" style="margin-left:auto;">
                        Buka Halaman Updates WordPress &rarr;
                    </a>
                </div>
            </div>

            <div style="background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:24px;margin-top:20px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
                <h2 style="margin-top:0;border-bottom:1px solid #eee;padding-bottom:12px;">Pengaturan Koneksi GitHub</h2>
                <form method="post" action="">
                    <?php wp_nonce_field( 'hakajobspro_updater_settings_nonce' ); ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row"><label for="hakajobspro_github_branch">Branch Utama GitHub</label></th>
                            <td>
                                <input type="text" id="hakajobspro_github_branch" name="hakajobspro_github_branch" value="<?php echo esc_attr( $branch ); ?>" class="regular-text" placeholder="main">
                                <p class="description">Default: <code>main</code>. Sistem akan membaca nomor versi dari <code>style.css</code> pada branch ini.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="hakajobspro_github_token">GitHub Personal Access Token (Opsional)</label></th>
                            <td>
                                <input type="password" id="hakajobspro_github_token" name="hakajobspro_github_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" autocomplete="off" placeholder="ghp_xxxxxxxxxxxxxxxxxxxx">
                                <p class="description">Kosongkan jika repositori <code>hakastudio/hakajobspro</code> bersifat <strong>Public</strong>. Isi hanya jika repositori diatur menjadi Private.</p>
                            </td>
                        </tr>
                    </table>
                    <p class="submit" style="margin-bottom:0;">
                        <button type="submit" name="hakajobspro_save_updater_settings" value="1" class="button button-primary">
                            Simpan Pengaturan
                        </button>
                    </p>
                </form>
            </div>

            <div style="background:#f8fafc;border:1px solid #cbd5e1;border-radius:8px;padding:20px;margin-top:20px;">
                <h3 style="margin-top:0;color:#1d3557;">Cara Mengirim Pembaruan Tema dari Komputer ke WordPress:</h3>
                <ol style="margin-bottom:0;line-height:1.7;color:#334155;">
                    <li>Lakukan perubahan kode tema di komputer Anda.</li>
                    <li>Buka file <code>style.css</code> lalu naikkan angka pada baris <code>Version: 1.0.1</code> (misal dari <code>1.0.0</code> menjadi <code>1.0.1</code> atau <code>1.1.0</code>).</li>
                    <li>Jalankan perintah <code>git add .</code>, <code>git commit -m "Update versi 1.0.1"</code>, dan <code>git push</code> ke GitHub <code>hakastudio/hakajobspro</code>.</li>
                    <li>Di Dasbor WordPress (menu <strong>Dashboard &rarr; Updates</strong>, <strong>Appearance &rarr; Themes</strong>, atau halaman ini), tombol <strong>Perbarui Tema Sekarang</strong> akan langsung muncul otomatis.</li>
                </ol>
            </div>
        </div>
        <?php
    }
}

new Hakajobspro_Theme_Updater();


<?php
/**
 * Popeye Tours — Export / Import for custom post types
 * Handles: services, destinations, journal articles (including meta & images)
 */

if ( ! defined('ABSPATH') ) exit;

// ── Register admin page under Tools ──────────────────────────────────────────
add_action('admin_menu', function () {
    add_management_page(
        'Popeye Export / Import',
        'Popeye Export/Import',
        'manage_options',
        'popeye-export-import',
        'popeye_ei_render_page'
    );
});

// ── Admin page HTML ───────────────────────────────────────────────────────────
function popeye_ei_render_page() {
    $base = admin_url('tools.php?page=popeye-export-import');

    // Status messages
    if ( isset($_GET['imported']) ) {
        $n = (int) $_GET['imported'];
        echo '<div class="notice notice-success is-dismissible"><p>✅ Successfully imported <strong>' . $n . '</strong> item(s).</p></div>';
    }
    if ( isset($_GET['skipped']) && (int) $_GET['skipped'] > 0 ) {
        echo '<div class="notice notice-warning is-dismissible"><p>⚠️ <strong>' . (int) $_GET['skipped'] . '</strong> item(s) skipped (slug already exists).</p></div>';
    }
    if ( isset($_GET['import_error']) ) {
        echo '<div class="notice notice-error is-dismissible"><p>❌ ' . esc_html( urldecode($_GET['import_error']) ) . '</p></div>';
    }

    $types = [
        'service'     => 'Services',
        'destination' => 'Destinations',
        'journal'     => 'Journal Articles',
    ];
    ?>
    <div class="wrap">
        <h1 style="margin-bottom:1.5rem;">Popeye Export / Import</h1>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:1000px;">

            <!-- ── EXPORT ── -->
            <div style="background:#fff;padding:1.75rem 2rem;border:1px solid #ddd;border-radius:8px;">
                <h2 style="margin-top:0;">📤 Export</h2>
                <p style="color:#555;">Downloads a JSON file containing your selected content, all custom fields, and image URLs.</p>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php wp_nonce_field('popeye_export', 'popeye_export_nonce'); ?>
                    <input type="hidden" name="action" value="popeye_export">

                    <fieldset style="border:1px solid #e0e0e0;border-radius:6px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
                        <legend style="font-weight:600;padding:0 0.4rem;">Post types</legend>
                        <?php foreach ( $types as $key => $label ) :
                            $published = (int) wp_count_posts($key)->publish;
                            $draft     = (int) wp_count_posts($key)->draft;
                            $total     = $published + $draft;
                        ?>
                        <label style="display:flex;align-items:center;gap:0.5rem;margin:0.45rem 0;cursor:pointer;">
                            <input type="checkbox" name="post_types[]" value="<?php echo esc_attr($key); ?>" <?php checked(true); ?>>
                            <span><?php echo esc_html($label); ?></span>
                            <span style="color:#888;font-size:0.85em;">(<?php echo $total; ?> total)</span>
                        </label>
                        <?php endforeach; ?>
                    </fieldset>

                    <p style="margin:0;">
                        <button type="submit" class="button button-primary">Download JSON</button>
                    </p>
                </form>
            </div>

            <!-- ── IMPORT ── -->
            <div style="background:#fff;padding:1.75rem 2rem;border:1px solid #ddd;border-radius:8px;">
                <h2 style="margin-top:0;">📥 Import</h2>
                <p style="color:#555;">Upload a Popeye JSON export file. Posts with a matching slug are <strong>skipped</strong> by default.</p>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                    <?php wp_nonce_field('popeye_import', 'popeye_import_nonce'); ?>
                    <input type="hidden" name="action" value="popeye_import">

                    <p style="margin-bottom:0.5rem;">
                        <label for="popeye-import-file" style="font-weight:600;display:block;margin-bottom:0.4rem;">JSON file</label>
                        <input type="file" id="popeye-import-file" name="popeye_import_file" accept=".json" required style="width:100%;">
                    </p>

                    <fieldset style="border:1px solid #e0e0e0;border-radius:6px;padding:0.85rem 1.25rem;margin:1rem 0;">
                        <legend style="font-weight:600;padding:0 0.4rem;">Images</legend>
                        <label style="display:flex;align-items:flex-start;gap:0.5rem;cursor:pointer;margin:0.3rem 0;">
                            <input type="checkbox" name="download_images" value="1" checked style="margin-top:3px;flex-shrink:0;">
                            <span>Download &amp; attach images from the source site<br>
                            <em style="color:#888;font-size:0.82em;">Fetches featured images and journal galleries. Uncheck to import text only.</em></span>
                        </label>
                    </fieldset>

                    <p style="margin:0;">
                        <button type="submit" class="button button-primary">Import</button>
                    </p>
                </form>
            </div>

        </div><!-- /grid -->

        <div style="margin-top:1.75rem;max-width:1000px;background:#f8f9fa;border:1px solid #ddd;border-radius:8px;padding:1.25rem 1.5rem;">
            <h3 style="margin-top:0;">How it works</h3>
            <ol style="margin:0;color:#444;line-height:1.9;">
                <li><strong>Export</strong> from your local/staging site — this downloads a <code>.json</code> file.</li>
                <li>On your production site, open <strong>Tools → Popeye Export/Import</strong>.</li>
                <li><strong>Import</strong> the JSON file. Each item is created as a draft or published post with all custom fields restored.</li>
                <li>If <em>Download images</em> is checked, featured images and journal galleries are fetched from the source site and added to the media library automatically.</li>
                <li>Posts whose slug already exists are skipped, so re-importing the same file is safe.</li>
            </ol>
        </div>
    </div>
    <?php
}

// ── Export handler ────────────────────────────────────────────────────────────
add_action('admin_post_popeye_export', function () {
    if ( ! current_user_can('manage_options') ) wp_die('Unauthorized');
    check_admin_referer('popeye_export', 'popeye_export_nonce');

    $allowed    = ['service', 'destination', 'journal'];
    $post_types = isset($_POST['post_types'])
        ? array_intersect( array_map('sanitize_key', (array) $_POST['post_types']), $allowed )
        : $allowed;

    // Internal WP meta keys we never want to export
    $skip_keys = ['_edit_lock', '_edit_last', '_wp_old_slug', '_wp_trash_meta_status', '_wp_trash_meta_time'];
    $data = [];

    foreach ( $post_types as $type ) {
        $posts = get_posts([
            'post_type'      => $type,
            'posts_per_page' => -1,
            'post_status'    => ['publish', 'draft'],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        foreach ( $posts as $post ) {
            // Collect public meta (skip internal _ prefixed and blacklisted keys)
            $raw_meta = get_post_meta($post->ID);
            $meta = [];
            foreach ( $raw_meta as $key => $values ) {
                if ( in_array($key, $skip_keys, true) ) continue;
                if ( substr($key, 0, 1) === '_' ) continue;
                $meta[$key] = maybe_unserialize($values[0]);
            }

            // Thumbnail URL (full size)
            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full') ?: '';

            // Journal gallery: convert stored IDs → URLs for portability
            $gallery_urls = [];
            $gallery_ids_raw = get_post_meta($post->ID, 'article_gallery_ids', true);
            if ( $gallery_ids_raw ) {
                foreach ( array_filter(array_map('absint', explode(',', $gallery_ids_raw))) as $img_id ) {
                    $url = wp_get_attachment_image_url($img_id, 'full');
                    if ( $url ) $gallery_urls[] = $url;
                }
            }

            // Remove the ID-based gallery meta — we store URLs in gallery_urls instead
            unset($meta['article_gallery_ids']);

            $data[] = [
                'post_type'     => $type,
                'title'         => $post->post_title,
                'content'       => $post->post_content,
                'excerpt'       => $post->post_excerpt,
                'slug'          => $post->post_name,
                'status'        => $post->post_status,
                'menu_order'    => (int) $post->menu_order,
                'meta'          => $meta,
                'thumbnail_url' => $thumbnail_url,
                'gallery_urls'  => $gallery_urls,
            ];
        }
    }

    $filename = 'popeye-export-' . gmdate('Y-m-d') . '.json';
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    echo wp_json_encode(
        ['version' => '1.0', 'exported_at' => gmdate('c'), 'site' => get_bloginfo('url'), 'data' => $data],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
});

// ── Import handler ────────────────────────────────────────────────────────────
add_action('admin_post_popeye_import', function () {
    if ( ! current_user_can('manage_options') ) wp_die('Unauthorized');
    check_admin_referer('popeye_import', 'popeye_import_nonce');

    $redirect_base = admin_url('tools.php?page=popeye-export-import');

    if ( empty($_FILES['popeye_import_file']['tmp_name']) ) {
        wp_redirect($redirect_base . '&import_error=' . rawurlencode('No file uploaded.'));
        exit;
    }

    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
    $raw     = file_get_contents(sanitize_text_field($_FILES['popeye_import_file']['tmp_name']));
    $payload = json_decode($raw, true);

    if ( ! $payload || ! isset($payload['data']) || ! is_array($payload['data']) ) {
        wp_redirect($redirect_base . '&import_error=' . rawurlencode('Invalid or unreadable JSON file.'));
        exit;
    }

    $download_images = ! empty($_POST['download_images']);
    $allowed_types   = ['service', 'destination', 'journal'];
    $imported = 0;
    $skipped  = 0;

    if ( $download_images ) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    foreach ( $payload['data'] as $item ) {
        $type = isset($item['post_type']) ? sanitize_key($item['post_type']) : '';
        if ( ! in_array($type, $allowed_types, true) ) continue;

        $slug = sanitize_title($item['slug'] ?? '');

        // Skip duplicates (match by slug)
        if ( $slug ) {
            $existing = get_posts([
                'name'           => $slug,
                'post_type'      => $type,
                'posts_per_page' => 1,
                'post_status'    => 'any',
            ]);
            if ( $existing ) {
                $skipped++;
                continue;
            }
        }

        $post_id = wp_insert_post([
            'post_type'    => $type,
            'post_title'   => sanitize_text_field($item['title'] ?? ''),
            'post_content' => wp_kses_post($item['content'] ?? ''),
            'post_excerpt' => sanitize_textarea_field($item['excerpt'] ?? ''),
            'post_name'    => $slug,
            'post_status'  => in_array($item['status'] ?? '', ['publish', 'draft'], true) ? $item['status'] : 'draft',
            'menu_order'   => (int) ($item['menu_order'] ?? 0),
        ], true);

        if ( is_wp_error($post_id) ) continue;

        // Restore custom meta
        if ( ! empty($item['meta']) && is_array($item['meta']) ) {
            foreach ( $item['meta'] as $key => $value ) {
                $key = sanitize_key($key);
                if ( ! $key ) continue;
                update_post_meta($post_id, $key, $value);
            }
        }

        // Thumbnail
        if ( $download_images && ! empty($item['thumbnail_url']) ) {
            $thumb_id = popeye_ei_sideload($item['thumbnail_url'], $post_id, sanitize_text_field($item['title'] ?? ''));
            if ( $thumb_id && ! is_wp_error($thumb_id) ) {
                set_post_thumbnail($post_id, $thumb_id);
            }
        }

        // Journal gallery
        if ( $download_images && ! empty($item['gallery_urls']) && is_array($item['gallery_urls']) ) {
            $new_ids = [];
            foreach ( $item['gallery_urls'] as $url ) {
                $img_id = popeye_ei_sideload($url, $post_id, sanitize_text_field($item['title'] ?? ''));
                if ( $img_id && ! is_wp_error($img_id) ) {
                    $new_ids[] = (int) $img_id;
                }
            }
            if ( $new_ids ) {
                update_post_meta($post_id, 'article_gallery_ids', implode(',', $new_ids));
            }
        }

        $imported++;
    }

    wp_redirect($redirect_base . '&imported=' . $imported . '&skipped=' . $skipped);
    exit;
});

// ── Helper: download a remote image into the media library ───────────────────
function popeye_ei_sideload( $url, $post_id, $desc = '' ) {
    if ( ! filter_var($url, FILTER_VALIDATE_URL) ) return false;

    // Only allow image content types
    $response = wp_remote_head($url, ['timeout' => 8]);
    if ( is_wp_error($response) ) return $response;
    $content_type = wp_remote_retrieve_header($response, 'content-type');
    if ( $content_type && strpos($content_type, 'image/') === false ) return false;

    $tmp = download_url($url, 30);
    if ( is_wp_error($tmp) ) return $tmp;

    $basename  = basename(parse_url($url, PHP_URL_PATH)) ?: 'image.jpg';
    $file_array = [
        'name'     => sanitize_file_name($basename),
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file_array, $post_id, sanitize_text_field($desc));

    if ( is_wp_error($id) && file_exists($tmp) ) {
        // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
        @unlink($tmp);
    }

    return $id;
}

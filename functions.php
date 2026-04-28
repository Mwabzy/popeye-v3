<?php
/**
 * Popeye Tours — functions.php
 * Enqueues styles, scripts, and registers theme supports.
 */

// ── Export / Import admin tool ───────────────────────────────────────────────
require_once __DIR__ . '/admin-export-import.php';

// ── Enqueue assets ──────────────────────────────────────────────────────────
function popeye_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'popeye-google-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;500;600&family=Lora:ital,wght@0,400;0,500;0,600;1,400&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'popeye-main',
        get_stylesheet_directory_uri() . '/css/main.css',
        ['popeye-google-fonts'],
        '2.3'
    );

    // Main JS (loaded in footer)
    wp_enqueue_script(
        'popeye-main',
        get_stylesheet_directory_uri() . '/js/main.js',
        [],
        '2.3',
        true  // load in footer
    );
}
add_action('wp_enqueue_scripts', 'popeye_enqueue_assets');


// ── Theme supports ───────────────────────────────────────────────────────────
function popeye_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');
    add_theme_support('menus');

    // Register nav menus
    register_nav_menus([
        'primary' => __('Primary Navigation', 'popeye-tours'),
        'footer'  => __('Footer Navigation', 'popeye-tours'),
    ]);
}
add_action('after_setup_theme', 'popeye_theme_setup');


// ── Custom Post Type: Destinations ──────────────────────────────────────────
function popeye_register_destinations_cpt() {
    register_post_type('destination', [
        'labels' => [
            'name'          => __('Destinations', 'popeye-tours'),
            'singular_name' => __('Destination', 'popeye-tours'),
            'add_new_item'  => __('Add New Destination', 'popeye-tours'),
            'edit_item'     => __('Edit Destination', 'popeye-tours'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-location-alt',
        'supports'     => ['title', 'thumbnail'],
        'rewrite'      => ['slug' => 'destinations'],
    ]);
}
add_action('init', 'popeye_register_destinations_cpt');


// ── Custom Post Type: Journal / Blog ────────────────────────────────────────
function popeye_register_journal_cpt() {
    register_post_type('journal', [
        'labels' => [
            'name'          => __('Journal', 'popeye-tours'),
            'singular_name' => __('Journal Article', 'popeye-tours'),
            'add_new_item'  => __('Add New Article', 'popeye-tours'),
            'edit_item'     => __('Edit Article', 'popeye-tours'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-book-alt',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'author', 'custom-fields'],
        'rewrite'      => ['slug' => 'journal'],
    ]);
}
add_action('init', 'popeye_register_journal_cpt');


// ── Excerpt length ───────────────────────────────────────────────────────────
function popeye_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'popeye_excerpt_length');

function popeye_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'popeye_excerpt_more');

function popeye_get_destination_static_defaults() {
    $base_uri = get_stylesheet_directory_uri();

    return [
        'kenya' => [
            'title'     => 'Kenya',
            'summary'   => 'The birthplace of safari. Witness the Great Migration and the Big Five in the legendary Maasai Mara and Amboseli.',
            'activities' => [
                'Great Migration game drives in the Maasai Mara',
                'Amboseli elephant safaris beneath Mount Kilimanjaro',
                'Conservancy sundowners and Maasai cultural visits',
            ],
            'thumbnail' => $base_uri . '/images/safari_wildlife_1775896676172.png',
        ],
        'tanzania' => [
            'title'     => 'Tanzania',
            'summary'   => 'Home to the vast Serengeti plains, the breathtaking Ngorongoro Crater, and the legendary wildebeest migration.',
            'activities' => [
                'Serengeti migration tracking across endless plains',
                'Ngorongoro Crater wildlife drives',
                'Bush breakfasts and balloon safaris at sunrise',
            ],
            'thumbnail' => $base_uri . '/images/tanzania_landscape_1775896936759.png',
        ],
        'zanzibar' => [
            'title'     => 'Zanzibar',
            'summary'   => 'Pristine white-sand beaches, crystal-clear waters, and rich Swahili culture on the Spice Island.',
            'activities' => [
                'Stone Town heritage walks and market tastings',
                'Dhow sunset cruises along the coast',
                'Snorkeling, reef swims, and spice farm visits',
            ],
            'thumbnail' => $base_uri . '/images/coastal_escape_1775896839404.png',
        ],
        'uganda' => [
            'title'     => 'Uganda',
            'summary'   => 'Trek through mist-covered mountains to encounter majestic silverback gorillas in Bwindi Impenetrable Forest.',
            'activities' => [
                'Gorilla trekking in Bwindi Impenetrable Forest',
                'Chimpanzee tracking in Kibale',
                'River and savannah adventures around Murchison Falls',
            ],
            'thumbnail' => $base_uri . '/images/uganda_gorilla_1775897079306.png',
        ],
        'rwanda' => [
            'title'     => 'Rwanda',
            'summary'   => 'The Land of a Thousand Hills, known for refined eco-tourism, primate tracking, and deeply scenic landscapes.',
            'activities' => [
                'Volcanoes National Park gorilla trekking',
                'Golden monkey encounters and forest walks',
                'Lake Kivu stays paired with Kigali art and culture',
            ],
            'thumbnail' => $base_uri . '/images/rwanda.jpg',
        ],
    ];
}

function popeye_get_destination_description_value($destination_id, $meta_key, $fallback_content = '', $fallback_words = 28) {
    $description = trim((string) get_post_meta($destination_id, $meta_key, true));

    if ( $description === '' ) {
        $description = trim((string) get_post_meta($destination_id, 'destination_summary_text', true));
    }

    if ( $description === '' ) {
        $description = trim((string) get_the_excerpt($destination_id));
    }

    if ( $description === '' && $fallback_content !== '' ) {
        $description = wp_trim_words(wp_strip_all_tags($fallback_content), $fallback_words);
    }

    return $description;
}

function popeye_get_destination_short_description($destination_id, $fallback_content = '') {
    return popeye_get_destination_description_value($destination_id, 'destination_short_description', $fallback_content, 28);
}

function popeye_get_destination_long_description($destination_id, $fallback_content = '') {
    return popeye_get_destination_description_value($destination_id, 'destination_long_description', $fallback_content, 40);
}

function popeye_get_destination_summary($destination_id, $fallback_content = '') {
    return popeye_get_destination_short_description($destination_id, $fallback_content);
}

function popeye_normalize_destination_activities($raw_value) {
    if ( is_array($raw_value) ) {
        $lines = [];
        array_walk_recursive($raw_value, function ($value) use (&$lines) {
            if ( is_scalar($value) ) {
                $lines[] = (string) $value;
            }
        });
    } else {
        $lines = preg_split('/\r\n|\r|\n/', (string) $raw_value);
    }

    $activities = [];

    foreach ( $lines as $line ) {
        $activity = trim(wp_strip_all_tags((string) $line));

        if ( $activity !== '' ) {
            $activities[] = $activity;
        }
    }

    return array_slice(array_values(array_unique($activities)), 0, 6);
}

function popeye_sanitize_destination_activities_meta($value) {
    return implode("\n", popeye_normalize_destination_activities($value));
}

function popeye_get_destination_activities($destination_id, $default_item = null) {
    $activities = popeye_normalize_destination_activities(
        get_post_meta($destination_id, 'destination_activities', true)
    );

    if ( empty($activities) && $default_item && ! empty($default_item['activities']) ) {
        $activities = popeye_normalize_destination_activities($default_item['activities']);
    }

    return $activities;
}

function popeye_get_destination_items() {
    $defaults = popeye_get_destination_static_defaults();
    $fallback_image = get_stylesheet_directory_uri() . '/images/safari_wildlife_1775896676172.png';
    $destination_posts = get_posts([
        'post_type'      => 'destination',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    $matched_items = [];
    $extra_items = [];

    foreach ( $destination_posts as $destination_post ) {
        $destination_id = $destination_post->ID;
        $destination_slug = sanitize_title($destination_post->post_title);
        $default_item = isset($defaults[$destination_slug]) ? $defaults[$destination_slug] : null;
        $destination_thumbnail = get_the_post_thumbnail_url($destination_id, 'large');
        $destination_short_description = popeye_get_destination_short_description($destination_id, $destination_post->post_content);
        $destination_long_description = popeye_get_destination_long_description($destination_id, $destination_post->post_content);
        $destination_activities = popeye_get_destination_activities($destination_id, $default_item);

        if ( ! $destination_thumbnail && $default_item ) {
            $destination_thumbnail = $default_item['thumbnail'];
        }

        if ( ! $destination_thumbnail ) {
            $destination_thumbnail = $fallback_image;
        }

        if ( $destination_short_description === '' && $default_item ) {
            $destination_short_description = $default_item['summary'];
        }

        if ( $destination_long_description === '' && $default_item ) {
            $destination_long_description = $default_item['summary'];
        }

        $item = [
            'ID'             => $destination_id,
            'title'          => get_the_title($destination_id),
            'slug'           => $destination_slug,
            'summary'        => $destination_short_description,
            'short_description' => $destination_short_description,
            'long_description'  => $destination_long_description,
            'activities'       => $destination_activities,
            'thumbnail'      => $destination_thumbnail,
            'hero_image'     => get_post_meta($destination_id, 'destination_hero_image', true),
            'permalink'      => get_permalink($destination_id),
            'archive_anchor' => home_url('/destinations/#' . $destination_slug),
            'content'        => $destination_post->post_content,
            'has_post'       => true,
        ];

        if ( $default_item ) {
            $matched_items[$destination_slug] = $item;
        } else {
            $extra_items[] = $item;
        }
    }

    $items = [];

    foreach ( $defaults as $destination_slug => $default_item ) {
        if ( isset($matched_items[$destination_slug]) ) {
            $items[] = $matched_items[$destination_slug];
            continue;
        }

        $items[] = [
            'ID'             => 0,
            'title'          => $default_item['title'],
            'slug'           => $destination_slug,
            'summary'        => $default_item['summary'],
            'short_description' => $default_item['summary'],
            'long_description'  => $default_item['summary'],
            'activities'       => ! empty($default_item['activities']) ? $default_item['activities'] : [],
            'thumbnail'      => $default_item['thumbnail'],
            'hero_image'     => '',
            'permalink'      => '',
            'archive_anchor' => home_url('/destinations/#' . $destination_slug),
            'content'        => '',
            'has_post'       => false,
        ];
    }

    return array_merge($items, $extra_items);
}

// ── Show Featured Image for Destinations ────────────────────────────────────
function popeye_add_destination_featured_image() {
    add_post_type_support('destination', 'thumbnail');
}
add_action('admin_init', 'popeye_add_destination_featured_image');

// ── Register custom meta fields for Destinations ────────────────────────────
function popeye_register_destination_meta() {
    register_post_meta('destination', 'destination_short_description', [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('destination', 'destination_long_description', [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('destination', 'destination_activities', [
        'type'         => 'string',
        'single'       => true,
        'sanitize_callback' => 'popeye_sanitize_destination_activities_meta',
        'show_in_rest' => true,
    ]);

    register_post_meta('destination', 'destination_summary_text', [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('destination', 'destination_hero_image', [
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'popeye_register_destination_meta');

function popeye_enqueue_admin_media($hook) {
    $screen = get_current_screen();

    if ( ! $screen || ! in_array($screen->post_type, ['destination', 'journal'], true) ) {
        return;
    }

    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'popeye_enqueue_admin_media');


// ── Remove WP default styles that may conflict ───────────────────────────────
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}, 100);


// ── Enable Custom Fields panel in Block Editor ──────────────────────────────
add_filter('use_block_editor_for_post_type', function($use_block_editor, $post_type) {
    if ( $post_type === 'destination' ) {
        return false;
    }

    return $use_block_editor;
}, 10, 2);

function popeye_add_destination_summary_metabox() {
    add_meta_box(
        'popeye-destination-summary',
        __('Destination Descriptions', 'popeye-tours'),
        'popeye_render_destination_summary_metabox',
        'destination',
        'normal',
        'high',
        [
            '__block_editor_compatible_meta_box' => true,
        ]
    );
}
add_action('add_meta_boxes', 'popeye_add_destination_summary_metabox');

function popeye_render_destination_summary_metabox($post) {
    $defaults = popeye_get_destination_static_defaults();
    $destination_slug = $post->post_name ? $post->post_name : sanitize_title($post->post_title);
    $default_item = isset($defaults[$destination_slug]) ? $defaults[$destination_slug] : null;
    $legacy_summary = get_post_meta($post->ID, 'destination_summary_text', true);
    $short_description = get_post_meta($post->ID, 'destination_short_description', true);
    $long_description = get_post_meta($post->ID, 'destination_long_description', true);
    $activities = get_post_meta($post->ID, 'destination_activities', true);

    if ( $short_description === '' ) {
        $short_description = $legacy_summary;
    }

    if ( $long_description === '' ) {
        $long_description = $legacy_summary;
    }

    if ( $activities === '' && $default_item && ! empty($default_item['activities']) ) {
        $activities = implode("\n", popeye_normalize_destination_activities($default_item['activities']));
    }

    wp_nonce_field('popeye_save_destination_descriptions', 'popeye_destination_descriptions_nonce');
    ?>
    <p>
        <?php esc_html_e('Add separate destination copy for the homepage card and the country guide card on the Destinations page.', 'popeye-tours'); ?>
    </p>

    <p>
        <label for="popeye-destination-short-description"><strong><?php esc_html_e('Short Description', 'popeye-tours'); ?></strong></label>
    </p>
    <p>
        <textarea
            class="widefat"
            rows="4"
            id="popeye-destination-short-description"
            name="popeye_destination_short_description"
        ><?php echo esc_textarea($short_description); ?></textarea>
    </p>
    <p class="description">
        <?php esc_html_e('Shown on the homepage destination card. If left empty, the excerpt or existing summary will be used.', 'popeye-tours'); ?>
    </p>

    <p style="margin-top:18px;">
        <label for="popeye-destination-long-description"><strong><?php esc_html_e('Long Description', 'popeye-tours'); ?></strong></label>
    </p>

    <p>
        <textarea
            class="widefat"
            rows="6"
            id="popeye-destination-long-description"
            name="popeye_destination_long_description"
        ><?php echo esc_textarea($long_description); ?></textarea>
    </p>

    <p class="description">
        <?php esc_html_e('Shown on the Country Guide card on the Destinations page. If left empty, the existing summary or excerpt will be used.', 'popeye-tours'); ?>
    </p>

    <p style="margin-top:18px;">
        <label for="popeye-destination-activities"><strong><?php esc_html_e('Activities', 'popeye-tours'); ?></strong></label>
    </p>

    <p>
        <textarea
            class="widefat"
            rows="5"
            id="popeye-destination-activities"
            name="popeye_destination_activities"
            placeholder="<?php esc_attr_e('Add one activity per line.', 'popeye-tours'); ?>"
        ><?php echo esc_textarea($activities); ?></textarea>
    </p>

    <p class="description">
        <?php esc_html_e('Shown as the simple activity bullets on the one-page Country Guide layout. Use one activity per line.', 'popeye-tours'); ?>
    </p>
    <?php
}

function popeye_add_destination_hero_image_metabox() {
    add_meta_box(
        'popeye-destination-hero-image',
        __('Destination Hero Image', 'popeye-tours'),
        'popeye_render_destination_hero_image_metabox',
        'destination',
        'side',
        'high',
        [
            '__block_editor_compatible_meta_box' => true,
        ]
    );
}
// add_action('add_meta_boxes', 'popeye_add_destination_hero_image_metabox');

function popeye_render_destination_hero_image_metabox($post) {
    $hero_image = get_post_meta($post->ID, 'destination_hero_image', true);

    wp_nonce_field('popeye_save_destination_hero_image', 'popeye_destination_hero_image_nonce');
    ?>
    <p>
        <?php esc_html_e('Upload an optional hero image for the destination detail page header. If left empty, the featured image will still be used.', 'popeye-tours'); ?>
    </p>

    <input
        type="hidden"
        id="popeye-destination-hero-image"
        name="popeye_destination_hero_image"
        value="<?php echo esc_url($hero_image); ?>"
    >

    <div class="popeye-destination-hero-preview<?php echo $hero_image ? ' has-image' : ''; ?>">
        <img
            src="<?php echo $hero_image ? esc_url($hero_image) : ''; ?>"
            alt=""
            <?php echo $hero_image ? '' : 'style="display:none;"'; ?>
        >
    </div>

    <p class="popeye-destination-hero-actions">
        <button type="button" class="button button-secondary" id="popeye-upload-destination-hero">
            <?php esc_html_e('Upload Hero Image', 'popeye-tours'); ?>
        </button>
        <button
            type="button"
            class="button-link-delete"
            id="popeye-remove-destination-hero"
            <?php echo $hero_image ? '' : 'style="display:none;"'; ?>
        >
            <?php esc_html_e('Remove Image', 'popeye-tours'); ?>
        </button>
    </p>

    <style>
        .popeye-destination-hero-preview {
            align-items: center;
            background: #f6f7f7;
            border: 1px dashed #c3c4c7;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            margin: 0 0 12px;
            min-height: 140px;
            overflow: hidden;
        }

        .popeye-destination-hero-preview img {
            display: block;
            height: 100%;
            min-height: 140px;
            object-fit: cover;
            width: 100%;
        }

        .popeye-destination-hero-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 0;
        }
    </style>

    <script>
        (function() {
            function initDestinationHeroImage() {
                var input = document.getElementById('popeye-destination-hero-image');
                var preview = document.querySelector('.popeye-destination-hero-preview');
                var image = preview ? preview.querySelector('img') : null;
                var uploadButton = document.getElementById('popeye-upload-destination-hero');
                var removeButton = document.getElementById('popeye-remove-destination-hero');

                if (!input || !preview || !image || !uploadButton || !removeButton || uploadButton.dataset.bound === '1') {
                    return;
                }

                uploadButton.dataset.bound = '1';

                function updateHeroImage(url) {
                    input.value = url || '';

                    if (url) {
                        image.src = url;
                        image.style.display = 'block';
                        removeButton.style.display = 'inline';
                        preview.classList.add('has-image');
                    } else {
                        image.src = '';
                        image.style.display = 'none';
                        removeButton.style.display = 'none';
                        preview.classList.remove('has-image');
                    }
                }

                uploadButton.addEventListener('click', function(event) {
                    event.preventDefault();

                    var frame = wp.media({
                        title: 'Select destination hero image',
                        button: {
                            text: 'Use this image'
                        },
                        library: {
                            type: 'image'
                        },
                        multiple: false
                    });

                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        var imageUrl = attachment && attachment.sizes && attachment.sizes.large
                            ? attachment.sizes.large.url
                            : attachment.url;

                        if (attachment && imageUrl) {
                            updateHeroImage(imageUrl);
                        }
                    });

                    frame.open();
                });

                removeButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    updateHeroImage('');
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDestinationHeroImage);
            } else {
                initDestinationHeroImage();
            }
        })();
    </script>
    <?php
}


function popeye_save_destination_descriptions($post_id) {
    if ( ! isset($_POST['popeye_destination_descriptions_nonce']) ) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['popeye_destination_descriptions_nonce']));

    if ( ! wp_verify_nonce($nonce, 'popeye_save_destination_descriptions') ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_revision($post_id) ) {
        return;
    }

    if ( ! current_user_can('edit_post', $post_id) ) {
        return;
    }

    $short_description = isset($_POST['popeye_destination_short_description'])
        ? sanitize_textarea_field(wp_unslash($_POST['popeye_destination_short_description']))
        : '';

    $long_description = isset($_POST['popeye_destination_long_description'])
        ? sanitize_textarea_field(wp_unslash($_POST['popeye_destination_long_description']))
        : '';

    $activities = isset($_POST['popeye_destination_activities'])
        ? popeye_sanitize_destination_activities_meta(wp_unslash($_POST['popeye_destination_activities']))
        : '';

    if ( $short_description === '' ) {
        delete_post_meta($post_id, 'destination_short_description');
        delete_post_meta($post_id, 'destination_summary_text');
    } else {
        update_post_meta($post_id, 'destination_short_description', $short_description);
        update_post_meta($post_id, 'destination_summary_text', $short_description);
    }

    if ( $long_description === '' ) {
        delete_post_meta($post_id, 'destination_long_description');
    } else {
        update_post_meta($post_id, 'destination_long_description', $long_description);
    }

    if ( $activities === '' ) {
        delete_post_meta($post_id, 'destination_activities');
        return;
    }

    update_post_meta($post_id, 'destination_activities', $activities);
}
add_action('save_post_destination', 'popeye_save_destination_descriptions');

function popeye_save_destination_hero_image($post_id) {
    if ( ! isset($_POST['popeye_destination_hero_image_nonce']) ) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['popeye_destination_hero_image_nonce']));

    if ( ! wp_verify_nonce($nonce, 'popeye_save_destination_hero_image') ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_revision($post_id) ) {
        return;
    }

    if ( ! current_user_can('edit_post', $post_id) ) {
        return;
    }

    $hero_image = isset($_POST['popeye_destination_hero_image'])
        ? esc_url_raw(wp_unslash($_POST['popeye_destination_hero_image']))
        : '';

    if ( $hero_image === '' ) {
        delete_post_meta($post_id, 'destination_hero_image');
        return;
    }

    update_post_meta($post_id, 'destination_hero_image', $hero_image);
}
add_action('save_post_destination', 'popeye_save_destination_hero_image');


// ── Custom Post Type: Packages ───────────────────────────────────────────────
function popeye_register_services_cpt() {
    register_post_type('service', [
        'labels' => [
            'name'          => __('Services', 'popeye-tours'),
            'singular_name' => __('Service', 'popeye-tours'),
            'add_new_item'  => __('Add New Service', 'popeye-tours'),
            'edit_item'     => __('Edit Service', 'popeye-tours'),
            'menu_name'     => __('Services', 'popeye-tours'),
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-star-filled',
        'menu_position' => 6,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'       => ['slug' => 'services'],
    ]);
}
add_action('init', 'popeye_register_services_cpt');


// ── Package Metabox: Type Badge ──────────────────────────────────────────────
function popeye_add_service_metaboxes() {
    add_meta_box(
        'popeye-service-meta',
        __('Service Details', 'popeye-tours'),
        'popeye_render_service_metabox',
        'service',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'popeye_add_service_metaboxes');

function popeye_render_service_metabox($post) {
    $service_type = get_post_meta($post->ID, 'service_type', true);
    wp_nonce_field('popeye_save_service_meta', 'popeye_service_meta_nonce');
    ?>
    <p>
        <label style="font-weight:600;display:block;margin-bottom:4px;"><?php esc_html_e('Type Badge', 'popeye-tours'); ?></label>
        <input
            type="text"
            class="widefat"
            name="popeye_service_type"
            value="<?php echo esc_attr($service_type); ?>"
            placeholder="e.g. Day &amp; Multi-Day"
        >
        <span class="description" style="display:block;margin-top:4px;"><?php esc_html_e('Short label on the card (e.g. "Day & Multi-Day", "Multi-Day").', 'popeye-tours'); ?></span>
    </p>
    <p class="description"><?php esc_html_e('Use Featured Image for the photo and Excerpt for the card description.', 'popeye-tours'); ?></p>
    <?php
}

function popeye_save_service_meta($post_id) {
    if ( ! isset($_POST['popeye_service_meta_nonce']) ) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['popeye_service_meta_nonce']));

    if ( ! wp_verify_nonce($nonce, 'popeye_save_service_meta') ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can('edit_post', $post_id) ) {
        return;
    }

    $service_type = isset($_POST['popeye_service_type'])
        ? sanitize_text_field(wp_unslash($_POST['popeye_service_type']))
        : '';

    if ( $service_type !== '' ) {
        update_post_meta($post_id, 'service_type', $service_type);
    } else {
        delete_post_meta($post_id, 'service_type');
    }
}
add_action('save_post_service', 'popeye_save_service_meta');


// ── Journal: Custom Author Metabox ───────────────────────────────────────────
function popeye_add_journal_author_metabox() {
    add_meta_box(
        'popeye-journal-author',
        __('Article Author', 'popeye-tours'),
        'popeye_render_journal_author_metabox',
        'journal',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'popeye_add_journal_author_metabox');

function popeye_render_journal_author_metabox($post) {
    $custom_author     = get_post_meta($post->ID, 'article_author', true);
    $custom_author_bio = get_post_meta($post->ID, 'article_author_bio', true);
    wp_nonce_field('popeye_save_journal_author', 'popeye_journal_author_nonce');
    ?>
    <p>
        <label style="font-weight:600;display:block;margin-bottom:4px;"><?php esc_html_e('Author Name', 'popeye-tours'); ?></label>
        <input
            type="text"
            class="widefat"
            name="popeye_article_author"
            value="<?php echo esc_attr($custom_author); ?>"
            placeholder="e.g. Romit Shah"
        >
        <span class="description" style="display:block;margin-top:4px;"><?php esc_html_e('Leave empty to use the WordPress post author.', 'popeye-tours'); ?></span>
    </p>
    <p>
        <label style="font-weight:600;display:block;margin-bottom:4px;"><?php esc_html_e('Author Bio', 'popeye-tours'); ?></label>
        <textarea
            class="widefat"
            name="popeye_article_author_bio"
            rows="3"
            placeholder="<?php esc_attr_e('Short bio shown below the article.', 'popeye-tours'); ?>"
        ><?php echo esc_textarea($custom_author_bio); ?></textarea>
    </p>
    <?php
}

function popeye_save_journal_author($post_id) {
    if ( ! isset($_POST['popeye_journal_author_nonce']) ) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['popeye_journal_author_nonce']));

    if ( ! wp_verify_nonce($nonce, 'popeye_save_journal_author') ) {
        return;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can('edit_post', $post_id) ) {
        return;
    }

    $author = isset($_POST['popeye_article_author'])
        ? sanitize_text_field(wp_unslash($_POST['popeye_article_author']))
        : '';

    $bio = isset($_POST['popeye_article_author_bio'])
        ? sanitize_textarea_field(wp_unslash($_POST['popeye_article_author_bio']))
        : '';

    if ( $author !== '' ) {
        update_post_meta($post_id, 'article_author', $author);
    } else {
        delete_post_meta($post_id, 'article_author');
    }

    if ( $bio !== '' ) {
        update_post_meta($post_id, 'article_author_bio', $bio);
    } else {
        delete_post_meta($post_id, 'article_author_bio');
    }
}
add_action('save_post_journal', 'popeye_save_journal_author');


// ── Journal: Gallery Images Metabox ─────────────────────────────────────────
function popeye_add_journal_gallery_metabox() {
    add_meta_box(
        'popeye-journal-gallery',
        __('Gallery Images', 'popeye-tours'),
        'popeye_render_journal_gallery_metabox',
        'journal',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'popeye_add_journal_gallery_metabox');

function popeye_render_journal_gallery_metabox($post) {
    $gallery_ids = get_post_meta($post->ID, 'article_gallery_ids', true);
    $ids_array   = $gallery_ids ? array_filter(explode(',', $gallery_ids)) : [];
    wp_nonce_field('popeye_save_journal_gallery', 'popeye_journal_gallery_nonce');
    ?>
    <input type="hidden" id="popeye-gallery-ids" name="popeye_gallery_ids" value="<?php echo esc_attr($gallery_ids); ?>">

    <div id="popeye-gallery-preview" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
        <?php foreach ($ids_array as $img_id) :
            $thumb = wp_get_attachment_image_url((int) $img_id, 'thumbnail');
            if ($thumb) : ?>
                <div class="popeye-gallery-item" data-id="<?php echo esc_attr($img_id); ?>" style="position:relative;width:80px;height:80px;border-radius:6px;overflow:hidden;border:1px solid #c3c4c7;">
                    <img src="<?php echo esc_url($thumb); ?>" style="width:100%;height:100%;object-fit:cover;display:block;">
                    <button type="button" class="popeye-gallery-remove" data-id="<?php echo esc_attr($img_id); ?>" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.6);color:#fff;border:none;border-radius:50%;width:20px;height:20px;font-size:12px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>
                </div>
            <?php endif;
        endforeach; ?>
    </div>

    <p style="display:flex;gap:10px;margin:0;">
        <button type="button" class="button button-secondary" id="popeye-gallery-add"><?php esc_html_e('Add Images', 'popeye-tours'); ?></button>
        <button type="button" class="button-link-delete" id="popeye-gallery-clear" <?php echo empty($ids_array) ? 'style="display:none;"' : ''; ?>><?php esc_html_e('Remove All', 'popeye-tours'); ?></button>
    </p>
    <p class="description" style="margin-top:8px;"><?php esc_html_e('These images appear as a grid below the article content. Optional — leave empty if not needed.', 'popeye-tours'); ?></p>

    <script>
    (function () {
        function init() {
            var input    = document.getElementById('popeye-gallery-ids');
            var preview  = document.getElementById('popeye-gallery-preview');
            var addBtn   = document.getElementById('popeye-gallery-add');
            var clearBtn = document.getElementById('popeye-gallery-clear');
            if (!input || !addBtn || addBtn.dataset.bound === '1') return;
            addBtn.dataset.bound = '1';

            function getIds() {
                return input.value ? input.value.split(',').filter(Boolean) : [];
            }

            function setIds(arr) {
                input.value = arr.join(',');
                clearBtn.style.display = arr.length ? 'inline' : 'none';
            }

            function renderPreview(ids) {
                preview.innerHTML = '';
                if (!ids.length) return;
                ids.forEach(function (id) {
                    // We can't get the thumb URL in JS without a round-trip, so just show a placeholder until save
                    var div = document.createElement('div');
                    div.className = 'popeye-gallery-item';
                    div.dataset.id = id;
                    div.style.cssText = 'position:relative;width:80px;height:80px;border-radius:6px;overflow:hidden;border:1px solid #c3c4c7;';
                    // Use WP attachment URL via wp.media if available
                    var attachment = wp.media.attachment(id);
                    attachment.fetch().then(function () {
                        var url = attachment.get('sizes') && attachment.get('sizes').thumbnail
                            ? attachment.get('sizes').thumbnail.url
                            : attachment.get('url');
                        var img = document.createElement('img');
                        img.src = url;
                        img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
                        div.appendChild(img);
                    });
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'popeye-gallery-remove';
                    btn.dataset.id = id;
                    btn.innerHTML = '&times;';
                    btn.style.cssText = 'position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.6);color:#fff;border:none;border-radius:50%;width:20px;height:20px;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;';
                    btn.addEventListener('click', function () {
                        var current = getIds().filter(function (i) { return i !== id; });
                        setIds(current);
                        renderPreview(current);
                    });
                    div.appendChild(btn);
                    preview.appendChild(div);
                });
            }

            // Delegated remove on existing items (rendered server-side)
            preview.addEventListener('click', function (e) {
                var btn = e.target.closest('.popeye-gallery-remove');
                if (!btn) return;
                var id      = btn.dataset.id;
                var current = getIds().filter(function (i) { return i !== id; });
                setIds(current);
                var item = preview.querySelector('.popeye-gallery-item[data-id="' + id + '"]');
                if (item) item.remove();
            });

            addBtn.addEventListener('click', function () {
                var frame = wp.media({
                    title: 'Select gallery images',
                    button: { text: 'Add to gallery' },
                    library: { type: 'image' },
                    multiple: true
                });
                frame.on('select', function () {
                    var selection = frame.state().get('selection').toJSON();
                    var current   = getIds();
                    selection.forEach(function (att) {
                        if (current.indexOf(String(att.id)) === -1) {
                            current.push(String(att.id));
                        }
                    });
                    setIds(current);
                    renderPreview(current);
                });
                frame.open();
            });

            clearBtn.addEventListener('click', function () {
                setIds([]);
                preview.innerHTML = '';
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
    <?php
}

function popeye_save_journal_gallery($post_id) {
    if ( ! isset($_POST['popeye_journal_gallery_nonce']) ) return;
    if ( ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['popeye_journal_gallery_nonce'])), 'popeye_save_journal_gallery') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    $raw = isset($_POST['popeye_gallery_ids']) ? sanitize_text_field(wp_unslash($_POST['popeye_gallery_ids'])) : '';
    // Sanitize: keep only comma-separated integers
    $ids = array_filter(array_map('absint', explode(',', $raw)));

    if ( empty($ids) ) {
        delete_post_meta($post_id, 'article_gallery_ids');
    } else {
        update_post_meta($post_id, 'article_gallery_ids', implode(',', $ids));
    }
}
add_action('save_post_journal', 'popeye_save_journal_gallery');


// ── Theme Customizer: Our Story Image ─────────────────────────────────────────
function popeye_customize_register($wp_customize) {
    // Panel
    $wp_customize->add_panel('popeye_theme_options', [
        'title'    => __('Popeye Tours Options', 'popeye-tours'),
        'priority' => 130,
    ]);

    // Section — Our Story
    $wp_customize->add_section('popeye_story_section', [
        'title'       => __('Our Story Section', 'popeye-tours'),
        'description' => __('Upload a photo that appears in the Our Story section on the homepage. Ideal dimensions: 900 × 500 px.', 'popeye-tours'),
        'panel'       => 'popeye_theme_options',
        'priority'    => 10,
    ]);

    // Setting
    $wp_customize->add_setting('popeye_story_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    // Control
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'popeye_story_image', [
        'label'     => __('Story Section Photo', 'popeye-tours'),
        'section'   => 'popeye_story_section',
        'mime_type' => 'image',
    ]));
}
add_action('customize_register', 'popeye_customize_register');

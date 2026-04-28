<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<?php
// Resolve author — custom meta takes priority over WP user
$custom_author     = get_post_meta(get_the_ID(), 'article_author',     true);
$custom_author_bio = get_post_meta(get_the_ID(), 'article_author_bio', true);

$display_author = $custom_author ?: get_the_author();
$display_bio    = $custom_author_bio ?: get_the_author_meta('description');

// Build initials (up to 2 characters)
$author_words = array_filter(explode(' ', $display_author));
$initials     = '';
foreach ($author_words as $word) {
    $initials .= strtoupper(substr($word, 0, 1));
}
$initials = substr($initials, 0, 2);
?>

<!-- Article Hero -->
<header class="article-hero" <?php if (has_post_thumbnail()) : ?>
    style="background-image: url('<?php the_post_thumbnail_url('full'); ?>');"
<?php else : ?>
    style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/safari_wildlife_1775896676172.png');"
<?php endif; ?>>
    <div class="ah-content">
        <span class="ah-meta"><?php echo get_post_meta(get_the_ID(), 'article_category', true) ?: 'Safari Journal'; ?></span>
        <h1 class="ah-title"><?php the_title(); ?></h1>
        <p style="font-size:1.2rem;color:#eee;margin:0;">By <?php echo esc_html($display_author); ?></p>
    </div>
</header>

<main class="article-body">
    <?php the_content(); ?>
</main>

<?php
// Optional gallery — image IDs stored via the Gallery Images metabox
$gallery_ids_raw = get_post_meta(get_the_ID(), 'article_gallery_ids', true);
$gallery_ids     = $gallery_ids_raw ? array_filter(array_map('absint', explode(',', $gallery_ids_raw))) : [];

if (!empty($gallery_ids)) :
    $gallery_count = count($gallery_ids);
?>
<div class="article-gallery">
    <div class="article-gallery-grid<?php echo $gallery_count === 1 ? ' single-img' : ''; ?>">
        <?php foreach ($gallery_ids as $img_id) :
            $img_url = wp_get_attachment_image_url($img_id, 'large');
            if (!$img_url) continue;
            $img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true) ?: get_the_title();
        ?>
            <img
                src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="article-gallery-img"
                loading="lazy"
            >
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="article-body" style="padding-top:0;">
    <!-- Author Box -->
    <div class="author-box">
        <div class="avatar"><?php echo esc_html($initials ?: '??'); ?></div>
        <div>
            <h4><?php echo esc_html($display_author); ?></h4>
            <?php if ($display_bio) : ?>
                <p><?php echo esc_html($display_bio); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>

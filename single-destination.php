<?php
// Redirect individual destination pages to the main destinations archive.
wp_redirect( home_url( '/destinations/' ), 301 );
exit;

/*
<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
<?php
$destination_id = get_the_ID();
$destination_title = get_the_title();
$destination_content = trim((string) get_the_content());
$destination_summary = popeye_get_destination_summary($destination_id, $destination_content);
$destination_hero_image = get_post_meta($destination_id, 'destination_hero_image', true);
$destination_thumbnail = get_the_post_thumbnail_url($destination_id, 'full');
$destination_header_image = $destination_hero_image ? $destination_hero_image : $destination_thumbnail;
?>

<header
    class="page-header destination-hero"
    <?php if ( $destination_header_image ) : ?>
        style="background-image: linear-gradient(rgba(25, 48, 37, 0.72), rgba(25, 48, 37, 0.72)), url('<?php echo esc_url($destination_header_image); ?>');"
    <?php endif; ?>
>
    <div class="destination-hero__inner">
        <a href="<?php echo esc_url(home_url('/destinations')); ?>" class="destination-back-link">All destinations</a>
        <p class="destination-eyebrow">Destination guide</p>
        <h1><?php echo esc_html($destination_title); ?></h1>
        <?php if ( $destination_summary ) : ?>
            <p class="destination-summary"><?php echo esc_html($destination_summary); ?></p>
        <?php endif; ?>
    </div>
</header>

<main class="country-section">
    <article class="country-wrap country-wrap--full">
        <div class="country-head">
            <div class="destination-intro">
                <p class="destination-section-label">Country overview</p>
                <h2><?php echo esc_html($destination_title); ?></h2>
                <?php if ( $destination_summary ) : ?>
                    <p class="destination-section-summary"><?php echo esc_html($destination_summary); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( $destination_thumbnail ) : ?>
                <img
                    src="<?php echo esc_url($destination_thumbnail); ?>"
                    class="country-img"
                    alt="<?php echo esc_attr($destination_title); ?>"
                >
            <?php endif; ?>
        </div>

        <?php if ( $destination_content !== '' ) : ?>
            <div class="destination-body">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
    </article>
</main>

<?php endwhile; ?>

<?php get_footer(); ?>
*/

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Navigation -->
<nav class="nav">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-logo">
        <img
            src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/images/Popeye Logo Transparent Bg.webp"
            alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
            class="nav-logo-img"
        >
    </a>
    <div class="nav-links">
        <a href="<?php echo esc_url(home_url('/#services')); ?>" class="link">Services</a>
        <a href="<?php echo esc_url(home_url('/#destinations')); ?>" class="link">Destinations</a>
        <a href="<?php echo esc_url(home_url('/#blog')); ?>" class="link">Journal</a>
        <a href="<?php echo esc_url(home_url('/#story')); ?>" class="link">Our Story</a>
        <div class="trust-badge-nav">Replies within 2 hrs</div>
        <button class="btn-primary" data-open-planner="true">Plan my trip</button>
    </div>
    <button class="hamburger" id="hamburger-menu" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<!-- Modal Overlay (available on every page) -->
<div class="modal-overlay" id="modal-overlay">
    <div class="modal-content">
        <button class="modal-close">&times;</button>
        <div id="modal-planner" class="planner-card" style="box-shadow:none;border:none;border-radius:20px;"></div>
    </div>
</div>

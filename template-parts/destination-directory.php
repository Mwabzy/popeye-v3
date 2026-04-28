<?php
$destination_items = popeye_get_destination_items();
?>

<header class="page-header destination-directory-hero">
    <div class="destination-directory-hero__inner">
        <p class="destination-eyebrow">Destination guides</p>
        <h1>Explore Destinations</h1>
        <p class="destination-directory-note">
            A simple guide to the countries we travel through across East Africa, with a short feel for what makes each destination special.
        </p>
    </div>
</header>

<main class="country-section destination-directory">
    <?php if ( ! empty($destination_items) ) : ?>
        <div class="destination-simple-list">
            <?php foreach ( $destination_items as $destination ) : ?>
                <section class="destination-simple-card" id="<?php echo esc_attr($destination['slug']); ?>">
                    <div class="destination-simple-card__media">
                        <img
                            src="<?php echo esc_url($destination['thumbnail']); ?>"
                            alt="<?php echo esc_attr($destination['title']); ?>"
                        >
                    </div>

                    <div class="destination-simple-card__content">
                        <p class="destination-section-label">Country guide</p>
                        <h2><?php echo esc_html($destination['title']); ?></h2>
                        <p class="destination-simple-card__summary">
                            <?php
                            echo esc_html(
                                $destination['long_description']
                                    ? $destination['long_description']
                                    : __('Destination details coming soon.', 'popeye-tours')
                            );
                            ?>
                        </p>

                        <?php if ( ! empty($destination['activities']) ) : ?>
                            <div class="destination-simple-card__activities">
                                <p class="destination-simple-card__activities-label">
                                    <?php esc_html_e('Signature activities', 'popeye-tours'); ?>
                                </p>
                                <ul class="destination-activity-list">
                                    <?php foreach ( $destination['activities'] as $activity ) : ?>
                                        <li><?php echo esc_html($activity); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="destination-empty">
            <h3>No destinations yet</h3>
            <p>Create destination posts in the WordPress dashboard and they will appear here automatically.</p>
        </div>
    <?php endif; ?>
</main>

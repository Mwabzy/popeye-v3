<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg-carousel" id="hero-carousel">
        <div class="hero-slide active" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/tanzania_landscape_1775896936759.png');"></div>
        <div class="hero-slide" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/safari_wildlife_1775896676172.png');"></div>
        <div class="hero-slide" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/handpicked_stays_1775896860742.png');"></div>
        <div class="hero-bg-overlay"></div>
    </div>
    <div class="hero-content">
        <h1>Every trip is<br><span>a work of art</span></h1>
        <p class="subtitle">Boutique East Africa tour operator specializing in bespoke itineraries across Kenya, Tanzania, Zanzibar, and Uganda.</p>
        <div class="hero-ctas">
            <a href="#" class="btn-whatsapp" data-open-planner="true">
                <svg viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.386 0 12.031c0 2.122.553 4.195 1.604 6.01L.226 23.364l5.46-1.43a12.016 12.016 0 006.345 1.802c6.645 0 12.031-5.385 12.031-12.032S18.676 0 12.031 0zm0 21.803c-1.782 0-3.525-.48-5.053-1.385l-.362-.215-3.753.984.996-3.662-.236-.376A9.97 9.97 0 012.062 12.03c0-5.503 4.478-9.98 9.97-9.98 5.5.002 9.972 4.48 9.973 9.98.001 5.504-4.474 9.98-9.974 9.973zm5.468-7.466c-.3-.15-1.776-.877-2.052-.977-.275-.1-.476-.15-.676.15-.2.301-.776.977-.951 1.177-.176.201-.351.226-.651.076a8.212 8.212 0 01-2.42-1.493 9.074 9.074 0 01-1.683-2.09c-.176-.302-.018-.465.132-.614.136-.135.301-.35.451-.525.15-.176.2-.301.301-.502.101-.2.05-.376-.025-.526-.075-.15-.676-1.628-.926-2.228-.244-.585-.492-.505-.676-.514-.176-.008-.376-.008-.576-.008s-.526.075-.801.376c-.275.301-1.052 1.028-1.052 2.505s1.077 2.895 1.227 3.095c.15.201 2.103 3.21 5.093 4.5.711.307 1.266.49 1.698.627.712.227 1.36.195 1.872.118.574-.086 1.776-.726 2.026-1.427.25-.701.25-1.302.176-1.427-.076-.125-.276-.2-.576-.35z"/></svg>
                Start a conversation
            </a>
            <a href="mailto:info@popeyetours.co.ke" class="btn-secondary">Email us</a>
        </div>
        <div class="trust-signals-hero">
            <span>
                <svg viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                Fully Customisable
            </span>
            <span>
                <svg viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                Local Experts
            </span>
            <span>
                <svg viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                Seamless Logistics
            </span>
        </div>
    </div>
    <div class="hero-right">
        <div id="inline-planner" class="planner-card"></div>
    </div>
</section>



<!-- Services Section -->
<section class="section" id="services">
    <div class="services-header-row">
        <div class="section-header">
            <h2>Our Services</h2>
            <p style="color:#666;font-size:1.15rem;font-weight:500;">Curated experiences across East Africa, fully tailored to you.</p>
        </div>
        <div class="services-arrow-group">
            <button class="services-arrow services-arrow--prev" id="services-prev" aria-label="Previous service" disabled>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="services-arrow services-arrow--next" id="services-next" aria-label="Next service">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>

    <div class="services-carousel-viewport">
        <div class="services-track" id="services-track">
            <?php
            $service_posts = get_posts([
                'post_type'      => 'service',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ]);

                $default_svc_img = get_stylesheet_directory_uri() . '/images/safari_wildlife_1775896676172.png';

                if ($service_posts) :
                    foreach ($service_posts as $svc) :
                        $svc_id    = $svc->ID;
                        $svc_title = get_the_title($svc_id);
                        $svc_desc  = $svc->post_excerpt ?: wp_trim_words(wp_strip_all_tags($svc->post_content), 20);
                        $svc_type  = get_post_meta($svc_id, 'service_type', true) ?: 'Multi-Day';
                        $svc_image = get_the_post_thumbnail_url($svc_id, 'large') ?: $default_svc_img;
                ?>
                 <a href="#" class="service-card" data-open-planner="<?php echo esc_attr($svc_title); ?>" style="color:inherit;display:block;">
                     <div class="sc-img">
                         <img src="<?php echo esc_url($svc_image); ?>" alt="<?php echo esc_attr($svc_title); ?>">
                         <span class="sc-type"><?php echo esc_html($svc_type); ?></span>
                     </div>
                     <div class="sc-content">
                         <h3><?php echo esc_html($svc_title); ?></h3>
                         <p><?php echo esc_html($svc_desc); ?></p>
                     </div>
                 </a>
                <?php
                    endforeach;
                else :
                ?>
                <a href="#" class="service-card" data-open-planner="true" style="color:inherit;display:block;">
                    <div class="sc-img">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bespoke_itinerary_1775896904954.png" alt="Bespoke itineraries">
                        <span class="sc-type">Day &amp; Multi-Day</span>
                    </div>
                    <div class="sc-content">
                        <h3>Bespoke Itineraries</h3>
                        <p>Designed around your exact desires, whether it's a quick day trip or a 2-week grand adventure.</p>
                    </div>
                </a>
                <a href="#" class="service-card" data-open-planner="true" style="color:inherit;display:block;">
                    <div class="sc-img">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/safari_wildlife_1775896676172.png" alt="Wildlife safaris">
                        <span class="sc-type">Day &amp; Multi-Day</span>
                    </div>
                    <div class="sc-content">
                        <h3>Wildlife Safaris</h3>
                        <p>Encounter the Big Five and witness the Great Migration in iconic reserves with expert local guides.</p>
                    </div>
                </a>
                <a href="#" class="service-card" data-open-planner="true" style="color:inherit;display:block;">
                    <div class="sc-img">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/coastal_escape_1775896839404.png" alt="Coastal escapes">
                        <span class="sc-type">Multi-Day</span>
                    </div>
                    <div class="sc-content">
                        <h3>Coastal Escapes</h3>
                        <p>Unwind on pristine Indian Ocean beaches, sail on traditional dhows, and explore historic stone towns.</p>
                    </div>
                </a>
                <a href="#" class="service-card" data-open-planner="true" style="color:inherit;display:block;">
                    <div class="sc-img">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/handpicked_stays_1775896860742.png" alt="Handpicked stays">
                        <span class="sc-type">Multi-Day</span>
                    </div>
                    <div class="sc-content">
                        <h3>Handpicked Stays</h3>
                        <p>Sleep under canvas in luxury boutique lodges and intimate eco-camps closely connected to nature.</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Destinations Section -->
<section class="section" id="destinations" style="background:var(--white);">
    <div class="section-header">
        <h2>Explore Destinations</h2>
        <p style="color:#666;font-size:1.15rem;font-weight:500;">Incredible regions, unforgettable encounters.</p>
    </div>
    <?php $homepage_destinations = array_slice(popeye_get_destination_items(), 0, 5); ?>
    <div class="dest-grid front-destination-grid">
        <?php foreach ( $homepage_destinations as $index => $destination ) : ?>
            <a
                href="<?php echo esc_url($destination['archive_anchor']); ?>"
                class="dest-card<?php echo $index === 0 ? ' dest-card--featured' : ''; ?>"
            >
                <img
                    src="<?php echo esc_url($destination['thumbnail']); ?>"
                    class="dest-img"
                    alt="<?php echo esc_attr($destination['title']); ?>"
                >
                <div class="dest-overlay">
                    <h3><?php echo esc_html($destination['title']); ?></h3>
                    <?php if ( $destination['short_description'] ) : ?>
                        <p class="dest-desc"><?php echo esc_html($destination['short_description']); ?></p>
                    <?php endif; ?>
                    <span class="dest-hover">Explore <?php echo esc_html($destination['title']); ?> &rarr;</span>
                </div>
            </a>
        <?php endforeach; ?>

        <a href="<?php echo esc_url(home_url('/destinations')); ?>" class="dest-card dest-more dest-more--uniform">
            <div class="dest-more-content">
                <svg class="compass-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="44" stroke="currentColor" stroke-width="4" stroke-dasharray="10 8"/>
                    <circle cx="50" cy="50" r="32" stroke="currentColor" stroke-width="2" opacity="0.5"/>
                    <path class="compass-needle" d="M50 18L58 50L50 82L42 50L50 18Z" fill="currentColor"/>
                    <circle cx="50" cy="50" r="6" fill="var(--green)"/>
                </svg>
                <h3>Explore All<br>Destinations</h3>
                <span style="opacity:1;color:var(--white);font-size:0.95rem;margin-top:0.5rem;">View full map &rarr;</span>
            </div>
        </a>
    </div>
</section>

<!-- Blog / Journal Section -->
<section class="section" id="blog" style="background:var(--sand);">
    <div class="journal-header-row">
        <div class="section-header">
            <h2>Safari Journal</h2>
            <p style="color:#666;font-size:1.15rem;font-weight:500;">Field notes, travel guides, and inspiration from the wild.</p>
        </div>
        <div class="services-arrow-group">
            <button class="services-arrow services-arrow--prev" id="journal-prev" aria-label="Previous article" disabled>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="services-arrow services-arrow--next" id="journal-next" aria-label="Next article">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </div>
    <div class="journal-carousel-viewport">
        <div class="journal-track" id="journal-track">
        <?php
        $journal_posts = get_posts([
            'post_type'      => 'journal',
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ]);

        if ($journal_posts) :
            foreach ($journal_posts as $post) :
                setup_postdata($post);
        ?>
        <a href="<?php the_permalink(); ?>" class="blog-card" style="text-decoration:none;color:inherit;">
            <div class="blog-img">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', ['alt' => get_the_title()]); ?>
                <?php else : ?>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/safari_wildlife_1775896676172.png" alt="<?php the_title_attribute(); ?>">
                <?php endif; ?>
            </div>
            <div class="blog-content">
                <span class="blog-meta"><?php echo get_post_meta(get_the_ID(), 'article_category', true) ?: 'Safari Journal'; ?></span>
                <h3><?php the_title(); ?></h3>
                <p><?php echo get_the_excerpt(); ?></p>
                <span class="read-more">Read article &rarr;</span>
            </div>
        </a>
        <?php
            endforeach;
            wp_reset_postdata();
        else :
        ?>
        <!-- Fallback static cards if no journal posts exist yet -->
        <a href="<?php echo esc_url(home_url('/journal')); ?>" class="blog-card" style="text-decoration:none;color:inherit;">
            <div class="blog-img">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/safari_wildlife_1775896676172.png" alt="Great Migration Guide">
            </div>
            <div class="blog-content">
                <span class="blog-meta">Wildlife Guides</span>
                <h3>The Ultimate Guide to Surviving the Great Migration</h3>
                <p>Everything you need to know about timing, locations, and the best camps to witness the spectacular Mara river crossings.</p>
                <span class="read-more">Read article &rarr;</span>
            </div>
        </a>
        <a href="<?php echo esc_url(home_url('/journal')); ?>" class="blog-card" style="text-decoration:none;color:inherit;">
            <div class="blog-img">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bespoke_itinerary_1775896904954.png" alt="Safari Packing List" style="object-position:top;">
            </div>
            <div class="blog-content">
                <span class="blog-meta">Travel Preparation</span>
                <h3>What to Pack for a Luxury East African Safari</h3>
                <p>Leave the heavy luggage behind. We detail the essential gear, the optimal colour palettes, and exactly what to bring.</p>
                <span class="read-more">Read article &rarr;</span>
            </div>
        </a>
        <?php endif; ?>
        </div><!-- /.journal-track -->
    </div><!-- /.journal-carousel-viewport -->
</section>

<!-- Story & Founders -->
<section class="section story-section" id="story">
    <div class="story-text">
        <?php
        $story_image_id  = get_theme_mod('popeye_story_image', '');
        $story_image_url = $story_image_id ? wp_get_attachment_image_url((int) $story_image_id, 'large') : '';
        if ($story_image_url) :
        ?>
        <div class="story-image-wrap">
            <img src="<?php echo esc_url($story_image_url); ?>" alt="Our Story" class="story-image">
        </div>
        <?php endif; ?>
        <h2>Our Story</h2>
        <p>At Popeye Tours, we believe travel shouldn't be mass-produced. Born from a lifelong love for the East African wilderness, we craft journeys that are personal, breathtaking, and seamless from touchdown to takeoff.</p>
        <blockquote class="pull-quote">"We're not here to just show you our home. We're here to make you fall in love with it."</blockquote>
        <div class="trust-badge-nav" style="display:inline-flex;border:1px solid rgba(43,76,59,0.2);margin-top:1rem;border-radius:10px;padding:0.8rem 1.2rem;background:transparent;">
            Promise: Under 2 hours response time, 7 days a week
        </div>
    </div>
    <div class="founder-grid">
        <div class="founder-card">
            <div class="avatar">RS</div>
            <div class="fc-info">
                <h4>Romit Shah</h4>
                <span class="role">Safari Specialist &middot; "The Wild One"</span>
                <p>With an instinct for identifying the best hidden trails and a tracking eye that never misses, Romit designs the wildest, most thrilling safari adventures across the savanna.</p>
            </div>
        </div>
        <div class="founder-card">
            <div class="avatar jr">JR</div>
            <div class="fc-info">
                <h4>Jignesh Rajani</h4>
                <span class="role">Logistics &amp; Stays &middot; "The Gentle One"</span>
                <p>Jignesh ensures every detail of your journey is flawlessly executed, handpicking the finest boutique lodges so you can simply relax and enjoy the magic of Africa.</p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>

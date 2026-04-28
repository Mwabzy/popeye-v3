<?php get_header(); ?>

<header class="page-header journal-archive-hero">
    <div class="journal-archive-hero__inner">
        <p class="destination-eyebrow">Safari journal</p>
        <h1>Journal</h1>
        <p class="destination-summary">Stories, travel notes, and field guides from across East Africa.</p>
    </div>
</header>

<main class="section journal-archive-section" style="background:var(--sand);">
    <?php
    $journal_posts = get_posts([
        'post_type'      => 'journal',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>

    <?php if ( $journal_posts ) : ?>
        <div class="blog-grid journal-archive-grid">
            <?php foreach ( $journal_posts as $post ) : setup_postdata($post); ?>
                <a href="<?php the_permalink(); ?>" class="blog-card" style="text-decoration:none;color:inherit;">
                    <div class="blog-img">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail('large', ['alt' => get_the_title()]); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/safari_wildlife_1775896676172.png'); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="blog-content">
                        <span class="blog-meta"><?php echo esc_html(get_post_meta(get_the_ID(), 'article_category', true) ?: 'Safari Journal'); ?></span>
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html(get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()), 24)); ?></p>
                        <span class="read-more">Read article &rarr;</span>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    <?php else : ?>
        <div class="destination-empty">
            <h3>No journal articles yet</h3>
            <p>Add articles in the WordPress dashboard and they will appear here automatically.</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>

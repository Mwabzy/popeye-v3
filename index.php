<?php
// This is a required WordPress fallback template.
// The site uses front-page.php for the homepage.
get_header(); ?>

<main style="padding: 160px 5% 80px; min-height: 60vh;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>

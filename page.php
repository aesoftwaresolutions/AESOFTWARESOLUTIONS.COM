<?php get_header(); ?>

<div class="content-area">
    <div class="content-container">
        <?php
        while ( have_posts() ) :
            the_post();
            the_title( '<h1 class="entry-title">', '</h1>' );
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>

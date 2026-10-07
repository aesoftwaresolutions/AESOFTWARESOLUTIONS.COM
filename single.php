<?php
/**
 * Single blog post. Without this file WordPress fell back to index.php, which
 * showed the "Our Blog" listing (title + excerpt) instead of the full post.
 *
 * @package AE_Softwares
 */

get_header();
?>

<div class="content-area">
    <div class="content-container">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <p class="entry-meta"><?php echo esc_html( get_the_date() ); ?></p>
                <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
        <p class="entry-back"><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>">&larr; Back</a></p>
    </div>
</div>

<?php get_footer(); ?>

<?php get_header(); ?>
<main id="main-content"<?php echo feast_is_elementor_page() ? '' : ' class="content-page"'; ?>>
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?>><h1><?php the_title(); ?></h1><?php the_content(); ?></article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>

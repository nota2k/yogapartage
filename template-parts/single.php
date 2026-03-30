<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php
while ( have_posts() ) :
	the_post();
	?>

<main <?php post_class( 'site-main post-container' ); ?> role="main">
	<div class="single-php"></div>	
		<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
			<header class="page-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>
		<?php endif; ?>
		<div class="page-content">
			
			<?php the_content(); ?>
			<div class="split"></div>
			<div class="meta-post-container">
				<span class="entry-date">
					Publié le <?php echo get_the_date(); ?>
				</span>
				<span class="autheur">
				<?php the_author(); ?>
				</span>
			</div>
			<div class="post-tags">
				<?php the_tags( '<span class="tag-links">' , null, '</span>' ); ?>
			</div>
			<?php wp_link_pages(); ?>
		</div>
		<div class="split"></div>		
		<div class="comments-wrapper"><?php comments_template(); ?></div>

</main>

	<?php
endwhile;

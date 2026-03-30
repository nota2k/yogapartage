<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<?php
while (have_posts()) :
	the_post();
?>

	<main <?php post_class('site-main'); ?> role="main">
		<div class="page-php">
			<?php if (apply_filters('hello_elementor_page_title', true)) : ?>
				<header class="page-header">
					<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
				</header>
			<?php endif; ?>
			<div class="page-content">
				<?php the_content(); ?>
				<div class="post-tags">
					<?php the_tags('<span class="tag-links">' . __('Tagged ', 'hello-elementor'), null, '</span>'); ?>
				</div>
				<?php wp_link_pages(); ?>
			</div>
		</div>
	</main>
	<div class="container-background">
		<div class="forme-01 rellax forme-background" data-rellax-speed="3">
			<img src="https://yogapartage.fr/wp-content/uploads/2022/02/forme-home-01.png" />
		</div>
		<div id="forme-02" class="rellax forme-background" data-rellax-speed="3">
			<img src="https://yogapartage.fr/wp-content/uploads/2022/02/forme-home-02.png" />
		</div>
		<div id="forme-03" class="rellax forme-background" data-rellax-speed="3">
			<img src="https://yogapartage.fr/wp-content/uploads/2022/02/forme-home-03.png" />
		</div>
	</div>
	<script>
		// Accepts any class name
		var rellax = new Rellax('.rellax');
	</script>
	</div>

<?php
endwhile;

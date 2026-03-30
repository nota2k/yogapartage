<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$is_editor = isset( $_GET['elementor-preview'] );
$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );
$footer_class = did_action( 'elementor/loaded' ) ? esc_attr( hello_get_footer_layout_class() ) : '';
$footer_nav_menu = wp_nav_menu( [
	'theme_location' => 'menu-2',
	'fallback_cb' => false,
	'echo' => false,
] );
?>
<footer id="site-footer" class="site-footer dynamic-footer <?php echo $footer_class; ?>" role="contentinfo">
	<!-- ajout de ma nouvelle widget area -->
	 <?php if ( is_active_sidebar( 'new-widget-area' ) ) : ?>
	 <div id="footer-widget-area" class="nwa-footer-widget widget-area" role="complementary">
	 <?php dynamic_sidebar( 'new-widget-area' ); ?>
	 </div>
	 <?php endif; ?>
	 <!-- fin nouvelle widget area -->
	<div class="footer-inner">
		<div class="site-branding show-<?php echo hello_elementor_get_setting( 'hello_footer_logo_type' ); ?>">
			<?php if ( has_custom_logo() && ( 'title' !== hello_elementor_get_setting( 'hello_footer_logo_type' ) || $is_editor ) ) : ?>
				<div class="site-logo <?php echo hello_show_or_hide( 'hello_footer_logo_display' ); ?>">
					<?php the_custom_logo(); ?>
				</div>
			<?php endif;

			if ( $site_name && ( 'logo' !== hello_elementor_get_setting( 'hello_footer_logo_type' ) ) || $is_editor ) : ?>
				<h4 class="site-title <?php echo hello_show_or_hide( 'hello_footer_logo_display' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-elementor' ); ?>" rel="home">
						<?php echo esc_html( $site_name ); ?>
					</a>
				</h4>
			<?php endif;

			if ( $tagline || $is_editor ) : ?>
				<p class="site-description <?php echo hello_show_or_hide( 'hello_footer_tagline_display' ); ?>">
					<?php echo esc_html( $tagline ); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $footer_nav_menu ) : ?>
			<nav class="site-navigation <?php echo hello_show_or_hide( 'hello_footer_menu_display' ); ?>" role="navigation">
				<?php echo $footer_nav_menu; ?>
			</nav>
		<?php endif; ?>

		<?php if ( '' !== hello_elementor_get_setting( 'hello_footer_copyright_text' ) || $is_editor ) : ?>
			<div class="copyright <?php echo hello_show_or_hide( 'hello_footer_copyright_display' ); ?>">
				<p><?php echo hello_elementor_get_setting( 'hello_footer_copyright_text' ); ?></p>
			</div>
		<?php endif; ?>
	</div>

</footer>

	<div class="container-background">
		<div class="forme-01 rellax forme-background" data-rellax-speed="3" >
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


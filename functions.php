<?php
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), NULL, filemtime(get_stylesheet_directory() . '/style.css'));
}

function my_theme_scripts()
{
    $js_path = get_stylesheet_directory() . '/assets/js/custom-jquery.js';
    wp_enqueue_script(
        'yogapartage-custom-jquery',
        get_stylesheet_directory_uri() . '/assets/js/custom-jquery.js',
        array('jquery'),
        file_exists($js_path) ? filemtime($js_path) : wp_get_theme()->get('Version'),
        true
    );
    wp_enqueue_script('rellax', get_stylesheet_directory_uri() . '/assets/js/rellax.js', array(), '1.0.0', true);
    wp_enqueue_script('rellax-min', get_stylesheet_directory_uri() . '/assets/js/rellax.min.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'my_theme_scripts');

// Editor color palette.
function mytheme_setup_theme_supported_features()
{

    add_theme_support(
        'editor-color-palette',
        array(
            array('name' => 'blue', 'slug'  => 'blue', 'color' => '#48ADD8'),
            array('name' => 'pink', 'slug'  => 'pink', 'color' => '#FF2952'),
            array('name' => 'green', 'slug'  => 'green', 'color' => '#83BD71'),
            array('name' => 'yellow', 'slug'  => 'yellow', 'color' => '#FFC334'),
            array('name' => 'red', 'slug'  => 'red', 'color' => '#B54D4D'),
            array('name' => 'grey', 'slug'  => 'grey', 'color' => '#f8f8f8'),
            array('name' => 'ui', 'slug'  => 'ui', 'color' => '#232634'),
            array('name' => 'ui-dark', 'slug'  => 'ui-dark', 'color' => '#2F3344'),
            array('name' => 'ui-light', 'slug'  => 'ui-light', 'color' => '#575D74'),
        )
    );
}
add_action('after_setup_theme', 'mytheme_setup_theme_supported_features');

/**
 * Fonctionnalités FSE (éditeur de site, blocs, parties de modèle) pour le thème enfant.
 * Le parent fournit déjà theme.json ; ce fichier enfant affine les réglages et
 * active l’édition des template parts dans l’éditeur de site.
 */
function hello_yogapartage_fse_support()
{
    add_theme_support('block-template-parts');
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'hello_yogapartage_fse_support', 20);

function footer_widgets_init()
{

    register_sidebar(array(

        'name' => 'Pied de page',
        'id' => 'new-widget-area',
        'before_widget' => '<div class="nwa-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="nwa-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'footer_widgets_init');

//Read More Button For Excerpt
function themeprefix_excerpt_read_more_link($output)
{
    global $post;
    return $output . ' <a href="' . get_permalink($post->ID) . '" class="more-link" title="Lire la suite">Lire la suite</a>';
}
add_filter('the_excerpt', 'themeprefix_excerpt_read_more_link');

add_filter('wpcf7_autop_or_not', '__return_false');


//Archive post of Homepage

function latest_post()
{
    ob_start();
    echo '<div class="container-recent-post">';

    $args = array(
        'posts_per_page' => 3, /* how many post you need to display */
        'offset' => 0,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post', /* your post type name */
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);
    $recent_posts = new WP_Query($args);
    $post_date = get_the_date('j F , Y');

    if ($query->have_posts()) :
        while ($recent_posts->have_posts()) :
            $recent_posts->the_post() ?>
            <li>
                <a href="<?php echo get_permalink() ?>">
                    <h2><?php the_title() ?></h2>
                </a>
                <span class="separateur"></span>
                <span class="date-post"><?php echo $post_date; ?></span>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="thmb-cat-wrapper">
                        <div class="cat-post-circle">
                            <h3><?php foreach ((get_the_category()) as $category) {
                                    echo $category->name;
                                } ?></h3>
                        </div>
                        <div class="recent-thmb"><a href="<?php echo get_permalink() ?>"><?php the_post_thumbnail('large') ?></div>
                    </div>
                    <div class="recent-post-excerpt"><?php the_excerpt() ?></div>
                    </a>
                <?php endif ?>
            </li>

<?php endwhile;
        wp_reset_postdata();
        echo '</div>';
        return ob_get_clean();
    endif;
}

add_shortcode('latest-post', 'latest_post');

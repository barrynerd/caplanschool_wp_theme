<?php
/* Template Name: Front-page Default, No Featured Image Template
Template Post Type: post, page, product
*/
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package understrap
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header("no_featured_img");

$container   = get_theme_mod('understrap_container_type');

?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr($container); ?> px-0" id="content" tabindex="-1">


		<main class="site-main" id="main">
			<?php
                while (have_posts()){
                    the_post();
                    get_template_part('loop-templates/content-no_featured_img', 'page');
                }
			?>
		</main><!-- #main -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>

<?php
/* 
Template Name: Front-page Default, No Featured Image Template
Template Post Type: post, page, product
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header("no_featured_img");

$container   = get_theme_mod('understrap_container_type');

?>

<div class="wrapper" id="page-wrapper">
    <?php
    if (is_front_page()) {
        if (is_active_sidebar('bcc-widget-area-01')) {
            dynamic_sidebar('bcc-widget-area-01');
        }
    }
    ?>

    <?php
    // only show these widgets to admin
    if (current_user_can('edit_posts')) {
        if (is_front_page()) {
            if (is_active_sidebar('bcc-widget-area-02')) {
                dynamic_sidebar('bcc-widget-area-02');
            }
        }
    }
    ?>

    <div class="<?php echo esc_attr($container); ?> px-0" id="content" tabindex="-1">

        <main class="site-main" id="main">
            <?php
            while (have_posts()) {
                the_post();
                get_template_part('loop-templates/content-no_featured_img', 'page');
            }
            ?>
        </main>
    </div>

</div>

<?php get_footer(); ?>
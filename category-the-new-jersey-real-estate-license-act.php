<?php
/* Template Name: Rules & Regs
Template Post Type: post, page
*/
/**
 *
 * @package understrap
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod('understrap_container_type');
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

		<div class="row">


			<!-- Do the left sidebar check -->
			<?php get_template_part('global-templates/left-sidebar-check'); ?>

			<main class="site-main" id="main">

				<?php if (have_posts()) : ?>

					<header class="page-header">
						<div class="headline-wrapper">
							<img class="headline-logo" src="/wordpress/wp-content/uploads/square-logo-caplanschool-61x60.png" />
							<?php
                            the_archive_title('<h1 class="page-title">', '</h1>');
                            ?>

                        </div>
                        <div class="card col-8">
                            <div class="card-body>">
                            <?php
                                the_archive_description('<div class="taxonomy-description">', '</div>');
                                ?>
                            </div>
                        </div>
					</header><!-- .page-header -->

					<?php /* Start the Loop */ ?>
					<?php while (have_posts()) : the_post(); ?>

						<?php

                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part('loop-templates/content-no-meta', get_post_format());
                        ?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part('loop-templates/content-no-meta', 'none'); ?>

				<?php endif; ?>

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php understrap_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php get_template_part('global-templates/right-sidebar-check'); ?>

		</div> <!-- .row -->

	</div><!-- #content -->

	</div><!-- #archive-wrapper -->

<?php get_footer(); ?>

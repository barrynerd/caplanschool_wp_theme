<?php
/**
 * The template for displaying the author pages.
 *
 * Learn more: https://codex.wordpress.org/Author_Templates
 *
 * @package understrap
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod('understrap_container_type');
?>

<div class="wrapper" id="author-wrapper">

	<div class="<?php echo esc_attr($container); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part('global-templates/left-sidebar-check'); ?>

			<main class="site-main" id="main">

				<header class="page-header author-header">

					<?php
                    if (isset($_GET['author_name'])) {
                        $curauth = get_user_by('slug', $author_name);
                    } else {
                        $curauth = get_userdata(intval($author));
                    }
                    ?>

					<h1><?php echo esc_html__('Posts by', 'understrap') . ' ' . esc_html($curauth->first_name) . ' '. esc_html($curauth->last_name) ; ?></h1>

					<?php if (! empty($curauth->ID)) : ?>
						<?php echo get_avatar($curauth->ID); ?>
					<?php endif; ?>

					<?php if (! empty($curauth->user_url) || ! empty($curauth->user_description)) : ?>
						<dl>
							<?php if (! empty($curauth->user_url)) : ?>
								<dt><?php esc_html_e('Website', 'understrap'); ?></dt>
								<dd>
									<a href="<?php echo esc_url($curauth->user_url); ?>"><?php echo esc_html($curauth->user_url); ?></a>
								</dd>
							<?php endif; ?>

							<?php if (! empty($curauth->user_description)) : ?>
								<dt><?php esc_html_e('Profile', 'understrap'); ?></dt>
								<dd><?php esc_html_e($curauth->user_description); ?></dd>
							<?php endif; ?>
						</dl>
					<?php endif; ?>


				</header><!-- .page-header -->


					<!-- The Loop -->
					<?php if (have_posts()) : ?>
						<?php while (have_posts()) : the_post(); ?>
							<div class="card">
							<div class="card-body">
			  			    <h4 class="card-title">
								<?php
                                printf(
                                    '<a class="title" rel="bookmark" href="%1$s" title="%2$s %3$s">%3$s</a>',
                                    esc_url(apply_filters('the_permalink', get_permalink($post), $post)),
                                    esc_attr(__('Permanent Link:', 'understrap')),
                                    the_title('', '', false)
                                );
                                ?>
							</h4>
								<?php understrap_posted_on(); ?>
								<div class="card-text entry-summary">
									<?php
                                    $my_excerpt = get_the_excerpt();
                                    print $my_excerpt;
                                    ?>
								</div>
							</div>
							</div>

						<?php endwhile; ?>

					<?php else : ?>

						<?php get_template_part('loop-templates/content', 'none'); ?>

					<?php endif; ?>

					<!-- End Loop -->


			</main><!-- #main -->

			<!-- The pagination component -->
			<?php understrap_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php get_template_part('global-templates/right-sidebar-check'); ?>

		</div> <!-- .row -->

	</div><!-- #content -->

</div><!-- #author-wrapper -->

<?php get_footer(); ?>

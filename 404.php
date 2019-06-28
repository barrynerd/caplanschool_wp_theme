<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="error-404-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check and opens the primary div -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main">

					<section class="error-404 not-found">

						<header class="page-header">

							<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'understrap' ); ?></h1>

						</header><!-- .page-header -->

						<div class="page-content">

							<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'understrap' ); ?></p>

							<?php get_search_form(); ?>


					</section><!-- .error-404 -->

				</main><!-- #main -->

			</div><!-- #primary -->

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>
		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #error-404-wrapper -->

<?php get_footer(); ?>

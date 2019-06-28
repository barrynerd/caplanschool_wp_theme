<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$container = get_theme_mod('understrap_container_type');
?>

<?php get_template_part('sidebar-templates/sidebar', 'footerfull'); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr($container); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">

						<div class="card-collection">

					        <div class="card">
					            <ul class="card-body">
					                <li>3455 State Route 66</li>
					                <li>Neptune, NJ 07753</li>
							<li>&nbsp;</li>
					                <li>(Corner of Green Grove Road in</li>
					                <li>Stout & O'Hagan Building)</li>
                                    <li><a class="font-italic" href="/directions-and-map/">Directions and map</a></li>
					                </ul>
					            </div>
					        <div class="card">

                                <ul class="card-body">
					                <li> Questions?</li>
							<li>&nbsp;</li>
					                <li> CALL 732-918-1300</li>
					                <li> or</li>
					                <li> <a class="font-italic"  href="mailto:info@caplanschool.com">General email</a> &middot <a class="font-italic"  href="mailto:ecaplan@caplanschool.com">email Elliott Caplan</a></li>
					                </ul>
					            </div>
					        </div>

					    <div id="footer-summary">
                            <div class="card">

    					        <ul class="card-body">
    					           <li class="left"><a href="<?php bloginfo('url') ?>/about">About Caplan School Of Real Estate</a></li>
    					           <li class="left"><a href="<?php bloginfo('url') ?>/photo-attribution">Photo Attribution</a></li>
    					           <li class="left">
                                       <a href="/feed/"><i class="fa fa-rss" aria-hidden="true"></i></a>
    					                </li>
    					            </ul>
                                </div>
                            <div class="card">
                                <ul class="card-body">
                                    <li class="foot">Owned and operated by: Caplan School of Real Estate LLC</li>
                                    <li class="left">© <?php echo date("Y") ?>  Caplan School of Real Estate LLC. All Rights Reserved.</li>
                                    </ul>
                                </div>
                            </div>


					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

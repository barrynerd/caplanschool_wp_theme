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

						<div class="card-collection d-sm-flex justify-content-around flex-sm-column flex-md-row">

					        <div class="card">
					            <ul class="card-body">
					                <li>3455 State Route 66</li>
					                <li>Neptune, NJ 07753</li>
							<li>&nbsp;</li>
					                <li>(Corner of Green Grove Road in</li>
					                <li>Stout & O'Hagan Building)</li>
					                </ul>
					            </div>
					        <div class="card">

                                <ul class="card-body">
					                <li> Questions?</li>
							<li>&nbsp;</li>
					                <li> CALL 732-918-1300</li>
					                <li> or</li>
					                <li> <a href="mailto:info@caplanschool.com">email:info@CaplanSchool.com</a></li>
					                </ul>
					            </div>
					        </div>

					    <div id="footer-summary" class="d-flex flex-column">

					        <ul class="d-flex flex-column">
					           <li class="left"><a href="<?php bloginfo('url') ?>/about">About Caplan School Of Real Estate</a></li>
					           <li class="left"><a href="<?php bloginfo('url') ?>/photo-attribution">Photo Attribution</a></li>
					           <li class="left">
                                   <a href="/feed/"><i class="fa fa-rss" aria-hidden="true"></i></a>
					                </li>
					            </ul>


                            <ul class="d-flex flex-column">
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

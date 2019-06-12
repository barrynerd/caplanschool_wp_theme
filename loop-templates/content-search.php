<?php
/**
 * Search results partial template.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php
		the_title(
			sprintf( '<h2 class="entry-title"><span><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
			'</a></span></h2>'
		);
		?>

		<?php if (False): #if ( 'post' == get_post_type() ) : ?>

			<div class="entry-meta">

				<?php understrap_posted_on(); ?>

			</div><!-- .entry-meta -->

		<?php endif; ?>

	</header><!-- .entry-header -->

	<div class="entry-summary">

		<?php
			$my_post_type = get_post_type();
			// print $my_post_type;

			switch ($my_post_type) {
				case "page": {
					if (has_excerpt()) { #has custom excerpt
						the_advanced_excerpt('length=50&length_type=words&no_custom=0&ellipsis=%26hellip;&exclude_tags=br,img,div,h4,p,iframed&read_more=');
						}
					else {
						print "";
					}
					break;
				}
				case "post": {
					the_advanced_excerpt('length=50&length_type=words&no_custom=1&ellipsis=%26hellip;&exclude_tags=br,img,div,h4,p,iframe&finish=word&read_more=');
					break;
				}

				case "product":
				default: {
					the_advanced_excerpt('length=50&length_type=words&no_custom=1&ellipsis=%26hellip;&exclude_tags=br,img,div,h4,p,iframe&finish=word&read_more=');

					// the_advanced_excerpt('length=10&length_type=words&no_custom=0&no_shortcode=0&ellipsis=%26hellip;&add_link=1&exclude_tags=h4,div p');
					// the_excerpt();
				}
			}


			?>

	</div><!-- .entry-summary -->

<?php if (False): ?>
	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->
<?php endif; ?>

</article><!-- #post-## -->

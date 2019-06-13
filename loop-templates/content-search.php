<?php
/**
 * Search results partial template.
 *
 * @package understrap
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php
        the_title(
            sprintf('<h2 class="entry-title"><span><a href="%s" rel="bookmark">', esc_url(get_permalink())),
            '</a></span></h2>'
        );
        ?>

		<?php if (false): #if ( 'post' == get_post_type() ) :?>

			<div class="entry-meta">

				<?php understrap_posted_on(); ?>

			</div><!-- .entry-meta -->

		<?php endif; ?>

	</header><!-- .entry-header -->

	<div class="entry-summary">

		<?php
            $my_post_type = get_post_type();
            if (current_user_can('administrator')) {
                // print $my_post_type;
            }

            switch ($my_post_type) {
                case "page": {
                    the_advanced_excerpt('length=50&length_type=words&no_custom=0&ellipsis=%26hellip;&exclude_tags=ul,li,i,br,img,div,h1,h2,h3,h4,h5,h6,p,iframe,figure,figcaption,strong,em,a&finish=word&read_more=');
                    break;
                }
                case "post": {
                    the_advanced_excerpt('length=50&length_type=words&no_custom=1&ellipsis=%26hellip;&exclude_tags=ul,li,i,br,img,div,h1,h2,h3,h4,h5,h6,p,iframe,figure,figcaption,strong,em,a&finish=word&read_more=');
                    break;
                }

                case "product":
                default: {
                    the_advanced_excerpt('length=50&length_type=words&no_custom=1&ellipsis=%26hellip;&exclude_tags=ul,li,i,br,img,div,h1,h2,h3,h4,h5,h6,p,iframe,figure,figcaption,strong,em,a&finish=word&read_more=');
                }
            }


            ?>

	</div><!-- .entry-summary -->

<?php if (false): ?>
	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->
<?php endif; ?>

</article><!-- #post-## -->

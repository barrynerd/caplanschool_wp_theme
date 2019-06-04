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

		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="card" >
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail('post-thumbnail',
						['class' => 'card-img-top']);
					}
				// echo theme_oembed_videos();
				?>
			  <div class="card-body">
			    <h4 class="card-title">		<?php
						the_title(
							sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
							'</a>'
						);
						?>
				</h4>
				<?php echo theme_oembed_videos(); ?>
			    <p class="card-text entry-summary">
					<?php the_excerpt(); ?>
					</p>
			  </div>

		<?php endif; ?>

</article><!-- #post-## -->

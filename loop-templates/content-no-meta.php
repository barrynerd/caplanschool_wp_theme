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

		<?php if ('post' == get_post_type()) : ?>
			<div class="card" >
				<?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail(
                        'post-thumbnail',
                        ['class' => 'card-img-top']
                    );
                }
                // echo theme_oembed_videos();
                ?>
			  <div class="card-body">
			    <h4 class="card-title">
					<?php
                        the_title(
                            sprintf('<a href="%s" rel="bookmark">', esc_url(get_permalink())),
                            '</a>'
                        );
                        ?>
				</h4>
				<div class="preview-wrapper">
                    <?php
                    $my_embed = theme_oembed_videos();
                    if (isset($my_embed)) :
                    ?>
					<div class="col p-0 m-0">
						<?php
						print $my_embed;
						?>
					</div>
                    <?php endif; ?>
					<div class="col p-0 pt-3 card-text entry-summary">
						<?php
						$my_excerpt = get_the_excerpt();
						print $my_excerpt;
						?>
					</div>
				</div>

			  </div>

		<?php endif; ?>

</article><!-- #post-## -->

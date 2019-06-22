<?php
/**
 * Single post partial template.
 *
 * @package understrap
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>

		<div class="entry-meta">

			<?php understrap_posted_on();?>

		</div><!-- .entry-meta -->

		<?php
        $categories = get_the_terms($id, 'category');
        if (! $categories || is_wp_error($categories)) {
            $categories = array();
        }

        $categories = array_values($categories);
        foreach (array_keys($categories) as $key) {
            $my_category = $categories[$key]->slug;
            switch ($my_category) {
                case "elliotts-industry-news":
				case "ask-elliott": {
                    // print($my_category);
                    print <<< END
					<div class="card col-8 description">
						<div class="card-body>">
END;
						print category_description( get_category_by_slug($my_category)->term_id );
					print <<< END
						</div>
					</div>
END;
                    break;
                    };
            }
        }
        ?>

				<!-- // the_archive_description('<div class="taxonomy-description">', '</div>'); -->
		</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail($post->ID, 'large'); ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
        wp_link_pages(array(
            'before' => '<div class="page-links">' . __('Pages:', 'understrap'),
            'after'  => '</div>',
        ));
        ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php #understrap_entry_footer();?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

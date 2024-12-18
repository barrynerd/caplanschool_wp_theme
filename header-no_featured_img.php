<?php

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$container = get_theme_mod('understrap_container_type');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;0,800;1,400&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class("bcc"); ?>>

	<!-- ******************* The Navbar Area ******************* -->
	<?php
	get_template_part('template-parts/header-menu');

	// only show these widgets to admin
	if (current_user_can('edit_posts')) {
		get_template_part('template-parts/header-menu-admin-only');
	}
	?>

	<div class="hfeed site" id="page">

		<?php
		if (false) {
			if (is_active_sidebar('Announce Sidebar')) { ?>
				<div id="wrapper-announce" class="header-announce">
					<?php dynamic_sidebar('Announce Sidebar'); ?>
				</div>
			<?php
			}
		}

		if (is_front_page()) {
			if (is_active_sidebar('announce-widget-area-front-page-only')) { ?>
				<div id="wrapper-announce" class="header-announce">
					<?php dynamic_sidebar('announce-widget-area-front-page-only'); ?>
				</div>
				<?php
			}

			// only show these front page widgets to admin
			if (current_user_can('edit_posts')) {
				if (is_active_sidebar('announce-widget-area-admin-only')) {
				?>
					<div id="wrapper-announce" class="header-announce admin-only">
						<?php dynamic_sidebar('announce-widget-area-admin-only'); ?>
					</div>
		<?php
				}
			}
		}
		?>
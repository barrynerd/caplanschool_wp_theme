<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$container   = get_theme_mod('understrap_container_type');
if ('container' == $container) : ?>
    <div class="container mx-0">
    <?php endif; ?>
    <!-- ******************* The Navbar Area ******************* -->
    <div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite" class="row mx-0  align-items-center flex-column" style="background-color: #ffee5d;">

        <a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e('Skip to content', 'understrap'); ?></a>

        <div>
            <a class="navbar-brand" href="/">
                <img class="old-logo" src="/wordpress/wp-content/uploads/cropped-roof-banner-02.png" />
            </a>
        </div>

        <div class=" nav-inner-wrapper col-12 col-md-12 px-0">
            <nav id="secondary-menu" class="navbar navbar-expand-md navbar-dark flex-column mr-0">



                <button class="navbar-toggler ml-0" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'understrap'); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- The WordPress Menu goes here -->
                <?php wp_nav_menu(
                    array(
                        'menu'            => "bootstrap-menu02a",
                        'theme_location'  => 'secondary',
                        'container_class' => 'collapse navbar-collapse',
                        'container_id'    => 'navbarNavDropdown',
                        'menu_class'      => 'navbar-nav ml-auto mr-auto align-items-center text-nowrap flex-column flex-md-row',
                        'fallback_cb'     => '',
                        // 'menu_id'         => 'secondary-menu',
                        'depth'           => 2,
                        'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
                    )
                ); ?>

            </nav><!-- .site-navigation -->

            <nav id="main-menu" class="navbar navbar-expand-md navbar-dark flex-column mr-0">
                <?php wp_nav_menu(
                    array(
                        'theme_location'  => 'primary',
                        'container_class' => 'collapse navbar-collapse',
                        'container_id'    => 'navbarNavDropdown',
                        'menu_class'      => 'navbar-nav mx-auto align-items-center text-nowrap',
                        'fallback_cb'     => '',
                        'menu_id'         => 'main-menu',
                        'depth'           => 2,
                        'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
                    )
                ); ?>
            </nav><!-- .site-navigation -->
        </div>
    </div>
    <?php if ('container' == $container) : ?>
    </div><!-- .container -->

<?php endif; ?>
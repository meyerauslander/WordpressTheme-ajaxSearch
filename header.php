<!doctype html>

<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

        <link href="<?php echo get_stylesheet_directory_uri(); ?>/img/fave.png" rel="shortcut icon">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>

		<!-- wrapper -->
		<div id="site_wrapper" class="wrapper">

			<!-- header -->
			<header class="header clear" role="banner">

					<!-- logo -->
					<div class="logo">
						<a href="<?php echo home_url(); ?>">
							<?php mysearch_the_custom_logo(); ?>
						</a>
					</div>
					<a class="alignright" href="<?php echo home_url(); ?>"><span class="go-to-text">go to </span>home</a>
					<!-- /logo -->


                    <!--search functionality-->
                    <?php
                        get_template_part('searchform'); 
                    ?>
			</header>
			<!-- /header -->

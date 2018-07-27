<?php get_header(); ?>
<div id="under_header">
	<main role="main">
		<!-- section -->
		<section>

			<h1><?php _e( 'Categories for ', MAUS_TEXT_DOMAIN ); single_cat_title(); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</section>
		<!-- /section -->
	</main>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
</div> <!--end of #under_header-->
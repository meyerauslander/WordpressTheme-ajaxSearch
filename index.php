<?php get_header(); ?>

<div id="under_header">
    <div id="content" class="clear"> <!--contains all the content but not the footer-->
        <main role="main" id="primary"> <!--contains the content except the sidebar-->
            <!-- section -->
            <section> <!--so far this is the only section in main-->
                <h3> 
                    <?php _e('<a href="https://support.stripe.com/">example site link(stripe.com)</a><br>',MAUS_TEXT_DOMAIN) ?> 
                    <?php _e('<a href="https://madisondocs.com/dev/hudson/wp-login.php">Dev site link</a>',MAUS_TEXT_DOMAIN) ?> 
                </h3>

                <h2>
                  <?php _e('Article Categories',MAUS_TEXT_DOMAIN); ?>

                </h2>
                <div name="catagories">
                    <?php 
                        $categories = get_categories();  
                        foreach($categories as $category){
                            if (function_exists('category_image_src')) {
                                if ($category_image = category_image_src( array( 'size' => 'full' , 'term_id' => $category->term_id) , false )){
                                    //override the ":before backround" style of this link with the specific image for this category
                                    ?>
                                    <style>
                                        .maus-category-grid a.img-cat-<?php echo $category->term_id ?>:before{
                                            background: url(<?php echo $category_image ?>) no-repeat;
                                        }
                                    </style>
                                    <?php
                                }
                            } else {
                                echo $category_image = '';
                            }
                            if ($category->name == reset($categories)->name) echo "<ul class=maus-category-grid>";
                            ?> 
                            <li class='maus-general'>
                                <?php 
                                echo sprintf( '<a class=img-cat-%1$s href="%2$s" alt="%3$s">%4$s</a>',  /*see https://developer.wordpress.org/reference/functions/get_categories/ */
                                                            $category->term_id,
                                                            esc_url( get_category_link( $category->term_id ) ),
                                                            esc_attr( sprintf( __( 'View all posts in %s', MAUS_TEXT_DOMAIN ), $category->name ) ),
                                                            esc_html( $category->name ));
                                ?>
                            </li>
                            <?php
                            if ($category->name == end($categories)->name) echo "</ul>";
                        }
                    ?>
                </div>
    <!--			<h1><? //php _e( 'Latest Posts', MAUS_TEXT_DOMAIN ); ?></h1>-->

                <?php //get_template_part('loop'); ?>

                <?php //get_template_part('pagination'); ?>

            </section>
            <!-- /section end if first section in main-->
        </main> <!--End of #primary-->

        <?php //get_sidebar(); ?>
    </div> <!--end of #content-->
    <?php get_footer(); ?>
</div> <!--end of #under_header-->

<?php get_header(); ?>
    
    <div id="content" class="clear">
        <main role="main" id="primary">
            <section>
                <?php if (have_posts()): while (have_posts()) : the_post(); 
                    $toc_post = tstn_toc_widget::is_toc_post();
                    if ($toc_post){  //output style to activate the side bar 
                            echo "<style>   
                                    #primary { /* The primary section of the page */
                                        width: 70%;
                                        float: left;
                                    }
                                    .sidebar {
                                        float: right;
                                        width: 25%;
                                    }
                                  </style>
                            ";
                        }
                ?>
                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <!-- post thumbnail -->
                        <?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php the_post_thumbnail(); // Fullsize image for the single post ?>
                            </a>
                        <?php endif; ?>
                        <!-- /post thumbnail -->

                        <!-- post title -->
                        <h1>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a><br>
                        </h1>
                        <section class="clear"> <!--author/update section-->
                            <span class="updated alignleft"><?php _e ("Last updated by " . get_the_modified_author() . " " . get_the_modified_time('F j, Y') . 
                                      " at " . get_the_modified_time('g:i a'), MAUS_TEXT_DOMAIN); ?></span>
                            <!-- /post title -->

                            <!-- post details -->
                            <!--<span class="date"><?php //the_time('F j, Y'); ?> <?php //the_time('g:i a'); ?></span>-->
                            <span class="author alignright"><?php _e( 'Written by', MAUS_TEXT_DOMAIN ); ?> <?php the_author_posts_link(); ?></span>
                        </section>
                        <!--<span class="comments"><?php //if (comments_open( get_the_ID() ) ) comments_popup_link( __( 'Leave your thoughts', MAUS_TEXT_DOMAIN ), __( '1 Comment', MAUS_TEXT_DOMAIN ), __( '% Comments', MAUS_TEXT_DOMAIN )); ?></span>-->
                        <!-- /post details -->

                        <?php the_content(); // Dynamic Content ?>

                        <?php the_tags( __( 'Tags: ', MAUS_TEXT_DOMAIN ), ', ', '<br>'); // Separated by commas with a line break at the end ?>

                        <p><?php _e( 'Categorised in: ', MAUS_TEXT_DOMAIN ); the_category(', '); // Separated by commas ?></p>

                        <p><?php _e( 'This post was written by ', MAUS_TEXT_DOMAIN ); the_author(); ?></p>

                        <?php edit_post_link();  // Always handy to have Edit Post Links available ?>

                        <?php comments_template(); ?>

                    </article>
                <?php endwhile; //end of "the loop" ?>

                <?php else: ?>
                    <article>
                        <h1><?php _e( 'Sorry, nothing to display.', MAUS_TEXT_DOMAIN ); ?></h1>
                    </article>
                <?php endif; //end of has_posts if ?>
            </section>
        </main>

        <?php if ($toc_post) get_sidebar(); ?>
    </div> <!--end of #content-->
<?php get_footer(); ?>

<?php if (have_posts()) { 
    while (have_posts()) { 
    the_post(); ?>
	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php // custom search result output
        if (isset($_GET['s'])) {  //highlight the seach text in the excerpt 
            ?>
            <div class="search-result-link">
                <a class="search-result-link"  href="<?php the_permalink(); ?>"> <!--make the entire result a link-->
                    <div class="search-title clear">
                        <h2>
                            <?php
                                $multiple_word = false;  //default of single word search phrase
                                $search_expressions = array();
                                $searchinfo = new maus_search_helper();
                                $search_phrase = $_GET['s'];
                                maus_search_helper::escape_url_phrase($search_phrase);      
                                $search_expressions = $searchinfo->get_search_expressions($search_phrase); //get all required regex search expressions 
                                $title_text = the_title('','',false); 
                               // echo $title_text . "<br>";
                                $title_text = html_entity_decode($title_text);
                                //highlight the searched for text in the title
                                $title_text = preg_replace(end($search_expressions), '<span class="text-highlight">$1</span>', $title_text);
                                echo "<p class='alignleft'>$title_text</p>"; 
                            ?>
                        </h2>
                        <?php
                            $categories = get_the_category();
                            if (count($categories) == 1){
                                echo  "<p style='font-size: 12px;'class='alignright'> Category: " . $categories[0]->name . " ";
                            } else{ //this post is part of several categories

                            }
                            _e ("Last updated by " . get_the_modified_author() . " " . 
                            get_the_modified_time('F j, Y') . " at " . get_the_modified_time('g:i a') . "</p>", MAUS_TEXT_DOMAIN);
                        ?>
                    </div> <!--end of title div-->
                    <!--ouput the search excerpt -->
                    <div class='search-entry-content'>
                    <?php
                        $multiple_word = false;  //default of single word search phrase      
                        if (count($search_expressions) > 1) $multiple_word = true;
                        $content = wp_strip_all_tags( get_the_content() );
                        $content = html_entity_decode($content);
            
                        // find all the matched in the content
                        preg_match_all("/($search_phrase)/i", $content, $matches1, PREG_OFFSET_CAPTURE); //best matches
                        if ($multiple_word) {
                            preg_match_all($search_expressions[0], $content, $matches2, PREG_OFFSET_CAPTURE);    //next best matches
                        } else $matches2 = null;

                        //prepare to make the excerts
                        $phrase_matches = new maus_excerpt_collection( "/($search_phrase)/i", $content, $matches1 );
                        if (  $phrase_matches->length < MAUS_MAX_LENGTH and $multiple_word){
                            //if there is room for more matches and there are more matches to find...
                            $all_matches = array();
                            $word_matches = new maus_excerpt_collection( $search_expressions[0] , $content, $matches2 );
                            $all_matches = $phrase_matches->merge($word_matches);
                            $all_matches->output($search_expressions[1]);
                        } else { //there are no word matches for whatever reason
                            $phrase_matches->output();
                        }      
                    ?>
                    </div> <!--end of the entry div-->
                </a><!--make this entire search entry a link -->
            </div> <!--end of search result div-->
            <?php
        } 
        else{ //this is not a search result must be a category result
            //default post display
            ?>
            <!-- post title -->
            <h2>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?> </a>
            </h2>
            <?php 
        }   ?>
	</article>
	<!-- /article -->
<?php
    } //end of "the loop"
} //end of "have posts" if 
 else { //when there are no posts
?>
	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', MAUS_TEXT_DOMAIN ); ?></h2>
	</article>
	<!-- /article -->
<?php
} 
?>

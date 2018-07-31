<?php

//static class containing function that aid in producing the custom search output
class maus_search_helper{
    
    //used to prepare the search term (obtained by the _GET array) for pregmatch()
    public static function escape_url_phrase(&$search_phrase){
        $search_phrase = urldecode($search_phrase);  //convert url encodings to their normal character vaules
        //$search_phrase = htmlentities($search_phrase); // for example for & character
        $search_phrase = preg_quote($search_phrase, '/'); //escape it for use in preg_match   
    }
    
    /*
    Return an array of one or two search exressions based on a search phrsae
    This functions assumes it's already been url decoded and html-entity replaced
    and escaped from regex special characters using '\' (see escape_url_phrase())
    */
    public static function get_search_expressions($search_phrase){
        $multiple_word = false;
        $return_expressions = array();
        if (preg_match("/\s/", $search_phrase)){
            $multiple_word = true;
            $search_phrase_array = explode(" ", $search_phrase);
            $search_expression = "/(";
            $full_search_expression = "/($search_phrase|"; // needed when highlighting all matched at once
            foreach ($search_phrase_array as $search_word){ 
                if ( !preg_match("/or/i", $search_word) && !preg_match("/and/i", $search_word) && 
                     !preg_match("/;/i", $search_word) ) { //exclude non-key words and trouble making characters
                    $search_expression .= $search_word . "|";
                    $full_search_expression .= $search_word . "|";
                } 
            }
            $search_expression[strlen($search_expression)-1] = ")";  //change last | to )
            $full_search_expression[strlen($full_search_expression) - 1] = ")";

            $search_expression .= "/i"; //conclude and make it case insensitive  
            $full_search_expression .= "/i"; 
            array_push( $return_expressions, $search_expression );  //add this expression to the array
            array_push( $return_expressions, $full_search_expression ); 
        } else{ //it's not multiple word.  only one expression is needed
            array_push( $return_expressions, "/($search_phrase)/i" ); //add this expression to the array
        }
        return $return_expressions;
    } //end of get_search_expressions()
} //end of class maus_search_helper

/*
    Display/Remove automatic search results depending on the search input

*/
(function($) { jQuery(function(){ //container function 
        //if the user clicks the x to clear the content of the search box then hide the search div box
        $("[name='s']").on("input", function() { 
            var search_text = $("[name='s']").val();
            search_text = search_text.trim();
            //revert back to this page's regular content when the search string is empty
            if ( search_text == '' ) { 
                $('#maus_search_div').css('visibility','hidden');
                $('#under_header').css('visibility','visible');
            }
        }); //end of on-input function
    
        //make a request to search at every key up event
        $("[name='s']").keyup(function(event){ 
            var search_text = $("[name='s']").val();
            search_text = search_text.trim();
            if (search_text != '') {
                //prepare the search text to go into the url
                search_text = encodeURIComponent(search_text);
            }
            var search_url  = $("[name='home_url']").val() + "/?s=" + search_text;
            //if the search exists then display new search results
            if ( search_text != '') {  
                $('#maus_search_container').load(search_url + ' #main');
                $('#under_header').css('visibility','hidden');
                $('#maus_search_div').css('visibility','visible');
            }
            else{ //revert back to this page's regular content when the search string is empty
                if ( search_text == '' ) { 
                    $('#maus_search_div').css('visibility','hidden');
                    $('#under_header').css('visibility','visible');
                }
            }
        }); //end of keyup function
    });  //end of container function  
}) (jQuery);
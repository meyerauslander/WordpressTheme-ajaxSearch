<?php

class maus_excerpt {
    public $start, $end, $length; 

    public function __construct($start, $end) {
        $this->start=$start;
        $this->end=$end;
        $this->length = $end-$start+1;
    }  
}

class maus_excerpt_collection{
    public $search_expression, $excerpts, $length, $output, $content_end_position, $content, $count; //excerpts is an array of maus_excerpts 
                                                                                    //content is the source from which excerpts are generated
    
    public function __construct($search_expression,$content=null,&$matches=null){  //$matches is the result array from preg_match_all 
                                                         //$content is what was searched for matches 
        //set user specific attributes
        $this->search_expression = $search_expression;
        $this->content = $content;
        $this->excerpts=array();
        $length=0;
        $count=0;
        $output='';
        $this->content_end_position=strlen($content) - 1;
        if ($matches && $content) $this->make($matches,$content);
    }  
   
    //add and excerpt to the collection
    //resolve_overlaps is only helpful when excerpts are being added in order of their occurance in the content
    //length_offset is to reduce the max length requirement when needed. 
    public function add($add_this_excerpt,$resolve_overlaps=true,$length_offset=0){
        //check if this one overlaps the prevous one, if so than expand the prevous instead of adding this
        $overlap=false; 
        $previous_end=0;
        if ($this->count && $resolve_overlaps){
            end($this->excerpts);  
            $previous_excerpt_key=key($this->excerpts);
            $previous_end = $this->excerpts[$previous_excerpt_key]->end;
        }
        if (($previous_end >= $add_this_excerpt->start) && $this->count && $resolve_overlaps){
            //this is an overlapping excerpt
            $additional_length = $add_this_excerpt->end - $previous_end;
            $new_total_length = $additional_length + $this->length; /*calculate the total length of all excerpts including this one*/
            $overlap=true;
        } else {
            $new_total_length = MAUS_BEFORE_PHRASE + MAUS_AFTER_PHRASE + $this->length; //calculate the total length of all excerpts including this one
                                //not using excerpt length so as not to exclude an expanded excerpt that will go over the limit by only a small amount
        }

        //update the excerpts array to include this excerpt
        if ($new_total_length <= MAUS_MAX_LENGTH - $length_offset) { 
            if (!$overlap){
                array_push($this->excerpts,$add_this_excerpt); //add this excerpt to the array   
                $this->length += $add_this_excerpt->length;
                $this->count++;
            }
            else{
                $this->excerpts[$previous_excerpt_key]->end = $add_this_excerpt->end;
                $this->excerpts[$previous_excerpt_key]->length += $additional_length;
                $this->length += $additional_length;
            }
            return true;
        } else { //stop adding instances if the length limit is exceeded
            return false;
        }
    }  //end of add function
    
    /* merge two excerpt collections into one*/
    public function merge(&$other_matches){ //other_matches is a  maus_excerpt_collection
        $all_matches = new maus_excerpt_collection($this->search_expression,$this->content);
        //resolve overlap conflicts
        foreach($this->excerpts as $main_excerpt){
            $other_count=0;
            foreach($other_matches->excerpts as $key => $other_excerpt){
                //There are 4 theoretical cases of overlap
                //1.  not sure if this ever happens...
                //RRRRRRRRRRRRRRRRRRRRRRRRRRRRR  phrase 
                //	RRRRRRRRRRRR 		word
                //
                //w.start >= p.start && w.end <= p.end :: then delete(w) 
                if ($other_excerpt->start >= $main_excerpt->start &&
                    $other_excerpt->end <= $main_excerpt->end) {
                    //the main excerpt contains the word excerpt
                    //delete the other_execerpt (now it's content is included in the main_excerpt)
                        unset ($other_matches->excerpts[$key]);
                }      
                //                2.
                //   RRRRRRRRRRRPPPPPPPPRR	  phrase
                //      	  RRRRRRRRRRRRRRRRR  word
                //w.start >= p.start && w.start <= p.end :: then p.end=w.end delete(w) 
                else if ($other_excerpt->start >= $main_excerpt->start &&
                    $other_excerpt->start <= $main_excerpt->end) {
                    //extend main content to include other excerpt's end 
                        $this->length += $other_excerpt->end - $main_excerpt->end;
                        $main_excerpt->end = $other_excerpt->end; 
                    //delete the other_execerpt (now it's content is included in the main_excerpt)
                        unset ($other_matches->excerpts[$key]);
                }
                //3.	
                //	RRRRRRRRRRRRRRRRRR phrase
                //RRRRRRRRRRRR		   word
                //
                //w.end >= p.start && w.start <=p.start :: then p.start=w.start detete(w)
                else if ($other_excerpt->end >= $main_excerpt->start &&
                    $other_excerpt->end <= $main_excerpt->end) {
                    //extend main content to include other excerpt's beginning 
                        $this->length += $main_excerpt->start - $other_excerpt->start;
                        $main_excerpt->start = $other_excerpt->start; 
                    //delete the other_execerpt (now it's content is included in the main_excerpt)
                    unset ($other_matches->excerpts[$key]);
                }
                
                //4.  this may also never happen...
                //	     RRRRRRRRRRRRRRR    phrase
                //     RRRRRRRRRRRRRRRRRRRR  word
                //w.start <= p.start && w.end >= p.end  :: p.start=w.start  p.end=w.end delete(w)
                else if ($other_excerpt->start <= $main_excerpt->start &&
                    $other_excerpt->end >= $main_excerpt->end) {
                    //extend main content to include other excerpt's beginning and end 
                        $this->length += $other_excerpt->end - $main_excerpt->end +
                                        $main_excerpt->start - $other_excerpt->start;
                        $main_excerpt->end = $other_excerpt->end;
                        $main_excerpt->start = $other_excerpt->start;
                    //delete the other_execerpt (now it's content is included in the main_excerpt)
                    unset ($other_matches->excerpts[$key]);
                }
                $other_count++;
            } //end of other_matches for loop
        }  //end of main matches for loop
        
        //now that all overlaps have been deleted, combine the two sets of matches, sort, and return the result 
        $all_matches = clone $this;
        foreach($other_matches->excerpts as $excerpt){
            if(!$all_matches->add($excerpt,false))
                break; //collection is full
        }
        
        //code to sort the result before returning 
        usort($all_matches->excerpts, array($this, 'compare_excerpts')); 
        
        return $all_matches;
    } //end of the merge function

    public function highlight($expression=null){
        if (!$expression) //the highlighting is not done with a specified expressions
            $expression = $this->search_expression;
        $this->output = preg_replace($expression, '<span class="text-highlight">$1</span>', $this->output);
    }
    
    //output this collection of excerpts with "..." seperation between excerpts and highlighting
    public function output($highlight_expression=null){
        $count = count($this->excerpts);
        $i=$count-1;
        foreach($this->excerpts as $excerpt){
            if ($excerpt->start != 0) 
                $this->output .= "...";
            $start = $excerpt->start;
            $end =   $excerpt->end;
            $excerpt_text = substr($this->content, $start, $end-$start+1); 
            $this->output .= $excerpt_text;        
            if ($i==0 && $end != $this->content_end_position) //if this the last and it ends before the end of the article
                $this->output .= "...";
            $i--;
        }
        
        if ($count != 0){ 
            //highlight it and output it
            $this->highlight($highlight_expression);
            echo $this->output;
        } else { //this post contains no exact matches of any of the terms! 
            //output nothing
        }
    }
    
    //create collection of excerpts from content and matches (pass by reference for efficiency)
    public function make(&$matches){
        if ($matches == null) return;
        
        //convert match positions to a one-dimensional array
        $positions = array();  
    
        foreach ($matches[0] as $the_match){
            array_push($positions,$the_match[1]);
        }
           
        $count = count($positions); //total count of instaces of the search phrase
        if ($count) {
            //calculate the start position and end position for each exerpt based on the search match position
            for ($i=0;$i<$count;$i++){   
                //can't start before the beginning!
                $start_position = ($positions[$i] - MAUS_BEFORE_PHRASE < 0) ? 0 : $positions[$i] - MAUS_BEFORE_PHRASE;
                
                //can't start in the middle of a word
                while(!(ctype_space($this->content[$start_position]))){
                    if ($start_position != 0){
                        $start_position--;
                    }
                    else break;
                }

                //can't end after the end
                $end_position = ($positions[$i] + MAUS_AFTER_PHRASE < $this->content_end_position) ? 
                                                    $positions[$i] + MAUS_AFTER_PHRASE : $this->content_end_position;

                //can't end in the middle of a word
                while (!(ctype_space($this->content[$end_position]))){
                    if ($end_position != $this->content_end_position){
                        $end_position++;
                    }
                    else break;
                }
                
                //now add this (or these) instance(s) to the excerpts but return if it cannot be added due to exceeded length limit
                $excerpt=new maus_excerpt($start_position,$end_position);
                if (!$this->add($excerpt)){
                    return;  //no room for any more excerpts!  
                }              
            } //end of search-phrase-instance "for" loop
        }  // end of 'count' if-statment
        return;
    } //end of make() 
    
    //needed to sort an array of excerpts with usort()
    public function compare_excerpts($a, $b){
        return $a->start - $b->start;
    }
} //end of maus_excerpt_collection class

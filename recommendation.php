<?php
/*
    This class is to provide recommandation using pearson corealtion 
    The data format 
    $data = array(
        "$person1" = array("Key1"=>Rating1, "Key2"=>Rating2, .........)
        "$person2" = array("Key1"=>Rating1, "Key2"=>Rating2, .........)
        "$person3" = array("Key1"=>Rating1, "Key2"=>Rating2, .........)
        .
        .
        .
        .
    )
*/

class Recommendation{

    /***********Pearson Correlation Similarity Score*****************************/
     public function similarityDistance($preferences, $person1, $person2){
        $similar = array();
        $sum = 0;
    
        foreach($preferences[$person1] as $key=>$value){
            if(array_key_exists($key, $preferences[$person2]))
                $similar[$key] = 1;
        }
        
        if(count($similar) == 0){
            return 0;
        }
        
        foreach($preferences[$person1] as $key=>$value){
            if(array_key_exists($key, $preferences[$person2]))
                $sum = $sum + pow($value - $preferences[$person2][$key], 2);
        }
        
        return  1/(1 + sqrt($sum));     
    }

    /***********Ranking the matches****************************************************/
      public function topMatches($preferences, $person){
        $score = array();
            foreach($preferences as $otherPerson=>$values){
                if($otherPerson !== $person){
                    $sim = $this->similarityDistance($preferences, $person, $otherPerson);
                    if($sim > 0){
                        $score[$otherPerson] = $sim;
                    }
                }
            }
        array_multisort($score, SORT_DESC);
        return $score;
    }


    
    public function transformPreferences($preferences){
        $result = array();
        foreach($preferences as $otherPerson => $values){
            foreach($values as $key => $value){
                $result[$key][$otherPerson] = $value;
            }
        }
        return $result;
    }
    /*******Faults
     *It can turn up reviewers who haven't reviewed the movies that i have liked
     *It also turn up reviewer who strangely liked a movie that got bad reviews.
     *
     *SOLUTION
     *There is need of a weighted score that ranks the critics
     *We goon'a take the votes of all others and multiply how similar they are to me by the score they gave each movie
     *eg
     *critic      similarity    night   sx.night    superman    sx.superman
     *A               .99        3.0       2.97       2.5           2.48
     *B               .89        4.5       4.02        -              -
     *C               .92        3.0       2.77       3.0           2.77
     *D               .66        3.0       1.99       3.0           1.99
     * ----------------------------------------------------------------------
     * Total rating                        11.75                    7.24
     *Similarity sum                        3.46                    2.57
     * Total/sim. sum                       3.39                    2.81
    *************************/

    /********Get Recommendations************************************************/
        public function getRecommendations($preferences, $person){
        $total = array();
        $simSums = array();
        $ranks = array();
        $sim = 0;
        
        foreach($preferences as $otherPerson=>$values){
            if($otherPerson != $person){
                $sim = $this->similarityDistance($preferences, $person, $otherPerson);
            }
            
            if($sim > 0){
                foreach($preferences[$otherPerson] as $key=>$value){
                    if(!array_key_exists($key, $preferences[$person])){
                        if(!array_key_exists($key, $total)) {
                            $total[$key] = 0;
                        }
                        $total[$key] += $preferences[$otherPerson][$key] * $sim;
                        if(!array_key_exists($key, $simSums)){
                            $simSums[$key] = 0;
                        }
                        $simSums[$key] += $sim;
                    }
                }    
            }
        }
        foreach($total as $key=>$value){
            $ranks[$key] = $value / $simSums[$key];
        } 
    array_multisort($ranks, SORT_DESC);    
    return $ranks;
    }
   
}

?>
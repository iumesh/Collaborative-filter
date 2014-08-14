<?php
/**
 * Created by JetBrains PhpStorm.
 * User: umesh
 * Date: 11/22/13
 * Time: 8:34 AM
 * To change this template use File | Settings | File Templates.
 */
class Filtering{

    /***************Jaccard coefficient***********************************/
    public function similarityJaccard($person1,$person2){
        $sim = array();
        foreach($person1 as $key=>$value){
            if(array_key_exists($key,$person2)){
                $sim[$key]=1;
            }
        }
        $num=count($sim);
        $den=count($person1);
        $result = $num/$den;
        return $result;
    }

    /***********Pearson Correlation Similarity Score*****************************/
    public function similarityPearson($person1,$person2){
        $sim = array();
        /*********Finding common preferences**********************************/
        foreach($person1 as $key=>$value){
            if(array_key_exists($key,$person2)){
                $sim[$key]=1;
            }
        }
        $counts=count($sim);
        if($counts==0) {
            return 0;
        }
        else{
            $sum1=0;
            $sum2=0;
            $sumSquare1=0;
            $sumSquare2=0;
            $sumProduct=0;
            foreach($sim as $k=>$v){

                /***********Adding up all preferences*************************/
                $sum1 += $person1[$k];
                $sum2 += $person2[$k];
                /************Sum up the squares*****************************/
                $sumSquare1 += ($person1[$k]*$person1[$k]);
                $sumSquare2 += ($person2[$k]*$person2[$k]);
                /***********Sum up the products ****************************/
                $sumProduct += (($person1[$k])*($person2[$k]));
            }
            /**********Pearson Score ************************************/
            $num=$sumProduct-($sum1*$sum2/$counts);
            $den=sqrt(($sumSquare1-(($sum1*$sum1)/$counts))*($sumSquare2-(($sum2*$sum2)/$counts)));
            if($den==0){
                return 0;
            }
            else{
                $r=$num/$den;
                return $r;
            }
        }
    }

    /***********Ranking the critics****************************************************/
    public function topMatches($person1,$list){
        $scores=array();
        foreach($list as $value){
            $similarity=$this->similarityPearson($person1,$value);
            $scores[$value]=$similarity;
        }
        $scores=sort($scores);
        return $scores;
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
    public function getRecommendations($person1,$userLists,$similarityList){
        $simSum=array();
        $total=array();
        foreach($userLists as $otherPerson=>$movieList){
            if($otherPerson==$person1){
                continue;
            }
            if($similarityList[$otherPerson]<=0){
                continue;
            }
            foreach($movieList as $someMovie=>$someRating){
                if(isset($person1[$someMovie])){
                    continue;
                }
                else{
                    if(!isset($total[$someMovie])){
                        $total[$someMovie]=0;
                    }
                    $total[$someMovie]+=$someRating*$similarityList[$otherPerson];
                    if(!isset($simSum[$someMovie])){
                        $simSum[$someMovie]=0;
                    }
                    $simSum[$someMovie]+=$similarityList[$otherPerson];
                }
            }//for each movie
        }//for each person
        //generate normalized list
        foreach($total as $anyMovie=>$anyValue){
            $anyValue=$anyValue/$simSum[$anyMovie];
        }
        arsort($total);
        return $total;
    }
}

?>
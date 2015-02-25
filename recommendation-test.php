<?php
require_once("recommendation.php");
require_once("filtering-data.php");
require_once("test-data.php");


$rec = new Recommendation();
echo '<H1>Recomendations :</H1> Recomendations Books :<pre>'; 
print_r($rec->getRecommendations($books, "jill")); 
echo '</pre>';

echo 'Top matches books : <pre>';
print_r($rec->topMatches($books, "jill")); 
echo '</pre>';

echo 'Recomendations movies: <pre>';
print_r($rec->getRecommendations($movies, "Person G")); 
echo '</pre>';

echo 'Top matches movies: <pre>';
print_r($rec->topMatches($movies, "Person G")); 
echo '</pre>';






echo '<H1>Filter your data Examples :</H1><br/><br/>';

$maxKey=6;				//default value for max allowed keys
$minKey=3;				//deafult value for min allowed keys
$minRating=1.1;			//default min rating out og 5
$maxRating=4.8;			//default max rating out of 5

/*You can use any constructor as per you need*/
$fil = new Filter($books);	//constructor with only preferences
$fil1 = new Filter($minKey, $maxKey, $books); 
$fil2 = new Filter($minKey, $maxKey, $books, $minRating, $maxRating);


echo "Before filtering <br/><pre>";
print_r($fil->getPreferences()); 
echo "</br>";

echo 'Sample Remove person with less than data: <pre>'; 
print_r($fil->filterPreferences()); 
echo '</pre>';



?>
<?php

/*
	This class is to filter data to improve the quality of recommandation
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

	class Filter{

		private $maxKey=20;				//default value for max allowed keys
		private $minKey=5;				//deafult value for min allowed keys
		private $preferences = array();
		private $minRating=.8;			//default min rating out og 5
		private $maxRating=4.8;			//default max rating out of 5

		public function __construct(){
			$a = func_get_args(); 
	        $i = func_num_args(); 
	        if (method_exists($this,$f='__construct'.$i)) { 
	            call_user_func_array(array($this,$f),$a); 
	        } 
		}

		public function __construct1($preferences){
			$this->preferences = $preferences;
		}

		public function __construct3($minKey, $maxKey, $preferences){
			$this->maxKey = $maxKey;
			$this->minKey = $minKey;
			$this->preferences = $preferences;
		}

		public function __construct5($minKey, $maxKey, $preferences, $minRating, $maxRating){
			$this->maxKey = $maxKey;
			$this->minKey = $minKey;
			$this->preferences = $preferences;
			$this->minRating = $minRating;
			$this->maxRating = $maxRating;
		}

		public function setMaxKey($key){
			$this->maxKey = $key;
		}

		public function setMinKey($key){
			$this->minKey = $key;
		}

		public function setPreferences($pref){
			$this->preferences = $pref;
		}

		public function setMaxRating($rate){
			$this->maxRating = $rate;
		}

		public function setMinRating($rate){
			$this->minRating = $rate;
		}

		public function getMaxKey(){
			return $this->maxKey;
		}

		public function getMinKey(){
			return $this->minKey;
		}

		public function getPreferences(){
			return $this->preferences;
		}

		public function getMaxRating(){
			return $this->maxRating;
		}

		public function getMinRating(){
			return $this->minRating;
		}

		/* Remove the person with less data */
		public function  removePersonWithLessData(){
			$preferences = $this->preferences;
			foreach($preferences as $person => $data){
				if(count($preferences[$person])<$this->minKey){
					unset($preferences[$person]);
				}
	        }
	       return $preferences;
		}


		/* Remove the person with high data */
		public function  removePersonWithMoreData(){
			$preferences = $this->preferences;
			foreach($preferences as $person => $data){
				if(count($preferences[$person]) > $this->maxKey){
					unset($preferences[$person]);
				}
	        }
	       return $preferences;
		}


		/*Extratct the person with adequate data i.e combination of above 2 methods*/
		public function filterPersons(){
			$preferences = $this->preferences;
			foreach($preferences as $person => $data){
				if(count($preferences[$person]) < $this->minKey || count($preferences[$person]) > $this->maxKey){
					unset($preferences[$person]);
				}
	        }
	       return $preferences;
		}



		/*Reove Keys with low or null ratings*/
		public function removeKeyWithLessRating(){
			$preferences = $this->preferences;
			foreach($preferences as $person => $data){
	            foreach($data as $key => $value){
	                if($data[$key] < $this->minRating){
	                	unset($data[$key]);
	                }
	            }
	        }
	        return $preferences;
		}

		/*Remove Keys with high or full ratings*/
		public function removeKeyWithMoreRating(){
			$preferences = $this->preferences;
			foreach($preferences as $person => $data){
	            foreach($data as $key => $value){
	                if($data[$key] > $this->maxRating){
	                	unset($preferences[$person][$key]);
	                }
	            }
	        }
	        return $preferences;
		}		

		/* Remove keys that are not fit i.e combination of upperr two*/
		public function filterKeys(){
			$preferences = $this->preferences;
			foreach($preferences as $person => $data){
	            foreach($data as $key => $value){
	                if($data[$key] < $this->minRating || $data[$key] > $this->maxRating){
	                	unset($preferences[$person][$key]);
	                }
	            }
	        }
	        return $preferences;
		}

		/* filter data according to set prefefrences*/

		public function filterPreferences(){
			$preferences = $this->preferences;
			
			foreach($preferences as $person => $data){
				
				if(count($preferences[$person]) < $this->minKey || count($preferences[$person]) > $this->maxKey){
					unset($preferences[$person]);
				}
				else{
					foreach($data as $key => $value){
		               if($data[$key] > $this->maxRating || $data[$key] < $this->minRating){
		                	unset($preferences[$person][$key]);
		                }
		            }
		           
		            if(count($preferences[$person]) < $this->minKey){
		            	unset($preferences[$person]);
		            }
				}
	        }

			return $preferences;
		}




	}

?>
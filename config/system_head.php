<?php

	error_reporting(E_ALL ^ E_NOTICE);
	session_start();

	$server_url = "http://bin:8888"; 

	date_default_timezone_set('Europe/Riga');
	$date = new DateTime();

	$akcijas_sakums = date_create('2013-10-01');
	$akcijas_nedelja = 38;


	$this_WEEK = date("W");
	$last_WEEK = $this_WEEK-1;



	function getStartAndEndDate($week, $year, $format) {

	    // $time = strtotime("1 January $year", time());
	    // $day = date('w', $time);
	    // $time += ((7*$week)+1-$day)*24*3600;
	    // $return[0] = date('Y-m-d', $time);
	    // $time += 6*24*3600;
	    // $return[1] = date('Y-m-d', $time);

		$date1 = date( $format, strtotime($year."W".$week."1") ); // First day of week
		$date2 = date( $format, strtotime($year."W".$week."7") ); // Last day of week


	    return array('first' => $date1, 'last'=>$date2);
	}






	function getRelativePathTo($targetPath) {
		$thisURL = $_SERVER['SCRIPT_NAME'];
		$a = getPathArray($thisURL);
		$b = getPathArray($targetPath);
		
		$up = 0;
		for($i = 0; $i < count($a) - 2; $i++) {
			$up++;
		}
		$endStr = '';
		for($i=0; $i<$up; $i++) {
			$endStr .= '../';
		}
		for($i = 0; $i < count($b); $i++) {
			if($i == 0) {
				$endStr .= '';
				continue;
			}
			if($i != count($b) - 1) {
				$endStr .= $b[$i].'/';
			} else {
				$endStr .= $b[$i];
			}
		}
		return $endStr;
	}

//var_dump("http://$_SERVER[HTTP_HOST]");

	function getPathArray($relativePath) {
		$pathArr = array();
		$exp = explode("/", $relativePath);
		for($i = 0; $i < count($exp); $i++) {
			$pathArr[$i] = $exp[$i];
		}
		return $pathArr;
	}
	
	
	function getURLParamsAsString() {
		$ret = '?';
		$i = 0;
		foreach($_GET as $k=>$v) {
			if($i != 0) {
				$ret .= '&';
			}
			$ret .= $k.'='.$v;
			$i++;
		}
		return $ret;
	 }
	

	function sortmulti ($array, $index, $order, $natsort=FALSE, $case_sensitive=FALSE) {
	        if(is_array($array) && count($array)>0) {
	            foreach(array_keys($array) as $key)
	            $temp[$key]=$array[$key][$index];
	            if(!$natsort) {
	                if ($order=='asc')
	                    asort($temp);
	                else   
	                    arsort($temp);
	            }
	            else
	            {
	                if ($case_sensitive===true)
	                    natsort($temp);
	                else
	                    natcasesort($temp);
	            if($order!='asc')
	                $temp=array_reverse($temp,TRUE);
	            }
	            foreach(array_keys($temp) as $key)
	                if (is_numeric($key))
	                    $sorted[]=$array[$key];
	                else   
	                    $sorted[$key]=$array[$key];
	            return $sorted;
	        }
	    return $sorted;
	}
	

	require_once(__DIR__."/../config/dbconfig.php");
	require_once(__DIR__."/../Application.php");

	                                                                                                                      
	
	$app = new Application($db);
	
                                             


?>
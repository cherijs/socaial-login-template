<?php

header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL ^ E_NOTICE);
require_once("config/system_head.php");

session_start();

// echo "<pre>";
// var_dump($_GET);


$params = explode( "/", $_GET['params'] );


// var_dump($params);

// for($i = 0; $i < count($params); $i+=2) {

//   echo $params[$i] ." has value: ". $params[$i+1] ."<br />";

// }



if (isset($_SESSION['id']) &&  (!isset($_GET['p']))) {
    // Redirection to login page twitter or facebook
    // header("location: home_new.php");
}





if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
        header("Location: login-twitter.php");
    } else if ($oauth_provider == 'facebook') {
        header("Location: login-facebook.php");
    }else if ($oauth_provider == 'draugiem') {
        header("Location: login-draugiem.php");
    }
}



switch ($params[0]) {
	
		
	case 'user':
	
		if($params[1]){
			$uid = (int)$params[1];
			
			include_once('user.php');


		} else {
			//nav bildes linka
			 header("Location: ".$server_url);
		}

	break;



	
	default:
		# code...
		include_once('home.php');
		break;
}








?>
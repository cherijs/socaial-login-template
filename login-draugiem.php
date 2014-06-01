<?php
require_once("config/system_head.php");

$app_key = '0f1db7b1355a1bf83da75fc9c1b82a36';//Application API key of your app goes here
$app_id = 15016207;//Application ID of your app goes here

session_start(); //Start PHP session

if(isset($_GET['logout'])){//Logout
	session_destroy();
	header('Location: ?');
}


include 'social/DraugiemApi.php';
	
$draugiem = new DraugiemApi($app_id, $app_key);//Create Draugiem.lv API object
	
$session = $draugiem->getSession(); //Try to authenticate user

if($session && !empty($_GET['dr_auth_code'])){//New session, check if we are not redirected from popup
	if(!empty($_GET['dr_popup'])){//Redirected from popup, refresh parent window and close the popup with Javascript
		?>
		<script type="text/javascript">
		window.opener.location.reload();
		window.opener.focus();
		if(window.opener!=window){
			window.close();
		}
		</script>
		<?php
	} else {//No popup, simply reload current window
		header('Location: ?');
	}
	exit;
}elseif(!empty($_GET['dr_popup'])){ // failed login
		?><script type="text/javascript">
		window.opener.location.reload();
		window.opener.focus();
		if(window.opener!=window){
			window.close();
		}
		</script>
		<?php
		exit;
}

?>
<?php
	if($session){//Authentication successful

		$user = $draugiem->getUserData();//Get user info

		$img = $user['img'];


        // echo "<pre>";
        // var_dump($user);
        // exit;
		

		$userdata =  $app->checkUser($user['uid'], 'draugiem', $user['name'].' '.$user['surname'],null,null,null,null, null,$img );
        

			$_SESSION['id'] = $userdata['id'];
            $_SESSION['oauth_id'] = $user['uid'];
            $_SESSION['pikcha'] = $user['imgm'];
            $_SESSION['username'] = $user['name'].' '.$user['surname'];
            $_SESSION['oauth_provider'] = "draugiem";

             header("Location: index.php");

		// array(15) { ["uid"]=> int(4028) ["name"]=> string(7) "Artūrs" ["surname"]=> string(6) "Cirsis" ["nick"]=> string(7) "cherijs" ["place"]=> string(0) "" ["img"]=> string(60) "http://i8.ifrype.com/profile/004/028/v1351607126/sm_4028.jpg" ["imgi"]=> string(59) "http://i8.ifrype.com/profile/004/028/v1351607126/i_4028.jpg" ["imgm"]=> string(60) "http://i8.ifrype.com/profile/004/028/v1351607126/nm_4028.jpg" ["imgl"]=> string(59) "http://i8.ifrype.com/profile/004/028/v1351607126/l_4028.jpg" ["sex"]=> string(1) "M" ["birthday"]=> bool(false) ["age"]=> bool(false) ["adult"]=> int(1) ["type"]=> string(12) "User_Default" ["deleted"]=> bool(false) }
		

	} else { //User not logged in, show login button

		$redirect_url = $server_url."/login-draugiem.php";
		//echo '<a href="http://api.draugiem.lv/authorize/?app='.$app_id.'&hash='.md5($app_key.$redirect_url).'&redirect='.$redirect_url.'">draugiem</a>';
		$url = 'http://api.draugiem.lv/authorize/?app='.$app_id.'&hash='.md5($app_key.$redirect_url).'&redirect='.$redirect_url;
 		header('Location: ' . $url);

		
	}
?>

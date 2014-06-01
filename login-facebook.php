<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();
require_once("config/system_head.php");
require 'social/facebook/facebook.php';
require 'config/fbconfig.php';
// require 'config/functions.php';
$facebook = new Facebook(array(
    'appId' => APP_ID,
    'secret' => APP_SECRET,
    'fileUpload' => true,
    'grant_type' => "fb_exchange_token",
    'cookie' => true
));
$user     = $facebook->getUser();
// echo "<pre>";
// var_dump($user);
// exit;
if ($user) {
    // $at = $app->getUserUID($user)["facebook_acces_token"];
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me', 'GET');
        $facebook->setExtendedAccessToken();
        $access_token = $_SESSION["fb_" . APP_ID . "_access_token"];
        $facebook->setAccessToken($access_token);
        $accessToken = $facebook->getAccessToken();
    }
    catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
        // echo "<pre>";
        // var_dump($e);
    }
    if (!empty($user_profile)) {
       
        $username             = $user_profile['name'];
        $uid                  = $user_profile['id'];
        $email                = $user_profile['email'];
        $img = "https://graph.facebook.com/".$uid."/picture?width=100&height=100";


        // $user                 = new User();
        $facebook_acces_token = $facebook->getAccessToken();
        // $userdata             = $user->checkUser($uid, 'facebook', $username, $email, $twitter_otoken, $twitter_otoken_secret, $facebook_acces_token, null);
        $userdata =  $app->checkUser($uid, 'facebook', $username,$email,$twitter_otoken,$twitter_otoken_secret,$facebook_acces_token, null, $img );
        

        if (!empty($userdata)) {
            session_start();
            $_SESSION['id']                   = $userdata['id'];
            $_SESSION['oauth_id']             = $uid;
            $_SESSION['username']             = $userdata['username'];
            $_SESSION['email']                = $email;
            $_SESSION['oauth_provider']       = $userdata['oauth_provider'];
            $_SESSION['facebook_acces_token'] = $facebook_acces_token;
            header("Location: index.php");
        }
    } else {
        if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
        } else {
            $login_url = $facebook->getLoginUrl(array(
                // 'scope' => 'publish_actions'
                // , 'redirect_uri' => 'http://madaragr.am/index_new.php'
            ));
        }
        // header("Location: " . $login_url);
        die(" There was an error. " . $login_url);
    }
} else {
    # There's no active session, let's generate one offline_access,publish_stream,status_update
    $login_url = $facebook->getLoginUrl(array(
        // 'scope' => 'publish_actions'
       // , 'redirect_uri' => 'http://madaragr.am/index_new.php'
    ));
    

    header("Location: " . $login_url);
}
?>

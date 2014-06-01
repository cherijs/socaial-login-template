<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();

require_once(__DIR__."/../config/system_head.php");

require(__DIR__."/../social/twitter/twitteroauth.php");
require (__DIR__.'/../config/twconfig.php');
// require 'config/functions.php';


session_start();

if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {



    $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
   
    $_SESSION['access_token'] = $access_token;
    $user_info = $twitteroauth->get('account/verify_credentials');

    if (isset($user_info->error)) {
        // Something's wrong, go back to square 1  
        header('Location: /login-twitter.php');
    } else {
        $twitter_otoken=$_SESSION['oauth_token'];
        $twitter_otoken_secret=$_SESSION['oauth_token_secret'];

        $email='';
        $uid = $user_info->id;
        $picture = $user_info->profile_image_url_https;
        

        $picture = str_replace("_normal.", "_bigger.", $user_info->profile_image_url_https);


        // echo "<pre>";
        // var_dump($user_info);
        // exit;
        //https://api.twitter.com/1/users/profile_image?screen_name=twitterapi&size=bigger
        $username = $user_info->name;


        // $user = new User();
        $facebook_acces_token = null;


        if($twitter_otoken && $twitter_otoken_secret && $access_token){
            // $userdata = $user->checkUser($uid, 'twitter', $username,$email,$twitter_otoken,$twitter_otoken_secret,$facebook_acces_token, serialize($access_token) );
            


           $userdata =  $app->checkUser($uid, 'twitter', $username,$email,$twitter_otoken,$twitter_otoken_secret,$facebook_acces_token, serialize($access_token),$picture  );
        
          
        }



        if(!empty($userdata)){
            session_start();
            $_SESSION['id'] = $userdata['id'];
            $_SESSION['oauth_id'] = $uid;
            $_SESSION['pikcha'] = str_replace('_normal', '', $picture);
            $_SESSION['username'] = $userdata['username'];
            $_SESSION['oauth_provider'] = $userdata['oauth_provider'];


            header("Location: /index.php");
        }
    }
} else {
    // Something's missing, go back to square 1
    header('Location: /login-twitter.php');
}
?>

<?php

require 'dbconfig.php';

class User {

    function checkUser($uid, $oauth_provider, $username,$email,$twitter_otoken,$twitter_otoken_secret,$facebook_acces_token,$access_token,$img) 
    {
        $query = mysql_query("SELECT * FROM `ORIGO_PARTY__users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'") or die(mysql_error());
        $result = mysql_fetch_array($query);
        if (!empty($result)) {
            # User is already present
            // apdeitojam facebook_acces_token

            if($oauth_provider=="facebook"){
                  $query = mysql_query("UPDATE `ORIGO_PARTY__users` SET facebook_acces_token = '$facebook_acces_token' WHERE oauth_uid = '$uid'") or die(mysql_error());

              } else { //twitter
                 $query = mysql_query("UPDATE `ORIGO_PARTY__users` SET twitter_oauth_token = '$twitter_otoken', twitter_oauth_token_secret = '$twitter_otoken_secret', access_token ='$access_token', img ='$img' WHERE oauth_uid = '$uid'") or die(mysql_error());

              }
          

        } else {
            #user not present. Insert a new Record
            if($uid){
                // $query = mysql_query("INSERT INTO `users` (oauth_provider, oauth_uid, username, email, twitter_oauth_token, twitter_oauth_token_secret, facebook_acces_token, access_token) VALUES ('$oauth_provider', '$uid', '$username','$email','$twitter_otoken','$twitter_otoken_secret','$facebook_acces_token', '$access_token')") or die(mysql_error());
              
                $oauth_provider = mysql_real_escape_string($oauth_provider);
                $uid = mysql_real_escape_string($uid);
                $username = mysql_real_escape_string($username);
                $email = mysql_real_escape_string($email);
                $twitter_otoken = mysql_real_escape_string($twitter_otoken);
                $twitter_otoken_secret = mysql_real_escape_string($twitter_otoken_secret);
                $facebook_acces_token = mysql_real_escape_string($facebook_acces_token);
                $access_token = mysql_real_escape_string($access_token);
                $img = mysql_real_escape_string($img);



                  $query = mysql_query("
                    INSERT INTO `ORIGO_PARTY__users` (oauth_provider, oauth_uid, username, email, twitter_oauth_token, twitter_oauth_token_secret, facebook_acces_token, access_token,img) 
                    VALUES (
                        '".$oauth_provider."', 
                        '".$uid."', 
                        '".$username."',
                        '".$email."',
                        '".$twitter_otoken."',
                        '".$twitter_otoken_secret."',
                        '".$facebook_acces_token."', 
                        '".$access_token."',
                        '".$img."'
                        )") or die(mysql_error());





                $query = mysql_query("SELECT * FROM `ORIGO_PARTY__users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
                $result = mysql_fetch_array($query);
                return $result;
            } else {
                return null;
            }
        }
        return $result;
    }

    

}
// $query = mysql_query("INSERT INTO `users` (oauth_provider, oauth_uid, username,email, facebook_acces_token) VALUES ('$oauth_provider', $uid, '$username','$email','$facebook_acces_token')") or die(mysql_error());

?>


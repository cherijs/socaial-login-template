<?php
class Application {
	
	private $db;

	public $debug = true;
	private $prefix;
	
	public function __construct($db) {
		$this->db = $db;
		$this->prefix = "BLANK_APP";



		$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->prefix."__users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `email` varchar(70) DEFAULT NULL,
		  `oauth_uid` varchar(200) NOT NULL DEFAULT '',
		  `oauth_provider` varchar(200) DEFAULT NULL,
		  `username` varchar(100) DEFAULT NULL,
		  `twitter_oauth_token` varchar(200) DEFAULT NULL,
		  `twitter_oauth_token_secret` varchar(200) DEFAULT NULL,
		  `facebook_acces_token` varchar(255) DEFAULT NULL,
		  `access_token` text,
		  `img` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`id`,`oauth_uid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");




	}


	public function report($dumper) {
		if($debug) {
			var_dump($dumper);
		}
	}


	//==========================================================================================
	//										INSERTION
	//==========================================================================================


	public function insertNewUser($oauth_uid, $oauth_provider, $username,$email,$twitter_oauth_token,$twitter_oauth_token_secret,$facebook_acces_token,$access_token,$img) {
		
				$oauth_uid = mysql_real_escape_string($oauth_uid);
		 		$oauth_provider = mysql_real_escape_string($oauth_provider);
                $username = mysql_real_escape_string($username);
                $email = mysql_real_escape_string($email);
                $twitter_oauth_token = mysql_real_escape_string($twitter_oauth_token);
                $twitter_oauth_token_secret = mysql_real_escape_string($twitter_oauth_token_secret);
                $facebook_acces_token = mysql_real_escape_string($facebook_acces_token);
                $access_token = mysql_real_escape_string($access_token);
                $img = mysql_real_escape_string($img);


		$q = array(
			"oauth_provider" => $oauth_provider,
			"oauth_uid" => $oauth_uid,
			"username" => $username,
			"email" => $email,
			"twitter_oauth_token" => $twitter_oauth_token,
			"twitter_oauth_token_secret" => $twitter_oauth_token_secret,
			"facebook_acces_token" => $facebook_acces_token,
			"access_token" => $access_token,
			"img" => $img
		);


		$this->db->insert($this->prefix."__users", $q);
		$id = $this->db->lastId();
		
		return $id;


	}
	
	



	public function checkUser($uid, $oauth_provider, $username,$email,$twitter_oauth_token,$twitter_oauth_token_secret,$facebook_acces_token,$access_token,$img) {
  

		$sql = "SELECT * FROM ".$this->prefix."__users WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'";
	    $this->db->query($sql) or die(mysql_error());
		$r = $this->db->fetch();
//

        if (!empty($r)) {
            # User is already present
            // apdeitojam facebook_acces_token

            if($oauth_provider=="facebook"){

                $this->updateAcces_token($uid,$facebook_acces_token);
              } else { //twitter

                 $this->updateAcces_token_tw($uid,$twitter_oauth_token,$twitter_oauth_token_secret,$access_token);
              }
          

        } else {
            #user not present. Insert a new Record
            if($uid){
               
                $oauth_provider = mysql_real_escape_string($oauth_provider);
                $uid = mysql_real_escape_string($uid);
                $username = mysql_real_escape_string($username);
                $email = mysql_real_escape_string($email);
                $twitter_oauth_token = mysql_real_escape_string($twitter_oauth_token);
                $twitter_oauth_token_secret = mysql_real_escape_string($twitter_oauth_token_secret);
                $facebook_acces_token = mysql_real_escape_string($facebook_acces_token);
                $access_token = mysql_real_escape_string($access_token);
                $img = mysql_real_escape_string($img);

                $id = $this->insertNewUser($uid, $oauth_provider, $username,$email,$twitter_oauth_token,$twitter_oauth_token_secret,$facebook_acces_token,$access_token,$img);

                $r = $this->getUser($id);
               // $r = $this->getUserUID($uid);
                return $r;


            } else {
                return null;
            }
        }
        return $r;
    }
	
	
	//==========================================================================================
	//											UPDATES
	//==========================================================================================

	public function updateAcces_token($uid,$facebook_acces_token) {
		$q = array(
			"facebook_acces_token" => $facebook_acces_token
		);
		$this->db->update($this->prefix."__users", $q, "oauth_uid", $uid);
		$_SESSION['facebook_acces_token'] = $facebook_acces_token;
	}

	public function updateAcces_token_tw($uid,$twitter_oauth_token,$twitter_oauth_token_secret,$access_token) {
		$q = array(
			"twitter_oauth_token" => $twitter_oauth_token,
			"twitter_oauth_token_secret" => $twitter_oauth_token_secret,
			"access_token" => $access_token
		);
		$this->db->update($this->prefix."__users", $q, "oauth_uid", $uid);
		$_SESSION['twitter_oauth_token'] = $twitter_oauth_token;
	}



	//==========================================================================================
	//											DELETION
	//==========================================================================================
	public function deleteUid($uid) {
		$sql = "DELETE FROM ".$this->prefix."__cron WHERE uid=$uid";
		$ret .= "__results:".$this->db->query($sql);
		
		
		return $ret;
	}
	
	
	
	//==========================================================================================
	//											GETS
	//==========================================================================================
	public function getUser($id) {


	    $sql = "SELECT * FROM ".$this->prefix."__users WHERE id = $id";
	    $this->db->query($sql);
		$r = $this->db->fetch();


        return $r;
	}





	public function getUserUID($uid) {


	    $sql = "SELECT * FROM ".$this->prefix."__users WHERE oauth_uid = $uid";
	    $this->db->query($sql);
		$r = $this->db->fetch();


        return $r;
	}


	



	
}
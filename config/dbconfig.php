<?php 

	class Db {
		
		private $host;
		private $username;
		private $password;
		private $database;
		
		private $result;
		
		public function __construct($host, $username, $password, $database){
			
			$this->host = $host;
			$this->username = $username;
			$this->password = $password;
			$this->database = $database;
			
			$this->connect();
			
		}
		
		private function connect(){
			mysql_connect($this->host, $this->username, $this->password);
			mysql_select_db($this->database)or die(mysql_error()) ;
			mysql_query("set names utf8");
			
		}
		
		public function query($sql){
	        if($this->result = mysql_query($sql) or die(mysql_error()) ) {
	        	return true;
	        } else {
	        	return false;
	        }
	    }
	
	    public function fetch(){

	        return @mysql_fetch_assoc($this->result);
	    }
	
	    public function getOne(){
	        list($value) = mysql_fetch_array($this->result);
	        return $value;
	    }
	    
	    public function rowCount(){
	        return mysql_num_rows($this->result);
	    }
	    
	    public function insert($table,$arr){
	
	        $fields = "";
	        $values = "";
	
	        foreach($arr as $k=>$v){
	            $fields .= $k.",";
	            $values .= "'".$this->esc($v)."',";
	        }
	
	        $fields = substr($fields,0,-1);
	        $values = substr($values,0,-1);
	
	        $sql = "insert into ".$table." (".$fields.") values (".$values.")";
	        if($this->query($sql)) {
	        	return true;
	        } else {
	        	return false;
	        }
	    }
		
		public function update($table,$arr, $field, $id){
	
	        $sql ="";
			$i = 0;
	        foreach($arr as $k=>$v){
	        	if($i != 0) {
	       		 	$sql .= ", ";
	        	}
	        	$v = $this->esc($v);
	        	$sql .= "$k='$v'";
	        	$i++;
	        }
	
	
	        $sqlx = "UPDATE ".$table." SET ".$sql." WHERE ".$field."='".$id."'";
	      
	        if($this->query($sqlx)) {
	        	return true;
	        } else {
	        	return false;
	        }
	    }

	    public function update2($table,$arr, $field, $id){
	
	        $sql ="";
			$i = 0;
	        foreach($arr as $k=>$v){
	        	if($i != 0) {
	       		 	$sql .= ", ";
	        	}
	        	$v = $this->esc($v);
	        	$sql .= "$k='$v'";
	        	$i++;
	        }
	
	
	        $sqlx = "UPDATE ".$table." SET ".$sql." WHERE ".$field."'".$id."'";
	      
	        if($this->query($sqlx)) {
	        	return true;
	        } else {
	        	return false;
	        }
	    }
		
	    public function esc($v, $type = "string"){
	        if($type == 'integer'){ $v = (int) $v; }
	        //return mysql_real_escape_string($v);
	        return stripslashes($v);
	    }
	
	    public function lastId(){
	        return mysql_insert_id();
	    }
	    
	}

	// $db = new Db('95.211.216.226', 'slurplv_dev', 'devdev', 'slurplv_dev');
	// $db = new Db('95.211.216.226', 'slurplv_apps', 'slurpapps', 'slurplv_apps');
	$db = new Db('localhost', 'slurplv_dev', 'devdev', 'slurplv_dev');



// define('DB_SERVER', '95.211.216.226');
// define('DB_USERNAME', 'slurplv_dev');
// define('DB_PASSWORD', 'devdev');
// define('DB_DATABASE', 'slurplv_dev');
// $connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());

// mysql_query("SET NAMES 'utf8'");
// $database = mysql_select_db(DB_DATABASE) or die(mysql_error());



?>

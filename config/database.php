<?php

class Database{

  // var $host = "127.0.0.1";
	// var $username = "root";
	// var $password = "";
  // var $dbname = "disbursement_via_flip";

	// function __construct(){
  //   $conn = mysqli_connect($this->host, $this->username, $this->password);
  //   mysqli_select_db($conn, $this->dbname);
  // }

  // function getConnection(){
  //   $conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
  //   if ($conn->connect_error) {
  //     die("Connection failed: " . $conn->connect_error);
  //   }
  //   return $conn;
  // }

  function getConnection(){
    $host = env("DB_HOST");
    $username = env("DB_USERNAME");
    $password = env("DB_PASSWORD");
    $dbname = env("DB_NAME");
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
  }

}

?>
<?php

class Database{

  function getConnectionDB($dbhost, $dbuser, $dbpass){
    /** set connection using mysqli */
    $conn = new mysqli($dbhost, $dbuser, $dbpass, "");

    /** check connection */
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
  }

  function getConnectionDisbursement(){
    /** get db host */
    $dbhost = env("DB_HOST");

    /** get db user */
    $dbuser = env("DB_USER");

    /** get db password */
    $dbpass = env("DB_PASSWORD");

    /** get connection DB */
    $conn = $this->getConnectionDB($dbhost, $dbuser, $dbpass);

    /** get db name */
    $dbname = env("DB_NAME");

    /** set db name */
    if (!$conn->select_db($dbname)) {
      die("Uh oh, couldn't select database $dbname");
    }

    return $conn;
  }

}

?>
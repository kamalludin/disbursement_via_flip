<?php
include "autoload.php";
include 'config/database.php';

/** create object db */
$db = new Database();

/** set db host db */
$dbhost = "127.0.0.1";

/** set db user */
$dbuser = "root";

/** set db pass */
$dbpass = "";

/** get connection db */
$conn = $db->getConnectionDB($dbhost, $dbuser, $dbpass);

/** begin transaction */
$conn->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);

/** set db name */
$dbname = "disbursement_via_flip";

/** query for new database */
$queryCreateDB = "create database ".$dbname;

/** create new database */
if(!$conn->query($queryCreateDB)) {
  /** rollback transaction */
  $conn->rollback();
  die("Error: " . $conn->error . "\n");
} else {
  printf("- Database '" . $dbname . "' successfully created.\n");
}

/** select db */
if (!$conn->select_db($dbname)) {
  /** rollback transaction */
  $conn->rollback();
  die("Error: couldn't select database $dbname");
}

/** query for new table */
$queryCreateTableDisbursement =
"CREATE TABLE `disbursement` (
  `id` varchar(36) NOT NULL,
  `flip_id` bigint(11) NOT NULL,
  `amount` int(15) NOT NULL,
  `status` varchar(10) NOT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `bank_code` varchar(10) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `beneficiary_name` varchar(50) NOT NULL,
  `remark` varchar(26) NOT NULL,
  `receipt` varchar(255) DEFAULT NULL,
  `time_served` timestamp NULL DEFAULT NULL,
  `fee` int(10) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flip_id` (`flip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

/** create new table */
if(!$conn->query($queryCreateTableDisbursement)){
  /** rollback transaction */
  $conn->rollback();
  die("Error: " . $conn->error . "\n");
} else {
  printf("- Table 'disbursement' successfully created.\n");
}

/** commit transaction */
$conn->commit();

/** close connection */
$conn->close();

/** message success */
printf("- Migration has been successful.\n");

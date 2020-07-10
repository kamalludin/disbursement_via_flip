<?php
include "autoload.php";
include "config/database.php";
include "services/disbursement.php";
include "utils/helper.php";

/** create object db */
$db = new Database();

/** get connection db */
$conn = $db->getConnectionDisbursement();

/** create object disbursement */
$disbursement = new Disbursement();

/** create object helper */
$helper = new Helper();

/**  get uri */
$request = $_SERVER['REQUEST_URI'];

/** ----- simple routing ----- */
switch ($request) {

  case '/' :
    echo "Disbursement via Flip";
    break;

  case '' :
    echo "Disbursement via Flip";
    break;

  /** for disbursement request */
  case '/disbursementRequest' :
    // $conn = $db->getConnection();
    $disbursement->requestToFlip($conn);
    // $conn->close();
    break;

  /** for check disbursement status */
  case '/checkDisbursementStatus' :
    // $conn = $db->getConnection();
    $disbursement->checkDisbursementStatus($conn);
    // $conn->close();
    break;

  /** not found */
  default:
    http_response_code(404);
    echo "Not Found!";
    break;

}

/** close connection */
$conn->close();
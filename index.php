<?php
include "autoload.php";
include "config/database.php";
include "services/disbursement.php";
include "utils/helper.php";

$db = new Database();

$disbursement = new Disbursement();
$helper = new Helper();

$request = $_SERVER['REQUEST_URI'];
switch ($request) {
  case '/' :
    echo "Disbursement via Flip";
    break;
  case '' :
    echo "Disbursement via Flip";
    break;
  case '/disbursementRequest' :
    $conn = $db->getConnection();
    $disbursement->requestToFlip($conn);
    $conn->close();
    break;
  case '/checkDisbursementStatus' :
    $conn = $db->getConnection();
    $disbursement->checkDisbursementStatus($conn);
    $conn->close();
    break;
  default:
    http_response_code(404);
    echo "Not Found!";
    break;
}
<?php

class Disbursement{

  function requestToFlip($conn){

    $helper = new Helper();
    $bank_code = $_POST['bank_code'];
    $account_number = $_POST['account_number'];
    $amount = $_POST['amount'];
    $remark = $_POST['remark'];

    $url = "https://nextar.flip.id/disburse";
    $dataRequest = "bank_code=" . $bank_code . "&account_number=" . $account_number . "&amount=" . $amount . "&remark=" . $remark;

    $responseCurl = $helper->postRequestWithCurl($url, $dataRequest);
    $responseCurl = json_decode($responseCurl, true);

    $id = uniqid();
    $query = "insert into disbursement (id, flip_id, amount, status, timestamp, bank_code, account_number, beneficiary_name, remark, fee)
              values (
                '" . $id . "',
                " . $responseCurl['id'] . ",
                " . $responseCurl['amount'] . ",
                '" . $responseCurl['status'] . "',
                '" . $responseCurl['timestamp'] . "',
                '" . $responseCurl['bank_code'] . "',
                '" . $responseCurl['account_number'] . "',
                '" . $responseCurl['beneficiary_name'] . "',
                '" . $responseCurl['remark'] . "',
                " . $responseCurl['fee'] . "
              )";

    if (!mysqli_query($conn, $query)) {

      $status = 500;
      $result = [
        'message' => 'error: '.mysqli_error($conn)
      ];

    } else {

      $status = 200;
      $result = [
        'message' => 'success',
        'data' => [
          'disbursement_id' => $id,
          'status' => $responseCurl['status']
        ]
      ];

    }

    $helper->responseJSON($status, $result);

  }

  function checkDisbursementStatus($conn){

    $helper = new Helper();
    $disbursement_id = $_POST['disbursement_id'];
    $query = "select flip_id, status from disbursement where id = '" . $disbursement_id . "'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $disbursement_status_db = $row['status'];
          $flip_id = $row['flip_id'];
      }

      if ($disbursement_status_db === "PENDING") {

        $disbursement_status_flip = $disbursement_status_db;
        $i=0;
        while ($disbursement_status_flip === "PENDING") {
          $url = "https://nextar.flip.id/disburse/" . $flip_id;
          $responseCurl = $helper->getRequestWithCurl($url);
          $responseCurl = json_decode($responseCurl, true);
          $disbursement_status_flip = $responseCurl['status'];
          $i++;
          if ($i == 5) {
            break;
          }
        }

        if ($disbursement_status_flip === "PENDING") {
          $status = 200;
          $result = [
            'message' => 'success',
            'data' => [
              'disbursement_id' => $disbursement_id,
              'status' => $disbursement_status_flip,
              'message' => "the status hasn't changed, please try again!"
            ]
          ];
        } else if ($disbursement_status_flip === "SUCCESS") {
          $query = "update disbursement set 
            status = '" . $responseCurl['status'] . "', 
            receipt = '" . $responseCurl['receipt'] . "', 
            time_served = '" . $responseCurl['time_served'] . "',
            updated_date = '" . date('Y-m-d H-i-s') . "' where id = '" . $disbursement_id . "'";

          if (!mysqli_query($conn, $query)) {
            $status = 500;
            $result = [
              'message' => 'error: '.mysqli_error($conn)
            ];
          } else {
            $status = 200;
            $result = [
              'message' => 'success',
              'data' => [
                'disbursement_id' => $disbursement_id,
                'status' => $responseCurl['status']
              ]
            ];
          }

        }

      } else {

        $status = 200;
        $result = [
          'message' => 'success',
          'data' => [
            'disbursement_id' => $id,
            'status' => $disbursement_status_db
          ]
        ];

      }

    } else {

      $status = 404;
      $result = [
        'message' => 'Data not found!',
      ];

    }

    $helper->responseJSON($status, $result);

  }

}

?>
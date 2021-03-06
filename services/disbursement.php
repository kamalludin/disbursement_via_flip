<?php

class Disbursement{

  function requestToFlip($conn){

    /** create object helper */
    $helper = new Helper();

    /** validate variable required */
    if (!isset($_POST['bank_code']) || empty($_POST['bank_code'])){
      /** set header status Internal Server Error */
      $status = 500;

      /** set result with error message */
      $result = [
        'message' => 'bank_code is required!'
      ];
    } else if (!isset($_POST['account_number']) || empty($_POST['account_number'])){
      /** set header status Internal Server Error */
      $status = 500;

      /** set result with error message */
      $result = [
        'message' => 'account_number is required!'
      ];
    } else if (!isset($_POST['amount']) || empty($_POST['amount'])){
      /** set header status Internal Server Error */
      $status = 500;

      /** set result with error message */
      $result = [
        'message' => 'amount is required!'
      ];
    } else if (!isset($_POST['remark']) || empty($_POST['remark'])){
      /** set header status Internal Server Error */
      $status = 500;

      /** set result with error message */
      $result = [
        'message' => 'remark is required!'
      ];
    } else {
      /** get value bank code */
      $bank_code = $_POST['bank_code'];

      /** get value account number */
      $account_number = $_POST['account_number'];

      /** get value amount */
      $amount = $_POST['amount'];

      /** get value remark */
      $remark = $_POST['remark'];

      /** set url disburse */
      $url = "https://nextar.flip.id/disburse";

      /** set parameter request */
      $dataRequest = "bank_code=" . $bank_code . "&account_number=" . $account_number . "&amount=" . $amount . "&remark=" . $remark;

      /** disbursement request */
      $responseCurl = $helper->postRequestWithCurl($url, $dataRequest);

      /** decode flip response from JSON to array object */
      $responseCurl = json_decode($responseCurl, true);

      /** create id using uniq id */
      $id = uniqid();

      /** describe insert query */
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

      /** process query */
      if (!$conn->query($query)) {
        /** if get error */
        /** set header status Internal Server Error */
        $status = 500;

        /** set result with error message */
        $result = [
          'message' => 'error: ' . $conn->error
        ];
      } else {
        /** if success */
        /** set header status is Success */
        $status = 200;

        /** set result */
        $result = [
          'message' => 'success',
          'data' => [
            'disbursement_id' => $id,
            'status' => $responseCurl['status']
          ]
        ];
      }
    }

    /** return result in JSON */
    $helper->responseJSON($status, $result);

  }

  function checkDisbursementStatus($conn){

    /** create object helper */
    $helper = new Helper();

    if (!isset($_POST['disbursement_id']) || empty($_POST['disbursement_id'])){
      /** set header status Internal Server Error */
      $status = 500;

      /** set result with error message */
      $result = [
        'message' => 'disbursement_id is required!'
      ];
    } else {
      /** get value disbursement_id */
      $disbursement_id = $_POST['disbursement_id'];

      /** describe select query */
      $query = "select flip_id, status from disbursement where id = '" . $disbursement_id . "'";

      /** process query */
      $result = $conn->query($query);

      /** check num rows */
      if ($result->num_rows > 0) {
        /** loop result */
        while($row = $result->fetch_assoc()) {
            /** get status value */
            $disbursement_status_db = $row['status'];

            /** get flip_id value */
            $flip_id = $row['flip_id'];
        }

        /** check status from database */
        if ($disbursement_status_db === "PENDING") {
          /** set url check status */
          $url = "https://nextar.flip.id/disburse/" . $flip_id;

          /** check status request */
          $responseCurl = $helper->getRequestWithCurl($url);

          /** decode flip response from JSON to array object */
          $responseCurl = json_decode($responseCurl, true);

          /** set query for update */
          $query = "update disbursement set
            status = '" . $responseCurl['status'] . "',
            receipt = '" . $responseCurl['receipt'] . "',
            time_served = '" . $responseCurl['time_served'] . "',
            updated_date = '" . date('Y-m-d H-i-s') . "' where id = '" . $disbursement_id . "'";

          /** process query */
          if (!$conn->query($query)) {
            /** if failed, it will set header status Internal Server Error */
            $status = 500;

            /** set result with error message */
            $result = [
              'message' => 'error: ' . $conn->error
            ];
          } else {
            /** get status from response */
            $disbursement_status_flip = $responseCurl['status'];

            /** checking for last flip status */
            if ($disbursement_status_flip === "PENDING") {
              /** if PENDING, it will return message, to re-check the process */
              /** set header status is Success */
              $status = 200;

              /** set result with message */
              $result = [
                'message' => 'success',
                'data' => [
                  'disbursement_id' => $disbursement_id,
                  'status' => $disbursement_status_flip,
                  'message' => "the status hasn't changed, please try again!"
                ]
              ];
            } else if ($disbursement_status_flip === "SUCCESS") {
              /** if SUCCESS
               * it will update some data (status, receipt, time_reserved and updated_date)
               */

              /** set header status is Success */
              $status = 200;

              /** set result with status SUCCESS */
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
          /** conditions, if the status in the database is SUCCESS */
          /** set header status is Success */
          $status = 200;

          /** set result */
          $result = [
            'message' => 'success',
            'data' => [
              'disbursement_id' => $disbursement_id,
              'status' => $disbursement_status_db
            ]
          ];
        }
      } else {
        /** conditions, if disbursement id not found in database */
        /** set header status not found */
        $status = 404;

        /** set result with message not found*/
        $result = [
          'message' => 'Data not found!',
        ];
      }
    }

    /** return result in JSON */
    $helper->responseJSON($status, $result);

  }

}

?>
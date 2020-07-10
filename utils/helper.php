<?php
  class Helper {

    function postRequestWithCurl($url, $postfields){
      /** set secret_key */
      $secret_key = env("SECRET_KEY");

      /** curl initiate */
      $curl = curl_init();

      /** set the required variables */
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/x-www-form-urlencoded",
          "Authorization: Basic " . $secret_key
        ),
      ));

      /** curl execution */
      $response = curl_exec($curl);

      /** curl close */
      curl_close($curl);

      /** return response from curl execution */
      return $response;
    }

    function getRequestWithCurl($url) {
      /** set secret_key */
      $secret_key = env("SECRET_KEY");

      /** curl initiate */
      $curl = curl_init();

      /** set the required variables */
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/x-www-form-urlencoded",
          "Authorization: Basic " . $secret_key
        ),
      ));

      /** curl execution */
      $response = curl_exec($curl);

      /** curl close */
      curl_close($curl);

      /** return response from curl execution */
      return $response;
    }

    function responseJSON($status, $data){
      /** set response code */
      http_response_code($status);

      /** set Content-Type to application/json */
      header('Content-Type: application/json');

      /** encode data from array to JSON */
      $result = json_encode($data);

      /** print result */
      echo $result;
    }

  }
?>
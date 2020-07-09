<?php
  class Helper {

    function postRequestWithCurl($url, $postfields){

      $secret_key = env("SECRET_KEY");
      $curl = curl_init();

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

      $response = curl_exec($curl);

      curl_close($curl);

      return $response;

    }

    function getRequestWithCurl($url) {

      $curl = curl_init();

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
          "Authorization: Basic SHl6aW9ZN0xQNlpvTzduVFlLYkc4TzRJU2t5V25YMUp2QUVWQWh0V0tadW1vb0N6cXA0MTo="
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      return $response;

    }

    function responseJSON($status, $data){
      // response code
      http_response_code($status);

      // set Content-Type to application/json
      header('Content-Type: application/json');

      // encode to JSON
      $result = json_encode($data);

      // print data
      echo $result;
    }

  }
?>
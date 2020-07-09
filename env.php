<?php
  $variables = [
      'DB_HOST' => '127.0.0.1',
      'DB_USERNAME' => 'root',
      'DB_PASSWORD' => '',
      'DB_NAME' => 'disbursement_via_flip',
      'SECRET_KEY' => 'SHl6aW9ZN0xQNlpvTzduVFlLYkc4TzRJU2t5V25YMUp2QUVWQWh0V0tadW1vb0N6cXA0MTo='
  ];

  foreach ($variables as $key => $value) {
      putenv("$key=$value");
  }
?>
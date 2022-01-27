<?php
$api_key = 'LYJkfbTERDaX9rEcHubF_d988c25b17504a9699140341edf54435';
$client_secret = 'KgMs87tKCuA8BTEj';
$request_ref = mt_rand(100000000, 999999999);
$signature = md5($request_ref . ";" . $client_secret);
function EncryptV2($encryption_key, $data)
{
  $method = "des-ede3-cbc";
  $source = mb_convert_encoding($encryption_key, 'UTF-16LE', 'UTF-8');
  $encryption_key = md5($source, true);
  $encryption_key .= substr($encryption_key, 0, 16);
  $iv =  "\0\0\0\0\0\0\0\0";
  $encData = openssl_encrypt($data, $method, $encryption_key, $options = OPENSSL_RAW_DATA, $iv);
  return base64_encode($encData);
};
$postData = [
  "request_ref" => "$request_ref",
  "request_type" => "collect",
  "auth" => [
    "type" => "bank.account",
    "secure" => strval(EncryptV2($client_secret, "2233305555;0001")),
    "auth_provider" => "DemoProvider",
    "route_mode" => null
  ],
  "transaction" => [
    "mock_mode" => "inspect",
    "transaction_ref" => "$request_ref",
    "transaction_desc" => "A random transaction",
    "transaction_ref_parent" => "",
    "amount" => 10000,
    "customer" => [
      "customer_ref" => "Jhon",
      "firstname" => "Uju",
      "surname" => "Usmanu",
      "email" => "ujuusmanu@gmail.com",
      "mobile_no" => "234802343132"
    ],
    "meta" => [
      "a_key" => "a_meta_value_1",
      "another_key" => "a_meta_value_2"
    ],
    "details" => null
  ]
];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.onepipe.io/v2/transact',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($postData),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    "Authorization: Bearer $api_key",
    "Signature: $signature"
  ),
));

$response = curl_exec($curl);
$response = json_decode($response, true);
curl_close($curl);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transfer</title>
</head>

<body>
  <?php
  if (isset($_POST['pay'])) {
    echo $response['message'];
  }
  ?>
  <p>Click the button to make your NGN <?php echo number_format($response['data']['provider_response']['transaction_final_amount']); ?> payment</p>
  <form action="./collect.php" method="POST">
    <input type="submit" name="pay" value="Pay">
  </form>
  <p>Go to account <a href="./dashboard.php">dashboard</a> or <a href="./index.php">home page</a> </p>
</body>

</html>
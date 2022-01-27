<?php
$api_key = 'LYJkfbTERDaX9rEcHubF_d988c25b17504a9699140341edf54435';
$client_secret = 'KgMs87tKCuA8BTEj';
$request_ref = mt_rand(100000000, 999999999);
$signature = md5($request_ref . ";" . $client_secret);
$postData = [
  "request_ref" => strval($request_ref),
  "request_type" => "open_account",
  "auth" => [
    "type" => null,
    "secure" => null,
    "auth_provider" => "DemoProvider",
    "route_mode" => null
  ],
  "transaction" => [
    "mock_mode" => "live",
    "transaction_ref" => strval($request_ref),
    "transaction_desc" => "A random transaction",
    "transaction_ref_parent" => null,
    "amount" => 0,
    "customer" => [
      "customer_ref" => "Customer007",
      "firstname" => "James",
      "surname" => "Bond",
      "email" => "jamesbond@gmail.com",
      "mobile_no" => "2348123456789"
    ],
    "meta" => [
      "a_key" => "a_meta_value_1",
      "another_key" => "a_meta_value_2"
    ],
    "details" => [
      "name_on_account" => "James Bond",
      "middlename" => "Young",
      "otp_override" => true,
      "dob" => "2007-07-07-02-00-07",
      "gender" => "M",
      "title" => "Mr",
      "address_line_1" => "23, Okon street, Ikeja",
      "address_line_2" => "Ikeja",
      "city" => "lagos",
      "state" => "lagos",
      "country" => "NG"
    ]
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
    "Authorization: Bearer $api_key",
    "Signature: $signature",
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response,true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Open DemoProvider Account</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
</head>

<body>
    <?php

    if (isset($_POST['open_account'])) {
        echo ('<p> Welcome' . $response['data']['provider_response']['account_name'] . ', Thank you for choosing' . $response['data']['provider_response']['bank_name'] . '</p>');
        echo ('<p>Your request to open an account with us was ' . $response['status'] . 'and your account is ' . $response['data']['provider_response']['status'] . '</p>');
        echo ('<p>Your account number is:' . $response['data']['provider_response']['account_number'] . '</p>');
        echo ("<p>Go to account <a href='./dashboard.php'>dashboard</a> or <a href='./index.php'>home page</a> </p>");
    }
    ?>
    <section>
        <h2>Account Opening Form</h2>
        <form method="POST" id="" name="" action="./open_account.php"><br><br>
            <label for="title">Title:</label>
            <input type="radio" id="Mr" name="title" value="Mr">
            <label for="Mr">Mr</label>
            <input type="radio" id="Mrs" name="title" value="Mrs">
            <label for="Mrs">Mrs</label>
            <input type="radio" id="Ms" name="title" value="Ms">
            <label for="Ms">Ms</label><br><br>
            <label>Firstname:</label>
            <input type="text" required name="firstname" id="firstname" placeholder="Firstname" /><br><br>
            <label>Surname:</label>
            <input type="text" required name="surname" id="surname" placeholder="Surname" /><br><br>
            <label>Email: </label>
            <input type="email" required name="email" id="email" placeholder="Email" /><br><br>
            <label>Mobile Number: </label>
            <input type="tel" required name="mobile_no" id="mobile_no" placeholder="090 0000 0000" /><br><br>
            <label>Name on the account:</label>
            <input type="text" name="name_on_account" id="name_on_account" placeholder="Name on Account" /><br><br>
            <label>Date of birth: </label>
            <input type="date" required name="dob" id="dob" placeholder="yyyy-mm-dd-HH-mm-ss" /><br><br>
            <label>Address: </label>
            <textarea name="address_line_1" id="address_line_1" placeholder="Address 1"></textarea><br><br>
            <label for="city">City:</label>
            <select name="city" id="city">
                <option value="lagos">lagos</option>
                <option value="abuja">abuja</option>
            </select><br><br>
            <label for="state">State:</label>
            <select name="state" id="state">
                <option value="lagos">lagos</option>
                <option value="abuja">abuja</option>
            </select><br><br>
            <label for="country">Country:</label>
            <select name="country" id="country">
                <option value="NG">Nigeria</option>
            </select><br><br>
            <input type="submit" name="open_account" value="Open Account" />
    </section>
</body>

</html>
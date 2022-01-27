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
    "request_ref" => strval($request_ref),
    "request_type" => "get_statement",
    "auth" => [
        "type" => "bank.account",
        "secure" => strval(EncryptV2($client_secret, "2233305555;0001")),
        "auth_provider" => "Demoprovider",
        "route_mode" => null
    ],
    "transaction" => [
        "mock_mode" => "live",
        "transaction_ref" => strval($request_ref),
        "transaction_desc" => "A random transaction",
        "transaction_ref_parent" => null,
        "amount" => 0,
        "customer" => [
            "customer_ref" => "DemoApp_Customer007",
            "firstname" => "Uju",
            "surname" => "Usmanu",
            "email" => "ujuusmanu@gmail.com",
            "mobile_no" => "234802343132"
        ],
        "meta" => [
            "a_key" => "a_meta_value_1",
            "another_key" => "a_meta_value_2"
        ],
        "details" => [
            "start_date" => "2019-06-01",
            "end_date" => "2019-06-01",
            "otp_override" => true
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
$response = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statements</title>
</head>

<body>
    <h1>Statement of Account</h1>
    <p>Opening Balance: NGN <?php echo number_format($response['data']['provider_response']['opening_balance']); ?></p>
    <table>
        <tr>
            <th>Date</th>
            <th>Reference</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Credit= C; Debit = D</th>
            <th>Balance</th>
        </tr>
        <?php
        for ($i = 0; $i < count($response['data']['provider_response']['statement_list']); $i++) {
        echo ('<tr>');
           echo('<td>'.$response['data']['provider_response']['statement_list'][$i]['transaction_date'].'</td>'); 
           echo('<td>'.$response['data']['provider_response']['statement_list'][$i]['transaction_reference'].'</td>') ;
           echo('<td>'.$response['data']['provider_response']['statement_list'][$i]['description'].'</td>'); 
           echo('<td>'.$response['data']['provider_response']['statement_list'][$i]['transaction_amount'].'</td>') ;
           echo('<td>'.$response['data']['provider_response']['statement_list'][$i]['transaction_type'].'</td>') ;
           echo('<td>'.$response['data']['provider_response']['statement_list'][$i]['balance'].'</td>') ;
        echo ('</tr>');
        }
        ?>
    </table>
    <p>Closing Balance: NGN <?php echo number_format($response['data']['provider_response']['closing_balance']); ?></p>
    <p>Go to account <a href="./dashboard.php">dashboard</a> or <a href="./index.php">home page</a> </p>
</body>

</html>
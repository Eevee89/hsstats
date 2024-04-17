<?php

$token = "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"; // Replace with your actual GitHub access token

$headers = array(
  "Authorization: Bearer " . $token,
  "User-Agent: test.php (Simple script to access GitHub API) PHP7; Linux x64",
  "Accept: application/vnd.github.v3.raw"
);

$url = "https://api.github.com/repos/Eevee89/hs_datas/contents/datas.json?ref=main";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$response = trim($response);
$response = utf8_decode($response);
echo $response;
echo "\n";

if (curl_errno($ch)) {
  echo 'Error:' . curl_error($ch);
  echo "\n";
} else {
  // Process the response data
  $data = json_decode($response, true);
  var_dump($data);
  if ($data === NULL) {
    echo json_last_error_msg();
    echo "\n";
    exit(1);
  }
}

curl_close($ch);

?>

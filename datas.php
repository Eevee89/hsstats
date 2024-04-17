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

if (curl_errno($ch)) {
  echo 'Error:' . curl_error($ch);
  echo "\n";
  exit(1);
}
$data = json_decode($response, true);
if ($data === NULL) {
  echo json_last_error_msg();
  echo "\n";
  exit(1);
}

curl_close($ch);

//$data = json_decode(file_get_contents("datas.json"), true);

if (!$data) {
  echo "Error: Could not parse JSON data.";
  exit;
}

// Traite les données lues
function treatDatas($data) {
  $heros_name = explode("\n", file_get_contents("heros.txt"));
  $heros = [];
  foreach($heros_name as $name) {
    $heros[$name] = 0;
  }

  $dataPoints = []; // Points pour le graphique
  $index = 0;
  $mean = 0; // Côte moyenne
  $min = 10000; // Côte max
  $max = 0; // Côte min

  $types = [
    "Dragon"=> 0, 
    "Démon" => 0, 
    "Elementaire" => 0, 
    "Murloc" => 0, 
    "Huran" => 0, 
    "Mort-Vivant" => 0, 
    "Naga" => 0, 
    "Bête" => 0, 
    "Pirate" => 0, 
    "Méca" => 0,
  ]; // Occurence des différents types

  $ranks = [ 
    1 => 0, 
    2 => 0, 
    3 => 0, 
    4 => 0, 
    5 => 0, 
    6 => 0, 
    7 => 0, 
    8 => 0
  ]; // Occurence des 8 rangs

  foreach($data as $dataObject) {
    $yValue = $dataObject["cote"];
    array_push($dataPoints, array("x" => $index++, "y" => $yValue));
    $mean += $yValue;
    if ($yValue > $max) {
      $max = $yValue;
    }
    if ($yValue < $min) {
      $min = $yValue;
    }
    $types[$dataObject["type"]] += 1;
    $heros[$dataObject["hero"]] += 1;
    $ranks[$dataObject["rank"]] += 1;
  }

  $mean = (int)($mean/count($data));
  $treated = [
    "points" => $dataPoints,
    "mean" => $mean,
    "minimum" => $min,
    "maximum" => $max,
    "types" => $types,
    "heros" => $heros,
    "ranks" => $ranks,
  ];
  return $treated;
}

$datas = treatDatas($data);

function writeDatas($newData, $data) {
  $data[] = $newData;
  $jsonData = json_encode($data);
  file_put_contents("datas.json", $jsonData);
  return $data;
}

// Récupère les noms des 5 héros les plus joués
function bestHeros($heros) {
  arsort($heros);
  $keys = array_keys($heros);
  $output = [];
  for ($i=0; $i<5; $i++) {
    $output[] = $keys[$i];
  }
  return $output;
}

?>
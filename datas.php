<?php

// Assuming your JSON data has multiple objects like {"date": "...", "cote": "..."}
$data = json_decode(file_get_contents("datas.json"), true);

// Error handling (optional, but recommended)
if (!$data) {
  echo "Error: Could not parse JSON data.";
  exit;
}

function treatDatas($data) {
  $heros_name = explode("\n", file_get_contents("heros.txt"));
  $heros = [];
  foreach($heros_name as $name) {
    $heros[$name] = 0;
  }

  $dataPoints = [];
  $index = 0; // To keep track of x-axis index
  $mean = 0;
  $min = 10000;
  $max = 0;

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
  ];

  foreach($data as $dataObject) {
    // Assuming "cote" is the key for the value you want on the y-axis
    $yValue = $dataObject["cote"];  // Convert cote to thousands
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
  }

  $mean = (int)($mean/count($data));
  $treated = [
    "points" => $dataPoints,
    "mean" => $mean,
    "minimum" => $min,
    "maximum" => $max,
    "types" => $types,
    "heros" => $heros,
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
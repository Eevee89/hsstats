<?php

$type = ["Dragon", "Démon", "Elementaire", "Murloc", "Huran", "Mort-Vivant", "Naga", "Bête", "Pirate", "Méca"];
$heros = explode("\n", file_get_contents("heros.txt"));

$data = [];
for ($i = 0; $i < 50; $i++) {
  $data[] = [
    "date" => "15/04/2024",
    "cote" => rand(3000, 7000),
    "type" => $type[rand(0, 9)],
    "hero" => $heros[rand(0, 96)],
    "rank" => rand(1, 8),
    "img" => "",
  ];
}

$jsonData = json_encode($data, JSON_PRETTY_PRINT);

file_put_contents("datas.json", $jsonData);

echo "JSON file generated successfully!\n";

?>
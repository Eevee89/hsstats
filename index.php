<?php

$url = "https://api.github.com/repos/Eevee89/hs_datas/datas.json";
$accessToken = getenv('GITHUB_ACCESS_TOKEN'); // Assuming stored in environment variable

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken"
]);

$response = curl_exec($curl);
$data = json_decode($response, true);

curl_close($curl);

if (isset($data['error'])) {
    echo "Erreur de parsing";
} else {
    var_dump($data);
}

$dataPoints = array();
$y = 40;
for($i = 0; $i < 1000; $i++){
  $y += rand(0, 10) - 5; 
  array_push($dataPoints, array("x" => $i, "y" => $y));
}

?>
<!DOCTYPE HTML>
<html>
<head> 
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
  theme: "light2", // "light1", "light2", "dark1", "dark2"
  animationEnabled: true,
  zoomEnabled: true,
  title: {
    text: "Try Zooming and Panning"
  },
  data: [{
    type: "area",     
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});
chart.render();

}
</script>
</head>
  <body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  </body>
</html>                              
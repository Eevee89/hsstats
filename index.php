<?php
function isMobile() {
  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

$mobile = isMobile();

function myDiv($type, $value) {
  $myStr = $type . " : " . $value;
  return <<<HTML
    <div class="alert alert-success">
      <p>$myStr</p>
    </div>
HTML;
}


function redirectToNewPage() {
  header("Location: form.php");
  exit();
}

if (isset($_POST['redirect'])) {
  redirectToNewPage();
}

function selectTypes($datas) {
  $class = $mobile ? "selectMobile" : "selectDesktop";
  $output = "<select name='type' class='$class' >";
  foreach ($datas['types'] as $type => $value) {
    $output .= "<option value='$type'>$type</option>";
  }
  $output .= "</select>";
  return $output;
}

function selectHeros($heros) {
  $class = $mobile ? "selectMobile" : "selectDesktop";
  $output = "<select name='hero' class='$class' >";
  foreach ($heros as $hero) {
    $output .= "<option value='$hero'>$hero</option>";
  }
  $output .= "</select>";
  return $output;
}


require_once "datas.php";

function fetchDatas($data) {
  $newData = [
    "date" => time(),
    "cote" => (int)$_POST["cote"],
    "type" => $_POST["type"],
    "hero" => $_POST["hero"],
    "img" => $_POST["img"],
  ];
  return writeDatas($newData, $data);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['submit']) and $_POST['cote'] !== 0 and $_POST['hero'] !== null and $_POST['type'] !== null and $_POST['img'] !== null)
{
  $data = fetchDatas($data);
  $datas = treatDatas($data);
}

?>

<!DOCTYPE HTML>
<html>
<head>
  <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" 
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
  <style>
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
        border-radius: .25rem;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }

    body {
      margin: 0;
      font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: #212529;
      text-align: left;
      background-color: #fff;
    }

    button, input, select {
        overflow: visible;
    }

    button, input, optgroup, select, textarea {
        margin: 0;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }

    .buttonMobile, .inputMobile, .selectMobile {
      width: 150%;
      height: 75px;
    }

    .buttonDesktop, .inputDesktop, .selectDesktop {
      width: 150%;
      height: 50px;
    }
    
    form { 
      margin: 0 auto; 
      width:250px;
    }

    p {
      font-size: 2rem;
    }

    .mySlides {display: none;}
    img {vertical-align: middle;}

    /* Slideshow container */
    .slideshow-container {
      max-width: 1000px;
      position: relative;
      margin: auto;
    }


    /* Fading animation */
    .fade {
      animation-name: fade;
      animation-duration: 1.5s;
    }

    @keyframes fade {
      from {opacity: .4} 
      to {opacity: 1}
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {
      .text {font-size: 11px}
    }


  </style>
</head>

<body>
  </br>
  <script>
  window.onload = function () {

  var chart = new CanvasJS.Chart("chartContainer", {
    theme: "light2", // "light1", "light2", "dark1", "dark2"
    animationEnabled: true,
    zoomEnabled: true,
    title: {
      text: "HearthStone Battlefield S7 Cote"
    },
    axisY: {
      minimum: 0,
      maximum: 10000
    },
    data: [{
      type: "area",     
      dataPoints: <?php echo json_encode($datas["points"], JSON_NUMERIC_CHECK); ?>
    }]
  });
  chart.render();

  }
  </script>
  <?php if ($mobile) : ?>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    </br>
    <div class="row">
      <div class="alert alert-success">
        <?= "Cote moyenne : " . $datas["mean"] ?>
      </div>
      <div class="alert alert-success">
        <?= "Minimum : " . $datas["minimum"] ?>
      </div>
      <div class="alert alert-success">
        <?= "Maximum : " . $datas["maximum"] ?>
      </div>
    </div>
    </br>

    <div class="row">
      <?php 
        foreach($datas["types"] as $type => $value) {
          if (!($type === "Mort-Vivant" || $type === "Naga" || $type === "Bête" || $type === "Pirate" || $type === "Méca")) {
            echo myDiv($type, $value);
          }
        }
      ?>
    </div>
    <div class="row">
      <?php 
        foreach($datas["types"] as $type => $value) {
          if (!($type === "Dragon" || $type === "Démon" || $type === "Elementaire" || $type === "Murloc" || $type === "Huran")) {
            echo myDiv($type, $value);
          }
        }
      ?>
    </div>


  <?php else : ?> <!-- PC -->
    <div class="row">
      <div id="chartContainer" style="height: 370px; width: 80%;"></div>
      <div>
        <div class="alert alert-success">
          <?= "Cote moyenne : " . $datas["mean"] ?>
        </div>
        <div class="alert alert-success">
          <?= "Minimum : " . $datas["minimum"] ?>
        </div>
        <div class="alert alert-success">
          <?= "Maximum : " . $datas["maximum"] ?>
        </div>
      </div>
    </div>

    </br>

    <div class="row">
      <?php 
        foreach($datas["types"] as $type => $value) {
          echo myDiv($type, $value);
        }
      ?>
    </div>
  <?php endif ?>

  </br>
  <div class="row">
    <?php 
      foreach(bestHeros($datas["heros"]) as $hero) {
        echo myDiv($hero, $datas["heros"][$hero]);
      }
    ?>
  </div>

    </br>

  <form action="/index.php" method="POST">
    <p>Héros</p>
    <?= selectHeros(array_keys($datas["heros"])) ?>

    <p>Côte</p>
    <input type="number" name="cote" placeholder="6932" class=<?php echo $mobile ? 'inputMobile' : 'inputDesktop'; ?> />

    <p>Type</p>
    <?= selectTypes($datas) ?>

    <p>Plateau</p>
    <input type="text" name="img" placeholder="IMG-15-04-2024-15-42-35.png" class=<?php echo $mobile ? 'inputMobile' : 'inputDesktop'; ?> />

    </br>
    </br>
    <input class=<?php echo $mobile ? 'inputMobile' : 'inputDesktop'; ?> type="submit" value="Submit" name="submit"/>
  </form>

  </br>

  <div class="slideshow-container">

  <div class="mySlides fade">
    <img src="https://i.ibb.co/j8xkk05/05-03-23-21-13-51.png" style="width:100%">
  </div>
  
  <div class="mySlides fade">
    <img src="https://i.ibb.co/wgy3y1t/03-03-24-18-04-34.png" style="width:100%">
  </div>
  
  <div class="mySlides fade">
    <img src="https://i.ibb.co/h9Sh4Lz/03-23-24-00-33-02.png" style="width:100%">
  </div>

  </div>
  <br>

  <script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
      let i;
      let slides = document.getElementsByClassName("mySlides");
      console.log(slides);
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
      }
      slideIndex++;
      console.log(slideIndex);
      if (slideIndex > slides.length) {slideIndex = 1}    
      slides[slideIndex-1].style.display = "block";  
      setTimeout(showSlides, 3000); // Change image every 2 seconds
    }
  </script>

  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>
<?php
  require("functions.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Slide Settings</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
  </head>
  <body>
    <?php
      printSidebar("slideSettings.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container my-4">
        <div class="mb-4">
          <h2>Slide Settings</h2>
          <hr>
          <div class="mb-3">
            <h5>Student Picture Interval Duration (ms)</h5>
            <?php
              $interval = getSlideInterval();
              echo "<input type='number' id='intervalDuration' class='form-control' value='".$interval."'>";
            ?>
          </div>
          <div class="row mb-5">
            <div class="col">
              <h5>Slide Background Colour</h5>
              <?php
                $slideBackgroundColour = getSlideBackgroundColour();
                echo "<input type='color' id='slideBackgroundColour' class='form-control w-50 h-100' value='".htmlspecialchars($slideBackgroundColour, ENT_QUOTES)."'>";
              ?>
            </div>
            <div class="col">
              <h5>Slide Text Colour</h5>
              <?php
                $slideTextColour = getSlideTextColour();
                echo "<input type='color' id='slideTextColour' class='form-control w-50 h-100' value='".htmlspecialchars($slideTextColour, ENT_QUOTES)."'>";
              ?>
            </div>
          </div>
          <button type="button" class="btn btn-success" onclick="updateSettings()">Update</button>
        </div>
        <div class="mb-4">
          <h2>Preview</h2>
          <hr>
          <div class="ratio ratio-16x9 border border-dark mb-3">
            <iframe src="slideshow.php"></iframe>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      function updateSettings(){
        var intervalDuration = encodeURIComponent(document.getElementById("intervalDuration").value);
        var slideBackgroundColour = encodeURIComponent(document.getElementById("slideBackgroundColour").value);
        var slideTextColour = encodeURIComponent(document.getElementById("slideTextColour").value);

        var request = new XMLHttpRequest();
        request.onload = function() {
          window.location.reload();
        }
        request.open("GET", "ajax.php?table=settings&action=updateSlideSettings&intervalDuration="+intervalDuration+"&slideBackgroundColour="+slideBackgroundColour+"&slideTextColour="+slideTextColour);
        request.send();
      }
    </script>
  </body>
</html>

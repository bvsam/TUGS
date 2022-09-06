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
    <title>Announcements</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
  </head>
  <body>
    <?php
      printSidebar("announcements.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container mt-3 mb-5">
        <div class="mb-3">
          <h3>Student Deadline</h3>
          <?php
            $currentDeadline = getDeadlineDate();
            echo "<input type='date' id='deadline' class='form-control' value='".htmlspecialchars($currentDeadline, ENT_QUOTES)."'>";
          ?>
        </div>
        <button type="button" class="btn btn-success" onclick="updateDeadline()">Update</button>
      </div>

      <div class="container my-3">
        <?php
          $announcement = getAnnouncement();
          $announcementName = $announcement["announcementName"];
          $announcementValue = $announcement["announcementValue"];
          $splitAnnouncementValue = explode('\\n', $announcementValue);
        ?>
        <div class="mb-3">
          <h3>Announcement Name</h3>
          <hr>
          <?php
            echo "<input type='text' id='announcementName' class='form-control bg-light mb-4' value='".htmlspecialchars($announcementName, ENT_QUOTES)."'>";
          ?>
        </div>
        <div class="mb-3">
          <h3>Announcement Text</h3>
          <hr>
          <?php
            echo "<textarea class='form-control' id='announcementValue' rows='10'>";
            foreach ($splitAnnouncementValue as $key => $val){
              if ($val == "" && $key != count($splitAnnouncementValue) - 1){
                echo "\n";
              }
              else {
                if ($key != count($splitAnnouncementValue) - 1){
                  echo $val."\n";
                }
                else {
                  echo $val;
                }
              }
            }
            echo "</textarea>";
          ?>
        </div>
        <button type="button" class="btn btn-success" onclick="updateAnnouncements()">Update</button>
        <button type="button" class="btn btn-info" onclick="clearInputs()">Clear</button>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      function updateAnnouncements(){
        var announcementName = encodeURIComponent(document.getElementById("announcementName").value);
        var announcementValue = document.getElementById("announcementValue").value;

        announcementValue = encodeURIComponent(announcementValue.split("\n").join("\\n"));

        var request = new XMLHttpRequest();

        request.open("GET", "ajax.php?table=settings&action=updateAnnouncements&announcementName=" + announcementName + "&announcementValue=" + announcementValue);
        request.send();

        alert("Updated successfully!");
      }

      function updateDeadline(){
        var deadline = encodeURIComponent(document.getElementById("deadline").value);

        var request = new XMLHttpRequest();

        request.open("GET", "ajax.php?table=settings&action=updateDeadline&newDeadline=" + deadline);
        request.send();

        alert("Updated successfully!");
      }

      function clearInputs(){
        document.getElementById("announcementName").value = "";
        document.getElementById("announcementValue").value = "";
      }
    </script>
  </body>
</html>

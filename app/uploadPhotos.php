<?php
  //Very large .zip file uploads can cause the file to not be uploaded (with current server settings)
  require("functions.php");
  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();

  $year = getCurrentYear();
  $pics = array();
  $failed = array();
  $fullPath = getFullPath();
  $tempPath = $fullPath."temp/";
  $name = $_FILES['uploadedPics']['name'];
  $nameNoZip = explode(".zip", $name)[0]."/";
  $zip = new ZipArchive;
  $delimeter = $_POST["delimeter"];
  $shortPath = $fullPath.$year."/";
  $zipPath = $fullPath.$name;
  $gradeSpecifier = "";

  move_uploaded_file($_FILES['uploadedPics']['tmp_name'], $zipPath);

  if ($zip->open($zipPath) === TRUE) {
      $zip->extractTo($tempPath);
      $zip->close();
  }

  $listing = scandir($tempPath.$nameNoZip);

  for($x=2; $x<count($listing); $x++) {
    $pics[$x-2] = $listing[$x];
  }

  if(!is_dir($fullPath.$year)){
    mkdir($fullPath.$year, 0777, true);
  }

  if($_POST["photoGrade"] == "Grade 9"){
    $gradeSpecifier = "_a";
  }
  elseif ($_POST["photoGrade"] == "Grade 10") {
    $gradeSpecifier = "_b";
  }
  elseif ($_POST["photoGrade"] == "Grade 11") {
    $gradeSpecifier = "_c";
  }
  elseif ($_POST["photoGrade"] == "Grade 12") {
    $gradeSpecifier = "_d";
  }
  elseif ($_POST["photoGrade"] == "Graduation") {
    $gradeSpecifier = "_e";
  }

  foreach ($pics as $pic) {
    copy($tempPath.$nameNoZip.$pic, $shortPath.$pic);
    $fullName = explode(".jpg", $pic)[0];

    if ($_POST["nameOrder"] == "firstName"){
      $firstName = explode($delimeter, $fullName)[0];
      $lastName = explode($delimeter, $fullName)[1];
    }
    elseif ($_POST["nameOrder"] == "lastName") {
      $firstName = explode($delimeter, $fullName)[0];
      $lastName = explode($delimeter, $fullName)[1];
    }

    $user = matchStudentByName($lastName, $firstName, $year);

    if (count($user) == 0){
      array_push($failed, $pic);
    }
    else {
      rename($shortPath.$pic, $shortPath.$user[0]["stud_num"].$gradeSpecifier.".jpg");
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Match Unpaired Student Photos</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/b-2.2.2/datatables.min.css"/>
  </head>
  <body>
    <?php
      printSidebar("studentList.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container my-4">
        <?php
          echo "<h1>Failed Matches - ".$_POST["photoGrade"]." (".$year.")</h1>";
          echo "<hr>";
          $numPerRow = 6;
          $counter = 0;
          $studentPicsPath = getStudentPicsPath();
          echo "<div class='row mb-4'>";
          foreach ($failed as $failedPic) {
            echo "<div id='".htmlspecialchars($failedPic, ENT_QUOTES)."' class='col text-center cardColumn'>";
            echo "<div class='card'>";
            echo "<img class='center-block w-100 card-img-top' src='".$studentPicsPath.$year."/".$failedPic."' alt='".$failedPic."'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>".$failedPic."</h5>";
            echo "<button type='button' class='btn btn-primary m-2' data-bs-toggle='modal' data-bs-target='#matchingModal' onclick='updateSelectedPic(\"".htmlspecialchars($failedPic, ENT_QUOTES)."\")'>Match</button>";
            echo "<button type='button' class='btn btn-danger m-2' onclick='deletePic(this)'>Delete</button>";
            echo "</div>";

            echo "</div>";
            echo "</div>";
            $counter++;
            if ($counter >= $numPerRow) {
              echo "</div>";
              echo "<div class='row'>";
              $counter = 0;
            }
          }
          echo "</div>";
        ?>
      </div>

      <div class="modal fade" id="matchingModal" tabindex="-1" aria-labelledby="matchingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title" id="matchingModalLabel">Match this picture to a student</h2>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <table class="table table-dark table-striped my-3" id="studentTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Student Number</th>
                    <th>Slide Status</th>
                    <th></th>
                    <?php
                      echo "<th>Has ".$_POST["photoGrade"]." Photo</th>";
                    ?>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $students = getAllStudents($year);
                  foreach ($students as $student) {
                    echo "<tr>\n";

                    echo "<td>\n";
                    echo "<a href='editStudInfo.php?stud_id=".$student['stud_id']."'>".$student['stud_lname'].", ".$student['stud_fname']."</a>";
                    echo "</td>\n";

                    echo "<td>\n".$student['stud_num']."</td>\n";

                    if ($student['stud_enabled'] == 0){
                      echo "<td data-order='0'>\n";
                      echo "<button type='button' id='".$student['stud_id']."' onclick='updateStudent(this.id)' class='btn btn-danger'></button>\n";
                      echo "</td>\n";
                    }
                    elseif ($student['stud_enabled'] == 1){
                      echo "<td data-order='1'>\n";
                      echo "<button type='button' id='".$student['stud_id']."' onclick='updateStudent(this.id)' class='btn btn-success'></button>\n";
                      echo "</td>\n";
                    }
                    echo "<td><button type='button' class='btn btn-success' data-bs-dismiss='modal' onclick='matchStudent(this, \"".$student["stud_num"]."\", \"".$shortPath."\", \"".$student["stud_num"].$gradeSpecifier.".jpg\")'>Match</button></td>";

                    if(file_exists($shortPath.$student["stud_num"].$gradeSpecifier.".jpg")){
                      echo "<td id='".$student["stud_num"]."' data-order='1'>Yes</td>";
                    }
                    else {
                      echo "<td id='".$student["stud_num"]."' data-order='0'>No</td>";
                    }

                    echo "</tr>\n\n";
                  }
                ?>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.11.5/b-2.2.2/datatables.min.js"></script>
    <script src='navbar.js'></script>
    <script>
      var selectedPic;
      var table;

      $(document).ready(function(){
          table = $("#studentTable").DataTable();
      });

      function updateSelectedPic(newPic){
          selectedPic = newPic;
      }

      function deletePic(element){
        var cardColumn = element.parentNode.parentNode.parentNode;
        if (cardColumn.classList.contains("cardColumn")){
          cardColumn.remove();
        }
      }

      function matchStudent(element, studentNumber, picturePath, newPictureName){
        var request = new XMLHttpRequest();
        request.onload = function(){
          var studentStatus = document.getElementById(studentNumber);
          document.getElementById(selectedPic).remove();
          studentStatus.innerHTML = "Yes";
          studentStatus.dataset.order = "1";
          table.cell(studentStatus).invalidate().draw();
        }
        var query = "ajax.php?table=students&action=matchStudent&picturePath="+encodeURIComponent(picturePath)+"&selectedPic="+encodeURIComponent(selectedPic)+"&newPictureName="+encodeURIComponent(newPictureName);
        request.open("GET", query);
        request.send();
      }

      function updateStudent(clicked_id){
        var clickedButton = document.getElementById(clicked_id);

        var request = new XMLHttpRequest();
        request.onload = function() {
          if (clickedButton.classList.contains("btn-danger")) {
            clickedButton.classList.remove("btn-danger");
            clickedButton.classList.add("btn-success");
          }
          else if (clickedButton.classList.contains("btn-success")) {
            clickedButton.classList.remove("btn-success");
            clickedButton.classList.add("btn-danger");
          }
        }
        request.open("GET", "ajax.php?table=students&action=updateEnabled&studid=" + clicked_id);
        request.send();
      }
    </script>
  </body>
</html>

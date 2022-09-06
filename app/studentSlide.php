<?php
  require("functions.php");
  require("database.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();

  $student = getStudInfo($_GET['stud_id'])[0];
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>Graduation Slideshow</title>
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
    <style media="screen">
      <?php
        $slideBackgroundColour = getSlideBackgroundColour();
        $slideTextColour = getSlideTextColour();
        echo "
        * {
          color: ".htmlspecialchars($slideTextColour, ENT_QUOTES)." !important;
          background-color: ".htmlspecialchars($slideBackgroundColour, ENT_QUOTES)." !important;
        }";
        echo "
        #slideContent {
          color: ".htmlspecialchars($slideTextColour, ENT_QUOTES)." !important;
          background-color: ".htmlspecialchars($slideBackgroundColour, ENT_QUOTES)." !important;
        }\n";
      ?>
      li {
        list-style-type: square;
      }
    </style>
  </head>
  <body>
    <div id="slideContent" class="bg-dark text-white vh-100 overflow-hidden py-3">
      <div class="row">
        <div class="col-3">
          <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center ps-3">
            <div class="carousel slide" data-bs-ride="carousel" data-bs-wrap="false">
              <div class="carousel-inner">
                <?php
                  $fullPath = getFullPath();
                  $picsPath = $fullPath.$student['stud_year']."/";
                  $studentPicsPath = getStudentPicsPath();
                  $imagePath = $studentPicsPath.$student['stud_year']."/";
                  $pictureInfo = array(
                    "Grade 9" => "_a",
                    "Grade 10" => "_b",
                    "Grade 11" => "_c",
                    "Grade 12" => "_d",
                    "Graduation" => "_e",
                  );
                  foreach ($pictureInfo as $grade => $gradeSpecifier) {
                    $pictureName = $student['stud_num'].$gradeSpecifier.".jpg";

                    if (file_exists($picsPath.$pictureName)){
                      $pictureInfo[$grade] = $imagePath.$pictureName;
                    }
                    else {
                      unset($pictureInfo[$grade]);
                    }
                  }
                  $slideInterval = getSlideInterval();
                  $firstItem = array_key_first($pictureInfo);
                  foreach ($pictureInfo as $grade => $gradePicture) {
                    if ($grade == $firstItem){
                      echo "<div class='carousel-item active' data-bs-interval='".$slideInterval."'>";
                    }
                    else {
                      echo "<div class='carousel-item' data-bs-interval='".$slideInterval."'>";
                    }
                    echo "<img src='".htmlspecialchars($gradePicture, ENT_QUOTES)."' class='d-block w-100' alt='".$grade."'>";
                    echo "</div>";
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-9">
          <div class="row">
            <div class="col-1">
              <img src="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png" class="img-fluid">
            </div>
            <div class="col-11">
              <h1 class="display-3">
                <?php
                  echo "West Carleton Secondary School Class of ".$student["stud_year"];
                ?>
              </h1>
            </div>
          </div>
          <div class="row">
            <h1 class="display-2 text-center">
              <?php
                echo $student["stud_fname"]." ".$student["stud_lname"];
              ?>
            </h1>
          </div>
          <hr>
          <div class="row">
            <?php
              $awards = json_decode($student["stud_awards"], true);
              $scholarships = json_decode($student["stud_scholarships"], true);

              if (isset($awards) && count($awards) > 0){
                foreach ($awards as $award) {
                  echo "<li class='col-6 display-6'>".$award."</li>";
                }
              }
              if (isset($scholarships) && count($scholarships) > 0){
                foreach ($scholarships as $scholarship) {
                  echo "<li class='col-6 display-6'>".$scholarship."</li>";
                }
              }
            ?>
          </div>
          <hr>
          <div class="row">
            <h1 class="display-5 text-center">
              <?php
                $plans = json_decode($student["stud_plans"], true);

                echo "Future Plans: ";
                if (isset($plans) && count($plans) > 1){
                  if ($plans[0] == "University" || $plans[0] == "College"){
                    echo $plans[2]." at ".$plans[1];
                  }
                  elseif ($plans[0] == "Work") {
                    echo $plans[1];
                  }
                }
              ?>
            </h1>
          </div>
          <hr>
          <div class="row">
            <h1 class="display-6 mb-3">Memorable Moments:</h1>
            <div class="container lead">
              <?php
                echo $student["stud_memMoments"];
              ?>
            </div>
          </div>
           <div class="position-fixed end-0 bottom-0 px-2 pt-2">
             <?php
               $year = $student["stud_year"];
               $stud_id = $_GET['stud_id'];
               $allStudents = specStudents($year);
               foreach ($allStudents as $key => $student) {
                 if ($student["stud_enabled"] != 1) {
                   unset($allStudents[$key]);
                   array_values($allStudents);
                 }
               }
               function compareByName($allStudents, $b) {
                 return strcmp($allStudents["stud_lname"], $b["stud_lname"]);
               }
               usort($allStudents, 'compareByName');
               $i = 0;
               foreach ($allStudents as $key) {
                 if ($key['stud_id'] == $stud_id) {
                   $realStudNum = $i;
                   break;
                 }
                 $i++;
               }
               if ($i == array_key_last($allStudents)) {
                 echo "Final Student";
               }
               elseif (isset($allStudents[($i+1)])) {
                 echo "Next Slide: ".$allStudents[($i+1)]['stud_fname']." ".$allStudents[($i+1)]['stud_lname'];
               }
              ?>
           </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
 </body>
</html>

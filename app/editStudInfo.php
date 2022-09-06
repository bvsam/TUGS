<?php
  require("functions.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();

  $stud_id = $_GET['stud_id'];
  $student = getStudInfo($stud_id)[0];
  $studLoginInfo = getStudentLoginInfo($stud_id);
  $year = getCurrentYear();
  $allStudents = specStudents($year);
  function compareByName($allStudents, $b) {
    return strcmp($allStudents["stud_lname"], $b["stud_lname"]);
  }
  usort($allStudents, 'compareByName');
  $studIndex = 0;
  foreach ($allStudents as $key) {
    if ($key['stud_id'] == $stud_id) {
      $realStudNum = $studIndex;
      break;
    }
    $studIndex++;
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
    <link rel = "stylesheet" href = "navbar.css">
    <title>
      <?php
        $studName = $student['stud_fname']." ".$student['stud_lname'];
        echo $studName;
      ?>
    </title>
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
    <style media="screen">
      .deleteDivButtons{
        background-color: transparent;
        border: none;
        float: right;
      }
    </style>
    <script src="https://cdn.tiny.cloud/1/t03uf5ormjh22cia4fb49getjm982kfb7ddhy8izfr6o9zpf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: "#memMoments"
      });
    </script>
  </head>
  <body>
    <?php
      printSidebar();
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container my-3">
        <!-- Student Name -->
        <div class="d-flex justify-content-start mt-5 mb-3">
          <?php
            if (isset($allStudents[($studIndex-1)]['stud_id'])) {
              echo "<button type='button' class='btn btn-info me-4 text-light fw-bold' onclick='location.href=\"editStudInfo.php?stud_id=".$allStudents[($studIndex-1)]['stud_id']."\";'><</button>";
            }
            echo "<h1>".$student['stud_fname'].", ".$student['stud_lname']."</h1>";
            if (isset($allStudents[($studIndex+1)]['stud_id'])) {
              echo "<button type='button' class='btn btn-info ms-4 text-light fw-bold' onclick='location.href=\"editStudInfo.php?stud_id=".$allStudents[($studIndex+1)]['stud_id']."\";'>></button>";
            }
          ?>

        </div>
        <div class="d-flex justify-content-start mb-3">
          <h4 class="me-4">Slide Status:</h4>
          <?php
            if ($student['stud_enabled'] == 0){
              echo "<button type='button' id='".$student['stud_id']."' onclick='updateStudentEnabled(this.id)' class='btn btn-danger'></button>\n";
            }
            elseif ($student['stud_enabled'] == 1){
              echo "<button type='button' id='".$student['stud_id']."' onclick='updateStudentEnabled(this.id)' class='btn btn-success'></button>\n";
            }
          ?>
        </div>
        <hr>

        <!-- Student Pictures -->
        <div class="row mt-4 mb-1">
          <?php
            $fullPath = getFullPath();
            $picsPath = $fullPath.$student['stud_year']."/";
            $pictureInfo = array(
              "Grade 9" => "_a",
              "Grade 10" => "_b",
              "Grade 11" => "_c",
              "Grade 12" => "_d",
              "Graduation" => "_e",
            );
            $pictureStatus = $pictureInfo;
            $studentPicsPath = getStudentPicsPath();
            foreach ($pictureInfo as $grade => $gradeSpecifier) {
              $pictureName = $student['stud_num'].$gradeSpecifier.".jpg";

              echo "<div class='col text-center'>";
              if (!file_exists($picsPath.$pictureName)){
                echo "<img class='img-fluid' src='Class of 20XX Emblem.png' alt='Missing Student Picture Placeholder'>";
                echo "<h2>(N/A)</h2>";
                $pictureStatus[$grade] = false;
              }
              else {
                echo "<img class='img-fluid' src='".$studentPicsPath.$student['stud_year']."/".htmlspecialchars($pictureName, ENT_QUOTES)."' alt='".htmlspecialchars($studName, ENT_QUOTES)."'>";
                $pictureStatus[$grade] = true;
              }
              echo "</div>";
            }
          ?>
        </div>
        <div class="row mb-4">
          <?php
            foreach ($pictureStatus as $grade => $picAvailable) {
              $pictureName = $student['stud_num'].$pictureInfo[$grade].".jpg";

              echo "<div class='col text-center'>";
              echo "<h5>".$grade."</h5>";
              if ($picAvailable) {
                echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#removePictureModal' onclick='currentPicturePath = \"".$picsPath.htmlspecialchars($pictureName, ENT_QUOTES)."\"'>Remove Picture</button>";
              }
              else {
                echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#addPictureModal' onclick='changeUploadedPicInfo(\"".$grade."\")'>Add Picture</button>";
              }
              echo "</div>";
            }
          ?>
        </div>
        <hr>

        <!-- Basic Student Info. -->
        <div class="row mb-4">
          <div class="col-lg">
            <h3>First Name</h3>
            <?php
              echo "<input type='text' id='fname' class='form-control' value='".htmlspecialchars($student['stud_fname'], ENT_QUOTES)."'>";
            ?>
          </div>
          <div class="col-lg">
            <h3>Last Name</h3>
            <?php
              echo "<input type='text' id='lname' class='form-control' value='".htmlspecialchars($student['stud_lname'], ENT_QUOTES)."'>";
            ?>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-lg">
            <h3>Student Number</h3>
            <?php
              echo "<input type='number' id='studNum' class='form-control' value='".$student['stud_num']."'>";
            ?>
          </div>
          <div class="col-lg">
            <h3>E-Mail</h3>
            <?php
              if (isset($studLoginInfo['login_email'])) {
                echo "<input type='email' id='email' class='form-control' value='".htmlspecialchars($studLoginInfo['login_email'], ENT_QUOTES)."'>";
              }
              else {
                echo "<input type='email' id='email' class='form-control' placeholder='Please Enter Email'>";
              }
            ?>
          </div>
        </div>

        <!-- Student Password Reset -->
        <div class="row mb-5">
          <div class="col-lg">
            <h3>Password</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePWModal" type="button">Reset Password</button>
          </div>
        </div>

        <!-- Student Awards Section -->
        <div class="row mb-5">
          <div class="col">
            <h3 class="mb-3">Awards</h3>
            <div id='awardsDivParent'>
              <div id='awardsDivChild'>
                <?php
                $awardsArr = json_decode($student["stud_awards"], true);
                if (empty($awardsArr)){
                  echo "<h5 id='noAwards'>".$student['stud_fname']." ".$student['stud_lname']." has no awards yet.</h5>";
                }
                else {
                  foreach ($awardsArr as $award) {
                    echo "<div data-value='".htmlspecialchars($award, ENT_QUOTES)."' class='form-control mb-3 studentAward'>".htmlspecialchars($award, ENT_QUOTES)."<button class='deleteDivButtons' onclick='removeItem(this)'>&#x2715;</button></div>\n\t\t";
                  }
                }
                ?>
              </div>
              <select class='form-select mb-3' id='awardsDropdown' onchange='checkAwardType()'>
                <option value='' disabled selected hidden>--Please Select--</option>
                <?php
                  $awards = getAwards();
                  foreach ($awards as $award) {
                    echo "<option value='".htmlspecialchars($award["award_name"], ENT_QUOTES)."'>".$award["award_name"]."</option>";
                  }
                ?>
                <option value='Other'>Other</option>
              </select>
            </div>
          </div>

          <!-- Student Future Plans Section -->
          <div class="col" id='plansDiv'>
            <h3 class="mb-3">Future Plans</h3>
              <?php
                $plansArr = json_decode($student["stud_plans"], true);
                $plans = array("University","College","Victory Lap","Work");

                if (empty($plansArr)){
                  echo "\n\t\t<h5 id='noPlans'>".$student['stud_fname']." ".$student['stud_lname']." has no plans yet.</h5>";
                }
                echo "<select class='form-select mb-3' id='futurePlansDropdown' onchange='changeFuturePlans()'>\n\t\t\t";
                echo "<option hidden>--Please Select--</option>\n\t\t\t";
                foreach ($plans as $plan) {
                  if (isset($plansArr[0]) && $plan == $plansArr[0]){
                    echo "<option selected>".$plan."</option>\n\t\t\t";
                  }
                  else {
                    echo "<option>".$plan."</option>\n\t\t\t";
                  }
                }
                echo "</select>\n\t";

                if (isset($plansArr[1])) {
                  echo "<input type='text' class='form-control mb-3' id='plansWhere' value='".htmlspecialchars($plansArr[1], ENT_QUOTES)."' placeholder='Where?'>\n\t\t";
                }
                else {
                  echo "<input type='text' class='form-control mb-3' id='plansWhere' value='' placeholder='Where?' readonly>\n\t\t";
                }
                if (isset($plansArr[2])) {
                  echo "<input type='text' class='form-control mb-3' id='plansWhat' value='".htmlspecialchars($plansArr[2], ENT_QUOTES)."' placeholder='What?'>\n\t\t";
                }
                else {
                  echo "<input type='text' class='form-control mb-3' id='plansWhat' value='' placeholder='What?' readonly>\n\t\t";
                }
              ?>
          </div>
        </div>

        <!-- Student Scholarships Section -->
        <h3>Scholarships</h3>
        <div id='scholarshipDiv'>
          <?php
          $scholarships = json_decode($student["stud_scholarships"], true);
          if (empty($scholarships)){
            echo "<h5 id='noScholarships'>".$student['stud_fname']." ".$student['stud_lname']." has no scholarships yet.</h5>\n\t";
          }
          else {
            foreach ($scholarships as $scholarship) {
              echo "<div data-value='".htmlspecialchars($scholarship, ENT_QUOTES)."' class='form-control mb-3 studentScholarship'>".$scholarship."<button class='deleteDivButtons' onclick='removeItem(this)'>&#x2715;</button></div>\n\t\t";
            }
          }
          ?>
        </div>
        <input type='text' id='scholarshipInput' class='form-control mb-1' placeholder='Enter New Scholarship'>

        <!-- Student Memorable Moments Section -->
        <div class="my-4">
          <h3>Memorable Moments</h3>
          <textarea class="form-control" id="memMoments">
            <?php
              echo  $student['stud_memMoments'];
            ?>
          </textarea>
        </div>

        <!-- Preview Slide and Save buttons -->
         <?php
          echo "<a href='studentSlide.php?stud_id=".$student['stud_id']."' target='_blank' class='btn btn-primary'>Preview Slide</a>";
         ?>
        <button type="button" class="btn btn-success" onclick="updateStudentInfo()">Save Changes</button>

        <!-- Modals -->
        <div class="modal" id="changePWModal">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <?php
                    echo "<h2>Reset ".$student["stud_fname"]." ".$student["stud_lname"]."'s Password</h2>";
                  ?>
                </div>
                <div class="modal-body">
                  <div>
                    <h4>New Password:</h4>
                    <input type="password" id="password1" class="form-control bg-light mb-4" value="">
                    <input type="checkbox" id='word1' class="mb-4" onclick="showPassword(this.id)">
                    Show Password
                  </div>
                  <div>
                    <h4>Confirm New Password:</h4>
                    <input type="password" id="password2" class="form-control bg-light mb-4" value="">
                    <input type="checkbox" id='word2' class="mb-4" onclick="showPassword(this.id)">
                    Show Password
                  </div>
                </div>
                <div id='errorDiv'></div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success"  data-bs-dismiss="modal" onclick="updatePassword()">Reset</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="uniqueAwardModal" tabindex="-1" aria-labelledby="uniqueAwardModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="uniqueAwardModalLabel">Add a Unique Award</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <h4>Unique Award Name:</h4>
                <input class="form-control my-4" id="uniqueAwardName" type="text" value="">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="addUniqueAward()">Add Award</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="removePictureModal" tabindex="-1" aria-labelledby="removePictureModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="removePictureModalLabel">Confirm Picture Removal</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p><b>Are you sure that you would like to remove this picture? Deleting this picture cannot be undone.</b></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="removeSelectedPicture()">Confirm</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="addPictureModal" tabindex="-1" aria-labelledby="addPictureModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <?php
                echo "<form action='editStudPics.php?stud_id=".$student["stud_id"]."' method='post' enctype='multipart/form-data'>";
              ?>
                <div class="modal-header">
                  <h3 class="modal-title" id="addPictureModalLabel">Add a photo</h3>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <h4 class="mb-3">Upload a single photo below.</h4>
                  <input class="form-control mb-3" type="file" name="uploadedPic">
                  <input id="uploadedPicNewPath" class="form-control mb-3" type="text" name="newPath" value="" hidden>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success">Save changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      <?php
        echo "let studentID = ".$student["stud_id"].";\n";
        echo "let currentStudentNumber = \"".$student["stud_num"]."\";\n";
        echo "let currentAwards = ".$student["stud_awards"].";\n";
        echo "let currentScholarships = ".$student["stud_scholarships"].";\n";
        echo "let basePath = \"".$picsPath."\";";
      ?>
      let awardsDivChild = document.getElementById("awardsDivChild");
      let awardsDropdown = document.getElementById('awardsDropdown');
      let scholarshipDiv = document.getElementById('scholarshipDiv');
      let plansWhere = document.getElementById("plansWhere");
      let plansWhat = document.getElementById("plansWhat");
      let currentPicturePath;

      function changeUploadedPicInfo(year){
        if (year == "Grade 9") {
          newPictureName = currentStudentNumber + "_a.jpg"
        }
        else if (year == "Grade 10") {
          newPictureName = currentStudentNumber + "_b.jpg"
        }
        else if (year == "Grade 11") {
          newPictureName = currentStudentNumber + "_c.jpg"
        }
        else if (year == "Grade 12") {
          newPictureName = currentStudentNumber + "_d.jpg"
        }
        else if (year == "Graduation") {
          newPictureName = currentStudentNumber + "_e.jpg"
        }

        document.getElementById("uploadedPicNewPath").value = basePath+newPictureName;
      }

      function removeSelectedPicture(){
        var request = new XMLHttpRequest();
        request.onload = function() {
          window.location.reload(true);
        }
        let query = "ajax.php?table=students&action=removePicture&picturePath="+encodeURIComponent(currentPicturePath);
        request.open("GET", query);
        request.send();
      }

      $("#scholarshipInput").on('keyup', function (e) {
        let scholarshipInput = document.getElementById("scholarshipInput");
        if ((e.key === 'Enter' || e.keyCode === 13) && /\S/.test(scholarshipInput.value)) {
          if (currentScholarships.includes(scholarshipInput.value)){
            alert("Scholarship has already been added!");
            scholarshipInput.value = "";
            return;
          }
          if (typeof(document.getElementById("noScholarships")) != 'undefined' && document.getElementById("noScholarships") != null) {
            document.getElementById("noScholarships").remove();
          }

          let newScholarship = document.createElement("div");
          newScholarship.setAttribute('data-value', scholarshipInput.value);
          newScholarship.classList.add('form-control', 'mb-3', 'studentScholarship');
          newScholarship.innerHTML = scholarshipInput.value;

          let newScholarshipDelButton = document.createElement("button");
          newScholarshipDelButton.classList.add('deleteDivButtons');
          newScholarshipDelButton.setAttribute('onclick','removeItem(this)');
          newScholarshipDelButton.innerHTML = "&#x2715;";

          newScholarship.appendChild(newScholarshipDelButton);
          scholarshipDiv.appendChild(newScholarship);

          currentScholarships.push(scholarshipInput.value);
          scholarshipInput.value = "";
        }
      });

      function checkAwardType(){
        if (awardsDropdown.value == "Other"){
          $('#uniqueAwardModal').modal('show');
        }
        else {
          addAward();
        }
      }

      function addUniqueAward(){
        uniqueAwardInput = document.getElementById("uniqueAwardName");
        if (/\S/.test(uniqueAwardInput.value)){
          addAward(uniqueAwardInput.value);
        }
        else {
          alert("No name was specified for the new award!");
        }
        uniqueAwardInput.value = "";
        awardsDropdown.value = "";
      }

      function addAward(newAwardName = ""){
        if (typeof(document.getElementById("noAwards")) != 'undefined' && document.getElementById("noAwards") != null) {
          document.getElementById("noAwards").remove();
        }

        if (newAwardName == "") {
          newAwardName = awardsDropdown.value;
        }

        if (currentAwards.includes(newAwardName)){
          alert("Award has already been added!");
          awardsDropdown.value = "";
          return;
        }

        let newAward = document.createElement("div");
        newAward.setAttribute('data-value', newAwardName);
        newAward.classList.add('form-control', 'mb-3', 'studentAward');
        newAward.innerHTML = newAwardName;

        let newAwardDelButton = document.createElement("button");
        newAwardDelButton.classList.add('deleteDivButtons');
        newAwardDelButton.setAttribute('onclick','removeItem(this)');
        newAwardDelButton.innerHTML = "&#x2715;";

        newAward.appendChild(newAwardDelButton);
        awardsDivChild.appendChild(newAward);
        currentAwards.push(newAwardName);
        awardsDropdown.value = "";
      }

      function removeItem(element){
        var parent = element.parentNode;
        if (parent.classList.contains("studentAward")){
          currentAwards = currentAwards.filter(e => e !== parent.dataset.value);
          parent.remove();
        }
        else if (parent.classList.contains("studentScholarship")) {
          currentScholarships = currentScholarships.filter(e => e !== parent.dataset.value);
          parent.remove();
        }
      }

      function updateStudentEnabled(clicked_id){
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

      function showPassword(clicked_id) {
        let x = document.getElementById('pass'+clicked_id);
        if (x.type == "password") {
          x.type = "text";
        } else if (x.type != "password") {
          x.type = "password";
        }
      }

      function updatePassword() {
        let password1 = document.getElementById("password1");
        let password2 = document.getElementById("password2");

        if (password1.value != password2.value) {
          alert("The 2 passwords do not match!");
          password1.value = "";
          password2.value = "";
        } else {
          var request = new XMLHttpRequest();
          request.onload = function() {
            alert("Password has been updated!");
          }
          let query = "ajax.php?table=login&action=updatePass&login_stud_id="+studentID+"&login_pass="+encodeURIComponent(password1.value);
          request.open("GET", query);
          request.send();
        }
      }

      function updateStudentInfo() {
        let firstName = encodeURIComponent(document.getElementById("fname").value);
        let lastName = encodeURIComponent(document.getElementById("lname").value);
        let studentNumber = encodeURIComponent(document.getElementById("studNum").value);
        let studentEmail = encodeURIComponent(document.getElementById("email").value);

        //Collect student's awards
        let studentAwards = [];
        for (let element of awardsDivChild.getElementsByClassName("studentAward")) {
            studentAwards.push(element.dataset.value);
        }
        studentAwards = encodeURIComponent(JSON.stringify(studentAwards));

        //Collect future plans
        let futurePlans = [];
        let futurePlanType = document.getElementById("futurePlansDropdown").value;
        if (futurePlanType == "University" || futurePlanType == "College"){
          futurePlans.push(futurePlanType);
          futurePlans.push(plansWhere.value);
          futurePlans.push(plansWhat.value);
        }
        else if (futurePlanType == "Victory Lap") {
          futurePlans.push(futurePlanType);
        }
        else if (futurePlanType == "Work") {
          futurePlans.push(futurePlanType);
          futurePlans.push(plansWhere.value);
        }
        futurePlans = encodeURIComponent(JSON.stringify(futurePlans));

        //Collect student's scholarships
        let studentScholarships = [];
        for (let element of scholarshipDiv.getElementsByClassName("studentScholarship")) {
            studentScholarships.push(element.dataset.value);
        }
        studentScholarships = encodeURIComponent(JSON.stringify(studentScholarships));

        //Collect student's memorable moments
        tinyMCE.triggerSave();
        let memorableMoments = encodeURIComponent(tinymce.activeEditor.getContent());

        var request = new XMLHttpRequest();
        request.onload = function() {
          //Attempt to reload page without using cache (which can cause pictures to display improperly)
          $.ajax({
              url: window.location.href,
              headers: {
                  "Pragma": "no-cache",
                  "Expires": -1,
                  "Cache-Control": "no-cache"
              }
          }).done(function () {
              window.location.reload(true);
          });
        }
        let query = "ajax.php?table=students&action=updateAll&studid="+studentID+"&fName="+firstName+"&lName="+lastName+"&sNumber="+studentNumber+"&email="+studentEmail+"&awards="+studentAwards+"&plans="+futurePlans+"&scholarships="+studentScholarships+"&memMoments="+memorableMoments;
        request.open("GET", query);
        request.send();
      }

      function changeFuturePlans(){
        if (typeof(document.getElementById("noPlans")) != 'undefined' && document.getElementById("noPlans") != null) {
          document.getElementById("noPlans").remove();
        }
        let newFuturePlan = document.getElementById("futurePlansDropdown").value;

        plansWhere.value = "";
        plansWhat.value = "";

        if (newFuturePlan == 'University') {
          plansWhere.readOnly = false;
          plansWhat.readOnly = false;
        }
        else if (newFuturePlan == 'College') {
          plansWhere.readOnly = false;
          plansWhat.readOnly = false;

        }
        else if (newFuturePlan == 'Victory Lap') {
          plansWhere.readOnly = true;
          plansWhat.readOnly = true;
        }
        else if (newFuturePlan == 'Work') {
          plansWhere.readOnly = false;
          plansWhat.readOnly = true;
        }
      }
    </script>
  </body>
</html>

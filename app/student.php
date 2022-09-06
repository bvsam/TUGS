<?php
  require("functions.php");

  if(!validateTimestamp() || !checkStudent()){
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
    <title>Edit Slide Information</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
    <style media="screen">
      .deleteDivButtons {
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
     $studInfo = getStudInfo($_SESSION['userid'])[0];
     $studName = $studInfo["stud_fname"]." ".$studInfo["stud_lname"];
     $studLoginInfo = getStudentLoginInfo($studInfo["stud_id"]);
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <span class="navbar-brand">
          <?php
            echo htmlspecialchars($studName, ENT_QUOTES);
          ?>
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a href="#" class="nav-link active" data-bs-toggle="modal" data-bs-target="#Help">Help</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
          <img src="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png" alt="WCSS Logo" width="50">
      </div>
    </nav>

    <div class="container my-4">

      <!-- Student Deadline Alert -->
      <div class="alert alert-danger mb-4" role="alert">
        <h3>
          <strong>
            Deadline:
            <?php
              $deadlineDate = getDeadlineDate();
              $deadlineDate = strtotime($deadlineDate);
              echo date("F jS, Y", $deadlineDate);
            ?>
          </strong>
        </h3>
      </div>

      <!-- Student Pictures -->
      <h3>Pictures</h3>
      <hr>
      <div class="row mb-1">
        <?php
          $fullPath = getFullPath();
          $picsPath = $fullPath.$studInfo['stud_year']."/";
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
            $pictureName = $studInfo['stud_num'].$gradeSpecifier.".jpg";

            echo "<div class='col text-center'>";
            if (!file_exists($picsPath.$pictureName)){
              echo "<img class='img-fluid' src='Class of 20XX Emblem.png' alt='Missing Student Picture Placeholder'>";
              echo "<h2>(N/A)</h2>";
              $pictureStatus[$grade] = false;
            }
            else {
              echo "<img class='img-fluid' src='".$studentPicsPath.$studInfo['stud_year']."/".htmlspecialchars($pictureName, ENT_QUOTES)."' alt='".htmlspecialchars($studName, ENT_QUOTES)."'>";
              $pictureStatus[$grade] = true;
            }
            echo "</div>";
          }
        ?>
      </div>
      <div class="row mb-4">
        <?php
          foreach ($pictureStatus as $grade => $picAvailable) {
            echo "<div class='col text-center'>";
            echo "<h5>".$grade."</h5>";
            echo "</div>";
          }
        ?>
      </div>

      <!-- Memorable Moments Section -->
      <div class="mb-4">
        <h3>Memorable Moments</h3>
        <hr>
        <textarea id="memMoments" class="form-control" rows="3" placeholder="Enter the memorable moments of your highschool years"><?php
          if (isset($studInfo['stud_memMoments'])) {
            echo $studInfo['stud_memMoments'];
          }
         ?>
       </textarea>
      </div>

      <!-- Scholarships Section -->
      <div class="mb-4">
        <h3>Scholarships</h3>
        <hr>
        <div id="scholarshipDiv" class="mb-3">
          <?php
            if (isset($studInfo['stud_scholarships'])) {
              $scholarships = json_decode($studInfo["stud_scholarships"], true);
              foreach ($scholarships as $scholarship) {
                echo "<div data-value='".htmlspecialchars($scholarship, ENT_QUOTES)."' class='form-control mb-3 studentScholarship'>".$scholarship."<button class='deleteDivButtons' onclick='removeItem(this)'>&#x2715;</button></div>\n\t\t";
              }
            }
           ?>
        </div>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addScholarships" name = "addScholarships">+ Add a Scholarship</button>
      </div>

      <!-- Post Secondary Plans Section -->
      <div class="mb-4">
        <h3>Post Secondary Plans</h3>
        <hr>
        <p>I am:</p>
        <?php
          $plansArr = json_decode($studInfo["stud_plans"], true);
          $possiblePlans = array(
            1 => "University",
            2 => "College",
            3 => "Victory Lap",
            4 => "Work",
        );
          foreach ($possiblePlans as $key => $plan) {
            $checkedVal = "";
            if (isset($plansArr[0]) && $plansArr[0] == $plan){
              $checkedVal = "checked";
            }
            echo "<div class='form-check form-check-inline mb-3'>\n";
            echo "<input id='inlineRadio".$key."' data-value='".$plan."' class='form-check-input' type='radio' name='futurePlanType' onclick='changeFuturePlans(\"".$plan."\")' ".$checkedVal.">\n";
            echo "<label class='form-check-label' for='inlineRadio".$key."'>".$plan."</label>\n";
            echo "</div>\n";
          }
        ?>
        <div id="plansList">
          <?php
            if (isset($plansArr[1])) {
              echo "<input type='text' class='form-control mb-1' id='plansWhere' value='".htmlspecialchars($plansArr[1], ENT_QUOTES)."' placeholder='Where?'>";
            }
            else {
              echo "<input type='text' class='form-control mb-1' id='plansWhere' value='' placeholder='Where?'>";
            }
            if (isset($plansArr[2])) {
              echo "<input type='text' class='form-control mb-1' id='plansWhat' value='".htmlspecialchars($plansArr[2], ENT_QUOTES)."' placeholder='What?'>";
            }
            else {
              echo "<input type='text' class='form-control mb-1' id='plansWhat' value='' placeholder='What?'>";
            }
          ?>
        </div>
      </div>

      <!-- Save Info. -->
      <button type="button" class="btn btn-success mb-5" onclick="updateStudentInfo();">Save</button>
    </div>

    <div id='alertDiv' class='alertC'></div>

    <div class="modal" id="addScholarships">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2><u>Add a Scholarship</u></h2>
          </div>
          <div class="modal-body">
            <p>Enter any scholarships you will receive.</p>
            <p><b>Do not put in scholarship amounts. Please only enter the scholarship names (ex: Entrance Scholarship).</b></p>
            <input id="scholarshipInput" type="text" class="form-control" placeholder="Add a scholarship">
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="addScholarship()">Submit</button>
             <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
           </div>
         </div>
       </div>
     </div>

     <div class="modal" id="Help">
       <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
               <h2>Help</h2>
             </div>
             <div class="modal-body">
                 <h5>Name</h5>
                 <hr>
                 <?php
                  echo "<input type='text' id='sName' class='form-control bg-light mb-4' value='".htmlspecialchars($studName, ENT_QUOTES)."' readonly>";
                 ?>
                 <h5>E-mail</h5>
                 <hr>
                 <?php
                  echo "<input type='text' id='sEmail' class='form-control bg-light mb-4' value='".htmlspecialchars($studLoginInfo["login_email"], ENT_QUOTES)."' readonly>";
                 ?>
                 <h5>Message</h5>
                 <hr>
                 <div class="mb-3">
                   <textarea class="form-control" id="sMessage" rows="3"></textarea>
                 </div>
             </div>
             <div class="modal-footer">
               <?php
                echo "<button type='button' class='btn btn-success' data-bs-dismiss='modal' onclick='submitIssue(\"".$_SESSION["userid"]."\")'>Submit</button>";
               ?>
               <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
             </div>
         </div>
       </div>
     </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
      <?php
        echo "let studentID = ".$studInfo["stud_id"].";\n";
        echo "let studentNumber = \"".$studInfo["stud_num"]."\";\n";
        echo "let currentScholarships = ".$studInfo["stud_scholarships"].";\n";
      ?>
      let scholarshipDiv = document.getElementById('scholarshipDiv');
      let plansWhere = document.getElementById("plansWhere");
      let plansWhat = document.getElementById("plansWhat");

      function removeItem(element){
        var parent = element.parentNode;
        if (parent.classList.contains("studentScholarship")) {
          currentScholarships = currentScholarships.filter(e => e !== parent.dataset.value);
          parent.remove();
        }
      }

      function updateStudentInfo(){
        //Collect future plans
        let futurePlans = [];
        let futurePlanType = document.querySelector("input[name='futurePlanType']:checked").dataset.value;
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
          window.location.reload(true);
        }
        let query = "ajax.php?table=students&action=updateAllStudent&studid="+studentID+"&sNumber="+studentNumber+"&plans="+futurePlans+"&scholarships="+studentScholarships+"&memMoments="+memorableMoments;
        request.open("GET", query);
        request.send();
      }

      function addScholarship(){
        let scholarshipInput = document.getElementById("scholarshipInput");
        if (/\S/.test(scholarshipInput.value)){
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
        else {
          scholarshipInput.value = "";
        }
      }

      function changeFuturePlans(newFuturePlan){
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

      function submitIssue(studID){
        var sName = document.getElementById("sName").value;
        var sEmail = document.getElementById("sEmail").value;
        var sMessage = document.getElementById("sMessage").value;

        if (!/\S/.test(sMessage)){
          alert("Please fill out the whole form!");
          return;
        }
        var sMessage = sMessage.split("\n").join("\\n");

        var request = new XMLHttpRequest();
        request.open("GET", "ajax.php?table=issues&action=newIssue&studid=" + studID + "&email=" + sEmail + "&message=" + encodeURIComponent(sMessage));
        request.send();
      }
    </script>
  </body>
</html>

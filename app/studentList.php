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
    <link rel="stylesheet" href="navbar.css">

    <title>Student List</title>
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
      <div class="container my-3">
        <h1 class="my-3">
          <?php
            $year = getCurrentYear();
            echo $year;
           ?>
        </h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addContent" type="button" name="addContent">+ Add Content</button>

        <table class="table table-dark table-striped my-3" id="studentTable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Student Number</th>
              <th>Slide Status</th>
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
                echo "</tr>\n\n";
              }
            ?>
          </tbody>
        </table>

        <div class="modal" id="addContent">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class = "modal-title">Add Content</h2>
              </div>
              <div class="modal-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudents" name="addStudents">Add Student</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPhotos" name="addPhotos">Add photos</button>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal" id="addStudents">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h2><u>Add a New Student</u></h2>
              </div>
              <div class="modal-body">
                <h4>Year</h4>
                <?php
                  echo "<input type='number' id='year' class='form-control bg-light mb-4' value='".$year."'>";
                ?>
                <h5>Student First Name:</h5>
                <input type="text" id="fName" class="form-control bg-light mb-4" value="">
                <h5>Student Last Name:</h5>
                <input type="text" id="lName" class="form-control bg-light mb-4" value="">
                <h5>Student Number:</h5>
                <input type="text" id="sNumber" class="form-control bg-light mb-4" value="">
                <h5>Student E-mail:</h5>
                <input type="text" id="sEmail" class="form-control bg-light mb-4" value="">
                <h5>Student Password:</h5>
                <input type="password" id="sPassword" class="form-control bg-light mb-4" value="">
                <input type="checkbox" class="mb-4" onclick="showPassword()">
                Show Password
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="addStudent()">Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal" id="addPhotos">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="uploadPhotos.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h2>
                    <u>Add Photos</u>
                  </h2>
                </div>
                <div class="modal-body">
                  <p class="mb-3">Upload a .zip file containing the labelled student pictures.</p>
                  <h3>Select a Grade</h3>
                  <hr>
                  <select class="form-select mb-4" name="photoGrade">
                    <option value="Grade 9" selected>Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                    <option value="Graduation">Graduation</option>
                  </select>
                  <h3>Choose a File</h3>
                  <hr>
                  <input class="form-control mb-3" type="file" name="uploadedPics">
                  <h3>Provide a Delimeter</h3>
                  <hr>
                  <p>Enter in a delimeter that seperates the first and last names. Do not enter any extra spaces, especially at the end of your input.</p>
                  <input class="form-control mb-3" type="text" name="delimeter" value="">
                  <h3>Specify the Order of Names</h3>
                  <hr>
                  <p>Please specify whether the first name or last name comes first (seperated by the delimeter above).</p>
                  <select class="form-select" name="nameOrder">
                    <option value="firstName">First Name</option>
                    <option value="lastName">Last Name</option>
                  </select>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Submit</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
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
      $(document).ready(function(){
          $('#studentTable').DataTable();
      } );

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

      function addStudent(){
        var year = document.getElementById("year").value;
        var fName = document.getElementById("fName").value;
        var lName = document.getElementById("lName").value;
        var sEmail = document.getElementById("sEmail").value;
        var sPassword = document.getElementById("sPassword").value;
        var sNumber = document.getElementById("sNumber").value;

        var request = new XMLHttpRequest();

        request.onload = function() {
          document.getElementById("fName").value = "";
          document.getElementById("lName").value = "";
          document.getElementById("sEmail").value = "";
          document.getElementById("sPassword").value = "";
          document.getElementById("sNumber").value = "";
        }
        request.open("GET", "ajax.php?table=students&action=addStudent&year=" + year + "&fName=" + fName + "&lName=" + lName + "&email=" + sEmail + "&password=" + sPassword + "&sNumber=" + sNumber);
        request.send();
      }
      function showPassword(){
        var passwordField = document.getElementById("sPassword");
        if (passwordField.type == "password"){
          passwordField.type = "text";
        }
        else {
          passwordField.type = "password";
        }
      }
    </script>
  </body>
</html>

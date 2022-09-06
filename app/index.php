<?php
  include("functions.php");

  if(validateTimestamp() && checkAdmin()){
    header("Location: studentList.php");
  }
  elseif (validateTimestamp() && checkStudent()) {
    header("Location: student.php");
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
    <title>WCSS Grad Slideshow Login</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
  </head>
  <body class="bg-dark">
    <!-- Login Page -->
    <div class="container my-5">
      <div class="container align-items-center w-50">
        <div class="alert alert-info" role="alert">
          <?php
            $announcement = getAnnouncement();
            $announcementName = $announcement["announcementName"];
            $announcementValue = $announcement["announcementValue"];
            $splitAnnouncementValue = explode('\\n', $announcementValue);

            echo "<h4 class='alert-heading'>".$announcementName."</h4>";
            echo "<hr>";
            foreach ($splitAnnouncementValue as $val){
              if ($val == ""){
                echo "<br>";
              }
              else {
               echo "<p>".$val."</p>";
              }
            }
          ?>
        </div>
      </div>
      <div class="container rounded p-3 bg-light text-center w-50">
        <h1 class="mb-4">WCSS Grad Slideshow</h1>
        <img src="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png" alt="WCSS Logo" class="img-fluid mb-4">
        <?php
          $deadlineDate = getDeadlineDate();
          if (date("Y-m-d") > $deadlineDate) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo "<h4>Student Login Has Been Disabled</h4>";
            echo "</div>";
          }
        ?>
        <form action="login.php" method="post">
          <input type="text" class="form-control form-control-lg mb-4" name="email" value="" placeholder="Username">
          <input type="password" class="form-control form-control-lg mb-4" name="password" value="" placeholder="Password">
          <?php
            //Check to see if the login attempt has failed. If so, notify the user
            if (isset($_SESSION["status"]) && $_SESSION["status"] == "failedLogin"){
              echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
              echo "Incorrect password or email!";
              echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
              echo "</div>";
              unset($_SESSION["status"]);
            }
          ?>
          <button type="submit" class="btn btn-primary btn-lg mb-4">Login</button>
        </form>
        <a href="" data-bs-toggle="modal" data-bs-target="#Help">Help</a>
      </div>
    </div>

    <!-- Modals -->
    <div class="modal" id="Help">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Help</h2>
          </div>
          <div class="modal-body">
              <h5>E-mail</h5>
              <hr>
              <input type="text" id="sEmail" class="form-control bg-light mb-4" value="">
              <h5>Message</h5>
              <hr>
              <div class="mb-3">
                <textarea class="form-control" id="sMessage" rows="3"></textarea>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="submitIssue()">Submit</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      function submitIssue(){

        var sEmail = document.getElementById('sEmail').value;
        var sMessage = document.getElementById('sMessage').value;

        sMessage = encodeURIComponent(sMessage.split("\n").join("\\n"));

        var request = new XMLHttpRequest();
        request.open("GET", "ajax.php?table=issues&action=newIssue&email="+sEmail+"&message="+sMessage);
        request.send();
      }
    </script>
  </body>
</html>

<?php
  require("functions.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();

  $notif = getNotification($_GET["id"]);
  if (count($notif) < 1){
    header("Location: notifications.php");
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
    <title>Notification</title>
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
    <link rel="stylesheet" href="navbar.css">
  </head>
  <body>
    <?php
      printSidebar("notifications.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container my-3">
        <div class="row">
            <h1 class="col-9">Notification</h1>
            <div class="col-3">
              <h5 class="pt-3">
                <?php
                  echo $notif[0]['issues_timestamp'];
                ?>
              </h5>
            </div>
        </div>
        <hr>
        <div class="row my-3">
          <h4 class="col-9">
            From:
            <?php
              $stud = getStudInfo($notif[0]["issues_stud_id"]);
              if ($notif[0]['issues_stud_id'] != "") {
                echo $stud[0]['stud_fname']." ".$stud[0]['stud_lname']."\n";
              }
              else {
                echo $notif[0]["issues_email"]." (On Login Page)";
              }
           ?>
          </h4>
          <h4 class="col-3">
            S Number:
            <?php
              if ($notif[0]['issues_stud_id'] != "") {
                echo $stud[0]['stud_num'];
              }
              else {
                echo "N/A";
              }
            ?>
          </h4>
        </div>
        <h4>Message:</h4>
        <div class="container border p-3 my-3">
          <?php
            $splitText = explode('\\n', $notif[0]['issues_note']);
            foreach ($splitText as $val) {
              if ($val == ""){
                echo "<br>";
              }
              else {
                echo "<p>".$val."</p>";
              }
            }
          ?>
        </div>
        <?php
          echo "<div class='col-lg-2 my-3'>\n";
          echo "<button type='button' class='btn btn-outline-danger btn-lg h-100 w-100 ' onclick=deleteNotif(".$_GET['id'].")>\n";
          echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>\n";
          echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'></path>\n";
          echo "<path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'></path>\n";
          echo "</svg>\n";
          echo "Delete\n";
          echo "</button>\n";
          echo "</div>\n";
         ?>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      function deleteNotif(notifId){
        const request = new XMLHttpRequest();
        request.onload = function() {
          window.close();
        }
        request.open("GET", "ajax.php?table=issues&action=delIssue&issueid=" + notifId);
        request.send();
      }

    </script>
  </body>
</html>

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
    <title>Notifications</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
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
        <h1>Notifications</h1>
        <hr>
         <?php
           $issues = getJoinedIssues();

           foreach ($issues as $key => $issue) {
             $splitText = explode('\\n', $issue['issues_note']);
             echo "<div class='container rounded border mb-2 p-3' id='".$issue['issues_id']."'>\n";
             echo "<div class='row'>\n";

             echo "<div class='col-lg-10 position-relative text-break'>\n";
             if ($issue['issues_stud_id'] != "") {
               echo "<h3>Message from  ".htmlspecialchars($issue['stud_fname'], ENT_QUOTES)." ".htmlspecialchars($issue['stud_lname'], ENT_QUOTES)."</h3>\n";
             }
             else {
               echo "<h3>Message from  ".htmlspecialchars($issue['issues_email'], ENT_QUOTES)."</h3>\n";
             }
             foreach ($splitText as $val) {
               if ($val == ""){
                 echo "<br>";
               }
               else {
                echo "<p>".$val."</p>";
               }
             }
             echo "<a href='notification.php?id=".$issue['issues_id']."' class='stretched-link' target='_blank' rel='noopener noreferrer'></a>";
             echo "</div>\n";

             echo "<div class='col-lg-2'>\n";
             echo "<button type='button' class='btn btn-outline-danger btn-lg h-100 w-100' onclick=deleteNotif(".$issue['issues_id'].")>\n";
             echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>\n";
             echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'></path>\n";
             echo "<path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'></path>\n";
             echo "</svg>\n";
             echo "Delete\n";
             echo "</button>\n";
             echo "</div>\n";

             echo "</div>\n";
             echo "</div>\n";
           }
          ?>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      function deleteNotif(notifId){
        var notification = document.getElementById(notifId);

        const request = new XMLHttpRequest();
        request.onload = function() {
          notification.remove();
        }

        request.open("GET", "ajax.php?table=issues&action=delIssue&issueid=" + notifId);
        request.send();
      }
    </script>
  </body>
</html>

<?php
  require("functions.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();

  if (isset($_POST["yearNumber"]) && isset($_FILES["csvFile"])) {
    uploadStudents($_POST["yearNumber"], $_FILES["csvFile"]);
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
    <title>Archived Years</title>
  </head>
  <body>
    <?php
      printSidebar("archivedYears.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container my-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addYearModal" type="button">+ Add New Year</button>
        <hr>
      </div>
      <div class="container mt-4 mb-5">
        <?php
        $years = getAllYears();

        $totalCount = count($years);
        $i=0;

        foreach ($years as $key => $year) {
          if ($i == 0) {
            echo "<div class='row my-3'>\n";
          }

          echo "<div class='col-lg-4'>\n";
          echo "<div class='card p-4'>\n";

          echo "<div class='card-body text-center'>\n";
          echo "<h2>\n";
          echo $year;
          echo "</h2>\n";

          echo "<a href='#' class='open-ChangeYearModal stretched-link' data-id='".htmlspecialchars($year, ENT_QUOTES)."' data-bs-toggle='modal' data-bs-target='#changeYearModal'></a>\n";
          echo "</div>\n";

          echo "</div>\n";
          echo "</div>\n";

          $i++;
          if ($key == $totalCount - 1) {
            echo "</div>\n\n";
          }
          elseif ($i == 3) {
            echo "</div>\n\n";
            $i = 0;
          }
        }
        ?>
      </div>

      <div class="modal" id="changeYearModal">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <h2>Change Current Year</h2>
              </div>
              <div class="modal-body">
                  <h3 class="text-danger">Warning:</h3>
                  <p>
                    <b>
                      You are about to change the current year for the graduating system to:
                      <span id="clickedYear"></span>
                    </b>
                  </p>
                  <p>Are you sure you want to continue?</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="changeYear()">Yes</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
              </div>
          </div>
        </div>
      </div>

      <div class="modal" id="addYearModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="archivedYears.php" enctype="multipart/form-data" method="POST">
              <div class="modal-header">
                <h2><u>Add a New Year</u></h2>
              </div>
              <div class="modal-body">
                <h3>Note</h3>
                <p>The accepted column format for .csv files being uploaded is:</p>
                <p><b>Email, Password, SNumber*, Last Name, First Name</b></p>
                <p>The first row of the file should <b>not</b> contain the column headers</p>
                <p>*The SNumber should not contain the letter "S"</p>
                <hr>
                <h4>New Year</h4>
                <input type="number" id="year" class="form-control bg-light mb-4" name="yearNumber" value="<?php echo getCurrentYear();?>">
                <h4>Student File (.csv)</h4>
                <input class="form-control mb-3" type="file" name="csvFile">
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
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      var newYear;

      $(document).on("click", ".open-ChangeYearModal", function () {
        var clickedYear = $(this).data('id');
        $(".modal-body #clickedYear").html(clickedYear);
        newYear = clickedYear;
      });

      function changeYear(){
        var request = new XMLHttpRequest();

        request.open("GET", "ajax.php?table=settings&action=changeYear&newYear=" + newYear);
        request.send();
      }

    </script>
  </body>
</html>

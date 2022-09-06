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
    <title>Edit Slides</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
    <script src="https://cdn.tiny.cloud/1/t03uf5ormjh22cia4fb49getjm982kfb7ddhy8izfr6o9zpf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: "#slideContent"
      });
    </script>
  </head>
  <body>
    <?php
      printSidebar("editSlides.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="container my-4">
        <h3>Edit Slides</h3>
        <hr>
        <h5>Select Slide:</h5>
        <p>
          <select id="slideSelect" class="form-select" onchange="updateEditor()">
            <?php
            $slides = getPSlides();
            foreach ($slides as $slide) {
              if (isset($_GET["id"]) && $slide["slide_id"] == $_GET["id"]) {
                echo "<option value='".htmlspecialchars($slide["slide_id"], ENT_QUOTES)."' selected>";
              }
              else {
                echo "<option value='".htmlspecialchars($slide["slide_id"], ENT_QUOTES)."'>";
              }

              if ($slide["slide_name"] == "") {
                echo "Slide ID #".$slide["slide_id"];
              }
              else {
                  echo $slide["slide_name"];
              }
              echo "</option>";
            }
            ?>
          </select>
        </p>
        <hr>
        <h5>Slide Name</h5>
        <?php
          if (isset($_GET["id"])) {
            foreach ($slides as $slide) {
              if ($slide["slide_id"] == $_GET["id"]){

                  echo "<input id='slideName' class='form-control my-3' name='slide_name' type='text' value='".htmlspecialchars($slide["slide_name"], ENT_QUOTES)."'>";
              }
            }
          }
          else {
            echo "<input id='slideName' class='form-control my-3' name='slide_name' type='text' value='".htmlspecialchars($slides[0]["slide_name"], ENT_QUOTES)."'>";
          }

          echo "<h5>Slide Content</h5>";
          echo "\n<textarea id='slideContent' name='slide_content'>";
          if (isset($_GET["id"])){
            foreach ($slides as $slide){
              if ($slide["slide_id"] == $_GET["id"]){
                  echo $slide["slide_content"];
              }
            }
          }
          else {
            echo $slides[0]["slide_content"];
          }
          echo "</textarea>\n";
        ?>
        <?php
          if (isset($_GET["id"])) {
            echo "<a href='slide.php?id=".$_GET["id"]."' class='btn btn-info' role='button'>Preview Slide</a>";
          }
        ?>
        <button type="submit" class="btn btn-success my-3" onclick="updateData()">Update</button>
      </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      function updateEditor(){
        var slide_id = document.getElementById("slideSelect").value;
        var request = new XMLHttpRequest();

        request.onload = function() {
          response = JSON.parse(this.responseText);
          document.getElementById("slideName").value = response["slide_name"];
          tinymce.activeEditor.setContent(response["slide_content"]);
        }
        request.open("GET", "ajax.php?table=pSlides&action=getSlide&slide_id=" + slide_id);
        request.send();
      }

      function updateData(){
        tinyMCE.triggerSave();
        var slide_id = document.getElementById("slideSelect").value;
        var slide_name = document.getElementById("slideName").value;
        var slide_content = tinymce.activeEditor.getContent();
        slide_content = encodeURIComponent(slide_content);
        var request = new XMLHttpRequest();

        request.onload = function() {

        }
        request.open("GET", "ajax.php?table=pSlides&action=updateSlide&slide_id=" + slide_id + "&slide_name=" + slide_name + "&slide_content=" + slide_content);
        request.send();
      }
    </script>
  </body>
</html>

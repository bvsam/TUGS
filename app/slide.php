<?php
  require("functions.php");

  if(!validateTimestamp() || !checkAdmin()){
    header("Location: logout.php");
  }
  updateTimestamp();

  $slideID = $_GET["id"];
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
    </style>
  </head>
  <body>
    <?php
      $slide = getPSlides($slideID);
      echo $slide["slide_content"];
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
 </body>
</html>

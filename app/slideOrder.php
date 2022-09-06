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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.css" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="navbar.css">
    <meta charset="utf-8">
    <title>Slide Order</title>
    <link rel="icon" type="image/x-icon" href="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png">
  </head>
  <body>
    <?php
      printSidebar("slideOrder.php");
    ?>
    <div id="main">
      <?php
        printNavbar();
      ?>
      <div class="my-3 p-3">
        <h1>Change the Slide Order</h1>
        <button type="button" class="btn btn-success my-2" onclick="updateSlideOrder()">Update</button>
        <hr>
        <div class="row">
          <div class="col-lg-4">
            <h3>Preshow Slides</h3>
            <button class="btn btn-primary mb-3" type="button" data-bs-toggle="modal" data-bs-target="#newSlideModal">+ Add a New Slide</button>
            <div id="preShowSlides" class="container rounded p-3 border border-dark">
              <?php
                $preSlides = getSlides(0);
                $preOrder = getSlideOrder(0);
                printSlides($preSlides, $preOrder, 0);
              ?>
            </div>
          </div>
          <div class="col-lg-4">
            <h3>Postshow Slides</h3>
            <button class="btn btn-primary mb-3" type="button" data-bs-toggle="modal" data-bs-target="#newSlideModal">+ Add a New Slide</button>
            <div id="postShowSlides" class="container rounded p-3 border border-dark">
              <?php
                $postSlides = getSlides(1);
                $postOrder = getSlideOrder(1);
                printSlides($postSlides, $postOrder, 1);
              ?>
            </div>
          </div>
          <div class="col-lg-4 justify-content-center">
            <h3 class="col-7 ">Inactive Slides</h3>
            <button class="btn btn-primary mb-3" type="button" data-bs-toggle="modal" data-bs-target="#newSlideModal">+ Add a New Slide</button>
            <div id="inactiveSlides" class="container rounded p-3 border border-dark">
              <?php
                $disabledSlides = getSlides(2);
                $disabledOrder = getSlideOrder(2);
                printSlides($disabledSlides, $disabledOrder, 2);
              ?>
            </div>
          </div>
        </div>

        <div class="modal fade" id="newSlideModal" tabindex="-1" aria-labelledby="newSlideModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="newSlideModalLabel">Add A New Slide</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <h3>New Slide Type</h3>
                <select id="newSlideType" class="form-select mb-4">
                  <option value="0">Preshow Slides</option>
                  <option value="1">Postshow Slides</option>
                  <option value="2">Inactive Slides</option>
                </select>
                <h3>New Slide Name</h3>
                <div class="input-group mb-4">
                  <input id="newSlideName" type="text" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="addNewSlide()">Add Slide</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.js" crossorigin="anonymous"></script>
    <script src='navbar.js'></script>
    <script>
      var preShowSlides = document.getElementById('preShowSlides');
      var postShowSlides = document.getElementById('postShowSlides');
      var inactiveSlides = document.getElementById('inactiveSlides');
      var drake = dragula([preShowSlides, postShowSlides, inactiveSlides]);

      drake.on('drop', function(el, target) {
        if (target.id == "preShowSlides") {
          el.className = "btn btn-primary my-2 p-3 col-12 slide";
        }
        else if (target.id == "postShowSlides") {
          el.className = "btn btn-info my-2 p-3 col-12 slide";
        }
        else if (target.id == "inactiveSlides") {
          el.className = "btn btn-secondary my-2 p-3 col-12 slide";
        }
      });

      function updateSlideOrder(){
        var preShowOrder = JSON.stringify($('#preShowSlides .slide').map(function(){
          return $(this).attr('id');
        }).get());
        var postShowOrder = JSON.stringify($('#postShowSlides .slide').map(function(){
          return $(this).attr('id');
        }).get());
        var inactiveOrder = JSON.stringify($('#inactiveSlides .slide').map(function(){
          return $(this).attr('id');
        }).get());

        var request = new XMLHttpRequest;
        request.open("GET", "ajax.php?table=pSlides&action=updateOrder&preOrder="+preShowOrder+"&postOrder="+postShowOrder+"&inactiveOrder="+inactiveOrder);
        request.send();
      }

      function deleteSlide(slideID){
        var request = new XMLHttpRequest();
        request.open("GET", "ajax.php?table=pSlides&action=deleteSlide&slide_id="+slideID);
        request.send();
        document.getElementById(slideID).remove();
        updateSlideOrder();
      }

      function addNewSlide(){
        newSlideType = document.getElementById("newSlideType").value;
        newSlideName = document.getElementById("newSlideName").value;

        var request = new XMLHttpRequest();
        request.open("GET", "ajax.php?table=pSlides&action=createSlide&slideType="+newSlideType+"&slideName="+newSlideName);
        request.send();

        location.reload(true);
      }
    </script>
  </body>
</html>

<?php
  require("functions.php");

  if (isset($_GET) && isset($_GET["table"])){
    if ($_GET["table"] == "students" && isset($_GET["action"])) {
      if ($_GET["action"] == "updateEnabled" && isset($_GET["studid"])){
        updateStudEnabled($_GET["studid"]);
      }
      elseif ($_GET["action"] == "addStudent" && isset($_GET["year"]) && isset($_GET["fName"]) && isset($_GET["lName"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["sNumber"])){
        addStudent($_GET["year"], $_GET["fName"], $_GET["lName"], $_GET["email"], $_GET["password"], $_GET["sNumber"]);

      }
      elseif ($_GET["action"] == "updateAll" && isset($_GET["studid"]) && isset($_GET["fName"]) && isset($_GET["lName"]) && isset($_GET["sNumber"]) && isset($_GET["email"]) && isset($_GET["awards"]) && isset($_GET["plans"]) && isset($_GET["scholarships"]) && isset($_GET["memMoments"])){
        updateAllStudInfo($_GET["studid"], $_GET["fName"], $_GET["lName"], $_GET["sNumber"], $_GET["email"], $_GET["awards"], $_GET["plans"], $_GET["scholarships"], $_GET["memMoments"]);
      }
      elseif ($_GET["action"] == "updateAllStudent" && isset($_GET["studid"]) && isset($_GET["sNumber"]) && isset($_GET["plans"]) && isset($_GET["scholarships"]) && isset($_GET["memMoments"])){
        updateStudInfo($_GET["studid"], $_GET["sNumber"], $_GET["plans"], $_GET["scholarships"], $_GET["memMoments"]);
        recordSave($_GET["studid"]);
      }
      elseif ($_GET["action"] == "matchStudent" && isset($_GET["picturePath"]) && isset($_GET["selectedPic"]) && isset($_GET["newPictureName"])){
        matchStudent($_GET["picturePath"], $_GET["selectedPic"], $_GET["newPictureName"]);
      }
      elseif ($_GET["action"] == "removePicture" && isset($_GET["picturePath"])){
        removePicture($_GET["picturePath"]);
      }
    }

    elseif ($_GET["table"] == "issues" && isset($_GET["action"])) {
      if ($_GET["action"] == "newIssue" && isset($_GET["studid"]) && isset($_GET["email"]) && isset($_GET["message"])){
        newIssue($_GET["studid"], $_GET["email"], $_GET["message"]);
      }
      elseif ($_GET["action"] == "newIssue" && !isset($_GET["studid"]) && isset($_GET["email"]) && isset($_GET["message"])){
        newIssue(-1, $_GET["email"], $_GET["message"]);
      }
      elseif ($_GET["action"] == "delIssue" && isset($_GET["issueid"])){
        delIssue($_GET["issueid"]);
      }
    }

    elseif ($_GET["table"] == "settings" && isset($_GET["action"])) {
      if ($_GET["action"] == "changeYear" && isset($_GET["newYear"])){
        changeCurrentYear($_GET["newYear"]);
      }
      elseif ($_GET["action"] == "updateAnnouncements" && isset($_GET["announcementName"]) && isset($_GET["announcementValue"])){
        updateAnnouncement($_GET["announcementName"], $_GET["announcementValue"]);
      }
      elseif ($_GET["action"] == "updateDeadline" && isset($_GET["newDeadline"])) {
        updateDeadline($_GET["newDeadline"]);
      }
      elseif ($_GET["action"] == "updateSlideSettings" && isset($_GET["intervalDuration"]) && isset($_GET["slideBackgroundColour"]) && isset($_GET["slideTextColour"])) {
        updateSlideSettings($_GET["intervalDuration"], $_GET["slideBackgroundColour"], $_GET["slideTextColour"]);
      }
    }

    elseif($_GET["table"] == "pSlides" && isset($_GET["action"])){
      if($_GET["action"] == "updateSlides" && isset($_GET["newSlide"]) && isset($_GET["newContent"])){
        updateCurrentSlide($_GET["newSlide"], $_GET["newContent"]);
      }
      elseif($_GET["action"] == "updateSlide" && isset($_GET["slide_id"])  && isset($_GET["slide_name"])  && isset($_GET["slide_content"])){
        updateSlide($_GET["slide_id"], $_GET["slide_name"], $_GET["slide_content"]);
      }
      elseif($_GET["action"] == "getSlide" && isset($_GET["slide_id"])){
        getSlide($_GET["slide_id"]);
      }
      elseif ($_GET["action"] == "updateOrder" && isset($_GET["preOrder"]) && isset($_GET["postOrder"]) && isset($_GET["inactiveOrder"])) {
        updateOrder($_GET["preOrder"], $_GET["postOrder"], $_GET["inactiveOrder"]);
      }
      elseif ($_GET["action"] == "deleteSlide"  && isset($_GET["slide_id"])) {
        deleteSlide($_GET["slide_id"]);
      }
      elseif ($_GET["action"] == "createSlide"  && isset($_GET["slideType"]) && isset($_GET["slideName"])) {
        createSlide($_GET["slideType"], $_GET["slideName"]);
      }
    }

    elseif ($_GET["table"] == "login" && isset($_GET["action"])) {
      if ($_GET["action"] == "updatePass") {
        updatePassword($_GET["login_stud_id"], $_GET["login_pass"]);
      }
    }
  }
?>

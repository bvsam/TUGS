<?php
require("functions.php");
$fullPath = getFullPath();
$tempPath = $fullPath."temp/";
if (isset($_FILES["uploadedPic"]["name"]) && isset($_POST["newPath"])){
  $uploadedName = $_FILES["uploadedPic"]["name"];
  if (isset($_FILES["uploadedPic"]["tmp_name"])){
    move_uploaded_file($_FILES["uploadedPic"]["tmp_name"], $tempPath.$uploadedName);
  }
  if (file_exists($tempPath.$uploadedName) && isset($_POST["newPath"])){
    $parts = explode('/', $_POST["newPath"]);
    $last = array_pop($parts);
    $parts = array(implode('/', $parts), $last);
    $picDirectory = $parts[0];
    if (!file_exists($picDirectory)) {
      mkdir($picDirectory, 0777, true);
    }
    copy($tempPath.$uploadedName, $_POST["newPath"]);
  }
}

$location = "editStudInfo.php?stud_id=".$_GET["stud_id"];
header("Location: ".$location);
?>

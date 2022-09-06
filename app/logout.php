<?php
  require("functions.php");
  if (isset($_SESSION['userid']) && $_SESSION['userid'] >= 0){
    recordLogout($_SESSION['userid']);
  }
  unset($_SESSION['userid']);
  unset($_SESSION['timestamp']);
  header("Location: ./");
?>

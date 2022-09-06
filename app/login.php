<?php
  include("functions.php");
  //Check to see if the page is being accessed as a result of a form submission to login
  if(isset($_POST['email']) && isset($_POST['password'])){
    //Use the function auth_checkLoginCredentials and store the returned value in $checkLogin
    $checkLogin = auth_checkLoginCredentials($_POST['email'], $_POST['password']);
    $deadlineDate = getDeadlineDate();

    //If the user is a student, set the appropriate $_SESSION variables and redirect to inputfield.php
    if ($checkLogin >= 0 && date("Y-m-d") <= $deadlineDate) {
      $_SESSION['userid'] = $checkLogin;
      $_SESSION['timestamp'] = time();
      unset($_SESSION['status']);
      recordLogin($checkLogin);
      header("Location: student.php");
    }
    //If the user is an admin, set the appropriate $_SESSION variables and redirect to studentList.php
    elseif ($checkLogin == -1) {
      //Set the $_SESSION userid key/field as -1 to denote an admin user
      $_SESSION['userid'] = $checkLogin;
      $_SESSION['timestamp'] = time();
      unset($_SESSION['status']);
      header("Location: studentList.php");
    }

    elseif ($checkLogin == -2) {
      //Redirect to login page (index.php) if login fails
      $_SESSION['status'] = "failedLogin";
      header("Location: ./");
    }
    else {
      header("Location: ./");
    }
  }
  else {
    header("Location: ./");
  }
?>

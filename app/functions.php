 <?php
  //Start the session. This will allow use of the $_SESSION variable for files that call functions.php
  session_start();

  //Verify the login credentials and return what type of user the login is for
  function auth_checkLoginCredentials($username, $password){

    //Find the user from the login table using the given email
    require("database.php");
    $query = $pdo->prepare("SELECT * FROM login WHERE login_email=:email");
    $query->bindParam(":email", $username);
    $query->execute();
    $user = $query->fetchAll(PDO::FETCH_ASSOC);

    //Check to make sure the user exists. If so, verify the password with password_verify()
    if (sizeof($user) > 0 && password_verify($password, $user[0]["login_pass"])){
      //Return -1 if the user is an admin
      if ($user[0]["login_isAdmin"] == 1){
        return -1;
      }
      //Return the login_stud_id is the user is a student
      elseif($user[0]["login_isAdmin"] == 0){
        return $user[0]["login_stud_id"];
      }
    }
    //Return -2 if the user doesn't exist or the login failed
    else{
      return -2;
    }
  }

  //Return a single student from the student table with the specified student id
  function getStudInfo($id) {
    require("database.php");
    $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_id = :id");
    $qStud -> bindParam(":id", $id);
    $qStud -> execute();
    $studInfo = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $studInfo;
  }

  function getStudentsbyAsc($year=null){
    require("database.php");
    if($year != null){
      $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_year=:year ORDER BY stud_lname, stud_fname ASC");
      $qStud -> bindParam(":year", $year);
    }
    else {
      $qStud = $pdo -> prepare("SELECT * FROM students ORDER BY stud_lname, stud_fname ASC");
    }
    $qStud -> execute();
    $students = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $students;
  }

  function getAwards(){
    require("database.php");
    $qAwards = $pdo -> prepare("SELECT * FROM awards");
    $qAwards -> execute();
    $awardsInfo = $qAwards -> fetchAll(PDO::FETCH_ASSOC);

    return $awardsInfo;
  }

  //Return the row in the login table for the specified student (returns the student's login information)
  function getStudentLoginInfo($studID){
    require("database.php");
    $qStud = $pdo -> prepare("SELECT * FROM login WHERE login_stud_id = :id");
    $qStud -> bindParam(":id", $studID);
    $qStud -> execute();
    $info = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $info[0];
  }

  //Return ALL students in the student table
  function getAllStudents($year = -1){
    require("database.php");
    if ($year == -1){
      $qStud = $pdo -> prepare("SELECT * FROM students");
    }
    else {
      $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_year=:year");
      $qStud -> bindParam(":year", $year);
    }
    $qStud -> execute();
    $students = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $students;
  }

  //Validate that the user is within the logout time limit set
  function validateTimestamp() {
    if (isset($_SESSION['userid']) && isset($_SESSION['timestamp'])){
      $currentTime = time();
      //Check to see if 30 minutes have passed since the last timestamp
      if ($_SESSION['timestamp'] >= $currentTime - 30*60){
        //Return to tell that the time limit is NOT exceeded
        return 1;
      }
      else{
        //Return to tell that the time limit HAS been exceeded
        return 0;
      }
    }
  }

  //Update the timestamp for the user to show that they are active
  function updateTimestamp(){
    $_SESSION['timestamp'] = time();
  }

  function updateStudEnabled($studID){
    require("database.php");

    $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_id = :id");
    $qStud -> bindParam(":id", $studID);
    $qStud -> execute();
    $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);
    if ($student[0]["stud_enabled"] == 1){
      $updateStatus = 0;
    }
    else {
      $updateStatus = 1;
    }

    $qStud = $pdo -> prepare("UPDATE students SET stud_enabled = :newStatus WHERE stud_id = :id");
    $qStud -> bindParam(":newStatus", $updateStatus);
    $qStud -> bindParam(":id", $studID);
    $qStud -> execute();
  }

  function newIssue($studID, $studEmail, $studMessage){
    require("database.php");

    if ($studID >= 0){
      $qStud = $pdo -> prepare("INSERT INTO issues (issues_email, issues_note, issues_stud_id) VALUES (:email, :note, :id)");
      $qStud -> bindParam(":id", $studID);
      $qStud -> bindParam(":email", $studEmail);
      $qStud -> bindParam(":note", $studMessage);
    }
    else{
      $qStud = $pdo -> prepare("INSERT INTO issues (issues_email, issues_note) VALUES (:email, :note)");
      $qStud -> bindParam(":email", $studEmail);
      $qStud -> bindParam(":note", $studMessage);
    }
    $qStud -> execute();
  }

  function checkAdmin(){
    if (isset($_SESSION) && $_SESSION["userid"] == -1){
      return 1;
    }
    else {
      return 0;
    }
  }

  function checkStudent(){
    if (isset($_SESSION) && $_SESSION["userid"] > 0){
      return 1;
    }
    else {
      return 0;
    }
  }

  function delIssue($issueID){
    require("database.php");

    $qStud = $pdo -> prepare("DELETE FROM issues WHERE issues_id = :id");
    $qStud -> bindParam(":id", $issueID);
    $qStud -> execute();

  }

  function getJoinedIssues(){
    require("database.php");
    $qNotif = $pdo -> prepare("SELECT issues.*, students.stud_fname, students.stud_lname
                              FROM issues
                              LEFT JOIN students
                              ON students.stud_id = issues.issues_stud_id
                              ORDER BY issues.issues_id DESC");
    $qNotif -> execute();
    $issues = $qNotif -> fetchAll(PDO::FETCH_ASSOC);

    return $issues;
  }

  function getCurrentYear(){
    require("database.php");
    $qNotif = $pdo -> prepare("SELECT * FROM settings WHERE setting_name = 'currentYear'");
    $qNotif -> execute();
    $currYear = $qNotif -> fetchAll(PDO::FETCH_ASSOC);

    return $currYear[0]["setting_value"];
  }

  function addStudent($year, $fName, $lName, $email, $password, $sNumber){
    require("database.php");
    $defaultJSON = "[]";

    $qStud = $pdo -> prepare("INSERT INTO students (stud_num, stud_lname, stud_fname, stud_year, stud_awards, stud_plans, stud_scholarships) VALUES (:sNumber, :lName, :fName, :year, :awards, :plans, :scholarships)");
    $qStud -> bindParam(":sNumber", $sNumber);
    $qStud -> bindParam(":lName", $lName);
    $qStud -> bindParam(":fName", $fName);
    $qStud -> bindParam(":year", $year);
    $qStud -> bindParam(":awards", $defaultJSON);
    $qStud -> bindParam(":plans", $defaultJSON);
    $qStud -> bindParam(":scholarships", $defaultJSON);
    $qStud -> execute();
    $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    $qStud = $pdo -> prepare("SELECT stud_id FROM students WHERE stud_num = :sNumber AND stud_lname = :lName AND stud_fname = :fName AND stud_year = :year");
    $qStud -> bindParam(":sNumber", $sNumber);
    $qStud -> bindParam(":lName", $lName);
    $qStud -> bindParam(":fName", $fName);
    $qStud -> bindParam(":year", $year);
    $qStud -> execute();
    $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);
    $stud_id = $student[0]["stud_id"];

    $qStud = $pdo -> prepare("INSERT INTO login (login_email, login_pass, login_isAdmin, login_stud_id) VALUES (:email, :password, 0, :stud_id)");
    $qStud -> bindParam(":email", $email);
    $qStud -> bindParam(":password", password_hash($password, PASSWORD_DEFAULT));
    $qStud -> bindParam(":stud_id", $stud_id);
    $qStud -> execute();

  }

  function getSlide($slideID){
    require ("database.php");

    $qSlides = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_id=:slideID");
    $qSlides -> bindParam(":slideID", $slideID);
    $qSlides -> execute();
    $slide = $qSlides -> fetchAll(PDO::FETCH_ASSOC);

    if (isset($slide[0])) {
      $slide = json_encode($slide[0]);
      echo $slide;
    }
  }

  function getSlidesbyId(){
    require ("database.php");

    $qSlides = $pdo -> prepare("SELECT slide_id FROM pSlides");
    $qSlides -> execute();
    $Slides = $qSlides -> fetchAll(PDO::FETCH_ASSOC);

    return $Slides;
  }

  function updateCurrentSlide($newSlide, $newContent){
    require("database.php");

    $qSlide = $pdo -> prepare("UPDATE pSlides SET slide_content=:slideContent WHERE slide_id = :slideID");
    $qSlide -> bindParam(":slideID", $newSlide);
    $qSlide -> bindParam(":slideContent", $newContent);
    $qSlide -> execute();

  }

  function getAllYears(){
    require("database.php");

    $currentYear = getCurrentYear();
    $allYears = array($currentYear);

    $qStud = $pdo -> prepare("SELECT stud_year FROM students WHERE stud_year <> :currentYear GROUP BY stud_year");
    $qStud -> bindParam(":currentYear", $currentYear);
    $qStud -> execute();
    $years = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    foreach ($years as $key => $value) {
      array_push($allYears, $value["stud_year"]);
    }

    rsort($allYears);

    return $allYears;
  }

  function changeCurrentYear($newYear){
    require("database.php");
    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:newYear WHERE setting_name='currentYear'");
    $qStud -> bindParam(":newYear", $newYear);
    $qStud -> execute();
  }

  function updateAnnouncement($announcementName, $announcementValue){
    require("database.php");
    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:announcementName WHERE setting_name='announcementName'");
    $qStud -> bindParam(":announcementName", $announcementName);
    $qStud -> execute();

    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:announcementValue WHERE setting_name='announcementValue'");
    $qStud -> bindParam(":announcementValue", $announcementValue);
    $qStud -> execute();
  }

  function getAnnouncement(){
    require("database.php");
    $qStud = $pdo -> prepare("SELECT * FROM settings WHERE setting_name='announcementName' || setting_name='announcementValue'");
    $qStud -> execute();
    $announcement = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return array("announcementName" => $announcement[0]["setting_value"], "announcementValue" => $announcement[1]["setting_value"]);;
  }

  function getSlides($type){
    require("database.php");
    if ($type == 0 || $type == 1){
      //Return enabled preshow or posthow slides

      //If $type == 0, get the preshow slides
      //If $type == 1, get the postshow slides
      $qPreSlides = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_prepost = :prepost AND slide_enabled = 1");
      $qPreSlides -> bindParam(":prepost", $type);
      $qPreSlides -> execute();
      $preSlides = $qPreSlides -> fetchAll(PDO::FETCH_ASSOC);
    }
    elseif ($type == 2) {
      //Return enabled disabled slides
      $qPreSlides = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_enabled = 0");
      $qPreSlides -> execute();
      $preSlides = $qPreSlides -> fetchAll(PDO::FETCH_ASSOC);
    }
    return $preSlides;
  }

  function getSlideOrder($type){
    require("database.php");

    $qPreSlides = $pdo -> prepare("SELECT setting_value FROM settings WHERE setting_name = :orderType");

    //If $type == 0, get the preshow slide order
    if ($type == 0){
      $type = "preOrder";
      $qPreSlides -> bindParam(":orderType", $type);
    }
    //If $type == 1, get the postshow slide order
    elseif ($type == 1) {
      $type = "postOrder";
      $qPreSlides -> bindParam(":orderType", $type);
    }
    //If $type == 2, get the disabled slide order
    elseif ($type == 2) {
      $type = "disabledOrder";
      $qPreSlides -> bindParam(":orderType", $type);
    }

    $qPreSlides -> execute();
    $slideOrder = $qPreSlides -> fetchAll(PDO::FETCH_ASSOC);
    $slideOrder = json_decode($slideOrder[0]["setting_value"], true);

    return $slideOrder;
  }

  function printSlides($slides, $slideOrder, $type){
    if ($type == 0) {
      $colour = "primary";
    }
    elseif ($type == 1) {
      $colour = "info";
    }
    elseif ($type == 2) {
      $colour = "secondary";
    }

    foreach ($slideOrder as $value) {
      foreach ($slides as $slideContent) {
        if ($slideContent['slide_name'] != "") {
          $slideName = $slideContent['slide_name'];
        }
        else {
          //If the slide name is not set, name it: "Slide " + slideID
          $slideName = "Slide ".$slideContent['slide_id'];
        }

        if ($value == $slideContent["slide_id"]) {
          echo "<div id='".$slideContent["slide_id"]."' class='btn btn-".$colour." my-2 p-3 col-12 slide'>";
          echo "<div class='row'>";

          echo "<div class='col-10 px-1 overflow-auto' onclick='window.open(\"editSlides.php?id=".$slideContent["slide_id"]."\");'>\n";
          echo "<p>".$slideName."</p>";
          echo "</div>\n";
          echo "<div class='col-2 px-1'>\n";
          echo "<button type='button' class='btn btn-outline-danger btn-lg h-100 w-100' onclick=deleteSlide(".$slideContent["slide_id"].")>\n";
          echo "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>\n";
          echo "<path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'></path>\n";
          echo "<path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'></path>\n";
          echo "</svg>\n";
          echo "</button>\n";
          echo "</div>\n";

          echo "</div>\n";
          echo "</div>\n\n";
        }
      }
    }
  }

  function updateAllStudInfo($ajaxStudId,$ajaxFName,$ajaxLName,$ajaxStudNum,$ajaxEmail,$ajaxAwards,$ajaxPlans,$ajaxScholarships,$ajaxMemMoments){
        require("database.php");
        $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_id=:stud_id");
        $qStud -> bindParam(":stud_id", $ajaxStudId);
        $qStud -> execute();
        $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);
        $student = $student[0];
        //If the student number is being changed, rename all of the student's pictures
        if ($student["stud_num"] != $ajaxStudNum){
          $pictureInfo = array(
            '_a.jpg',
            '_b.jpg',
            '_c.jpg',
            '_d.jpg',
            '_e.jpg',
          );
          foreach ($pictureInfo as $pictureEnding) {
            $fullPath = getFullPath();
            $basePath = $fullPath.$student["stud_year"]."/";
            $pathToPic = $basePath.$student["stud_num"].$pictureEnding;
            if (file_exists($pathToPic)) {
              rename($pathToPic, $basePath.$ajaxStudNum.$pictureEnding);
            }
          }
        }

        $qStud = $pdo -> prepare("UPDATE students
                                  SET stud_fname = :ajaxFName,
                                      stud_lname = :ajaxLName,
                                      stud_num = :ajaxStudNum,
                                      stud_awards = :ajaxAwards,
                                      stud_plans = :ajaxPlans,
                                      stud_memMoments = :ajaxMemMoments,
                                      stud_scholarships = :ajaxScholarships
                                  WHERE stud_id = :id");
        $qStud -> bindParam(":ajaxFName", $ajaxFName);
        $qStud -> bindParam(":ajaxLName", $ajaxLName);
        $qStud -> bindParam(":ajaxStudNum", $ajaxStudNum);
        $qStud -> bindParam(":ajaxAwards", $ajaxAwards);
        $qStud -> bindParam(":ajaxPlans", $ajaxPlans);
        $qStud -> bindParam(":ajaxMemMoments", $ajaxMemMoments);
        $qStud -> bindParam(":ajaxScholarships", $ajaxScholarships);
        $qStud -> bindParam(":id", $ajaxStudId);
        $qStud -> execute();

        $qStud = $pdo -> prepare("UPDATE login
                                  SET login_email = :ajaxEmail
                                  WHERE login_stud_id = :id");
        $qStud -> bindParam(":ajaxEmail", $ajaxEmail);
        $qStud -> bindParam(":id", $ajaxStudId);
        $qStud -> execute();
  }

  function updatePassword($ajaxStudID, $ajaxPass){
    require("database.php");
    $hashedPassword = password_hash($ajaxPass, PASSWORD_DEFAULT);

    $qStud = $pdo -> prepare("UPDATE login
                              SET login_pass = :ajaxPass
                              WHERE login_stud_id = :ajaxStudID");
    $qStud -> bindParam(":ajaxPass", $hashedPassword);
    $qStud -> bindParam(":ajaxStudID", $ajaxStudID);
    $qStud -> execute();
  }

  function uploadStudents($year, $csvFile){
    require("database.php");

    $file = fopen($csvFile['tmp_name'], "r");
    $defaultJSON = "[]";
    while ($row = fgetcsv($file)){
      $qStud = $pdo -> prepare("INSERT INTO students (stud_num, stud_lname, stud_fname, stud_enabled, stud_year, stud_awards, stud_plans, stud_scholarships) VALUES (:sNum, :lName, :fName, 1, :year, :awards, :plans, :scholarships)");
      $qStud -> bindParam(":sNum", $row[2]);
      $qStud -> bindParam(":lName", $row[3]);
      $qStud -> bindParam(":fName", $row[4]);
      $qStud -> bindParam(":year", $year);
      $qStud -> bindParam(":awards", $defaultJSON);
      $qStud -> bindParam(":plans", $defaultJSON);
      $qStud -> bindParam(":scholarships", $defaultJSON);
      $qStud -> execute();

      $qStud = $pdo -> prepare("SELECT stud_id FROM students WHERE stud_num = :sNum");
      $qStud -> bindParam(":sNum", $row[2]);
      $qStud -> execute();
      $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);

      $studID = $student[0]["stud_id"];
      $password = password_hash($row[1], PASSWORD_DEFAULT);

      $qStud = $pdo -> prepare("INSERT INTO login (login_email, login_pass, login_isAdmin, login_stud_id) VALUES (:email, :password, 0, :studID)");
      $qStud -> bindParam(":email", $row[0]);
      $qStud -> bindParam(":password", $password);
      $qStud -> bindParam(":studID", $studID);
      $qStud -> execute();
    }
    fclose($file);
  }
  //Return students in the student table from certain year
  function specStudents($year){
    require("database.php");
    $qStud = $pdo -> prepare("SELECT stud_id, stud_lname, stud_fname, stud_enabled
                              FROM students
                              WHERE stud_year =:year");
    $qStud -> bindParam(":year", $year);
    $qStud -> execute();
    $students = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $students;
  }

  function updateSlide($slide_id, $slide_name, $slide_content){
    require("database.php");

    $qStud = $pdo -> prepare("UPDATE pSlides SET slide_content=:content, slide_name=:name WHERE slide_id = :id");
    $qStud -> bindParam(":id", $slide_id);
    $qStud -> bindParam(":content", $slide_content);
    $qStud -> bindParam(":name", $slide_name);
    $qStud -> execute();
  }

  function getAllSlides(){
    require ("database.php");

    $qSlides = $pdo -> prepare("SELECT * FROM pSlides");
    $qSlides -> execute();
    $Slides = $qSlides -> fetchAll(PDO::FETCH_ASSOC);

    return $Slides;
  }

  function getStudNumber($studID){
    require("database.php");
    $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_id=:id");
    $qStud -> bindParam(":id", $studID);
    $qStud -> execute();
    $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $student[0]["stud_num"];
  }

  function matchStudent($picturePath, $selectedPic, $newPictureName){
    rename($picturePath.$selectedPic, $picturePath.$newPictureName);
  }

  function updateOrder($preOrder, $postOrder, $inactiveOrder){
    require("database.php");
    $qOrder = $pdo -> prepare("UPDATE settings SET setting_value = :pre WHERE setting_name = 'preOrder'");
    $qOrder -> bindParam(":pre", $preOrder);
    $qOrder -> execute();

    $qOrder = $pdo -> prepare("UPDATE settings SET setting_value = :post WHERE setting_name = 'postOrder'");
    $qOrder -> bindParam(":post", $postOrder);
    $qOrder -> execute();

    $qOrder = $pdo -> prepare("UPDATE settings SET setting_value = :disabled WHERE setting_name = 'disabledOrder'");
    $qOrder -> bindParam(":disabled", $inactiveOrder);
    $qOrder -> execute();

    $preOrder = json_decode($preOrder);
    $postOrder = json_decode($postOrder);
    $disabledOrder = json_decode($inactiveOrder);

    foreach ($preOrder as $key => $id) {
      $qSlide = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_id = :id");
      $qSlide -> bindParam(":id", $id);
      $qSlide -> execute();
      $slide = $qSlide -> fetchAll(PDO::FETCH_ASSOC);

      if ($slide[0]['slide_prepost'] != 0 || $slide[0]['slide_enabled'] != 1) {
        $qPre = $pdo -> prepare("UPDATE pSlides
                                   SET slide_prepost = 0, slide_enabled = 1
                                   WHERE slide_id = :id");
        $qPre -> bindParam(":id", $id);
        $qPre -> execute();
      }
    }
    foreach ($postOrder as $key => $id) {
      $qSlide = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_id = :id");
      $qSlide -> bindParam(":id", $id);
      $qSlide -> execute();
      $slide = $qSlide -> fetchAll(PDO::FETCH_ASSOC);

      if ($slide[0]['slide_prepost'] != 1 || $slide[0]['slide_enabled'] != 1) {
        $qPost = $pdo -> prepare("UPDATE pSlides SET slide_prepost = 1, slide_enabled = 1 WHERE slide_id = :id");
        $qPost -> bindParam(":id", $id);
        $qPost -> execute();
      }
    }
    foreach ($disabledOrder as $key => $id) {
      $qSlide = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_id = :id");
      $qSlide -> bindParam(":id", $id);
      $qSlide -> execute();
      $slide = $qSlide -> fetchAll(PDO::FETCH_ASSOC);

      if ($slide[0]['slide_enabled'] != 0) {
        $qDisabled = $pdo -> prepare("UPDATE pSlides SET slide_enabled = 0 WHERE slide_id = :id");
        $qDisabled -> bindParam(":id", $id);
        $qDisabled -> execute();
      }
    }
  }

  function deleteSlide($slideID){
    require("database.php");
    $q = $pdo -> prepare("DELETE FROM pSlides WHERE slide_id = :id");
    $q -> bindParam(":id", $slideID);
    $q -> execute();
  }

  function createSlide($slideType, $slideName){
    require("database.php");

    $insertSlideType = $slideType;
    $slideEnabled = 1;
    if ($slideType == 2){
      $insertSlideType = 0;
      $slideEnabled = 0;
    }

    $qSlide = $pdo -> prepare("INSERT INTO pSlides (slide_prepost, slide_enabled, slide_name) VALUES (:slideType, :slide_enabled, :slide_name)");
    $qSlide -> bindParam(":slideType", $insertSlideType);
    $qSlide -> bindParam(":slide_enabled", $slideEnabled);
    $qSlide -> bindParam(":slide_name", $slideName);
    $qSlide -> execute();
    $insertedSlideID = $pdo->lastInsertId();

    $slideOrder = getSlideOrder($slideType);
    array_push($slideOrder, $insertedSlideID);
    $slideOrder = json_encode($slideOrder);

    if ($slideType == 0){
      $orderType = "preOrder";
    }
    elseif ($slideType == 1) {
      $orderType = "postOrder";
    }
    elseif ($slideType == 2) {
      $orderType = "disabledOrder";
    }

    $qOrder = $pdo -> prepare("UPDATE settings SET setting_value = :slideOrder WHERE setting_name = :orderType");
    $qOrder -> bindParam(":slideOrder", $slideOrder);
    $qOrder -> bindParam(":orderType", $orderType);
    $qOrder -> execute();
  }

  function getPSlides($slideID = -1){
    require("database.php");

    if ($slideID == -1) {
      $qStud = $pdo -> prepare("SELECT * FROM pSlides ORDER BY slide_id");
      $qStud -> execute();
      $slides = $qStud -> fetchAll(PDO::FETCH_ASSOC);

      return $slides;
    }
    else {
      $qStud = $pdo -> prepare("SELECT * FROM pSlides WHERE slide_id = :id");
      $qStud -> bindParam(":id", $slideID);
      $qStud -> execute();
      $slide = $qStud -> fetchAll(PDO::FETCH_ASSOC);

      return $slide[0];
    }
  }

  function updateStudInfo($studID, $sNumber, $plans, $scholarships, $memMoments){
    require("database.php");

    $qStud = $pdo -> prepare("SELECT * FROM students WHERE stud_id=:id");
    $qStud -> bindParam(":id", $studID);
    $qStud -> execute();
    $student = $qStud -> fetchAll(PDO::FETCH_ASSOC);
    $sNumberTrue = $student[0]["stud_num"];

    if ($sNumber == $sNumberTrue){
      $qStud = $pdo -> prepare("UPDATE students
                                SET stud_plans = :ajaxPlans,
                                    stud_memMoments = :ajaxMemMoments,
                                    stud_scholarships = :ajaxScholarships
                                WHERE stud_id = :id");
      $qStud -> bindParam(":ajaxPlans", $plans);
      $qStud -> bindParam(":ajaxMemMoments", $memMoments);
      $qStud -> bindParam(":ajaxScholarships", $scholarships);
      $qStud -> bindParam(":id", $studID);
      $qStud -> execute();
    }
  }

  function removePicture($picturePath){
    unlink($picturePath);
  }

  function getSlideInterval(){
    require("database.php");

    $qStud = $pdo -> prepare("SELECT * FROM settings WHERE setting_name='pictureFadeTime'");
    $qStud -> execute();
    $interval = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $interval[0]["setting_value"];
  }

  function getDeadlineDate(){
    require("database.php");

    $qStud = $pdo -> prepare("SELECT * FROM settings WHERE setting_name='studentDeadline'");
    $qStud -> execute();
    $interval = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $interval[0]["setting_value"];
  }

  function updateDeadline($newDeadline){
    require("database.php");
    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:newDeadline WHERE setting_name='studentDeadline'");
    $qStud -> bindParam(":newDeadline", $newDeadline);
    $qStud -> execute();
  }

  function getNotification($notificationID){
    require("database.php");
    $qNotif = $pdo -> prepare("SELECT * FROM issues WHERE issues_id = :id");
    $qNotif -> bindParam(":id", $notificationID);
    $qNotif -> execute();
    $notification = $qNotif->fetchAll(PDO::FETCH_ASSOC);

    return $notification;
  }

  function getSlideBackgroundColour(){
    require("database.php");

    $qStud = $pdo -> prepare("SELECT * FROM settings WHERE setting_name='slideBackgroundColour'");
    $qStud -> execute();
    $slideBackgroundColour = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $slideBackgroundColour[0]["setting_value"];
  }

  function getSlideTextColour(){
    require("database.php");

    $qStud = $pdo -> prepare("SELECT * FROM settings WHERE setting_name='slideTextColour'");
    $qStud -> execute();
    $slideTextColour = $qStud -> fetchAll(PDO::FETCH_ASSOC);

    return $slideTextColour[0]["setting_value"];
  }

  function updateSlideSettings($intervalDuration, $slideBackgroundColour, $slideTextColour){
    require("database.php");

    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:intervalDuration WHERE setting_name='pictureFadeTime'");
    $qStud -> bindParam(":intervalDuration", $intervalDuration);
    $qStud -> execute();

    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:slideBackgroundColour WHERE setting_name='slideBackgroundColour'");
    $qStud -> bindParam(":slideBackgroundColour", $slideBackgroundColour);
    $qStud -> execute();

    $qStud = $pdo -> prepare("UPDATE settings SET setting_value=:slideTextColour WHERE setting_name='slideTextColour'");
    $qStud -> bindParam(":slideTextColour", $slideTextColour);
    $qStud -> execute();
  }

  function matchStudentByName($lastName, $firstName, $year){
    require("database.php");
    $query = $pdo->prepare("SELECT * FROM students WHERE stud_lname=:lname AND stud_fname=:fname AND stud_year=:year");
    $query->bindParam(":lname", $lastName);
    $query->bindParam(":fname", $firstName);
    $query->bindParam(":year", $year);
    $query->execute();
    $user = $query->fetchAll(PDO::FETCH_ASSOC);

    return $user;
  }

  function printSidebar($selectedPageLink=null){
    $pageLinkPairs = array(
      "studentList.php" => "Student List",
      "slideOrder.php" => "Slide Order",
      "editSlides.php" => "Edit Slide",
      "notifications.php" => "Notifications",
      "slideSettings.php" => "Slide Settings",
      "archivedYears.php" => "Archived Years",
      "announcements.php" => "Announcements",
      "startSlideshow.php" => "Start Slideshow"
    );
    echo "\n<div id='mySidebar' class='sidebar'>\n";
    echo "<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>×</a>\n";
    foreach ($pageLinkPairs as $pageLink => $pageName) {
      if ($pageLink == $selectedPageLink){
        echo "<a class='active' href='".$pageLink."'>".$pageName."</a>\n";
      }
      else {
        echo "<a href='".$pageLink."'>".$pageName."</a>\n";
      }
    }
    echo "</div>\n\n";
  }

  function printNavbar(){
    echo '
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <button class="openbtn me-4" onclick="openNav()">☰</button>
        <span class="navbar-brand">Admin</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
        <img src="https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png" alt="WCSS Logo" width="50">
      </div>
    </nav>';
  }

  function recordLogin($studID){
    require("database.php");
    $activityType = "Login";
    $qStud = $pdo -> prepare("INSERT INTO logs (log_stud_id, log_activityType) VALUES (:studID, :activityType)");
    $qStud -> bindParam(":studID", $studID);
    $qStud -> bindParam(":activityType", $activityType);
    $qStud -> execute();
  }
  function recordLogout($studID){
    require("database.php");
    $activityType = "Logout";
    $qStud = $pdo -> prepare("INSERT INTO logs (log_stud_id, log_activityType) VALUES (:studID, :activityType)");
    $qStud -> bindParam(":studID", $studID);
    $qStud -> bindParam(":activityType", $activityType);
    $qStud -> execute();
  }
  function recordSave($studID){
    require("database.php");
    $activityType = "Saved";
    $qStud = $pdo -> prepare("INSERT INTO logs (log_stud_id, log_activityType) VALUES (:studID, :activityType)");
    $qStud -> bindParam(":studID", $studID);
    $qStud -> bindParam(":activityType", $activityType);
    $qStud -> execute();
  }

  function getFullPath(){
    require("database.php");
    $settingName = "fullPath";
    $query = $pdo->prepare("SELECT * FROM settings WHERE setting_name=:settingName");
    $query -> bindParam(":settingName", $settingName);
    $query->execute();
    $path = $query->fetchAll(PDO::FETCH_ASSOC);

    return $path[0]["setting_value"];
  }

  function getStudentPicsPath(){
    require("database.php");
    $settingName = "fullPath";
    $query = $pdo->prepare("SELECT * FROM settings WHERE setting_name=:settingName");
    $query -> bindParam(":settingName", $settingName);
    $query->execute();
    $path = $query->fetchAll(PDO::FETCH_ASSOC);
    $path = $path[0]["setting_value"];
    $path = explode('/', $path);
    $path = $path[count($path)-2];

    return $path."/";
  }
?>

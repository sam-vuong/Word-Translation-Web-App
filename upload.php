<?php
// Sam Vuong | San Jose State University | CS-174 | Final: Lame Translate
  //=========================================================================================================
  // HTML
  require_once 'functions.php';
  require_once 'login.php';
  echo <<<_END
  <html><head><title>Lame Translate - Upload</title>
  <script type="text/javascript" src="validate_functions.js">
  </script>
  </head><body>
  <form method="post" action="logout.php" enctype="multipart/form-data">
    <input type="submit" value="Log Out">
  </form>
  <form method="post" action="upload.php" enctype="multipart/form-data">
    Upload English: <input type="file" name="english" size="10">
    <br>
    Upload Translation: <input type="file" name="translation" size="10">
    <br>
    <input type="submit" value="Submit">
  </form>
  <form method="post" action="upload.php" onsubmit="return validate_text(this)" enctype="multipart/form-data">
    Translate the following: <input type="text" name="input" size="30">
    <br>
    <input type="submit" value="Submit">
  </form>
_END;
  //===============================================================================================================
  // PHP
  // Session restoration
  session_start();
  // Prevent session fixation
  if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = 1;
  }
  if (isset($_SESSION['username'])) {
    // Prevent session hijacking via packet sniffing
    if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])) different_user();
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
  }
  else echo "Please <a href='authenticate.php'>click here</a> to log in.";

  // Translation Model Upload
  $conn = new mysqli($hn, $un, $pw, $db);
  // Check for query success: if failed, provide user-friendly error message and exit out of php script
  if ($conn->connect_error) die(mysql_fatal_error("Unable to establish a connection to the database!"));
  // Check for uploaded files
  if ($_FILES) {
    $title1 = $_FILES['english']['tmp_name'];
    // ensure that the files are in plain text
    $type1 = $_FILES['english']['type'];
    switch ($type1) {
      case 'text/plain' : $ext1 = 'plain'; break;
      default           : $ext1 = ''; break;
    }
    $title2 = $_FILES['translation']['tmp_name'];
    // ensure that the file is a plain text file
    $type2 = $_FILES['translation']['type'];
    switch ($type2) {
      case 'text/plain' : $ext2 = 'plain'; break;
      default           : $ext2 = ''; break;
    }
    if ($ext1 && $ext2) {
      // Add translation model to the database
      $resultStr1 = file_get_contents($title1);
      $resultStr2 = file_get_contents($title2);
      add_English($conn, $username, $resultStr1);
      add_Translation($conn, $username, $resultStr2);
      $result->close();
    }
    else echo "Unsupported file extension! Please upload a plain text file." . "<br>";
  }

  // Obtain translation based on user's stored translation model
  if (isset($_POST['input'])) {
    $conn = new mysqli($hn, $un, $pw, $db);
    // Check for query success: if failed, provide user-friendly error message and exit out of php script
    if ($conn->connect_error) die(mysql_fatal_error("Unable to establish a connection to the database!"));
    $query = "SELECT * FROM users WHERE Username = '$username'";
    $result = $conn->query($query);
    if (!result) die (mysql_fatal_error("Unable to retrieve table contents!")); // should change error message
    elseif ($result->num_rows) {
      $result->data_seek(0);
      $entry = $result->fetch_array(MYSQLI_ASSOC);
      $result->close();
      $text = mysql_entities_fix_string($conn, $_POST['input']);
      $eng = $entry['English'];
      $trans = $entry['Translation'];
      // If user does not have a translation model, use the default model
      if ($eng == null || $trans == null) {
        $eng = file_get_contents("default_model_english.txt");
        $trans = file_get_contents('default_model_translation.txt');
      }
      $res = obtain_translation($text, $eng, $trans);
      echo "This is the translation: $res";
    }
    else die("Invalid username...");
  }
  $conn->close();
?>

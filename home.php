<?php
// Sam Vuong | San Jose State University | CS-174 | Final: Lame Translate
require_once 'functions.php';
require_once 'login.php';
  //==========================================================================================
  // HTML
  echo <<<_END
  <html><head><title>Lame Translate - Home Page</title>
    <script type="text/javascript" src="validate_functions.js">
    </script>
  </head><body>
  <form method="post" action="registration.php" enctype="multipart/form-data">
    <input type="submit" value="Sign Up">
  </form>
  <form method="post" action="authenticate.php" enctype="multipart/form-data">
    <input type="submit" value="Log In">
  </form>
  <form method="post" action="home.php" onsubmit="return validate_text(this)" enctype="multipart/form-data">
    Translate the following: <input type="text" name="input" size="30">
    <br>
    <input type="submit" value="Submit">
  </form>
_END;
  //==========================================================================================
  // PHP
  // Successful sign-up message
  if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
  }

  // Obtain translation based on default translation model
  if (isset($_POST['input'])) {
    $conn = new mysqli($hn, $un, $pw, $db);
    // Check for query success: if failed, provide user-friendly error message and exit out of php script
    if ($conn->connect_error) die(mysql_fatal_error("Unable to establish a connection to the database!"));
    $eng = file_get_contents("default_model_english.txt");
    //echo "$eng";
    $trans = file_get_contents('default_model_translation.txt');
    //echo "$trans";
    $text = mysql_entities_fix_string($conn, $_POST['input']);
    $res = obtain_translation($text, $eng, $trans);
    echo "This is the translation: $res";
    $conn->close();
  }
?>

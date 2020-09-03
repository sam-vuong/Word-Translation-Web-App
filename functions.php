<?php

  function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
  }

  function mysql_fix_string($connection, $string) {
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $connection->real_escape_string($string);
  }

  function mysql_fatal_error($msg) {
    echo <<< _END
    We're sorry about this, but we are unable to satisfy your request.
    The error message we got was:
    <p>$msg</p>
    _END;
  }

  function add_user($connection, $username, $password, $salt) {
    echo "adding user...";
    $query = "INSERT INTO users(Username, Password, Salt) VALUES('$username', '$password', '$salt')";
    $result = $connection->query($query);
    if (!result) die(mysql_fatal_error("Unable to add user to the database"));
  }

  function add_English($connection, $username, $content) {
    echo "adding english definitions...";
    $query = "UPDATE users SET English='$content' WHERE Username = '$username'";
    $result = $connection->query($query);
    if (!result) die(mysql_fatal_error("Unable to add user to the database"));
  }

  function add_Translation($connection, $username, $content) {
    echo "adding translation definitions...";
    $query = "UPDATE users SET Translation='$content' WHERE Username = '$username'";
    $result = $connection->query($query);
    if (!result) die(mysql_fatal_error("Unable to add user to the database"));
  }

  function obtain_translation($word, $english, $translation) {
    // Put the english dictionary into an array
    $engArray = explode(", ", $english);
    print_r($engArray);
    // Put the translation into an array
    $transArray = explode(", ", $translation);
    print_r($transArray);
    // Look for inputted word in the english dictionary, storing the index
    $key = array_search($word, $engArray);
    // Word entered not found in english dictionary
    echo "$key";
    if ($key === false) return "Translation not found!";
    // Return word in translation with the corresponding index
    return $transArray[$key];
  }

  function destroy_session_and_data() {
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
  }

  function different_user() {
    destroy_session_and_data();
    echo "Technical error encountered.";
    die ("<p><a href=authenticate.php>Please re-log.</a></p>");
  }
?>

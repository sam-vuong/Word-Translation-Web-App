<?php
  require_once 'functions.php';
  destroy_session_and_data();
  header("Location:home.php?Message=Successfully logged out!" . urlencode($Message));
  exit;
?>

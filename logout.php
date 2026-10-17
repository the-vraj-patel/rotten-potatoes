<?php
/*
 * logout.php — ends the session and goes back to the homepage.
 */
include_once "includes/auth.php";

session_unset();        // remove user_id and user_name
session_destroy();      // end the session

header("Location: index.php");
exit;
?>

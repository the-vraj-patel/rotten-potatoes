<?php
/*
 * auth.php — login sessions.
 * Must be included before any HTML is sent, because session_start() uses a cookie.
 *
 * After login, the session remembers:
 *   $_SESSION["user_id"]   : the member ID (id in the users table)
 *   $_SESSION["user_name"] : the member's name
 */
session_start();

// true when someone is logged in
function isLoggedIn() {
    return isset($_SESSION["user_id"]);
}

// The logged-in member's id, or 0 for visitors
function currentUserId() {
    if (isLoggedIn()) {
        return $_SESSION["user_id"];
    }
    return 0;
}

// Logs a member in by saving their id and name in the session
function loginUser($id, $name) {
    $_SESSION["user_id"]   = $id;
    $_SESSION["user_name"] = $name;
}

// Pages we may send people back to after login (anything else goes to the homepage).
// This stops a link from sending visitors to another website after logging in.
function safeReturn($url) {
    if (strpos($url, "details.php?id=") === 0 || $url == "wishlist.php" || $url == "index.php") {
        return $url;
    }
    return "index.php";
}

// A one-time message shown at the top of the next page (e.g. "Welcome back!")
function setFlash($text) {
    $_SESSION["flash"] = $text;
}

function takeFlash() {
    if (isset($_SESSION["flash"])) {
        $text = $_SESSION["flash"];
        unset($_SESSION["flash"]);
        return $text;
    }
    return "";
}
?>

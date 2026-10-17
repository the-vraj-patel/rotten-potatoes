<?php
/*
 * db.php — connects to MySQL and handles the visitors' star ratings (CRUD).
 *
 * Before using it, create the database by importing database.sql in phpMyAdmin.
 * XAMPP's default MySQL login is user "root" with an empty password.
 *
 * Every query uses prepared statements (the ? placeholders), so text typed by
 * visitors can never change the SQL (protection against SQL injection).
 */

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "rotten_potatoes";

// Don't stop the whole page if MySQL is off: we check $conn ourselves below
mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// $dbReady is false when MySQL isn't running or database.sql wasn't imported yet
$dbReady = ($conn != false);

// Saves a rating. If this visitor already rated it, their old rating is changed.
function saveRating($conn, $item, $episode, $stars, $voter) {
    // READ: has this visitor rated it before?
    $stmt = mysqli_prepare($conn, "SELECT id FROM ratings WHERE item = ? AND episode = ? AND voter = ?");
    mysqli_stmt_bind_param($stmt, "sss", $item, $episode, $voter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // UPDATE the old rating
        $stmt = mysqli_prepare($conn, "UPDATE ratings SET stars = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $stars, $row["id"]);
    } else {
        // CREATE a new rating
        $stmt = mysqli_prepare($conn, "INSERT INTO ratings (item, episode, stars, voter) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssis", $item, $episode, $stars, $voter);
    }
    mysqli_stmt_execute($stmt);
}

// Returns the visitor ratings of one title and all its episodes:
//   array("" => array("avg" => 4.2, "count" => 10, "mine" => 5), "s1e1" => array(...), ...)
// "" is the rating of the whole title, "mine" is this visitor's own rating (0 = not rated)
function getRatings($conn, $item, $voter) {
    $ratings = array();

    // Average and number of ratings for every episode
    $stmt = mysqli_prepare($conn, "SELECT episode, AVG(stars) AS average, COUNT(*) AS total
                                   FROM ratings WHERE item = ? GROUP BY episode");
    mysqli_stmt_bind_param($stmt, "s", $item);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $ratings[$row["episode"]] = array("avg" => round($row["average"], 1), "count" => $row["total"], "mine" => 0);
    }

    // This visitor's own ratings
    $stmt = mysqli_prepare($conn, "SELECT episode, stars FROM ratings WHERE item = ? AND voter = ?");
    mysqli_stmt_bind_param($stmt, "ss", $item, $voter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $ratings[$row["episode"]]["mine"] = $row["stars"];
    }

    return $ratings;
}
?>

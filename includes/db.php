<?php
/*
 * db.php — connects to MySQL and holds all database functions (CRUD):
 * users (register / login), star ratings, written reviews and wishlists.
 *
 * The database and its tables are created by install.php (open it once).
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

// $dbReady is false when MySQL isn't running or install.php wasn't opened yet
$dbReady = false;
if ($conn) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    if ($check && mysqli_num_rows($check) == 1) {
        $dbReady = true;
    }
}

// Reads all rows of an executed statement into an array
function fetchAllRows($stmt) {
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// ---------- Users ----------

// READ: one user by email (or null if nobody has that email)
function findUserByEmail($conn, $email) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, email, password, created_at FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $rows = fetchAllRows($stmt);
    if (count($rows) == 0) {
        return null;
    }
    return $rows[0];
}

// READ: one user by id
function findUserById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, email, created_at FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $rows = fetchAllRows($stmt);
    if (count($rows) == 0) {
        return null;
    }
    return $rows[0];
}

// CREATE: a new user. Returns the new member id (given by AUTO_INCREMENT).
// The password is stored as a hash (scrambled), never as plain text.
function createUser($conn, $name, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($conn);
}

// ---------- Star ratings ----------

// Saves a rating. If this user already rated it, their old rating is changed.
function saveRating($conn, $userId, $item, $episode, $stars) {
    // READ: has this user rated it before?
    $stmt = mysqli_prepare($conn, "SELECT id FROM ratings WHERE user_id = ? AND item = ? AND episode = ?");
    mysqli_stmt_bind_param($stmt, "iss", $userId, $item, $episode);
    mysqli_stmt_execute($stmt);
    $rows = fetchAllRows($stmt);

    if (count($rows) > 0) {
        // UPDATE the old rating
        $stmt = mysqli_prepare($conn, "UPDATE ratings SET stars = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $stars, $rows[0]["id"]);
    } else {
        // CREATE a new rating
        $stmt = mysqli_prepare($conn, "INSERT INTO ratings (user_id, item, episode, stars) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issi", $userId, $item, $episode, $stars);
    }
    mysqli_stmt_execute($stmt);
}

// Returns the ratings of one title and all its episodes:
//   array("" => array("avg" => 4.2, "count" => 10, "mine" => 5), "s1e1" => array(...), ...)
// "" is the rating of the whole title, "mine" is this user's own rating (0 = not rated)
function getRatings($conn, $item, $userId) {
    $ratings = array();

    // Average and number of ratings for every episode
    $stmt = mysqli_prepare($conn, "SELECT episode, AVG(stars) AS average, COUNT(*) AS total
                                   FROM ratings WHERE item = ? GROUP BY episode");
    mysqli_stmt_bind_param($stmt, "s", $item);
    mysqli_stmt_execute($stmt);
    foreach (fetchAllRows($stmt) as $row) {
        $ratings[$row["episode"]] = array("avg" => round($row["average"], 1), "count" => $row["total"], "mine" => 0);
    }

    // This user's own ratings
    $stmt = mysqli_prepare($conn, "SELECT episode, stars FROM ratings WHERE item = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $item, $userId);
    mysqli_stmt_execute($stmt);
    foreach (fetchAllRows($stmt) as $row) {
        $ratings[$row["episode"]]["mine"] = $row["stars"];
    }

    return $ratings;
}

// ---------- Written reviews ----------

// CREATE: saves a written review
function saveReview($conn, $userId, $name, $item, $stars, $review) {
    $stmt = mysqli_prepare($conn, "INSERT INTO reviews (user_id, name, item, stars, review) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issis", $userId, $name, $item, $stars, $review);
    mysqli_stmt_execute($stmt);
}

// READ: all reviews of one title, newest first
function getReviews($conn, $item) {
    $stmt = mysqli_prepare($conn, "SELECT name, stars, review, is_sample, created_at
                                   FROM reviews WHERE item = ? ORDER BY created_at DESC, id DESC");
    mysqli_stmt_bind_param($stmt, "s", $item);
    mysqli_stmt_execute($stmt);
    return fetchAllRows($stmt);
}

// ---------- Wishlist ----------

// READ: the ids of all titles in a user's wishlist, newest first
function getWishlist($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT item FROM wishlist WHERE user_id = ? ORDER BY created_at DESC, id DESC");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $ids = array();
    foreach (fetchAllRows($stmt) as $row) {
        $ids[] = $row["item"];
    }
    return $ids;
}

// CREATE: adds a title to the wishlist (does nothing if it's already there)
function addToWishlist($conn, $userId, $item) {
    if (in_array($item, getWishlist($conn, $userId))) {
        return;
    }
    $stmt = mysqli_prepare($conn, "INSERT INTO wishlist (user_id, item) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "is", $userId, $item);
    mysqli_stmt_execute($stmt);
}

// DELETE: removes a title from the wishlist
function removeFromWishlist($conn, $userId, $item) {
    $stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE user_id = ? AND item = ?");
    mysqli_stmt_bind_param($stmt, "is", $userId, $item);
    mysqli_stmt_execute($stmt);
}
?>

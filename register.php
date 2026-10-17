<?php
/*
 * register.php — sign up form (name, email, password).
 * On success the new member gets an id from MySQL (AUTO_INCREMENT),
 * is logged in straight away, and is sent back to the page they came from.
 */
include_once "includes/auth.php";
include_once "includes/data.php";
include_once "includes/db.php";

// The page to go back to afterwards, e.g. details.php?id=inception#reviews
$return = isset($_GET["return"]) ? safeReturn($_GET["return"]) : "index.php";

$name  = "";
$email = "";
$error = "";

// ---- Form processing ----
if (isset($_POST["register"])) {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    if (!$dbReady) {
        $error = "The database is not set up yet. Open install.php first.";
    } elseif ($name == "" || strlen($name) > 50) {
        $error = "Please enter your name (up to 50 characters).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Your password must have at least 6 characters.";
    } elseif ($password != $confirm) {
        $error = "The two passwords are not the same.";
    } elseif (findUserByEmail($conn, $email) != null) {
        $error = "This email is already registered. Please log in instead.";
    } else {
        $newId = createUser($conn, $name, $email, $password);
        loginUser($newId, $name);
        setFlash("Welcome to Rotten Potatoes, " . $name . "! Your member ID is #" . $newId . ".");
        header("Location: " . $return);         // go back to where the visitor was
        exit;
    }
}

$pageTitle = "Register - Rotten Potatoes";
include "includes/header.php";
?>

<section class="container section">
    <div class="auth-box">
        <h2 class="auth-title">Create your account</h2>
        <p class="text-muted-light">Join to rate episodes, write reviews and make your own wishlist.</p>

        <?php if ($error != "") { ?>
            <p class="form-error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="post" action="register.php?return=<?php echo urlencode($return); ?>" id="registerForm">
            <label for="regName" class="form-label">Name</label>
            <input type="text" class="form-control" id="regName" name="name" maxlength="50"
                   value="<?php echo htmlspecialchars($name); ?>">

            <label for="regEmail" class="form-label mt-3">Email</label>
            <input type="email" class="form-control" id="regEmail" name="email" maxlength="100"
                   value="<?php echo htmlspecialchars($email); ?>">

            <label for="regPassword" class="form-label mt-3">Password <small class="text-muted-light">(at least 6 characters)</small></label>
            <input type="password" class="form-control" id="regPassword" name="password">

            <label for="regConfirm" class="form-label mt-3">Confirm password</label>
            <input type="password" class="form-control" id="regConfirm" name="confirm">

            <button type="submit" name="register" value="1" class="btn btn-potato w-100 mt-4">Register</button>
        </form>

        <p class="auth-switch">Already a member?
            <a href="login.php?return=<?php echo urlencode($return); ?>">Log in</a></p>
    </div>
</section>

<?php include "includes/footer.php"; ?>

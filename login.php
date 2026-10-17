<?php
/*
 * login.php — log in with email and password.
 * On success the member's id and name are stored in the session.
 */
include_once "includes/auth.php";
include_once "includes/data.php";
include_once "includes/db.php";

// The page to go back to afterwards, e.g. details.php?id=inception#reviews
$return = isset($_GET["return"]) ? safeReturn($_GET["return"]) : "index.php";

$email = "";
$error = "";

// ---- Form processing ----
if (isset($_POST["login"])) {
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!$dbReady) {
        $error = "The database is not set up yet. Open install.php first.";
    } else {
        $user = findUserByEmail($conn, $email);

        // password_verify compares the typed password with the saved hash
        if ($user == null || !password_verify($password, $user["password"])) {
            $error = "Wrong email or password.";
        } else {
            loginUser($user["id"], $user["name"]);
            setFlash("Welcome back, " . $user["name"] . "!");
            header("Location: " . $return);
            exit;
        }
    }
}

$pageTitle = "Log in - Rotten Potatoes";
include "includes/header.php";
?>

<section class="container section">
    <div class="auth-box">
        <h2 class="auth-title">Log in</h2>
        <p class="text-muted-light">Welcome back! Log in to rate, review and see your wishlist.</p>

        <?php if ($error != "") { ?>
            <p class="form-error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="post" action="login.php?return=<?php echo urlencode($return); ?>" id="loginForm">
            <label for="loginEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="loginEmail" name="email"
                   value="<?php echo htmlspecialchars($email); ?>">

            <label for="loginPassword" class="form-label mt-3">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="password">

            <button type="submit" name="login" value="1" class="btn btn-potato w-100 mt-4">Log in</button>
        </form>

        <p class="auth-switch">New here?
            <a href="register.php?return=<?php echo urlencode($return); ?>">Create an account</a></p>
    </div>
</section>

<?php include "includes/footer.php"; ?>

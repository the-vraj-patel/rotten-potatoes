<?php
/*
 * header.php — included at the top of every page.
 * Before including this file, a page can set:
 *   $pageTitle  : text for the browser tab
 *   $activeType : which category link to highlight (e.g. "movies")
 *   $returnHere : page to come back to after login/register (optional)
 */
include_once "auth.php";
include_once "data.php";

if (!isset($returnHere)) { $returnHere = "index.php"; }

// One-time message from the previous page, e.g. "Welcome back, Kevin!"
$flash = takeFlash();

// ---- Themes ----
// The chosen theme is saved in a cookie for one year, so it is remembered next time
$themes = array(
    "classic" => "🥔 Classic",
    "light"   => "☀️ Light",
    "neon"    => "💜 Neon Nights",
    "crimson" => "🔴 Crimson Cinema",
    "matrix"  => "💚 Digital Rain",
    "noir"    => "🎞️ Noir"
);

if (isset($_POST["theme"]) && isset($themes[$_POST["theme"]])) {
    setcookie("theme", $_POST["theme"], time() + 60 * 60 * 24 * 365, "/");
    // Load the same page again (so refreshing doesn't ask to send the form again)
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
}

$theme = "classic";
if (isset($_COOKIE["theme"]) && isset($themes[$_COOKIE["theme"]])) {
    $theme = $_COOKIE["theme"];
}

// The intro animation plays when someone opens the homepage from outside the site
// (new tab, typed address, bookmark). HTTP_REFERER is the page the visitor came from:
// if it is one of our own pages (they clicked "Home"), the intro is skipped.
$showIntro = false;
if ($activeType == "home") {
    $cameFromOurSite = isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]) !== false;
    if (!$cameFromOurSite) {
        $showIntro = true;
    }
}

if (!isset($pageTitle))  { $pageTitle = "Rotten Potatoes"; }
if (!isset($activeType)) { $activeType = ""; }

// Keep the search box filled after searching (value comes from the query string)
$searchText = isset($_GET["q"]) ? $_GET["q"] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rotten Potatoes - track and rate movies, web series, anime and books">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Our own CSS (external stylesheet). Change ?v=6 to a new number after editing the CSS,
         so browsers load the new file instead of an old saved copy. -->
    <link rel="stylesheet" href="css/style.css?v=6">
</head>
<body class="theme-<?php echo $theme; ?>">

<?php if ($showIntro) { ?>
    <!-- Intro: the logo zooms in and fades away (CSS animation) -->
    <div class="intro">
        <div class="intro-logo">🥔 ROTTEN POTATOES</div>
        <div class="intro-line"></div>
    </div>
<?php } ?>

<!-- ===== Top strip: theme picker + account links ===== -->
<div class="top-strip">
    <div class="container top-strip-inner">
        <!-- Theme picker: a Bootstrap dropdown with one small POST form -->
        <div class="dropdown theme-picker">
            <button class="btn btn-account dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">🎨 Theme</button>
            <form method="post" class="dropdown-menu dropdown-menu-end">
                <?php foreach ($themes as $key => $label) { ?>
                    <button type="submit" name="theme" value="<?php echo $key; ?>"
                            class="theme-option <?php if ($key == $theme) echo "active"; ?>">
                        <span class="theme-dot dot-<?php echo $key; ?>"></span> <?php echo $label; ?>
                    </button>
                <?php } ?>
            </form>
        </div>

        <!-- Account links: Register / Login for visitors, name + wishlist + logout for members -->
        <div class="account-links">
            <?php if (isLoggedIn()) { ?>
                <a href="wishlist.php" class="btn btn-account">❤️ My Wishlist</a>
                <span class="account-name">👤 <?php echo htmlspecialchars($_SESSION["user_name"]); ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            <?php } else { ?>
                <a href="login.php?return=<?php echo urlencode($returnHere); ?>" class="btn btn-outline-light btn-sm">Login</a>
                <a href="register.php?return=<?php echo urlencode($returnHere); ?>" class="btn btn-potato btn-sm">Register</a>
            <?php } ?>
        </div>
    </div>
</div>

<!-- ===== Site title ===== -->
<header class="site-header text-center">
    <a href="index.php" class="brand">
        <span class="brand-icon">🥔</span> Rotten Potatoes
    </a>
    <p class="brand-tagline">Track it. Rate it. Watch it.</p>
</header>

<!-- ===== Category navigation + search (Bootstrap navbar) ===== -->
<nav class="navbar navbar-expand-md navbar-dark main-nav sticky-top">
    <div class="container">
        <a class="navbar-brand d-md-none" href="index.php">🥔 Categories</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu"
                aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if ($activeType == "home") echo "active"; ?>" href="index.php">Home</a>
                </li>
                <?php foreach ($categories as $key => $label) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($activeType == $key) echo "active"; ?>"
                           href="category.php?type=<?php echo $key; ?>"><?php echo $label; ?></a>
                    </li>
                <?php } ?>
            </ul>

            <!-- Search form: sends ?q=... to search.php using GET -->
            <form class="d-flex search-form" action="search.php" method="get" id="searchForm">
                <input class="form-control me-2" type="search" name="q" id="searchInput"
                       placeholder="Movies, shows, anime, books..." aria-label="Search"
                       value="<?php echo htmlspecialchars($searchText); ?>">
                <button class="btn btn-potato" type="submit">Search</button>
            </form>

        </div>
    </div>
</nav>

<main>

<?php if ($flash != "") { ?>
    <div class="container">
        <div class="flash-message"><?php echo htmlspecialchars($flash); ?></div>
    </div>
<?php } ?>

<?php
/*
 * header.php — included at the top of every page.
 * Before including this file, a page can set:
 *   $pageTitle  : text for the browser tab
 *   $activeType : which category link to highlight (e.g. "movies")
 */
include_once "data.php";

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
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Our own CSS (external stylesheet) -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

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

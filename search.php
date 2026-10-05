<?php
/*
 * search.php — searches movie titles, cast and director.
 * The search text comes from the form in header.php (search.php?q=...)
 */
include_once "includes/data.php";

$q = isset($_GET["q"]) ? trim($_GET["q"]) : "";

// Loop through all movies and keep the ones that match
$results = array();
if ($q != "") {
    foreach ($movies as $id => $movie) {
        $text = $movie["title"] . " " . $movie["director"] . " " . implode(" ", $movie["cast"]);
        // stripos = case-insensitive search; returns false when not found
        if (stripos($text, $q) !== false) {
            $results[$id] = $movie;
        }
    }
}

$pageTitle = "Search: " . $q . " - Rotten Potatoes";
include "includes/header.php";
?>

<section class="container section">
    <div class="section-head">
        <h2>Search results for "<?php echo htmlspecialchars($q); ?>"</h2>
    </div>

    <?php if (count($results) > 0) { ?>
        <p class="text-muted-light"><?php echo count($results); ?> title(s) found</p>
        <div class="row g-4">
            <?php
            foreach ($results as $id => $movie) {
                include "includes/movie-card.php";
            }
            ?>
        </div>
    <?php } else { ?>
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🔍</div>
            <h3>No potatoes found.</h3>
            <p>Try another title, actor or director — e.g. "Nolan" or "Inception".</p>
        </div>
    <?php } ?>
</section>

<?php include "includes/footer.php"; ?>

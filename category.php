<?php
/*
 * category.php — shows all items of one category.
 * The category comes from the query string, e.g. category.php?type=movies
 */
include_once "includes/data.php";

// Read "type" from the URL. If it is missing or wrong, use "movies".
$type = isset($_GET["type"]) ? $_GET["type"] : "movies";
if (!array_key_exists($type, $categories)) {
    $type = "movies";
}

$pageTitle  = $categories[$type] . " - Rotten Potatoes";
$activeType = $type;
include "includes/header.php";
?>

<section class="container section">
    <div class="section-head">
        <h2><?php echo $categories[$type]; ?></h2>
    </div>

    <?php if ($type == "movies") { ?>
        <div class="row g-4">
            <?php
            foreach ($movies as $id => $movie) {
                include "includes/movie-card.php";
            }
            ?>
        </div>
    <?php } else { ?>
        <!-- Other categories are not added yet -->
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🥔</div>
            <h3><?php echo $categories[$type]; ?> are coming soon!</h3>
            <p>We are still peeling these potatoes. Check out our movies in the meantime.</p>
            <a href="category.php?type=movies" class="btn btn-potato">Browse Movies</a>
        </div>
    <?php } ?>
</section>

<?php include "includes/footer.php"; ?>

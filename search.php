<?php
/*
 * search.php — searches all titles: name, cast, director, author and studio.
 * The search text comes from the form in header.php (search.php?q=...)
 * The results are shown in groups: Movies, Web Series, Anime, Books.
 */
include_once "includes/data.php";

$q = isset($_GET["q"]) ? trim($_GET["q"]) : "";

$total = 0;
$results = array();

if ($q != "") {
    // Search each category separately, so the results stay grouped
    foreach ($catalog as $category => $list) {
        $results[$category] = array();

        foreach ($list as $id => $t) {
            // Put all searchable text of this title into one string
            $text = $t["title"];
            if (isset($t["original"]))  $text .= " " . $t["original"];
            if (isset($t["director"]))  $text .= " " . $t["director"];
            if (isset($t["author"]))    $text .= " " . $t["author"];
            if (isset($t["studio"]))    $text .= " " . $t["studio"];
            if (isset($t["creator"]))   $text .= " " . $t["creator"];
            if (isset($t["cast"]))      $text .= " " . implode(" ", $t["cast"]);

            // stripos = case-insensitive search; returns false when not found
            if (stripos($text, $q) !== false) {
                $results[$category][$id] = $t;
                $total++;
            }
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

    <?php if ($total > 0) { ?>

        <?php foreach ($results as $category => $list) {
            if (count($list) > 0) {
        ?>
            <h3 class="search-group"><?php echo $categories[$category]; ?></h3>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4 mb-4">
                <?php
                $cardCols = "col";
                foreach (sortTitles($list, "rating") as $id => $movie) {
                    include "includes/movie-card.php";
                }
                ?>
            </div>
        <?php }
        } ?>

    <?php } else { ?>
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🔍</div>
            <h3>No potatoes found.</h3>
            <p>Try a title, actor, director or author — e.g. "Nolan", "Shah Rukh", "Ghibli" or "Rowling".</p>
        </div>
    <?php } ?>
</section>

<?php include "includes/footer.php"; ?>

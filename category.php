<?php
/*
 * category.php — lists all items of one category, with filters.
 * Everything comes from the query string, for example:
 *   category.php?type=movies
 *   category.php?type=movies&genre=horror&industry=bollywood
 *   category.php?type=anime&format=movie
 *   category.php?type=books&list=new&sort=year&page=2
 */
include_once "includes/data.php";

// Read "type" from the URL. If it is missing or wrong, use "movies".
$type = isset($_GET["type"]) ? $_GET["type"] : "movies";
if (!array_key_exists($type, $categories)) {
    $type = "movies";
}

// Read the optional filters. Values we don't know are ignored ("").
$genre    = isset($_GET["genre"])    ? $_GET["genre"]    : "";
$industry = isset($_GET["industry"]) ? $_GET["industry"] : "";
$format   = isset($_GET["format"])   ? $_GET["format"]   : "";
$list     = isset($_GET["list"])     ? $_GET["list"]     : "";
$sort     = isset($_GET["sort"])     ? $_GET["sort"]     : "rating";
$page     = isset($_GET["page"])     ? (int) $_GET["page"] : 1;

if (!array_key_exists($genre, $genres))          $genre = "";
if (!array_key_exists($industry, $industries))   $industry = "";
if ($format != "series" && $format != "movie")   $format = "";
if (!array_key_exists($list, $bookLists))        $list = "";
if ($sort != "year" && $sort != "title")         $sort = "rating";

// All titles of this category, then only the ones matching the filters, sorted
$allOfType = $catalog[$type];
$items = filterTitles($allOfType, $genre, $industry, $format, $list);
$items = sortTitles($items, $sort);

// Pagination: 24 cards per page
$perPage    = 24;
$totalPages = ceil(count($items) / $perPage);
if ($totalPages < 1)     $totalPages = 1;
if ($page < 1)           $page = 1;
if ($page > $totalPages) $page = $totalPages;
$pageItems = array_slice($items, ($page - 1) * $perPage, $perPage);

// Heading, e.g. "Bollywood Horror Movies"
$heading = $categories[$type];
if ($list != "")         $heading = $bookLists[$list];
if ($format == "series") $heading = "Anime Series";
if ($format == "movie")  $heading = "Anime Movies";
if ($genre != "")        $heading = label($genre) . " " . $heading;
if ($industry != "")     $heading = label($industry) . " " . $heading;

$pageTitle  = $heading . " - Rotten Potatoes";
$activeType = $type;
include "includes/header.php";
?>

<section class="container section">
    <div class="section-head">
        <h2><?php echo $heading; ?></h2>
    </div>

    <!-- ===== Filters (each button is a link with different query string values) ===== -->
    <div class="filters">

        <?php if ($type == "anime") { ?>
            <div class="filter-row">
                <span class="filter-label">Format</span>
                <a class="btn btn-filter <?php if ($format == "") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, "", $list, $sort); ?>">All</a>
                <a class="btn btn-filter <?php if ($format == "series") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, "series", $list, $sort); ?>">Series</a>
                <a class="btn btn-filter <?php if ($format == "movie") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, "movie", $list, $sort); ?>">Movies</a>
            </div>
        <?php } ?>

        <?php if ($type == "books") { ?>
            <div class="filter-row">
                <span class="filter-label">Lists</span>
                <a class="btn btn-filter <?php if ($list == "") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, $format, "", $sort); ?>">All books</a>
                <?php foreach ($bookLists as $key => $label) { ?>
                    <a class="btn btn-filter <?php if ($list == $key) echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, $format, $key, $sort); ?>"><?php echo $label; ?></a>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if ($type == "movies" || $type == "webseries") { ?>
            <div class="filter-row">
                <span class="filter-label">Industry</span>
                <a class="btn btn-filter <?php if ($industry == "") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, "", $format, $list, $sort); ?>">All</a>
                <?php foreach (countBy($allOfType, "industry") as $key => $count) { ?>
                    <a class="btn btn-filter <?php if ($industry == $key) echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $key, $format, $list, $sort); ?>">
                        <?php echo label($key); ?>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="filter-row">
            <span class="filter-label">Genre</span>
            <a class="btn btn-filter <?php if ($genre == "") echo "active"; ?>" href="<?php echo categoryLink($type, "", $industry, $format, $list, $sort); ?>">All</a>
            <?php foreach (countBy($allOfType, "genres") as $key => $count) { ?>
                <a class="btn btn-filter <?php if ($genre == $key) echo "active"; ?>" href="<?php echo categoryLink($type, $key, $industry, $format, $list, $sort); ?>">
                    <?php echo label($key); ?>
                </a>
            <?php } ?>
        </div>

        <div class="filter-row">
            <span class="filter-label">Sort by</span>
            <a class="btn btn-filter <?php if ($sort == "rating") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, $format, $list, "rating"); ?>">Top rated</a>
            <a class="btn btn-filter <?php if ($sort == "year") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, $format, $list, "year"); ?>">Newest</a>
            <a class="btn btn-filter <?php if ($sort == "title") echo "active"; ?>" href="<?php echo categoryLink($type, $genre, $industry, $format, $list, "title"); ?>">A–Z</a>
        </div>
    </div>

    <!-- ===== Results ===== -->
    <?php if (count($pageItems) > 0) { ?>
        <div class="row g-4">
            <?php
            $cardCols = "col-6 col-md-4 col-lg-3 col-xxl-2";
            foreach ($pageItems as $id => $movie) {
                include "includes/movie-card.php";
            }
            ?>
        </div>

        <!-- Page numbers (Bootstrap pagination) -->
        <?php if ($totalPages > 1) { ?>
            <nav class="mt-5" aria-label="Pages">
                <ul class="pagination potato-pages justify-content-center flex-wrap">
                    <?php for ($p = 1; $p <= $totalPages; $p++) { ?>
                        <li class="page-item <?php if ($p == $page) echo "active"; ?>">
                            <a class="page-link" href="<?php echo categoryLink($type, $genre, $industry, $format, $list, $sort); ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        <?php } ?>

    <?php } else { ?>
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🥔</div>
            <h3>No potatoes here (yet).</h3>
            <p>Nothing matches these filters. Try removing one.</p>
            <a href="category.php?type=<?php echo $type; ?>" class="btn btn-potato">Clear filters</a>
        </div>
    <?php } ?>
</section>

<?php include "includes/footer.php"; ?>

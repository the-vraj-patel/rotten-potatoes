<?php
$pageTitle  = "Rotten Potatoes - Home";
$activeType = "home";
include "includes/header.php";

// The rows of posters on the homepage: heading, list of titles, "See all" link
$rows = array(
    array("heading" => "Top Rated Movies",
          "items"   => $movies,
          "link"    => "category.php?type=movies"),
    array("heading" => "Bollywood Hits",
          "items"   => filterTitles($movies, "", "bollywood", "", ""),
          "link"    => "category.php?type=movies&industry=bollywood"),
    array("heading" => "Binge-worthy Web Series",
          "items"   => $webseries,
          "link"    => "category.php?type=webseries"),
    array("heading" => "Must-watch Anime",
          "items"   => filterTitles($anime, "", "", "series", ""),
          "link"    => "category.php?type=anime&format=series"),
    array("heading" => "Bestselling Books",
          "items"   => filterTitles($books, "", "", "", "bestseller"),
          "link"    => "category.php?type=books&list=bestseller"),
    array("heading" => "Underrated New Books",
          "items"   => filterTitles($books, "", "", "", "new"),
          "link"    => "category.php?type=books&list=new"),
);
?>

<!-- ===== Image slider (Bootstrap Carousel, changes every 4 seconds) ===== -->
<div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

    <!-- Small dots at the bottom -->
    <div class="carousel-indicators">
        <?php for ($i = 0; $i < count($featured); $i++) { ?>
            <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="<?php echo $i; ?>"
                    class="<?php if ($i == 0) echo "active"; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
        <?php } ?>
    </div>

    <div class="carousel-inner">
        <?php foreach ($featured as $index => $slide) {
            $t = $allTitles[$slide["id"]];      // title, year etc. come from the data arrays
        ?>
            <div class="carousel-item <?php if ($index == 0) echo "active"; ?>">
                <div class="hero-slide hero-<?php echo $slide["style"]; ?>">

                    <!-- Background picture -->
                    <div class="hero-bg" style="background-image: url('<?php echo $slide["image"]; ?>');"></div>

                    <!-- For books/anime, also show the cover itself on the right -->
                    <?php if ($slide["style"] == "cover") { ?>
                        <img class="hero-poster d-none d-sm-block" src="<?php echo $slide["image"]; ?>" alt="">
                    <?php } ?>

                    <!-- Text over the picture -->
                    <div class="hero-text">
                        <span class="hero-type"><?php echo $slide["type"]; ?> &middot; <?php echo $t["year"]; ?></span>
                        <h2><?php echo htmlspecialchars($t["title"]); ?></h2>
                        <p><?php echo htmlspecialchars($slide["tagline"]); ?></p>
                        <a href="details.php?id=<?php echo $slide["id"]; ?>" class="btn btn-potato">View details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- ===== Browse by category ===== -->
<section class="container section">
    <div class="row g-3">
        <?php
        $icons = array("movies" => "🎬", "webseries" => "📺", "anime" => "🍥", "books" => "📚");
        foreach ($categories as $key => $label) {
        ?>
            <div class="col-6 col-md-3">
                <a href="category.php?type=<?php echo $key; ?>" class="category-tile">
                    <span class="tile-icon"><?php echo $icons[$key]; ?></span>
                    <span><?php echo $label; ?></span>
                    <small class="tile-count"><?php echo count($catalog[$key]); ?> titles</small>
                </a>
            </div>
        <?php } ?>
    </div>
</section>

<!-- ===== Rows of posters: the 12 best rated of each list ===== -->
<?php foreach ($rows as $row) { ?>
    <section class="container section">
        <div class="section-head">
            <h2><?php echo $row["heading"]; ?></h2>
            <a href="<?php echo $row["link"]; ?>">See all &rarr;</a>
        </div>
        <div class="poster-row">
            <?php
            $cardCols = "poster-row-item";
            $top = array_slice(sortTitles($row["items"], "rating"), 0, 12);
            foreach ($top as $id => $movie) {
                include "includes/movie-card.php";
            }
            ?>
        </div>
    </section>
<?php } ?>

<!-- ===== Browse movies by genre ===== -->
<section class="container section">
    <div class="section-head">
        <h2>Browse Movies by Genre</h2>
    </div>
    <?php
    $homeGenres = array("scifi", "mystery", "comedy", "romance", "horror", "thriller", "crime", "fantasy", "biography", "action", "drama", "animation");
    foreach ($homeGenres as $g) {
    ?>
        <a class="filter-pill big" href="category.php?type=movies&genre=<?php echo $g; ?>"><?php echo label($g); ?></a>
    <?php } ?>
</section>

<?php include "includes/footer.php"; ?>

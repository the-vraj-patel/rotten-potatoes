<?php
/*
 * details.php — full information about one movie.
 * The movie id comes from the query string, e.g. details.php?id=inception
 */
include_once "includes/data.php";

$id = isset($_GET["id"]) ? $_GET["id"] : "";

// If the id does not exist in our $movies array, show a "not found" message
$found = array_key_exists($id, $movies);

if ($found) {
    $movie     = $movies[$id];
    $pageTitle = $movie["title"] . " (" . $movie["year"] . ") - Rotten Potatoes";
} else {
    $pageTitle = "Not found - Rotten Potatoes";
}
$activeType = "movies";
include "includes/header.php";
?>

<?php if (!$found) { ?>

    <section class="container section">
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🥔💀</div>
            <h3>This potato went rotten.</h3>
            <p>We couldn't find that title.</p>
            <a href="category.php?type=movies" class="btn btn-potato">Back to Movies</a>
        </div>
    </section>

<?php } else { ?>

    <!-- Blurred banner behind the top of the page -->
    <div class="details-backdrop" style="background-image: url('<?php echo $movie["banner"]; ?>');"></div>

    <article class="container details">

        <!-- 1. Title -->
        <h1 class="details-title">
            <?php echo htmlspecialchars($movie["title"]); ?>
            <span class="details-year">(<?php echo $movie["year"]; ?>)</span>
        </h1>

        <div class="row g-4">
            <!-- 2. Poster -->
            <div class="col-md-4 col-lg-3">
                <img src="<?php echo $movie["poster"]; ?>" class="details-poster"
                     alt="<?php echo htmlspecialchars($movie["title"]); ?> poster">
            </div>

            <div class="col-md-8 col-lg-9">
                <!-- 3. Genre -->
                <div class="info-block">
                    <h4>Genre</h4>
                    <?php foreach ($movie["genre"] as $g) { ?>
                        <span class="genre-pill"><?php echo $g; ?></span>
                    <?php } ?>
                </div>

                <!-- 4. Movie time -->
                <div class="info-block">
                    <h4>Runtime</h4>
                    <p>⏱ <?php echo formatRuntime($movie["runtime"]); ?></p>
                </div>

                <!-- 5. Cast and director -->
                <div class="info-block">
                    <h4>Director</h4>
                    <p><?php echo $movie["director"]; ?></p>
                </div>
                <div class="info-block">
                    <h4>Cast</h4>
                    <p><?php echo implode(", ", $movie["cast"]); ?></p>
                </div>

                <!-- 6. Rating -->
                <div class="info-block">
                    <h4>Rating</h4>
                    <div class="rating-row">
                        <div class="rating-box">
                            <div class="stars">
                                <?php
                                // Rating is out of 10, stars are out of 5
                                $stars = round($movie["rating"] / 2);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $stars) {
                                        echo '<span class="star on">★</span>';
                                    } else {
                                        echo '<span class="star">★</span>';
                                    }
                                }
                                ?>
                            </div>
                            <p><strong><?php echo $movie["rating"]; ?></strong>/10 &middot; Audience</p>
                        </div>

                        <?php
                        // Potato Meter: 60% or more = Fresh, below 60% = Rotten
                        if ($movie["potato"] >= 60) {
                            $meterClass = "fresh";
                            $meterText  = "Fresh Potato";
                        } else {
                            $meterClass = "rotten";
                            $meterText  = "Rotten Potato";
                        }
                        ?>
                        <div class="rating-box <?php echo $meterClass; ?>">
                            <div class="meter-number">🥔 <?php echo $movie["potato"]; ?>%</div>
                            <p><?php echo $meterText; ?> &middot; Potato Meter</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. Overview -->
        <section class="info-block mt-4">
            <h4>Overview</h4>
            <p class="overview"><?php echo $movie["overview"]; ?></p>
        </section>

        <!-- 8. Where to watch -->
        <section class="info-block">
            <h4>Where to Watch</h4>
            <?php foreach ($movie["watch"] as $platform => $url) { ?>
                <a href="<?php echo $url; ?>" target="_blank" rel="noopener" class="watch-btn">▶ <?php echo $platform; ?></a>
            <?php } ?>
            <p class="small text-muted-light mt-2">Availability may differ by region.</p>
        </section>

        <!-- 9. Trailer (YouTube embed inside a responsive 16:9 box) -->
        <section class="info-block">
            <h4>Trailer</h4>
            <div class="ratio ratio-16x9 trailer">
                <iframe src="https://www.youtube.com/embed/<?php echo $movie["trailer"]; ?>"
                        title="<?php echo htmlspecialchars($movie["title"]); ?> trailer"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen></iframe>
            </div>
        </section>

        <a href="category.php?type=movies" class="btn btn-outline-light mt-2">&larr; Back to Movies</a>
    </article>

<?php } ?>

<?php include "includes/footer.php"; ?>

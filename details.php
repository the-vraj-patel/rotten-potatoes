<?php
/*
 * details.php — full information about one title (movie, web series, anime or book).
 * The id comes from the query string, e.g. details.php?id=inception
 * For shows, the season too: details.php?id=attack-on-titan&season=2
 *
 * Visitors rate with the star forms on this page. The form is sent back to this
 * same page with POST, and the rating is saved in MySQL before the page is shown.
 */
session_start();                    // the session id tells us which visitor is rating
include_once "includes/data.php";
include_once "includes/db.php";
include_once "includes/rating-form.php";

$id = isset($_GET["id"]) ? $_GET["id"] : "";

// If the id does not exist in our arrays, show a "not found" message
$found = array_key_exists($id, $allTitles);

if ($found) {
    $movie    = $allTitles[$id];
    $category = $typeToCategory[$movie["type"]];
    $isBook   = ($movie["type"] == "book");
    $isShow   = ($movie["format"] == "series");

    // Shows: load the episode list ($seasons) and pick the season to display
    $seasons = array();
    $season  = 0;
    $current = null;
    if ($isShow && file_exists("data/episodes/" . $id . ".php")) {
        include "data/episodes/" . $id . ".php";
        $season = isset($_GET["season"]) ? (int) $_GET["season"] : $seasons[0]["number"];
        foreach ($seasons as $s) {
            if ($s["number"] == $season) {
                $current = $s;
            }
        }
        if ($current == null) {         // wrong season number in the URL: show the first one
            $current = $seasons[0];
            $season  = $current["number"];
        }
    }

    // ---- Form processing: a visitor clicked a star ----
    $message = "";
    if (isset($_POST["stars"])) {
        $stars   = (int) $_POST["stars"];
        $episode = isset($_POST["episode"]) ? $_POST["episode"] : "";

        // The episode must be "" (whole title) or a real episode of this show
        $validEpisode = ($episode == "");
        foreach ($seasons as $s) {
            foreach ($s["episodes"] as $ep) {
                if ($ep["id"] == $episode) {
                    $validEpisode = true;
                }
            }
        }

        if (!$dbReady) {
            $message = "Ratings are not available: start MySQL and import database.sql first.";
        } elseif ($stars < 1 || $stars > 5 || !$validEpisode) {
            $message = "That rating was not valid.";
        } else {
            saveRating($conn, $id, $episode, $stars, session_id());
            $message = "Thanks! Your rating was saved.";
        }
    }

    // Visitor ratings for this title and its episodes
    $ratings = array();
    if ($dbReady) {
        $ratings = getRatings($conn, $id, session_id());
    }

    $pageTitle  = $movie["title"] . " (" . $movie["year"] . ") - Rotten Potatoes";
    $activeType = $category;
} else {
    $pageTitle  = "Not found - Rotten Potatoes";
    $activeType = "";
}
include "includes/header.php";
?>

<?php if (!$found) { ?>

    <section class="container section">
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🥔💀</div>
            <h3>This potato went rotten.</h3>
            <p>We couldn't find that title.</p>
            <a href="index.php" class="btn btn-potato">Back to Home</a>
        </div>
    </section>

<?php } else {
    // Background picture: the wide banner if we have one, otherwise the blurred poster
    if ($movie["type"] != "book" && $movie["banner"] != "") {
        $backdrop = $movie["banner"];
        $backdropClass = "";
    } else {
        $backdrop = $movie["poster"];
        $backdropClass = "from-poster";
    }

    // Big poster (shows from TVmaze also have a larger version)
    $bigPoster = $movie["poster"];
    if (isset($movie["posterBig"])) {
        $bigPoster = $movie["posterBig"];
    }
?>

    <!-- Blurred banner behind the top of the page -->
    <div class="details-backdrop <?php echo $backdropClass; ?>" style="background-image: url('<?php echo $backdrop; ?>');"></div>

    <article class="container details">

        <?php if ($message != "") { ?>
            <div class="rate-message"><?php echo $message; ?></div>
        <?php } ?>

        <!-- 1. Title -->
        <h1 class="details-title">
            <?php echo htmlspecialchars($movie["title"]); ?>
            <span class="details-year">(<?php echo $movie["year"]; ?>)</span>
        </h1>
        <?php if (isset($movie["original"]) && $movie["original"] != "") { ?>
            <p class="details-original"><?php echo htmlspecialchars($movie["original"]); ?></p>
        <?php } ?>

        <div class="row g-4">
            <!-- 2. Poster -->
            <div class="col-md-4 col-lg-3">
                <?php if ($bigPoster != "") { ?>
                    <img src="<?php echo $bigPoster; ?>" class="details-poster"
                         alt="<?php echo htmlspecialchars($movie["title"]); ?> poster">
                <?php } else { ?>
                    <div class="details-poster no-poster"><span>🥔</span></div>
                <?php } ?>
            </div>

            <div class="col-md-8 col-lg-9">
                <!-- 3. Genre -->
                <div class="info-block">
                    <h4>Genre</h4>
                    <?php foreach ($movie["genres"] as $g) { ?>
                        <a class="genre-pill" href="category.php?type=<?php echo $category; ?>&genre=<?php echo $g; ?>"><?php echo label($g); ?></a>
                    <?php } ?>
                </div>

                <!-- 4. Facts: different for books, shows and movies -->
                <div class="row">
                    <?php if ($isBook) { ?>
                        <div class="col-6 col-lg-4 info-block"><h4>Author</h4><p><?php echo htmlspecialchars($movie["author"]); ?></p></div>
                        <div class="col-6 col-lg-4 info-block"><h4>Published</h4><p><?php echo $movie["year"]; ?></p></div>
                        <?php if ($movie["pages"] != null) { ?>
                            <div class="col-6 col-lg-4 info-block"><h4>Pages</h4><p>📖 <?php echo $movie["pages"]; ?></p></div>
                        <?php } ?>

                    <?php } elseif ($isShow) { ?>
                        <div class="col-6 col-lg-4 info-block"><h4>Seasons</h4><p><?php echo $movie["seasons"]; ?> &middot; <?php echo $movie["episodes"]; ?> episodes</p></div>
                        <div class="col-6 col-lg-4 info-block"><h4>Episode length</h4><p>⏱ <?php echo formatRuntime($movie["runtime"]); ?></p></div>
                        <div class="col-6 col-lg-4 info-block"><h4>Status</h4>
                            <p><?php if ($movie["status"] == "Ended") { echo "Ended " . $movie["ended"]; } else { echo $movie["status"]; } ?></p>
                        </div>
                        <?php if ($movie["studio"] != "") { ?>
                            <div class="col-6 col-lg-4 info-block"><h4>Studio</h4><p><?php echo htmlspecialchars($movie["studio"]); ?></p></div>
                        <?php } ?>
                        <?php if ($movie["creator"] != "") { ?>
                            <div class="col-6 col-lg-4 info-block"><h4>Created by</h4><p><?php echo htmlspecialchars($movie["creator"]); ?></p></div>
                        <?php } ?>
                        <?php if ($movie["channel"] != "") { ?>
                            <div class="col-6 col-lg-4 info-block"><h4>Network</h4><p><?php echo htmlspecialchars($movie["channel"]); ?></p></div>
                        <?php } ?>

                    <?php } else { ?>
                        <div class="col-6 col-lg-4 info-block"><h4>Runtime</h4><p>⏱ <?php echo formatRuntime($movie["runtime"]); ?></p></div>
                        <div class="col-6 col-lg-4 info-block"><h4>Director</h4><p><?php echo htmlspecialchars($movie["director"]); ?></p></div>
                        <?php if ($movie["studio"] != "") { ?>
                            <div class="col-6 col-lg-4 info-block"><h4>Studio</h4><p><?php echo htmlspecialchars($movie["studio"]); ?></p></div>
                        <?php } ?>
                        <div class="col-6 col-lg-4 info-block"><h4>Industry</h4>
                            <p><a href="category.php?type=<?php echo $category; ?>&industry=<?php echo $movie["industry"]; ?>"><?php echo label($movie["industry"]); ?></a>
                            <?php if ($movie["language"] != "") { echo " &middot; " . $movie["language"]; } ?></p>
                        </div>
                    <?php } ?>
                </div>

                <!-- 5. Cast -->
                <?php if (!$isBook && count($movie["cast"]) > 0) { ?>
                    <div class="info-block">
                        <h4><?php if ($movie["type"] == "anime") { echo "Voice cast"; } else { echo "Cast"; } ?></h4>
                        <p><?php echo htmlspecialchars(implode(", ", $movie["cast"])); ?></p>
                    </div>
                <?php } ?>

                <!-- 6. Rating -->
                <div class="info-block">
                    <h4>Rating</h4>
                    <div class="rating-row">
                        <?php if ($movie["rating"] != null) { ?>
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
                                <p><strong><?php echo $movie["rating"]; ?></strong>/10 &middot;
                                    <?php
                                    if ($isBook) {
                                        echo "Readers (" . $movie["ratingCount"] . ")";
                                    } else {
                                        echo "Audience";
                                    }
                                    ?>
                                </p>
                            </div>
                        <?php } ?>

                        <?php
                        // Potato Meter: 60% or more = Fresh, below 60% = Rotten
                        if ($movie["potato"] != null) {
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
                        <?php } ?>

                        <!-- Visitors rate the whole title here -->
                        <div class="rating-box">
                            <?php echo ratingForm($id, $season, "", $ratings); ?>
                            <p>Your rating</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. Overview -->
        <?php if ($movie["overview"] != "") { ?>
            <section class="info-block mt-4">
                <h4><?php if ($isBook) { echo "About the book"; } else { echo "Overview"; } ?></h4>
                <p class="overview"><?php echo htmlspecialchars($movie["overview"]); ?></p>
                <?php if ($isBook) { ?>
                    <div class="mt-3">
                        <?php foreach ($movie["subjects"] as $s) { ?>
                            <span class="subject-tag"><?php echo htmlspecialchars($s); ?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </section>
        <?php } ?>

        <!-- 8. Episodes (web series and anime series only) -->
        <?php
        if ($isShow && $current != null) {
            include "includes/episodes.php";
        }
        ?>

        <!-- 9. Where to watch / read -->
        <section class="info-block">
            <?php if ($isBook) { ?>
                <h4>Where to Read</h4>
                <a href="<?php echo $movie["source"]; ?>" target="_blank" rel="noopener" class="watch-btn">📘 Open Library</a>
                <a href="https://www.google.com/search?tbm=bks&q=<?php echo urlencode($movie["title"] . " " . $movie["author"]); ?>" target="_blank" rel="noopener" class="watch-btn">📘 Google Books</a>
                <a href="https://www.amazon.in/s?k=<?php echo urlencode($movie["title"] . " " . $movie["author"]); ?>" target="_blank" rel="noopener" class="watch-btn">📘 Amazon</a>
            <?php } else { ?>
                <h4>Where to Watch</h4>
                <?php if (isset($movie["platform"]) && $movie["platform"] != "") { ?>
                    <span class="watch-btn">▶ <?php echo htmlspecialchars($movie["platform"]); ?></span>
                <?php } ?>
                <a href="https://www.justwatch.com/in/search?q=<?php echo urlencode($movie["title"]); ?>" target="_blank" rel="noopener" class="watch-btn">▶ Find on JustWatch</a>
                <p class="small text-muted-light mt-2">Availability may differ by region.</p>
            <?php } ?>
        </section>

        <!-- 10. Trailer (YouTube embed inside a responsive 16:9 box) -->
        <?php if (!$isBook) { ?>
            <section class="info-block">
                <h4>Trailer</h4>
                <?php if ($movie["trailer"] != "") { ?>
                    <div class="ratio ratio-16x9 trailer">
                        <iframe src="https://www.youtube.com/embed/<?php echo $movie["trailer"]; ?>"
                                title="<?php echo htmlspecialchars($movie["title"]); ?> trailer"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen></iframe>
                    </div>
                <?php } else { ?>
                    <a class="watch-btn" target="_blank" rel="noopener"
                       href="https://www.youtube.com/results?search_query=<?php echo urlencode($movie["title"] . " " . $movie["year"] . " trailer"); ?>">▶ Watch trailer on YouTube</a>
                <?php } ?>
            </section>
        <?php } ?>

        <p class="small text-muted-light">
            More info: <a href="<?php echo $movie["source"]; ?>" target="_blank" rel="noopener"><?php echo $movie["source"]; ?></a>
        </p>

        <a href="category.php?type=<?php echo $category; ?>" class="btn btn-outline-light mt-2">&larr; Back to <?php echo $categories[$category]; ?></a>
    </article>

<?php } ?>

<?php include "includes/footer.php"; ?>

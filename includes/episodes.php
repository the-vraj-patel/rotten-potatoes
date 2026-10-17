<?php
/*
 * episodes.php — the episode section of a web series / anime page.
 * Included by details.php, which sets $id, $seasons, $season, $current and $ratings.
 *
 * Part 1: "Ratings at a Glance": highlights (best, worst, average) and a table with
 *         the rating of every episode, one column per season, coloured from
 *         green (great) to red (rotten). Click a number to jump to that episode.
 * Part 2: buttons to choose a season (details.php?id=...&season=2)
 * Part 3: every episode of the chosen season, with its rating and stars
 *         so visitors can rate each episode individually.
 */

// Go through all episodes once to find: the longest season, the best and worst
// episode, and the average of all episode ratings
$maxEpisodes = 0;
$best   = null;
$worst  = null;
$sum    = 0;
$rated  = 0;
foreach ($seasons as $s) {
    if (count($s["episodes"]) > $maxEpisodes) {
        $maxEpisodes = count($s["episodes"]);
    }
    foreach ($s["episodes"] as $ep) {
        if ($ep["rating"] == null) {
            continue;
        }
        if ($best == null || $ep["rating"] > $best["rating"]) {
            $best = $ep;
        }
        if ($worst == null || $ep["rating"] < $worst["rating"]) {
            $worst = $ep;
        }
        $sum += $ep["rating"];
        $rated++;
    }
}
?>

<!-- ===== Part 1: Ratings at a Glance ===== -->
<section class="info-block mt-4 glance">
    <div class="glance-head">
        <div>
            <h4>Ratings at a Glance</h4>
            <p>Every episode's score out of 10. Click a score to jump to that episode.</p>
        </div>
    </div>

    <?php if ($rated > 0) { ?>
        <!-- Highlights -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <a class="glance-stat" href="details.php?id=<?php echo $id; ?>&season=<?php echo $best["season"]; ?>#ep-<?php echo $best["id"]; ?>">
                    <span class="glance-stat-label">🏆 Best episode</span>
                    <span class="glance-stat-value r-text-great"><?php echo number_format($best["rating"], 1); ?></span>
                    <span class="glance-stat-name">S<?php echo $best["season"]; ?> E<?php echo $best["number"]; ?> &middot; <?php echo htmlspecialchars($best["name"]); ?></span>
                </a>
            </div>
            <div class="col-md-4">
                <div class="glance-stat">
                    <span class="glance-stat-label">📊 Average episode</span>
                    <span class="glance-stat-value"><?php echo number_format($sum / $rated, 1); ?></span>
                    <span class="glance-stat-name"><?php echo $rated; ?> rated episodes in <?php echo count($seasons); ?> season(s)</span>
                </div>
            </div>
            <div class="col-md-4">
                <a class="glance-stat" href="details.php?id=<?php echo $id; ?>&season=<?php echo $worst["season"]; ?>#ep-<?php echo $worst["id"]; ?>">
                    <span class="glance-stat-label">🥔 Lowest episode</span>
                    <span class="glance-stat-value r-text-bad"><?php echo number_format($worst["rating"], 1); ?></span>
                    <span class="glance-stat-name">S<?php echo $worst["season"]; ?> E<?php echo $worst["number"]; ?> &middot; <?php echo htmlspecialchars($worst["name"]); ?></span>
                </a>
            </div>
        </div>
    <?php } ?>

    <!-- The table: one column per season, one row per episode number -->
    <div class="glance-table-wrap">
        <table class="glance-table">
            <thead>
                <tr>
                    <th class="corner">Episode</th>
                    <?php foreach ($seasons as $s) { ?>
                        <th title="<?php echo htmlspecialchars($s["name"]); ?>">
                            <span class="season-name">S<?php echo $s["number"]; ?></span>
                            <span class="season-avg <?php echo ratingClass($s["rating"]); ?>">
                                <?php if ($s["rating"] != null) { echo number_format($s["rating"], 1); } else { echo "–"; } ?>
                            </span>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php for ($row = 0; $row < $maxEpisodes; $row++) { ?>
                    <tr>
                        <th>E<?php echo $row + 1; ?></th>
                        <?php foreach ($seasons as $s) { ?>
                            <td>
                                <?php if (isset($s["episodes"][$row])) {
                                    $ep = $s["episodes"][$row];
                                ?>
                                    <a href="details.php?id=<?php echo $id; ?>&season=<?php echo $s["number"]; ?>#ep-<?php echo $ep["id"]; ?>"
                                       class="glance-cell <?php echo ratingClass($ep["rating"]); ?>"
                                       title="S<?php echo $ep["season"]; ?> E<?php echo $ep["number"]; ?>: <?php echo htmlspecialchars($ep["name"]); ?>">
                                        <?php if ($ep["rating"] != null) { echo number_format($ep["rating"], 1); } else { echo "–"; } ?>
                                    </a>
                                <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="glance-legend">
        <span><i class="r-great"></i> 9+ Awesome</span>
        <span><i class="r-good"></i> 8+ Great</span>
        <span><i class="r-ok"></i> 7+ Good</span>
        <span><i class="r-meh"></i> 6+ Okay</span>
        <span><i class="r-bad"></i> Below 6 Rotten</span>
        <span><i class="r-none"></i> No rating yet</span>
    </div>
</section>

<!-- ===== Part 2: choose a season ===== -->
<section class="info-block" id="episodes">
    <h4>Episodes</h4>
    <div class="season-buttons">
        <?php foreach ($seasons as $s) { ?>
            <a href="details.php?id=<?php echo $id; ?>&season=<?php echo $s["number"]; ?>#episodes"
               class="btn btn-filter <?php if ($s["number"] == $season) echo "active"; ?>"><?php echo htmlspecialchars($s["name"]); ?></a>
        <?php } ?>
    </div>

    <p class="season-summary">
        <?php echo count($current["episodes"]); ?> episodes
        <?php if ($current["rating"] != null) { ?>
            &middot; season average <strong><?php echo $current["rating"]; ?></strong>/10
        <?php } ?>
    </p>

    <!-- ===== Part 3: episodes of the chosen season ===== -->
    <?php foreach ($current["episodes"] as $ep) { ?>
        <div class="episode" id="ep-<?php echo $ep["id"]; ?>">
            <div class="episode-number">E<?php echo $ep["number"]; ?></div>

            <?php if ($ep["image"] != "") { ?>
                <img class="episode-still" src="<?php echo $ep["image"]; ?>" alt="">
            <?php } ?>

            <div class="episode-body">
                <h5><?php echo htmlspecialchars($ep["name"]); ?></h5>
                <p class="episode-meta">
                    <?php echo $ep["airdate"]; ?>
                    <?php if ($ep["runtime"] != null) { echo " &middot; " . $ep["runtime"] . " min"; } ?>
                </p>
                <p class="episode-summary"><?php echo htmlspecialchars($ep["summary"]); ?></p>
            </div>

            <div class="episode-rating">
                <span class="episode-score <?php echo ratingClass($ep["rating"]); ?>">
                    <?php if ($ep["rating"] != null) { echo "★ " . number_format($ep["rating"], 1); } else { echo "No score"; } ?>
                </span>
                <?php echo ratingForm($id, $season, $ep["id"], $ratings, ""); ?>
            </div>
        </div>
    <?php } ?>
</section>

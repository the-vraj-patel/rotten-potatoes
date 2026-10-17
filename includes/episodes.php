<?php
/*
 * episodes.php — the episode section of a web series / anime page.
 * Included by details.php, which sets $id, $seasons, $season, $current and $ratings.
 *
 * Part 1: a table with the rating of every episode (one column per season),
 *         coloured from green (great) to red (rotten). Click a number to jump to it.
 * Part 2: buttons to choose a season (details.php?id=...&season=2)
 * Part 3: a table with every episode of the chosen season, its rating and stars
 *         so visitors can rate each episode individually.
 */

// The longest season decides how many rows the table needs
$maxEpisodes = 0;
foreach ($seasons as $s) {
    if (count($s["episodes"]) > $maxEpisodes) {
        $maxEpisodes = count($s["episodes"]);
    }
}
?>

<!-- ===== Part 1: rating table ===== -->
<section class="info-block mt-4">
    <h4>Ratings at a Glance</h4>

    <div class="episode-grid-wrap">
        <table class="episode-grid">
            <tr>
                <th></th>
                <?php foreach ($seasons as $s) { ?>
                    <th title="<?php echo htmlspecialchars($s["name"]); ?>">S<?php echo $s["number"]; ?></th>
                <?php } ?>
            </tr>

            <?php for ($row = 0; $row < $maxEpisodes; $row++) { ?>
                <tr>
                    <th>E<?php echo $row + 1; ?></th>
                    <?php foreach ($seasons as $s) { ?>
                        <td>
                            <?php if (isset($s["episodes"][$row])) {
                                $ep = $s["episodes"][$row];
                            ?>
                                <a href="details.php?id=<?php echo $id; ?>&season=<?php echo $s["number"]; ?>#ep-<?php echo $ep["id"]; ?>"
                                   class="grid-cell <?php echo ratingClass($ep["rating"]); ?>"
                                   title="<?php echo htmlspecialchars($ep["name"]); ?>">
                                    <?php if ($ep["rating"] != null) { echo number_format($ep["rating"], 1); } else { echo "–"; } ?>
                                </a>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>

            <!-- Last row: average of each season -->
            <tr class="grid-average">
                <th>Avg</th>
                <?php foreach ($seasons as $s) { ?>
                    <td>
                        <span class="grid-cell <?php echo ratingClass($s["rating"]); ?>">
                            <?php if ($s["rating"] != null) { echo number_format($s["rating"], 1); } else { echo "–"; } ?>
                        </span>
                    </td>
                <?php } ?>
            </tr>
        </table>
    </div>

    <p class="grid-legend">
        <span class="grid-cell r-great">9+</span> Awesome
        <span class="grid-cell r-good">8+</span> Great
        <span class="grid-cell r-ok">7+</span> Good
        <span class="grid-cell r-meh">6+</span> Okay
        <span class="grid-cell r-bad">&lt;6</span> Rotten
        <span class="grid-cell r-none">–</span> No rating yet
    </p>
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

    <!-- ===== Part 3: episodes of the chosen season, in a table ===== -->
    <div class="table-responsive episode-table-wrap">
        <table class="table episode-table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Episode</th>
                    <th class="d-none d-md-table-cell">Aired</th>
                    <th>Score</th>
                    <th>Rate this episode</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($current["episodes"] as $ep) { ?>
                    <tr id="ep-<?php echo $ep["id"]; ?>">
                        <td class="ep-number"><?php echo $ep["number"]; ?></td>

                        <td class="ep-main">
                            <div class="ep-flex">
                                <?php if ($ep["image"] != "") { ?>
                                    <img class="ep-still d-none d-lg-block" src="<?php echo $ep["image"]; ?>" alt="">
                                <?php } ?>
                                <div>
                                    <strong><?php echo htmlspecialchars($ep["name"]); ?></strong>
                                    <?php if ($ep["summary"] != "") { ?>
                                        <p class="ep-summary"><?php echo htmlspecialchars($ep["summary"]); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>

                        <td class="ep-date d-none d-md-table-cell">
                            <?php echo $ep["airdate"]; ?>
                            <?php if ($ep["runtime"] != null) { ?><br><small><?php echo $ep["runtime"]; ?> min</small><?php } ?>
                        </td>

                        <td class="ep-score">
                            <?php if ($ep["rating"] != null) { ?>
                                <span class="score-pill <?php echo ratingClass($ep["rating"]); ?>"><?php echo number_format($ep["rating"], 1); ?></span>
                                <!-- small bar: 10/10 = full width -->
                                <div class="mini-bar"><div class="<?php echo ratingClass($ep["rating"]); ?>" style="width: <?php echo $ep["rating"] * 10; ?>%"></div></div>
                            <?php } else { ?>
                                <span class="score-pill r-none">–</span>
                            <?php } ?>
                        </td>

                        <td class="ep-rate"><?php echo ratingForm($id, $season, $ep["id"], $ratings, ""); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>

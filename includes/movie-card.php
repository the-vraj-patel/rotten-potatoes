<?php
/*
 * movie-card.php — one poster card (used for movies, series, anime and books).
 * Used inside a foreach loop that sets $id and $movie.
 * A page can set $cardCols to change the Bootstrap column classes.
 */
if (!isset($cardCols)) {
    $cardCols = "col-6 col-md-4 col-lg-3";
}
?>
<div class="<?php echo $cardCols; ?>">
    <a href="details.php?id=<?php echo $id; ?>" class="movie-card">
        <div class="poster-wrap">
            <?php if ($movie["poster"] != "") { ?>
                <img src="<?php echo $movie["poster"]; ?>" alt="<?php echo htmlspecialchars($movie["title"]); ?> poster">
            <?php } else { ?>
                <div class="no-poster"><span>🥔</span><?php echo htmlspecialchars($movie["title"]); ?></div>
            <?php } ?>

            <?php if ($movie["rating"] != null) { ?>
                <span class="rating-badge">★ <?php echo $movie["rating"]; ?></span>
            <?php } ?>

            <?php if ($movie["type"] == "anime" && $movie["format"] == "movie") { ?>
                <span class="format-badge">Movie</span>
            <?php } ?>
        </div>
        <h3 class="card-title"><?php echo htmlspecialchars($movie["title"]); ?></h3>
        <p class="card-meta"><?php echo htmlspecialchars(cardMeta($movie)); ?></p>
    </a>
</div>

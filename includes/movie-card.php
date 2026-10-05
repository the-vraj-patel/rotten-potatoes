<?php
/*
 * movie-card.php — one poster card.
 * Used inside a foreach loop that sets $id and $movie.
 */
?>
<div class="col-6 col-md-4 col-lg-3">
    <a href="details.php?id=<?php echo $id; ?>" class="movie-card">
        <div class="poster-wrap">
            <img src="<?php echo $movie["poster"]; ?>" alt="<?php echo htmlspecialchars($movie["title"]); ?> poster">
            <span class="rating-badge">★ <?php echo $movie["rating"]; ?></span>
        </div>
        <h3 class="card-title"><?php echo htmlspecialchars($movie["title"]); ?></h3>
        <p class="card-meta"><?php echo $movie["year"]; ?> &middot; <?php echo $movie["genre"][0]; ?></p>
    </a>
</div>

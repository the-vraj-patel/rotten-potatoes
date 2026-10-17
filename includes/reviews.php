<?php
/*
 * reviews.php — written reviews of a title + the form to write one.
 * Included by details.php, which sets $id, $season, $reviews, $reviewError,
 * $reviewName, $reviewText and $isBook.
 * The form is sent to details.php with POST and saved in the MySQL "reviews" table.
 */
?>
<section class="info-block mt-4" id="reviews">
    <h4>Reviews</h4>

    <div class="row g-4">
        <!-- ===== The reviews ===== -->
        <div class="col-lg-7">
            <?php if (count($reviews) == 0) { ?>
                <div class="review-empty">
                    <span>✍️</span>
                    <p>No reviews yet. Tell others what you thought!</p>
                </div>
            <?php } ?>

            <?php foreach ($reviews as $r) { ?>
                <div class="review-card">
                    <div class="review-head">
                        <!-- First letter of the name in a circle -->
                        <span class="review-avatar"><?php echo htmlspecialchars(strtoupper(substr($r["name"], 0, 1))); ?></span>
                        <div>
                            <strong><?php echo htmlspecialchars($r["name"]); ?></strong>
                            <?php if ($r["is_sample"] == 1) { ?>
                                <span class="sample-badge">Sample review</span>
                            <?php } ?>
                            <div class="review-stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $r["stars"]) {
                                        echo '<span class="on">★</span>';
                                    } else {
                                        echo '<span>★</span>';
                                    }
                                }
                                ?>
                                <span class="review-date"><?php echo date("j M Y", strtotime($r["created_at"])); ?></span>
                            </div>
                        </div>
                    </div>
                    <p class="review-text"><?php echo htmlspecialchars($r["review"]); ?></p>
                </div>
            <?php } ?>
        </div>

        <!-- ===== Write a review (plain HTML form, method POST) ===== -->
        <div class="col-lg-5">
            <form method="post" action="details.php?id=<?php echo $id; ?><?php if ($season > 0) echo "&season=" . $season; ?>#reviews"
                  class="review-form" id="reviewForm">
                <h5>Write a review</h5>

                <?php if ($reviewError != "") { ?>
                    <p class="review-error"><?php echo $reviewError; ?></p>
                <?php } ?>

                <label for="reviewName" class="form-label">Your name</label>
                <input type="text" class="form-control" id="reviewName" name="name" maxlength="50"
                       value="<?php echo htmlspecialchars($reviewName); ?>">

                <label class="form-label mt-3">Your rating</label>
                <div class="star-choice">
                    <?php for ($i = 5; $i >= 1; $i--) { ?>
                        <input type="radio" name="review_stars" value="<?php echo $i; ?>" id="rs<?php echo $i; ?>">
                        <label for="rs<?php echo $i; ?>" title="<?php echo $i; ?> stars">★</label>
                    <?php } ?>
                </div>

                <label for="reviewText" class="form-label mt-2">Your review</label>
                <textarea class="form-control" id="reviewText" name="review" rows="4" maxlength="1000"
                          placeholder="What did you like or dislike?"><?php echo htmlspecialchars($reviewText); ?></textarea>

                <button type="submit" class="btn btn-potato mt-3">Post review</button>
            </form>
        </div>
    </div>
</section>

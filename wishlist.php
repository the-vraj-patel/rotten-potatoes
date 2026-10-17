<?php
/*
 * wishlist.php — a member's own page: account info, wishlist and recommendations.
 * Only for logged-in members; visitors are sent to the login page.
 */
include_once "includes/auth.php";
include_once "includes/data.php";
include_once "includes/db.php";

if (!isLoggedIn()) {
    header("Location: login.php?return=wishlist.php");
    exit;
}

$user     = null;
$wishIds  = array();
if ($dbReady) {
    $user    = findUserById($conn, currentUserId());
    $wishIds = getWishlist($conn, currentUserId());
}

// The full titles of the wishlist (skip ids that are no longer in our data)
$wishlist = array();
foreach ($wishIds as $wishId) {
    if (isset($allTitles[$wishId])) {
        $wishlist[$wishId] = $allTitles[$wishId];
    }
}

// Titles with the same genres and categories as the wishlist
$recommended = array();
if (count($wishlist) > 0) {
    $recommended = recommendTitles(array_keys($wishlist), 12);
}

$pageTitle  = "My Wishlist - Rotten Potatoes";
$returnHere = "wishlist.php";
include "includes/header.php";
?>

<section class="container section">

    <!-- ===== Account card ===== -->
    <?php if ($user != null) { ?>
        <div class="profile-card">
            <span class="profile-avatar"><?php echo htmlspecialchars(strtoupper(substr($user["name"], 0, 1))); ?></span>
            <div>
                <h2><?php echo htmlspecialchars($user["name"]); ?></h2>
                <p><?php echo htmlspecialchars($user["email"]); ?></p>
                <p class="profile-meta">Member ID <strong>#<?php echo $user["id"]; ?></strong>
                   &middot; joined <?php echo date("j M Y", strtotime($user["created_at"])); ?></p>
            </div>
        </div>
    <?php } ?>

    <!-- ===== Wishlist ===== -->
    <div class="section-head">
        <h2>❤️ My Wishlist</h2>
    </div>

    <?php if (count($wishlist) > 0) { ?>
        <div class="row g-4">
            <?php
            $cardCols = "col-6 col-md-4 col-lg-3 col-xxl-2";
            foreach ($wishlist as $id => $movie) {
                include "includes/movie-card.php";
            }
            ?>
        </div>
    <?php } else { ?>
        <div class="coming-soon text-center">
            <div class="display-3 mb-3">🤍</div>
            <h3>Your wishlist is empty.</h3>
            <p>Open any movie, show, anime or book and click "Add to wishlist".</p>
            <a href="category.php?type=movies" class="btn btn-potato">Browse Movies</a>
        </div>
    <?php } ?>
</section>

<!-- ===== Recommendations ===== -->
<?php if (count($recommended) > 0) { ?>
    <section class="container section">
        <div class="section-head">
            <h2>✨ Recommended for You</h2>
        </div>
        <p class="text-muted-light">Picked because they share genres with your wishlist.</p>
        <div class="row g-4">
            <?php
            foreach ($recommended as $id => $movie) {
                include "includes/movie-card.php";
            }
            ?>
        </div>
    </section>
<?php } ?>

<?php include "includes/footer.php"; ?>

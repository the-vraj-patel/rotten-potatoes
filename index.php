<?php
$pageTitle  = "Rotten Potatoes - Home";
$activeType = "home";
include "includes/header.php";
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
        <?php foreach ($featured as $index => $slide) { ?>
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
                        <span class="hero-type"><?php echo $slide["type"]; ?> &middot; <?php echo $slide["year"]; ?></span>
                        <h2><?php echo htmlspecialchars($slide["title"]); ?></h2>
                        <p><?php echo htmlspecialchars($slide["tagline"]); ?></p>
                        <a href="<?php echo $slide["link"]; ?>" class="btn btn-potato">View details</a>
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

<!-- ===== Popular movies ===== -->
<section class="container section">
    <div class="section-head">
        <h2>Popular Movies</h2>
        <a href="category.php?type=movies">See all &rarr;</a>
    </div>
    <div class="row g-4">
        <?php
        foreach ($movies as $id => $movie) {
            include "includes/movie-card.php";
        }
        ?>
    </div>
</section>

<!-- ===== Browse by category ===== -->
<section class="container section">
    <div class="section-head">
        <h2>Browse Categories</h2>
    </div>
    <div class="row g-3">
        <?php
        $icons = array("movies" => "🎬", "webseries" => "📺", "anime" => "🍥", "books" => "📚");
        foreach ($categories as $key => $label) {
        ?>
            <div class="col-6 col-md-3">
                <a href="category.php?type=<?php echo $key; ?>" class="category-tile">
                    <span class="tile-icon"><?php echo $icons[$key]; ?></span>
                    <span><?php echo $label; ?></span>
                </a>
            </div>
        <?php } ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>

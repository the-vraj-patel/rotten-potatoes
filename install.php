<?php
/*
 * install.php — creates the database and all its tables. Open it ONCE:
 *     http://localhost/rotten-potatoes/install.php
 * and click the button. (Running it again starts the database fresh.)
 *
 * Tables:
 *   users    : registered members (the id is their member ID)
 *   ratings  : star ratings of titles and episodes, one per user
 *   reviews  : written reviews
 *   wishlist : titles each user saved
 */
include_once "includes/data.php";

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "rotten_potatoes";

// Sample reviews written for the demo (shown with a "Sample review" label)
// item, name, stars, review
$sampleReviews = array(
    array("inception", "Aarav", 5, "Watched it three times and still found new details. The hallway fight and the ending kept my whole friend group arguing for days."),
    array("inception", "Meera", 4, "Brilliant idea and an amazing soundtrack. The first hour has a lot of explaining, but once the heist starts it never slows down."),
    array("inception", "Rohan", 5, "One of those movies that makes you think long after it ends. Every layer of the dream has its own rules and it all fits together."),
    array("inception", "Sana", 4, "Great visuals and a clever story. I wish the characters had a bit more emotion, but the spinning top ending is perfect."),
    array("interstellar", "Kabir", 5, "The docking scene with the music is pure goosebumps. A space movie that is really about a father and his daughter."),
    array("interstellar", "Isha", 5, "I cried at the video messages scene. The science is heavy but the feelings are simple and real."),
    array("interstellar", "Dev", 4, "Huge, beautiful and ambitious. The ending is a bit confusing, but the planets and the black hole look unbelievable."),
    array("interstellar", "Anaya", 4, "Long, but worth every minute on a big screen. The time difference on the water planet is terrifying."),
    array("the-dark-knight", "Vivaan", 5, "Heath Ledger's Joker is unforgettable. This is a crime thriller first and a superhero movie second."),
    array("the-dark-knight", "Priya", 5, "Every scene with the Joker is tense. The ferry scene and the interrogation are some of the best moments in any film."),
    array("the-dark-knight", "Arjun", 4, "Dark, serious and smart. Two-Face's story feels a bit rushed at the end, but the rest is near perfect."),
    array("the-dark-knight", "Nisha", 5, "Still the best Batman movie after all these years. The music alone makes your heart race."),
    array("stranger-things", "Aditya", 5, "The kids are the heart of the show. 80s vibes, great music and real scares — season 1 is a perfect binge."),
    array("stranger-things", "Riya", 4, "Loved Eleven and Hopper. Some later seasons get a bit too big, but I still couldn't stop watching."),
    array("stranger-things", "Yash", 4, "The Upside Down is so creepy. Season 4 with Vecna is the scariest and the best one for me."),
    array("stranger-things", "Tara", 5, "Running Up That Hill will never sound the same again. Great friendships and great monsters."),
    array("attack-on-titan", "Harsh", 5, "Starts as a monster story and turns into something much bigger. The plot twists in season 3 blew my mind."),
    array("attack-on-titan", "Kavya", 5, "Levi is the coolest character in anime. The animation and the soundtrack are on another level."),
    array("attack-on-titan", "Neel", 4, "Very dark and sometimes hard to watch, but the story is planned so well. Every early detail matters later."),
    array("attack-on-titan", "Zoya", 4, "Season 1 hooked me in one episode. The final season makes you question who the real villain is."),
    array("harry-potter-and-the-philosophers-stone", "Ishaan", 5, "The book that made me love reading. Hogwarts feels like a real place you want to live in."),
    array("harry-potter-and-the-philosophers-stone", "Diya", 5, "Simple, magical and funny. Perfect for kids but I enjoyed rereading it as an adult too."),
    array("harry-potter-and-the-philosophers-stone", "Reyansh", 4, "A lovely start to the series. It is shorter and lighter than the later books, but full of wonder."),
    array("harry-potter-and-the-philosophers-stone", "Myra", 5, "The mirror of Erised chapter is so beautiful. I finished the whole book in one weekend."),
    array("breaking-bad", "Karan", 5, "Watching Walter White change from a teacher into Heisenberg is incredible. Not a single bad season."),
    array("breaking-bad", "Pooja", 5, "The best TV show I have ever watched. Ozymandias is a perfect episode of television."),
    array("breaking-bad", "Siddharth", 4, "Season 1 is a bit slow, but once it gets going it is impossible to stop. Jesse is my favourite character."),
    array("breaking-bad", "Aisha", 5, "Every episode builds tension. The ending is one of the most satisfying finales ever."),
    array("demon-slayer", "Atharv", 5, "Episode 19 is the most beautiful animation I have ever seen. Ufotable did magic with this show."),
    array("demon-slayer", "Saanvi", 4, "Simple story about a brother and sister, but the fights are amazing. Zenitsu makes me laugh every time."),
    array("demon-slayer", "Laksh", 5, "The Entertainment District arc is pure fire. Great music and great characters."),
    array("demon-slayer", "Ira", 4, "Gorgeous to look at and easy to binge. Tanjiro is one of the kindest main characters in anime."),
);

$steps = array();     // what happened, shown as a list
$error = "";

if (isset($_POST["install"])) {
    mysqli_report(MYSQLI_REPORT_OFF);
    // Connect WITHOUT a database name first, because the database may not exist yet
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass);

    if (!$conn) {
        $error = "Could not connect to MySQL. Start MySQL in the XAMPP control panel and try again.";
    } else {
        mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbName");
        mysqli_select_db($conn, $dbName);
        $steps[] = "Database '$dbName' is ready.";

        // Remove old tables (also the first test version of 'ratings')
        mysqli_query($conn, "DROP TABLE IF EXISTS wishlist, reviews, ratings, users");

        mysqli_query($conn, "CREATE TABLE users (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            name       VARCHAR(50)  NOT NULL,
            email      VARCHAR(100) NOT NULL UNIQUE,
            password   VARCHAR(255) NOT NULL,
            created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
        $steps[] = "Created table 'users'.";

        mysqli_query($conn, "CREATE TABLE ratings (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            user_id    INT          NOT NULL,
            item       VARCHAR(100) NOT NULL,
            episode    VARCHAR(20)  NOT NULL DEFAULT '',
            stars      TINYINT      NOT NULL,
            created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY one_vote (user_id, item, episode)
        )");
        $steps[] = "Created table 'ratings'.";

        mysqli_query($conn, "CREATE TABLE reviews (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            user_id    INT          NULL,
            name       VARCHAR(50)  NOT NULL,
            item       VARCHAR(100) NOT NULL,
            stars      TINYINT      NOT NULL,
            review     TEXT         NOT NULL,
            is_sample  TINYINT      NOT NULL DEFAULT 0,
            created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
        $steps[] = "Created table 'reviews'.";

        mysqli_query($conn, "CREATE TABLE wishlist (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            user_id    INT          NOT NULL,
            item       VARCHAR(100) NOT NULL,
            created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY one_save (user_id, item)
        )");
        $steps[] = "Created table 'wishlist'.";

        // Insert the sample reviews with a prepared statement
        $stmt = mysqli_prepare($conn, "INSERT INTO reviews (name, item, stars, review, is_sample) VALUES (?, ?, ?, ?, 1)");
        foreach ($sampleReviews as $r) {
            mysqli_stmt_bind_param($stmt, "ssis", $r[1], $r[0], $r[2], $r[3]);
            mysqli_stmt_execute($stmt);
        }
        $steps[] = "Added " . count($sampleReviews) . " sample reviews.";
    }
}

$pageTitle = "Install - Rotten Potatoes";
include "includes/header.php";
?>

<section class="container section">
    <div class="auth-box">
        <h2 class="auth-title">🥔 Set up the database</h2>

        <?php if (count($steps) > 0) { ?>
            <ul class="install-steps">
                <?php foreach ($steps as $step) { ?>
                    <li>✅ <?php echo $step; ?></li>
                <?php } ?>
            </ul>
            <p>All done! You can now register, rate, review and make a wishlist.</p>
            <a href="register.php" class="btn btn-potato">Register now</a>
            <a href="index.php" class="btn btn-outline-light ms-2">Go to Home</a>

        <?php } else { ?>
            <?php if ($error != "") { ?>
                <p class="form-error"><?php echo $error; ?></p>
            <?php } ?>
            <p>This creates the <strong>rotten_potatoes</strong> database in MySQL with the tables
               <em>users</em>, <em>ratings</em>, <em>reviews</em> and <em>wishlist</em>.</p>
            <p class="text-muted-light">If the tables already exist, they are deleted and created again
               (all members, ratings and wishlists are removed).</p>
            <form method="post" action="install.php">
                <button type="submit" name="install" value="1" class="btn btn-potato">Create the database</button>
            </form>
        <?php } ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>

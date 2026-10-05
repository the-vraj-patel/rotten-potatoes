<?php
/*
 * data.php
 * All the website's data lives here in PHP arrays.
 * (Later, this can be moved into a MySQL database — Unit 5: CRUD with PHP.)
 */

// The 4 categories shown in the navigation bar
// key = value used in the URL (category.php?type=movies), value = text shown to the user
$categories = array(
    "movies"    => "Movies",
    "webseries" => "Web Series",
    "anime"     => "Anime",
    "books"     => "Books"
);

// Slides for the homepage carousel
// "style" => "wide"  : landscape picture fills the slide
// "style" => "cover" : a book/manga cover is shown on a blurred background
$featured = array(
    array("title" => "Inception",             "type" => "Movie",      "year" => "2010", "image" => "images/banners/inception.jpg",       "style" => "wide",  "link" => "details.php?id=inception",
          "tagline" => "Your mind is the scene of the crime."),
    array("title" => "Stranger Things",       "type" => "Web Series", "year" => "2016", "image" => "images/banners/stranger-things.jpg", "style" => "wide",  "link" => "category.php?type=webseries",
          "tagline" => "Something strange is happening in Hawkins."),
    array("title" => "Attack on Titan",       "type" => "Anime",      "year" => "2013", "image" => "images/posters/aot.jpg",             "style" => "cover", "link" => "category.php?type=anime",
          "tagline" => "Humanity's last stand behind the walls."),
    array("title" => "Interstellar",          "type" => "Movie",      "year" => "2014", "image" => "images/banners/interstellar.jpg",    "style" => "wide",  "link" => "details.php?id=interstellar",
          "tagline" => "Mankind was born on Earth. It was never meant to die here."),
    array("title" => "Harry Potter and the Philosopher's Stone", "type" => "Book", "year" => "1997", "image" => "images/posters/harry-potter.jpg", "style" => "cover", "link" => "category.php?type=books",
          "tagline" => "The boy who lived begins his journey."),
    array("title" => "Breaking Bad",          "type" => "Web Series", "year" => "2008", "image" => "images/banners/breaking-bad.jpg",    "style" => "wide",  "link" => "category.php?type=webseries",
          "tagline" => "A chemistry teacher turns to a life of crime."),
    array("title" => "The Dark Knight",       "type" => "Movie",      "year" => "2008", "image" => "images/banners/dark-knight.jpg",     "style" => "wide",  "link" => "details.php?id=dark-knight",
          "tagline" => "Why so serious?"),
    array("title" => "Demon Slayer",          "type" => "Anime",      "year" => "2019", "image" => "images/posters/demon-slayer.jpg",    "style" => "cover", "link" => "category.php?type=anime",
          "tagline" => "A boy fights demons to save his sister."),
);

// Full details of each movie (only 4 movies for now)
// The key (e.g. "inception") is the id used in the URL: details.php?id=inception
$movies = array(

    "inception" => array(
        "title"    => "Inception",
        "year"     => 2010,
        "poster"   => "images/posters/inception.jpg",
        "banner"   => "images/banners/inception.jpg",
        "genre"    => array("Sci-Fi", "Action", "Thriller"),
        "runtime"  => 148,                       // in minutes
        "director" => "Christopher Nolan",
        "cast"     => array("Leonardo DiCaprio", "Joseph Gordon-Levitt", "Elliot Page", "Tom Hardy", "Cillian Murphy", "Marion Cotillard"),
        "rating"   => 8.8,                       // out of 10
        "potato"   => 87,                        // Potato Meter (% of critics who liked it)
        "overview" => "Dom Cobb is a skilled thief who steals secrets from deep within people's dreams. "
                    . "He is offered a chance to get his old life back if he can do the impossible: instead of stealing an idea, "
                    . "plant one in someone's mind. Cobb and his team dive into dreams within dreams, where one mistake could trap them forever.",
        "watch"    => array("Prime Video" => "https://www.primevideo.com", "Apple TV" => "https://tv.apple.com", "YouTube Movies" => "https://www.youtube.com/movies"),
        "trailer"  => "YoHD9XEInc0"              // YouTube video id
    ),

    "interstellar" => array(
        "title"    => "Interstellar",
        "year"     => 2014,
        "poster"   => "images/posters/interstellar.jpg",
        "banner"   => "images/banners/interstellar.jpg",
        "genre"    => array("Sci-Fi", "Adventure", "Drama"),
        "runtime"  => 169,
        "director" => "Christopher Nolan",
        "cast"     => array("Matthew McConaughey", "Anne Hathaway", "Jessica Chastain", "Michael Caine", "Matt Damon"),
        "rating"   => 8.7,
        "potato"   => 73,
        "overview" => "Earth is dying, and crops are failing one after another. A former pilot named Cooper joins a team of explorers "
                    . "who travel through a wormhole near Saturn to search for a new home for humanity — leaving behind his children, "
                    . "knowing that time moves differently out there.",
        "watch"    => array("Prime Video" => "https://www.primevideo.com", "Apple TV" => "https://tv.apple.com", "YouTube Movies" => "https://www.youtube.com/movies"),
        "trailer"  => "zSWdZVtXT7E"
    ),

    "dark-knight" => array(
        "title"    => "The Dark Knight",
        "year"     => 2008,
        "poster"   => "images/posters/dark-knight.jpg",
        "banner"   => "images/banners/dark-knight.jpg",
        "genre"    => array("Action", "Crime", "Drama"),
        "runtime"  => 152,
        "director" => "Christopher Nolan",
        "cast"     => array("Christian Bale", "Heath Ledger", "Aaron Eckhart", "Michael Caine", "Gary Oldman", "Maggie Gyllenhaal"),
        "rating"   => 9.0,
        "potato"   => 94,
        "overview" => "Batman, Lieutenant Gordon and District Attorney Harvey Dent team up to clean Gotham City of organised crime. "
                    . "But a mysterious criminal called the Joker appears and pushes the city — and Batman — into chaos, "
                    . "testing how far a hero will go to stop him.",
        "watch"    => array("Prime Video" => "https://www.primevideo.com", "Apple TV" => "https://tv.apple.com", "YouTube Movies" => "https://www.youtube.com/movies"),
        "trailer"  => "EXeTwQWrcwY"
    ),

    "endgame" => array(
        "title"    => "Avengers: Endgame",
        "year"     => 2019,
        "poster"   => "images/posters/endgame.jpg",
        "banner"   => "images/banners/endgame.jpg",
        "genre"    => array("Action", "Adventure", "Sci-Fi"),
        "runtime"  => 181,
        "director" => "Anthony Russo & Joe Russo",
        "cast"     => array("Robert Downey Jr.", "Chris Evans", "Mark Ruffalo", "Chris Hemsworth", "Scarlett Johansson", "Josh Brolin"),
        "rating"   => 8.4,
        "potato"   => 94,
        "overview" => "After Thanos wiped out half of all life in the universe, the remaining Avengers are broken and scattered. "
                    . "Five years later, a small chance appears to undo the damage. The heroes assemble one final time "
                    . "for a mission across time to bring everyone back.",
        "watch"    => array("JioHotstar" => "https://www.hotstar.com", "Apple TV" => "https://tv.apple.com"),
        "trailer"  => "TcMBFSGVi1c"
    ),
);

// Small helper function: converts minutes into "2h 28m"
function formatRuntime($minutes) {
    $hours = floor($minutes / 60);
    $mins  = $minutes % 60;
    return $hours . "h " . $mins . "m";
}
?>

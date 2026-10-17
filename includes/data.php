<?php
/*
 * data.php
 * Loads all the website's data (stored in PHP arrays) and holds the
 * lists, labels and small helper functions that every page needs.
 *
 * The big arrays are kept in separate files so they are easy to edit:
 *   data/movies.php     -> $movies
 *   data/webseries.php  -> $webseries
 *   data/anime.php      -> $anime
 *   data/books.php      -> $books
 *   data/episodes/<id>.php -> $seasons of one show (loaded only on its details page)
 */

include_once __DIR__ . "/../data/movies.php";
include_once __DIR__ . "/../data/webseries.php";
include_once __DIR__ . "/../data/anime.php";
include_once __DIR__ . "/../data/books.php";

// The 4 categories shown in the navigation bar
// key = value used in the URL (category.php?type=movies), value = text shown to the user
$categories = array(
    "movies"    => "Movies",
    "webseries" => "Web Series",
    "anime"     => "Anime",
    "books"     => "Books"
);

// Each category with its array of titles
$catalog = array(
    "movies"    => $movies,
    "webseries" => $webseries,
    "anime"     => $anime,
    "books"     => $books
);

// Every title in one array (used by details.php and search.php)
$allTitles = array_merge($movies, $webseries, $anime, $books);

// Which category a title's "type" belongs to
$typeToCategory = array(
    "movie"  => "movies",
    "series" => "webseries",
    "anime"  => "anime",
    "book"   => "books"
);

// Genre key used in the data => label shown on the site
$genres = array(
    "scifi"      => "Sci-Fi",
    "mystery"    => "Mystery",
    "comedy"     => "Comedy",
    "romance"    => "Romance",
    "horror"     => "Horror",
    "thriller"   => "Thriller",
    "crime"      => "Crime",
    "fantasy"    => "Fantasy",
    "biography"  => "Biography",
    "action"     => "Action",
    "drama"      => "Drama",
    "adventure"  => "Adventure",
    "animation"  => "Animation",
    "family"     => "Family",
    "war"        => "War",
    "sports"     => "Sports",
    "musical"    => "Musical",
    // genres used only by books
    "literary"   => "Literary Fiction",
    "historical" => "Historical Fiction",
    "selfhelp"   => "Self-Help",
    "nonfiction" => "Non-Fiction",
    "ya"         => "Young Adult",
    "classics"   => "Classics",
    "indian"     => "Indian Authors",
    "manga"      => "Manga & Graphic Novels"
);

// Film industries
$industries = array(
    "hollywood"       => "Hollywood",
    "bollywood"       => "Bollywood",
    "south-indian"    => "South Indian",
    "indian-regional" => "Indian Regional",
    "indian"          => "Indian",
    "korean"          => "Korean",
    "japanese"        => "Japanese",
    "chinese"         => "Chinese & HK",
    "european"        => "European",
    "world"           => "World Cinema"
);

// Book lists
$bookLists = array(
    "bestseller" => "Bestsellers",
    "new"        => "Underrated New Releases"
);

// Slides for the homepage carousel
// "style" => "wide"  : landscape picture fills the slide
// "style" => "cover" : a book/manga cover is shown on a blurred background
$featured = array(
    array("id" => "inception",       "type" => "Movie",      "image" => "images/banners/inception.jpg",       "style" => "wide",
          "tagline" => "Your mind is the scene of the crime."),
    array("id" => "stranger-things", "type" => "Web Series", "image" => "images/banners/stranger-things.jpg", "style" => "wide",
          "tagline" => "Something strange is happening in Hawkins."),
    array("id" => "attack-on-titan", "type" => "Anime",      "image" => "images/posters/aot.jpg",             "style" => "cover",
          "tagline" => "Humanity's last stand behind the walls."),
    array("id" => "interstellar",    "type" => "Movie",      "image" => "images/banners/interstellar.jpg",    "style" => "wide",
          "tagline" => "Mankind was born on Earth. It was never meant to die here."),
    array("id" => "harry-potter-and-the-philosophers-stone", "type" => "Book", "image" => "images/posters/harry-potter.jpg", "style" => "cover",
          "tagline" => "The boy who lived begins his journey."),
    array("id" => "breaking-bad",    "type" => "Web Series", "image" => "images/banners/breaking-bad.jpg",    "style" => "wide",
          "tagline" => "A chemistry teacher turns to a life of crime."),
    array("id" => "the-dark-knight", "type" => "Movie",      "image" => "images/banners/dark-knight.jpg",     "style" => "wide",
          "tagline" => "Why so serious?"),
    array("id" => "demon-slayer",    "type" => "Anime",      "image" => "images/posters/demon-slayer.jpg",    "style" => "cover",
          "tagline" => "A boy fights demons to save his sister."),
);

// ---------- Helper functions ----------

// Converts minutes into "2h 28m"
function formatRuntime($minutes) {
    if ($minutes == 0) {
        return "—";
    }
    $hours = floor($minutes / 60);
    $mins  = $minutes % 60;
    if ($hours == 0) {
        return $mins . "m";
    }
    return $hours . "h " . $mins . "m";
}

// Label for a genre or industry key, e.g. "scifi" -> "Sci-Fi"
function label($key) {
    global $genres, $industries;
    if (isset($genres[$key])) {
        return $genres[$key];
    }
    if (isset($industries[$key])) {
        return $industries[$key];
    }
    return ucfirst($key);
}

// Keeps only the titles that match the filters. An empty filter ("") means "any".
function filterTitles($list, $genre, $industry, $format, $bookList) {
    $result = array();
    foreach ($list as $id => $t) {
        if ($genre != "" && !in_array($genre, $t["genres"])) {
            continue;
        }
        if ($industry != "" && (!isset($t["industry"]) || $t["industry"] != $industry)) {
            continue;
        }
        if ($format != "" && $t["format"] != $format) {
            continue;
        }
        if ($bookList != "" && (!isset($t["lists"]) || !in_array($bookList, $t["lists"]))) {
            continue;
        }
        $result[$id] = $t;
    }
    return $result;
}

// Sorts titles: "rating" (best first), "year" (newest first) or "title" (A-Z)
function sortTitles($list, $sort) {
    // 1. Make a small array of id => value to sort by
    $values = array();
    foreach ($list as $id => $t) {
        if ($sort == "year") {
            $values[$id] = $t["year"];
        } elseif ($sort == "title") {
            $values[$id] = strtolower($t["title"]);
        } else {
            $values[$id] = $t["rating"];
        }
    }

    // 2. Sort it (asort = low to high, arsort = high to low; both keep the keys)
    if ($sort == "title") {
        asort($values);
    } else {
        arsort($values);
    }

    // 3. Put the full titles back in the new order
    $sorted = array();
    foreach ($values as $id => $value) {
        $sorted[$id] = $list[$id];
    }
    return $sorted;
}

// Counts how many titles have each genre (or industry): used for the filter buttons
function countBy($list, $field) {
    $counts = array();
    foreach ($list as $t) {
        if (!isset($t[$field])) {
            continue;
        }
        // "genres" is an array, "industry" is a single value
        if (is_array($t[$field])) {
            $values = $t[$field];
        } else {
            $values = array($t[$field]);
        }
        foreach ($values as $v) {
            if (isset($counts[$v])) {
                $counts[$v]++;
            } else {
                $counts[$v] = 1;
            }
        }
    }
    arsort($counts);
    return $counts;
}

// Short text under a poster card, e.g. "2010 · Sci-Fi" or "2013–2023 · 4 seasons"
function cardMeta($t) {
    if ($t["type"] == "book") {
        return $t["author"];
    }
    if ($t["format"] == "series") {
        $years = $t["year"];
        if ($t["ended"] != null && $t["ended"] != $t["year"]) {
            $years = $years . "–" . $t["ended"];
        } elseif ($t["status"] == "Running") {
            $years = $years . "–";
        }
        if ($t["seasons"] == 1) {
            return $years . " · 1 season";
        }
        return $years . " · " . $t["seasons"] . " seasons";
    }
    return $t["year"] . " · " . label($t["genres"][0]);
}

// Builds a link to category.php with the given filters (empty filters are left out)
function categoryLink($type, $genre, $industry, $format, $bookList, $sort) {
    $link = "category.php?type=" . $type;
    if ($genre != "")     $link .= "&genre=" . $genre;
    if ($industry != "")  $link .= "&industry=" . $industry;
    if ($format != "")    $link .= "&format=" . $format;
    if ($bookList != "")  $link .= "&list=" . $bookList;
    if ($sort != "" && $sort != "rating") $link .= "&sort=" . $sort;
    return $link;
}
?>

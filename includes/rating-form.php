<?php
/*
 * rating-form.php — the "rate it yourself" stars.
 * Each star is a submit button of a normal HTML form (method POST).
 * details.php receives the form, saves the rating in MySQL and shows the page again.
 */

// $id      : title id
// $season  : season shown on the page (so we come back to the same season)
// $episode : episode id like "s1e5", or "" for the whole title
// $ratings : the array from getRatings()
// $label   : text above the stars, e.g. "Rate this movie" (empty = no text)
function ratingForm($id, $season, $episode, $ratings, $label) {
    if (isset($ratings[$episode])) {
        $data = $ratings[$episode];
    } else {
        $data = array("avg" => 0, "count" => 0, "mine" => 0);
    }

    // After saving, jump back to the same place on the page
    $action = "details.php?id=" . $id;
    if ($season > 0) {
        $action .= "&season=" . $season;
    }
    if ($episode != "") {
        $action .= "#ep-" . $episode;
    } else {
        $action .= "#rating";
    }

    $html = '<form method="post" action="' . $action . '" class="rate-form">';
    if ($label != "") {
        $html .= '<span class="rate-label">' . $label . '</span>';
    }
    $html .= '<input type="hidden" name="episode" value="' . $episode . '">';
    $html .= '<div class="rate-stars">';
    for ($i = 1; $i <= 5; $i++) {
        $class = "rate-star";
        if ($i <= $data["mine"]) {
            $class .= " on";
        }
        $html .= '<button type="submit" name="stars" value="' . $i . '" class="' . $class . '" title="' . $i . ' out of 5">★</button>';
    }
    $html .= '</div>';

    // Small line under the stars
    if ($data["mine"] > 0) {
        $html .= '<span class="rate-info">You gave ' . $data["mine"] . '★ &middot; average ' . $data["avg"] . '★ (' . $data["count"] . ')</span>';
    } elseif ($data["count"] > 0) {
        $html .= '<span class="rate-info">Visitors: ' . $data["avg"] . '★ (' . $data["count"] . ')</span>';
    }
    $html .= '</form>';
    return $html;
}

// CSS class for a rating out of 10, used for the colours in the episode tables
function ratingClass($rating) {
    if ($rating == null) return "r-none";
    if ($rating >= 9)    return "r-great";
    if ($rating >= 8)    return "r-good";
    if ($rating >= 7)    return "r-ok";
    if ($rating >= 6)    return "r-meh";
    return "r-bad";
}
?>

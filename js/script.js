/*
 * script.js — client side scripting with jQuery
 */

$(document).ready(function () {

    // 1. Search validation: don't submit the form if the search box is empty
    $("#searchForm").on("submit", function (event) {
        var text = $("#searchInput").val().trim();

        if (text === "") {
            event.preventDefault();                       // stop the form from submitting
            $("#searchInput").addClass("is-invalid")
                             .attr("placeholder", "Type something first!")
                             .focus();
        }
    });

    // Remove the red border as soon as the user starts typing
    $("#searchInput").on("input", function () {
        $(this).removeClass("is-invalid").attr("placeholder", "Movies, shows, anime, books...");
    });

    // 2. Fade in the cards one after another when the page loads
    $(".movie-card").each(function (index) {
        $(this).hide().delay(index * 60).fadeIn(400);
    });

    // 3. Star rating: light up the stars up to the one under the mouse
    $(".rate-star").on("mouseenter", function () {
        $(this).prevAll(".rate-star").addBack().addClass("hover");
    });
    $(".rate-star").on("mouseleave", function () {
        $(this).siblings(".rate-star").addBack().removeClass("hover");
    });

});

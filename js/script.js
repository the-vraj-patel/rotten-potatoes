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
        $(this).removeClass("is-invalid").attr("placeholder", "Search titles...");
    });

    // 2. Fade in movie cards one after another when the page loads
    $(".movie-card").each(function (index) {
        $(this).hide().delay(index * 120).fadeIn(500);
    });

});

# Rotten Potatoes 🥔

A simple cinema tracking and rating website, built for Web Development (BTCO23307).

## How to run (XAMPP)
1. Copy the `rotten-potatoes` folder into XAMPP's `htdocs` folder
   (Linux: `/opt/lampp/htdocs/`, Windows: `C:\xampp\htdocs\`).
2. Start **Apache** from the XAMPP control panel.
3. Open http://localhost/rotten-potatoes/ in your browser.

You need an internet connection for Bootstrap, jQuery, Google Fonts and the YouTube trailers.

## Files
| File | What it does |
|------|-------------|
| `index.php` | Homepage: image slider, popular movies, categories |
| `category.php?type=movies` | Lists the items in one category |
| `details.php?id=inception` | Full details for one movie |
| `search.php?q=nolan` | Search results |
| `includes/data.php` | All the movie data, stored in PHP arrays |
| `includes/header.php` / `footer.php` | Shared top and bottom of every page |
| `includes/movie-card.php` | One poster card, reused in loops |
| `css/style.css` | All the styling |
| `js/script.js` | jQuery: search validation and fade-in effect |

## Syllabus topics used
- **HTML/CSS:** semantic tags, iframe (YouTube), meta tags, CSS variables, box model,
  positioning, animations (`@keyframes`), media queries, `*`, `^=` and `$=` selectors
- **Bootstrap:** CDN, grid system, navbar, carousel, buttons, responsive utilities
- **JavaScript/jQuery:** form validation, event handling, DOM manipulation, effects (`fadeIn`)
- **PHP:** variables, arrays, associative arrays, `if`/`else`, `for`/`foreach`, functions,
  `include`, form processing with GET, query strings (`?id=...`)

## Next steps
- Move the data from `data.php` into a **MySQL** database and read it with PHP (CRUD + prepared statements)
- Add a login system with **sessions**, so users can give their own ratings
- Fill in the Web Series, Anime and Books categories
# rotten-potatoes

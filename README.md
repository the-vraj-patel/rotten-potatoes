# Rotten Potatoes 🥔

A cinema, series, anime and book tracking and rating website, built for Web Development (BTCO23307).

It has **638 titles**: 240 movies (Hollywood, Bollywood, South Indian, Korean, Japanese, European and world cinema),
90 web series, 81 anime series (with every episode rated) + 30 anime movies, and 197 books
(all genres, bestsellers and underrated new releases).

## How to run (XAMPP)
1. Copy the `rotten-potatoes` folder into XAMPP's `htdocs` folder
   (Linux: `/opt/lampp/htdocs/`, Windows: `C:\xampp\htdocs\`).
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open http://localhost/phpmyadmin → **Import** → choose `database.sql` → **Go**.
   (This creates the `rotten_potatoes` database for visitor ratings.)
4. Open http://localhost/rotten-potatoes/ in your browser.

You need an internet connection for Bootstrap, jQuery, Google Fonts, the posters and the YouTube trailers.
The site still works without step 3 — only the "rate it yourself" stars are switched off.

## Files
| File | What it does |
|------|-------------|
| `index.php` | Homepage: image slider, rows of top titles, categories, genres |
| `category.php?type=movies&genre=horror&industry=bollywood` | One category, with filters, sorting and page numbers |
| `details.php?id=inception` | Full details for one title |
| `details.php?id=attack-on-titan&season=2` | Shows: episode rating table, seasons, every episode with its rating |
| `search.php?q=nolan` | Search results, grouped by category |
| `includes/data.php` | Loads the data arrays; lists of categories, genres, industries; helper functions |
| `includes/db.php` | MySQL connection and the rating functions (CRUD with prepared statements) |
| `includes/rating-form.php` | The star rating form (plain HTML form, method POST) |
| `includes/episodes.php` | The episode section of a series/anime page |
| `includes/header.php` / `footer.php` | Shared top and bottom of every page |
| `includes/movie-card.php` | One poster card, reused in loops |
| `data/movies.php`, `webseries.php`, `anime.php`, `books.php` | All titles, stored in PHP arrays |
| `data/episodes/<id>.php` | Seasons and episodes of each show, stored in PHP arrays |
| `database.sql` | Creates the MySQL database and `ratings` table |
| `css/style.css` | All the styling |
| `js/script.js` | jQuery: search validation, fade-in effect, star hover effect |

## How rating works
- Every star is a submit button of a normal HTML form (`method="post"`).
- `details.php` receives the form, checks the values, and saves the rating in MySQL:
  it first **reads** whether this visitor already rated it, then **updates** the old rating or **inserts** a new one.
- Visitors are told apart by their PHP **session** id, so each visitor has one rating per title/episode.
- The page shows the average of all visitors (`AVG`, `COUNT` with `GROUP BY` in SQL).

## Syllabus topics used
- **HTML/CSS:** semantic tags, forms, tables, iframe (YouTube), meta tags, CSS variables, box model,
  positioning, flexbox, animations (`@keyframes`), media queries, `*`, `^=`, `$=` and `:target` selectors
- **Bootstrap:** CDN, grid system, navbar, carousel, buttons, pagination, responsive utilities
- **JavaScript/jQuery:** form validation, event handling, DOM manipulation, effects (`fadeIn`)
- **PHP:** variables, arrays, associative arrays, `if`/`else`, `for`/`foreach`, functions, `include`,
  form processing with GET and POST, query strings, sessions, sorting arrays (`asort`/`arsort`)
- **MySQL:** database and table creation, CRUD, prepared statements (`mysqli_prepare`), `AVG`/`COUNT`/`GROUP BY`

## Where the data comes from
The titles were chosen by hand (lists in `data/seeds/`). Posters, summaries, cast, episodes and
episode ratings were then copied in once from open sources:
[TVmaze](https://www.tvmaze.com) (CC BY-SA), [Wikipedia](https://www.wikipedia.org) and
[Open Library](https://openlibrary.org). The website itself only reads the PHP arrays — it does not call any API.

# Rotten Potatoes 🥔

A cinema, series, anime and book tracking and rating website, built for Web Development (BTCO23307).

It has **638 titles**: 240 movies (Hollywood, Bollywood, South Indian, Korean, Japanese, European and world cinema),
90 web series, 81 anime series (with every episode rated) + 30 anime movies, and 197 books
(all genres, bestsellers and underrated new releases).

## How to run (XAMPP)
1. Copy the `rotten-potatoes` folder into XAMPP's `htdocs` folder
   (Linux: `/opt/lampp/htdocs/`, Windows: `C:\xampp\htdocs\`).
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open http://localhost/rotten-potatoes/install.php once and click **Create the database**.
   (PHP creates the `rotten_potatoes` database with the tables `users`, `ratings`, `reviews`, `wishlist`.)
4. Open http://localhost/rotten-potatoes/ in your browser, click **Register**, and start rating.

**Before putting the site online, delete `install.php`** — anyone who opens it could reset the database.

You need an internet connection for Bootstrap, jQuery, Google Fonts, the posters and the YouTube trailers.
The site still works without step 3 — only accounts, ratings, reviews and wishlists are switched off.

## Files
| File | What it does |
|------|-------------|
| `index.php` | Homepage: image slider, rows of top titles, categories, genres |
| `category.php?type=movies&genre=horror&industry=bollywood` | One category, with filters, sorting and page numbers |
| `details.php?id=inception` | Full details for one title |
| `details.php?id=attack-on-titan&season=2` | Shows: episode rating table, seasons, every episode with its rating |
| `search.php?q=nolan` | Search results, grouped by category |
| `includes/data.php` | Loads the data arrays; lists of categories, genres, industries; helper functions |
| `install.php` | Creates the MySQL database and tables (open once) |
| `register.php` / `login.php` / `logout.php` | Member accounts (sessions) |
| `wishlist.php` | Member page: member ID, wishlist and recommendations |
| `includes/auth.php` | Session start and login helper functions |
| `includes/db.php` | MySQL connection and all database functions (CRUD with prepared statements) |
| `includes/rating-form.php` | The star rating form (plain HTML form, method POST) |
| `includes/reviews.php` | Written reviews and the review form |
| `includes/episodes.php` | The episode section of a series/anime page |
| `includes/header.php` / `footer.php` | Shared top and bottom of every page |
| `includes/movie-card.php` | One poster card, reused in loops |
| `data/movies.php`, `webseries.php`, `anime.php`, `books.php` | All titles, stored in PHP arrays |
| `data/episodes/<id>.php` | Seasons and episodes of each show, stored in PHP arrays |
| `css/style.css` | All the styling |
| `js/script.js` | jQuery: search validation, fade-in effect, star hover effect |

## Accounts, ratings, reviews and wishlist
- **Register** with name, email and password. MySQL gives every member an id (`AUTO_INCREMENT`),
  shown as the **member ID** on the wishlist page. Passwords are saved as a hash (`password_hash`), never as plain text.
- **Login** checks the password with `password_verify` and remembers the member in the **session**.
- Visitors who click a star, the wishlist button or "write a review" are sent to login/register,
  and come back to the same page afterwards.
- **Rating:** every star is a submit button of a normal HTML form (`method="post"`). `details.php` saves it:
  it **reads** whether this member already rated it, then **updates** the old rating or **inserts** a new one.
  The average of all members is shown with `AVG`, `COUNT` and `GROUP BY`.
- **Reviews:** a form with stars and text, saved in the `reviews` table. Sample reviews are marked "Sample review".
- **Wishlist:** add/remove with one button (**insert** / **delete**). The wishlist page recommends titles that
  share genres with the wishlist (more shared genres and the same kind of title = higher in the list).

## Syllabus topics used
- **HTML/CSS:** semantic tags, forms, tables, iframe (YouTube), meta tags, CSS variables, box model,
  positioning, flexbox, animations (`@keyframes`), media queries, `*`, `^=`, `$=` and `:target` selectors
- **Bootstrap:** CDN, grid system, navbar, carousel, buttons, pagination, responsive utilities
- **JavaScript/jQuery:** form validation, event handling, DOM manipulation, effects (`fadeIn`)
- **PHP:** variables, arrays, associative arrays, `if`/`else`, `for`/`foreach`, functions, `include`,
  form processing with GET and POST, query strings, sessions (login system), `header()` redirects,
  sorting arrays (`asort`/`arsort`)
- **MySQL:** creating the database and tables from PHP, CRUD, prepared statements (`mysqli_prepare`),
  `AVG`/`COUNT`/`GROUP BY`, `UNIQUE` keys

## Where the data comes from
The titles were chosen by hand (lists in `data/seeds/`). Posters, summaries, cast, episodes and
episode ratings were then copied in once from open sources:
[TVmaze](https://www.tvmaze.com) (CC BY-SA), [Wikipedia](https://www.wikipedia.org) and
[Open Library](https://openlibrary.org). The website itself only reads the PHP arrays — it does not call any API.

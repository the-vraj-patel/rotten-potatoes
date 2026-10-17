-- ==========================================================
-- Rotten Potatoes — database for visitor ratings and reviews
-- Import this file once in phpMyAdmin (http://localhost/phpmyadmin → Import),
-- or run it from the MySQL console.
-- ==========================================================

CREATE DATABASE IF NOT EXISTS rotten_potatoes;
USE rotten_potatoes;

-- One row = one visitor's star rating of one title or one episode
CREATE TABLE IF NOT EXISTS ratings (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    item       VARCHAR(100) NOT NULL,              -- title id, e.g. 'attack-on-titan'
    episode    VARCHAR(20)  NOT NULL DEFAULT '',   -- e.g. 's1e5', or '' for the whole title
    stars      TINYINT      NOT NULL,              -- 1 to 5
    voter      VARCHAR(128) NOT NULL,              -- the visitor's PHP session id
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY one_vote (item, episode, voter)     -- each visitor rates each thing only once
);

-- One row = one written review of a title
CREATE TABLE IF NOT EXISTS reviews (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    item       VARCHAR(100) NOT NULL,              -- title id
    name       VARCHAR(50)  NOT NULL,              -- name the visitor typed
    stars      TINYINT      NOT NULL,              -- 1 to 5
    review     TEXT         NOT NULL,
    is_sample  TINYINT      NOT NULL DEFAULT 0,    -- 1 = sample review written for the demo
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------
-- Sample reviews (written by us for the demo, shown with a "Sample review" label)
-- ----------------------------------------------------------
INSERT INTO reviews (item, name, stars, review, is_sample) VALUES
('inception', 'Aarav', 5, 'Watched it three times and still found new details. The hallway fight and the ending kept my whole friend group arguing for days.', 1),
('inception', 'Meera', 4, 'Brilliant idea and an amazing soundtrack. The first hour has a lot of explaining, but once the heist starts it never slows down.', 1),
('inception', 'Rohan', 5, 'One of those movies that makes you think long after it ends. Every layer of the dream has its own rules and it all fits together.', 1),
('inception', 'Sana', 4, 'Great visuals and a clever story. I wish the characters had a bit more emotion, but the spinning top ending is perfect.', 1),

('interstellar', 'Kabir', 5, 'The docking scene with the music is pure goosebumps. A space movie that is really about a father and his daughter.', 1),
('interstellar', 'Isha', 5, 'I cried at the video messages scene. The science is heavy but the feelings are simple and real.', 1),
('interstellar', 'Dev', 4, 'Huge, beautiful and ambitious. The ending is a bit confusing, but the planets and the black hole look unbelievable.', 1),
('interstellar', 'Anaya', 4, 'Long, but worth every minute on a big screen. The time difference on the water planet is terrifying.', 1),

('the-dark-knight', 'Vivaan', 5, 'Heath Ledger''s Joker is unforgettable. This is a crime thriller first and a superhero movie second.', 1),
('the-dark-knight', 'Priya', 5, 'Every scene with the Joker is tense. The ferry scene and the interrogation are some of the best moments in any film.', 1),
('the-dark-knight', 'Arjun', 4, 'Dark, serious and smart. Two-Face''s story feels a bit rushed at the end, but the rest is near perfect.', 1),
('the-dark-knight', 'Nisha', 5, 'Still the best Batman movie after all these years. The music alone makes your heart race.', 1),

('stranger-things', 'Aditya', 5, 'The kids are the heart of the show. 80s vibes, great music and real scares — season 1 is a perfect binge.', 1),
('stranger-things', 'Riya', 4, 'Loved Eleven and Hopper. Some later seasons get a bit too big, but I still couldn''t stop watching.', 1),
('stranger-things', 'Yash', 4, 'The Upside Down is so creepy. Season 4 with Vecna is the scariest and the best one for me.', 1),
('stranger-things', 'Tara', 5, 'Running Up That Hill will never sound the same again. Great friendships and great monsters.', 1),

('attack-on-titan', 'Harsh', 5, 'Starts as a monster story and turns into something much bigger. The plot twists in season 3 blew my mind.', 1),
('attack-on-titan', 'Kavya', 5, 'Levi is the coolest character in anime. The animation and the soundtrack are on another level.', 1),
('attack-on-titan', 'Neel', 4, 'Very dark and sometimes hard to watch, but the story is planned so well. Every early detail matters later.', 1),
('attack-on-titan', 'Zoya', 4, 'Season 1 hooked me in one episode. The final season makes you question who the real villain is.', 1),

('harry-potter-and-the-philosophers-stone', 'Ishaan', 5, 'The book that made me love reading. Hogwarts feels like a real place you want to live in.', 1),
('harry-potter-and-the-philosophers-stone', 'Diya', 5, 'Simple, magical and funny. Perfect for kids but I enjoyed rereading it as an adult too.', 1),
('harry-potter-and-the-philosophers-stone', 'Reyansh', 4, 'A lovely start to the series. It is shorter and lighter than the later books, but full of wonder.', 1),
('harry-potter-and-the-philosophers-stone', 'Myra', 5, 'The mirror of Erised chapter is so beautiful. I finished the whole book in one weekend.', 1),

('breaking-bad', 'Karan', 5, 'Watching Walter White change from a teacher into Heisenberg is incredible. Not a single bad season.', 1),
('breaking-bad', 'Pooja', 5, 'The best TV show I have ever watched. Ozymandias is a perfect episode of television.', 1),
('breaking-bad', 'Siddharth', 4, 'Season 1 is a bit slow, but once it gets going it is impossible to stop. Jesse is my favourite character.', 1),
('breaking-bad', 'Aisha', 5, 'Every episode builds tension. The ending is one of the most satisfying finales ever.', 1),

('demon-slayer', 'Atharv', 5, 'Episode 19 is the most beautiful animation I have ever seen. Ufotable did magic with this show.', 1),
('demon-slayer', 'Saanvi', 4, 'Simple story about a brother and sister, but the fights are amazing. Zenitsu makes me laugh every time.', 1),
('demon-slayer', 'Laksh', 5, 'The Entertainment District arc is pure fire. Great music and great characters.', 1),
('demon-slayer', 'Ira', 4, 'Gorgeous to look at and easy to binge. Tanjiro is one of the kindest main characters in anime.', 1);

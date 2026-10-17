-- ==========================================================
-- Rotten Potatoes — database for visitor ratings
-- Import this file once in phpMyAdmin (http://localhost/phpmyadmin → Import),
-- or run it from the MySQL console.
-- ==========================================================

CREATE DATABASE IF NOT EXISTS rotten_potatoes;
USE rotten_potatoes;

-- One row = one visitor's rating of one title or one episode
CREATE TABLE IF NOT EXISTS ratings (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    item       VARCHAR(100) NOT NULL,              -- title id, e.g. 'attack-on-titan'
    episode    VARCHAR(20)  NOT NULL DEFAULT '',   -- e.g. 's1e5', or '' for the whole title
    stars      TINYINT      NOT NULL,              -- 1 to 5
    voter      VARCHAR(128) NOT NULL,              -- the visitor's PHP session id
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY one_vote (item, episode, voter)     -- each visitor rates each thing only once
);

CREATE DATABASE IF NOT EXISTS eventhub_pro;
USE eventhub_pro;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150),
    email VARCHAR(150) UNIQUE,
    role ENUM('organizer','participant') DEFAULT 'participant'
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    location VARCHAR(255),
    category_id INT,
    organizer_id INT,
    event_date DATETIME,
    capacity INT,
    registered_count INT DEFAULT 0,
    alert_sent TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE CASCADE,

    FOREIGN KEY (organizer_id) REFERENCES users(id)
    ON DELETE CASCADE
);

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    token VARCHAR(255) UNIQUE,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY (event_id) REFERENCES events(id)
    ON DELETE CASCADE
);

CREATE INDEX idx_event_date_category
ON events(event_date, category_id);

-- catégories
INSERT INTO categories(name)
VALUES
('Hackathon'),
('Conference');

-- utilisateurs
INSERT INTO users(fullname,email,role)
VALUES
('Admin Event','admin@test.com','organizer'),
('Ali','ali@test.com','participant'),
('Sara','sara@test.com','participant'),
('Youssef','youssef@test.com','participant'),
('Lina','lina@test.com','participant');

-- événements
INSERT INTO events(
title,
description,
location,
category_id,
organizer_id,
event_date,
capacity
)
VALUES
(
'DevFest Marrakech 2026',
'Grand événement tech',
'Marrakech',
1,
1,
'2026-06-20 10:00:00',
5
),
(
'PHP Conference',
'Conférence PHP',
'Casablanca',
2,
1,
'2026-07-10 09:00:00',
100
),
(
'AI Meetup',
'Meetup IA',
'Rabat',
2,
1,
'2026-08-01 14:00:00',
50
);
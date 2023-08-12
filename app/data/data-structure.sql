DROP TABLE IF EXISTS books;
--
DROP TABLE IF EXISTS books_notes;
--
DROP TABLE IF EXISTS books_types;
--
DROP TABLE IF EXISTS books_author;
--
CREATE TABLE books_notes
(
    id     int(11)     NOT NULL,
    note   varchar(50) NOT NULL,
    legend varchar(50) NULL,
    PRIMARY KEY (id)
);
--
CREATE TABLE books_types
(
    name varchar(60) NOT NULL,
    PRIMARY KEY (name)
);
--
CREATE TABLE books
(
    slug            varchar(300) NOT NULL,
    title           varchar(200) NOT NULL,
    author          varchar(200) NOT NULL,
    type_id         varchar(60)           DEFAULT NULL,
    note_id         int(11)               DEFAULT NULL,
    summary         longtext,
    finished_at     date         DEFAULT NULL,
    private_book    boolean      NOT NULL DEFAULT FALSE,
    private_summary boolean      NOT NULL DEFAULT FALSE,
    abandonned_at   date         NULL,
    created_at      date         NULL,
    deleted_at      date         NULL,
    PRIMARY KEY (slug),
    KEY type_id (type_id),
    KEY note_id (note_id),
    CONSTRAINT books_ibfk_1 FOREIGN KEY (type_id) REFERENCES books_types (name),
    CONSTRAINT books_ibfk_2 FOREIGN KEY (note_id) REFERENCES books_notes (id)
);
--
CREATE TABLE books_author
(
    slug varchar(255) NOT NULL PRIMARY KEY,
    name varchar(255) NOT NULL
);

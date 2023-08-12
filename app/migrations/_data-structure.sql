--
-- Table structure for table books
--
DROP TABLE IF EXISTS books_notes;
CREATE TABLE books_notes (
    id int(11) NOT NULL,
    note varchar(50) NOT NULL,
    PRIMARY KEY (id)
);

--
-- Table structure for table books_types
--
DROP TABLE IF EXISTS books_types;
CREATE TABLE books_types
(
    name varchar(60)  NOT NULL,
    PRIMARY KEY (name)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Table structure for table books
--
DROP TABLE IF EXISTS books;
CREATE TABLE books
(
    slug        varchar(300) NOT NULL,
    title       varchar(200) NOT NULL,
    author      varchar(200) NOT NULL,
    type_id     varchar(60) DEFAULT NULL,
    note_id     int(11) DEFAULT NULL,
    summary     longtext,
    finished_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (slug),
    KEY type_id (type_id),
    KEY note_id (note_id),
    CONSTRAINT books_ibfk_1 FOREIGN KEY (type_id) REFERENCES books_types (name),
    CONSTRAINT books_ibfk_2 FOREIGN KEY (note_id) REFERENCES books_notes (id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

create table books_author
(
    slug varchar(255) not null primary key,
    name varchar(255) not null
);

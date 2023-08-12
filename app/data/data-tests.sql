INSERT INTO books_notes
    (id, note, legend)
VALUES (0, '*', 'mh'),
       (1, '**', 'good'),
       (2, '***', 'great')
;
--
INSERT INTO books_types
VALUES ('essay'),
       ('novel');
--
INSERT INTO books
    (slug, title, author, type_id, note_id, summary, finished_at, private_book, private_summary, abandonned_at, created_at, deleted_at)
VALUES ('title1_author1', 'Title 1', 'author1', 'essay', 0, 'summary', '2022-01-01', 1, 0, NULL, NULL, NULL), # private
       ('title2_author1', 'Title 2', 'author1', 'essay', 1, 'summary', '2022-01-02', 0, 0, NULL, NULL, NULL), # normal, same author
       ('title3_author2', 'Title 3', 'author2', 'novel', 2, NULL, NULL, 0, 1, NULL, NULL, NULL), # unfinished, private summary
       ('title4_author2', 'Title 4', 'author2', 'novel', 2, NULL, NULL, 0, 0, '2022-01-03', NULL, NULL), # abandonned
       ('title5_author2', 'Title 5', 'author2', 'novel', 2, NULL, NULL, 0, 0, NULL, NULL, '2022-01-01') # deleted
;
--
INSERT INTO books_author (slug, name)
VALUES ('author1', 'Author #1'),
       ('author2', 'Author #2')
;

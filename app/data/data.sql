DELETE
FROM books;
DELETE
FROM books_notes;
DELETE
FROM books_types;
DELETE
FROM books_author;


INSERT INTO books_notes
    (id, note)
VALUES (6, '☆'),
       (5, '★'),
       (4, '★ ★'),
       (3, '★ ★ ★'),
       (2, '♥'),
       (1, '★ ★ ★ ★'),
       (0, '★ ★ ★ ★ ★');

# or with emojis
# UPDATE `books_notes` SET `note`='😞' WHERE id=0;
# UPDATE `books_notes` SET `note`='😐' WHERE id=1;
# UPDATE `books_notes` SET `note`='🙂' WHERE id=2;
# UPDATE `books_notes` SET `note`='⭐' WHERE id=3;
# UPDATE `books_notes` SET `note`='💖' WHERE id=4;
# UPDATE `books_notes` SET `note`='✨' WHERE id=5;
# UPDATE `books_notes` SET `note`='🎇' WHERE id=6;


INSERT INTO books_types
    (name)
VALUES ('essay'),
       ('novel'),
       ('biography'),
       ('art'),
       ('BD');

INSERT INTO books
(slug, title, author, type_id, note_id)
VALUES ('douglas_hofstadter_godel_escher_bach', 'Godel Escher Bach', 'douglas_hofstadter', 'essay', 0),
       ('georges_perec_la_disparition', 'La Disparition', 'georges_perec', 'novel', 1),
       ('jorge_luis_borges_enquetes', 'Enquêtes', 'jorge_luis_borges', 'novel', 2),
       ('olivia_gazale_je_taime_a_la_philo', 'Je T’aime à la Philo', 'olivia_gazale', 'essay', 2),
       ('david_mazzucchelli_asterios_polyp', 'Asterios Polyp', 'david_mazzucchelli', 'BD', 2)
;

INSERT INTO books_authors
    (slug, name)
VALUES ('douglas_hofstadter', 'Douglas Hofstadter'),
       ('georges_perec', 'Georges Perec'),
       ('david_mazzucchelli', 'David Mazzucchelli'),
       ('jorge_luis_borges', 'Jorge Louis Borges'),
       ('olivia_gazale', 'Olivia Gazalé');

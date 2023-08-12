ALTER TABLE books_notes ADD COLUMN legend VARCHAR(64) NULL;

UPDATE books_notes SET legend = 'nul!' WHERE id = 0; -- 0   ☆
UPDATE books_notes SET legend = 'mhh' WHERE id = 1; -- 1   ★
UPDATE books_notes SET legend = 'ok…' WHERE id = 2; -- 2   ★ ★
UPDATE books_notes SET legend = 'bien' WHERE id = 3; -- 3   ★ ★ ★
UPDATE books_notes SET legend = 'adorable' WHERE id = 4; -- 4   ♥
UPDATE books_notes SET legend = 'très bien' WHERE id = 5; -- 5   ★ ★ ★ ★
UPDATE books_notes SET legend = 'extraordinaire' WHERE id = 6; -- 6   ★ ★ ★ ★ ★

INSERT INTO books_notes (id, note, legend) VALUES (7, '⁂⁂⁂', 'wow');

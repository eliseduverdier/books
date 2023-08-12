create table books_author
(
    slug varchar(255) not null primary key,
    name varchar(255) not null
);

insert into books_author (slug, name)
values ('alain_damasio', 'Alain Damasio'),
       ('amandine_dhée', 'Amandine Dhée'),
       ('andré_gide', 'André Gide'),
       ('anne-isabelle_queneau', 'Anne-Isabelle Queneau'),
       ('antonio_damasio', 'Antonio Damasio'),
       ('barjavel', 'Barjavel'),
       ('bertrand_russel', 'Bertrand Russel'),
       ('bunpei_yorifuji', 'Bunpei Yorifuji'),
       ('catherine_dufour', 'Catherine Dufour'),
       ('catherine_musa', 'Catherine Musa'),
       ('c.g._jung', 'C.G. Jung'),
       ('claude_burgelin', 'Claude Burgelin'),
       ('claude_ponti', 'Claude Ponti'),
       ('daniel_kahneman', 'Daniel Kahneman'),
       ('david_graeber', 'David Graeber'),
       ('delphine_de_vigan', 'Delphine de Vigan'),
       ('emmanuel_carrère', 'Emmanuel Carrère'),
       ('françoise_dolto', 'Françoise Dolto'),
       ('hervé_le_tellier', 'Hervé Le Tellier'),
       ('homère', 'Homère'),
       ('italo_calvino', 'Italo Calvino'),
       ('james_joyce', 'James Joyce'),
       ('jean-marc_savoye', 'Jean-Marc Savoye'),
       ('jl_borges', 'JL Borges'),
       ('j._laplanche,_j.b._pontalis', 'J. Laplanche, J.B. Pontalis'),
       ('lorraine_les_bains', 'Lorraine les Bains'),
       ('lydie_salvayre', 'Lydie Salvayre'),
       ('many', 'many'),
       ('marion_fayolle', 'Marion Fayolle'),
       ('alexandre_lacroix', 'Alexandre Lacroix');

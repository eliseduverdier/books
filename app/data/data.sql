delete from books;
delete from books_notes;
delete from books_types;
delete from books_author;


INSERT INTO books_notes
    (id, note)
VALUES (6, '☆'),
       (5, '★'),
       (4, '★ ★'),
       (3, '★ ★ ★'),
       (2, '♥'),
       (1, '★ ★ ★ ★'),
       (0, '★ ★ ★ ★ ★');

# UPDATE `books_notes` SET `note`='😞' WHERE id=0;
# UPDATE `books_notes` SET `note`='😐' WHERE id=1;
# UPDATE `books_notes` SET `note`='🙂' WHERE id=2;
# UPDATE `books_notes` SET `note`='⭐' WHERE id=3;
# UPDATE `books_notes` SET `note`='💖' WHERE id=4;
# UPDATE `books_notes` SET `note`='✨' WHERE id=5;
# UPDATE `books_notes` SET `note`='🎇' WHERE id=6;
-- OR
# UPDATE `books_notes` SET `note`='☆' WHERE id=0;
# UPDATE `books_notes` SET `note`='★' WHERE id=1;
# UPDATE `books_notes` SET `note`='★ ★' WHERE id=2;
# UPDATE `books_notes` SET `note`='★ ★ ★' WHERE id=3;
# UPDATE `books_notes` SET `note`='♥' WHERE id=4;
# UPDATE `books_notes` SET `note`='★ ★ ★ ★' WHERE id=5;
# UPDATE `books_notes` SET `note`='★ ★ ★ ★ ★' WHERE id=6;



INSERT INTO books_types
    (name)
VALUES ('essay'),
       ('novel'),
       ('biography'),
       ('art'),
       ('BD');

INSERT INTO books
    (slug, title, author, type_id, note_id, finished_at)
VALUES ('daniel_kahneman_thinking_fast_and_slow', 'Thinking, fast and slow', 'Daniel Kahneman', 'essay', '5',
        '2022-04-02'),
       ('bertrand_russel_autobiography_of_bertrand_russel', 'Autobiography of Bertrand Russel', 'Bertrand Russel',
        'biography', '6', '2022-07-23'),
       ('antonio_damasio_le_sentiment_meme_de_soi__corps_emotions_conscience',
        'Le Sentiment même de soi : corps, émotions, conscience', 'Antonio Damasio', 'essay', '2', '2022-06-01'),
       ('claude_ponti_les_pieds_bleus', 'Les pieds bleus', 'Claude Ponti', 'novel', '5', '2022-08-16'),
       ('delphine_de_vigan_rien_ne_soppose_a_la_nuit', 'Rien ne s’oppose à la nuit', 'Delphine de Vigan', 'novel', '6',
        '2022-08-26'),
       ('many_formules_2_revue_des_littratures_a_contraintes', 'Formules #2 (Revue des littératures à contraintes)',
        'many', 'essay', '5', '2022-08-20'),
       ('jl_borges_enquetes', 'Enquêtes', 'JL Borges', 'novel', '3', '2022-10-06'),
       ('emmanuel_carrere_yoga', 'Yoga', 'Emmanuel Carrère', 'novel', '3', '2022-04-24'),
       ('emmanuel_carrere_le_royaume', 'Le Royaume', 'Emmanuel Carrère', 'novel', '5', '2023-04-22'),
       ('emmanuel_carrère_un_roman_russe', 'Un Roman Russe', 'Emmanuel Carrère', 'novel', '0', '2022-07-01'),
       ('claude_burgelin_album_perec_pleiades', 'Album Perec Pleiades', 'Claude Burgelin', 'biography', '5',
        '2022-09-07'),
       ('montaigne_essais', 'Essais', 'Montaigne', 'biography', '5', '2022-08-15'),
       ('alain_damasio_les_furtifs', 'Les Furtifs', 'Alain Damasio', 'novel', '6', '2022-08-15'),
       ('david_graeber_la_democratie_aux_marges', 'La démocratie aux marges', 'David Graeber', 'essay', '2',
        '2022-04-30'),
       ('bunpei_yorifuji_le_dessin__les_mots', 'Le dessin & les mots', 'Bunpei Yorifuji', 'essay', '3', '2022-02-01'),
       ('alexandre_lacroix_la_naissance_dun_père', 'La Naissance d’un Père', 'Alexandre Lacroix', 'novel', '6',
        '2020-12-01'),
       ('herve_le_tellier_lanomalie', 'L’Anomalie', 'Hervé Le Tellier', 'novel', '6', '2020-10-24'),
       ('mona_chollet_reinventer_lamour', 'Réinventer l’amour', 'Mona Chollet', 'essay', '5', '2022-03-19'),
       ('delphine_de_vigan_jours_sans_faim', 'Jours sans faim', 'Delphine de Vigan', 'novel', '5', '2022-08-27'),
       ('delphine_de_vigan_les_heures_souterraines', 'Les Heures Souterraines', 'Delphine de Vigan', 'novel', '5',
        '2022-08-31'),
       ('delphine_de_vigan_les_loyautes', 'Les Loyautés', 'Delphine de Vigan', 'novel', '5', '2022-09-11'),
       ('anneisabelle_queneau_album_raymon_queneau', 'Album Raymon Queneau', 'Anne-Isabelle Queneau', 'biography', '5',
        '2022-10-24'),
       ('cg_jung_dialectique_du_moi_et_de_linconscient', 'Dialectique du Moi et de l’inconscient', 'C.G. Jung', 'essay',
        '5', '2023-02-04'),
       ('michel_dethy_introduction_a_la_psychanalyse_de_freud', 'Introduction à la psychanalyse de Freud',
        'Michel Dethy', 'essay', '3', '2022-11-27'),
       ('j_laplanche_jb_pontalis_vocabulaire_de_la_psychanalyse', 'Vocabulaire de la psychanalyse',
        'J. Laplanche, J.B. Pontalis', 'essay', '5', '2023-03-01'),
       ('amandine_dhee_mains_nues', 'À Mains Nues', 'Amandine Dhée', 'novel', '3', '2023-01-02'),
       ('andre_gide_la_symfony_pastorale', 'La Symfony Pastorale', 'André Gide', 'novel', '3', '2023-01-15'),
       ('andre_gide_les_nourritures_terrestres', 'Les Nourritures Terrestres', 'André Gide', 'novel', '2',
        '2023-02-19'),
       ('andre_gide_les_caves_du_vatican', 'Les Caves du Vatican', 'André Gide', 'novel', '5', '2022-12-19'),
       ('martin_winkler_le_chur_des_femmes', 'Le Chœur des Femmes', 'Martin Winkler', 'novel', '6', '2023-02-20'),
       ('rousseau_emile_ou_de_lducation', 'Émile ou de l’éducation', 'Rousseau', 'essay', '5', '2023-04-22'),
       ('lydie_salvayre_pas_pleurer', 'Pas Pleurer', 'Lydie Salvayre', 'novel', '5', '2023-05-03'),
       ('pierre_assouline_u00c9tat_limite', 'État Limite', 'Pierre Assouline', 'novel', '3', '2023-05-02'),
       ('olivia_gazal_je_taime_a_la_philo', 'Je t’aime à la philo', 'Olivia Gazalé', 'essay', '6', '2023-04-07'),
       ('james_joyce_dedalus', 'Dedalus', 'James Joyce', 'novel', '5', '2023-05-11'),
       ('barjavel_les_chemins_de_katmandou', 'Les Chemins de Katmandou', 'Barjavel', 'novel', '3', '2023-05-10'),
       ('simone_veil_une_vie_autobiographie', 'Une Vie (autobiographie)', 'Simone Veil', 'biography', '2',
        '2023-05-14'),
       ('francoise_dolto_tout_est_language', 'Tout est language', 'Françoise Dolto', 'essay', '3', '2023-05-13'),
       ('marion_fayolle_les_petits', 'Les Petits', 'Marion Fayolle', 'art', '4', '2022-11-09'),
       ('lorraine_les_bains_maisonologue', 'Maisonologue', 'Lorraine les Bains', 'art', '6', '2022-09-15'),
       ('racine_iphigenie', 'Iphigenie', 'Racine', 'novel', '3', '2023-05-22'),
       ('francoise_dolto_quand_les_parents_se_sparent', 'Quand les parents se séparent', 'Françoise Dolto', 'essay',
        '1', '2023-05-21'),
       ('catherine_musa_mieux_vivre_avec_un_trouble_borderline', 'Mieux vivre avec un trouble borderline',
        'Catherine Musa', 'essay', '5', '2023-05-27')
;

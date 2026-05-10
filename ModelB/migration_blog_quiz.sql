-- À exécuter une fois sur une base existante (MySQL / MariaDB).
-- Les nouvelles installations peuvent importer schema.sql à jour à la place.

ALTER TABLE blog
  ADD COLUMN quiz_token VARCHAR(64) NULL DEFAULT NULL AFTER user_id,
  ADD COLUMN quiz_json LONGTEXT NULL AFTER quiz_token;

ALTER TABLE blog
  ADD UNIQUE KEY uq_blog_quiz_token (quiz_token);

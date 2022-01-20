DROP TABLE IF EXISTS contacts;
CREATE TABLE contacts
(
  id                  int unsigned NOT NULL auto_increment,
  publicationDate     date NOT NULL,
  content         mediumtext NOT NULL,
 
  PRIMARY KEY     (id)
);

DROP TABLE IF EXISTS image_files;
CREATE TABLE image_files
(
  id                  int unsigned NOT NULL auto_increment,
  publicationDate     date NOT NULL,
  image_title         varchar(255) NOT NULL,                      # Название категории
  image_fname         varchar(255) NOT NULL,
  dir_name            varchar(255) NOT NULL,
 
  PRIMARY KEY     (id)
);
/*-------------------------------------------------------------------------------------------*/

DROP TABLE IF EXISTS categories;
CREATE TABLE categories
(
  id              int unsigned NOT NULL auto_increment,
  name            varchar(255) NOT NULL,                      # Название категории
  description     text NOT NULL,                              # Короткое описание категории
 
  PRIMARY KEY     (id)
);
 
DROP TABLE IF EXISTS articles;
CREATE TABLE articles
(
  id              int unsigned NOT NULL auto_increment,
  publicationDate date NOT NULL,                              # Дата публикации категории
  categoryId      smallint unsigned NOT NULL,                 # Идентификатор категории статьи
  title           varchar(255) NOT NULL,                      # Полное название статьи
  summary         text NOT NULL,                              # Короткое резюме
  content         mediumtext NOT NULL,                        # Содержание HTML статьи
 
  PRIMARY KEY     (id)
);
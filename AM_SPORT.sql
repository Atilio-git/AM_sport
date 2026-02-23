drop database if exists AM_SPORT;
create database AM_SPORT;
use AM_SPORT;

CREATE TABLE clients (
    id_client INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nom_client VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    addresse_client VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    PRIMARY KEY (id_client)
);

CREATE TABLE categorie (
    id_categorie INT NOT NULL AUTO_INCREMENT,
    nom_categorie VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_categorie)
);

CREATE TABLE articles (
    id_article INT NOT NULL AUTO_INCREMENT,
    nom_article VARCHAR(255) NOT NULL,
    id_categorie INT NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id_article),
    FOREIGN KEY (id_categorie)
        REFERENCES categorie (id_categorie)
);

CREATE TABLE infos_articles (
    id_info INT NOT NULL AUTO_INCREMENT,
    id_article INT NOT NULL,
    taille VARCHAR(255) NOT NULL,
    prix DECIMAL(10 , 2 ),
    PRIMARY KEY (id_info),
    FOREIGN KEY (id_article)
        REFERENCES articles (id_article)
);

CREATE TABLE commande (
    id_client INT NOT NULL,
    id_commande INT NOT NULL AUTO_INCREMENT,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en attente ', 'payé', 'livré') DEFAULT 'en attente ',
    PRIMARY KEY (id_commande),
    FOREIGN KEY (id_client)
        REFERENCES clients (id_client)
);
 
CREATE TABLE addresse (
    id_addresse INT NOT NULL AUTO_INCREMENT,
    id_client INT NOT NULL,
    addresse_livraison VARCHAR(255),
    pays VARCHAR(255) NOT NULL,
    code_postal INT NOT NULL,
    PRIMARY KEY (id_addresse),
    FOREIGN KEY (id_client)
        REFERENCES clients (id_client)
);
 
CREATE TABLE panier (
    id_panier INT NOT NULL AUTO_INCREMENT,
    id_client INT NOT NULL,
    id_article INT NOT NULL,
    nom_article VARCHAR(255) NOT NULL,
    prix_unitaire DECIMAL(10 , 2 ),
    quantite INT NOT NULL,
    prix_total DECIMAL(10 , 2 ),
    PRIMARY KEY (id_panier),
    FOREIGN KEY (id_client)
        REFERENCES clients (id_client),
    FOREIGN KEY (id_article)
        REFERENCES articles (id_article)
);
 
CREATE TABLE avis_clients (
    id_avis INT NOT NULL AUTO_INCREMENT,
    id_client INT NOT NULL,
    nom_client VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    propos TEXT,
    date_commentaire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_avis),
    FOREIGN KEY (id_client)
        REFERENCES clients (id_client)
);
  






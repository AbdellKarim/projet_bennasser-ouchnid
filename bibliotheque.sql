CREATE DATABASE bibliotheque DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE bibliotheque;

CREATE TABLE auteur (
  IdAuteur bigint(20) NOT NULL AUTO_INCREMENT,
  Nom varchar(40) NOT NULL,
  Prenom varchar(40) NULL,
  PRIMARY KEY (IdAuteur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO auteur (IdAuteur, Nom, Prenom) VALUES
(1, 'Lemaitre', 'Pierre'),
(2, 'Nothomb', 'Amélie'),
(3, 'Salvayre', 'Lydie'),
(4, 'Juillard', 'André'),
(5, 'Sente', 'Yves'),
(6, 'Cothias', 'André'),
(7, 'Hawking', 'Stephen'),
(8, 'Hergé', NULL),
(9, 'King', 'Stephen');

CREATE TABLE ouvrage (
  ISBN char(10) NOT NULL,
  Titre varchar(128) NOT NULL,
  PRIMARY KEY (ISBN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO ouvrage (ISBN, Titre) VALUES
('2226249672', 'Au revoir là-haut'),
('225314911X', 'Mercure'),
('2021116190', 'Pas pleurer'),
('2870972423', 'Blake et Mortimer - Le testament de William S'),
('2205070487', 'Les 7 vies de l\'épervier - Quinze ans après'),
('2226328718', 'Fin de ronde'),
('2290006459', 'Une brève histoire du temps : Du Big Bang aux trous noirs'),
('2203136804', 'Tintin au pays des Soviets');

CREATE TABLE auteur_ouvrage (
  IdAuteur bigint(20) NOT NULL,
  ISBN char(10) NOT NULL,
  PRIMARY KEY (IdAuteur,ISBN),
  FOREIGN KEY (IdAuteur) REFERENCES auteur (IdAuteur),
  FOREIGN KEY (ISBN) REFERENCES ouvrage (ISBN)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO auteur_ouvrage (IdAuteur, ISBN) VALUES
(1, '2226249672'),
(2, '225314911X'),
(3, '2021116190'),
(4, '2205070487'),
(4, '2870972423'),
(5, '2870972423'),
(6, '2205070487'),
(7, '2290006459'),
(8, '2203136804'),
(9, '2226328718');

CREATE TABLE utilisateur (
  IdUtilisateur bigint(20) NOT NULL AUTO_INCREMENT,
  Sexe char(1) NOT NULL,
  Nom varchar(100) NOT NULL,
  Prenom varchar(100) NOT NULL,
  Mail varchar(250) NOT NULL,
  Telephone varchar(20) NULL,
  Pseudo varchar(10) NOT NULL,
  Password varchar(255) NOT NULL,
  AboNewsletter tinyint(1) NOT NULL DEFAULT 0,
  Commentaire text NULL,
  PRIMARY KEY (IdUtilisateur),
  UNIQUE KEY Pseudo (Pseudo),
  UNIQUE KEY Mail (Mail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO utilisateur (IdUtilisateur, Sexe, Nom, Prenom, Mail, Telephone, Pseudo, `Password`, AboNewsletter, Commentaire) VALUES
(1, 'H', 'Administrateur', 'Administrateur', 'admin@univ-reims.fr', '03 26 91 30 00', 'admin', '$2y$10$nAxwKHv2rOUNc8/I1EyGNe5AZg0rli1ifImdIaSmsoA9zHItV6z2q', 0, NULL),
(2, 'H', 'Simpson', 'Bart', 'bartsim@springfield.net', NULL, 'bartsim', '$2y$10$fKop0Kf5c3Dpn7dYz6yYQeGOvx3wFE2Iqj2ckmuUjSkt/Hn6KiGru', 1, 'Ay, caramba!'),
(3, 'F', 'Stark', 'Arya', 'arya@winterfell.com', NULL, 'faceless', '$2y$10$iB4T4o3g5mCrrU.FVg9BY.SV0s4MxjlZ4m3c//0c2OiBNoSNcnXQ.', 1, 'Winter is coming !'),
(4, 'H', 'Adams', 'Douglas', 'adams@galaxy.com', NULL, 'dougadams', '$2y$10$BK9Of4ovwzD/7Ft9.DxrHeQnWyLdm23q4WaCnmqbpxYWs7mtozPEG', 0, NULL),
(5, 'H', 'Dusse', 'Jean-Claude', 'jc@clubmed.com', '+33 1 7892 4005 ', 'jcdusse', '$2y$10$IERnNDbZz0S90P7Sx4GRc.lBVBYgibtz74avhddHz4AEjd72GtcIq', 1, NULL),
(6, 'H', 'White', 'Walter', 'ww@polloshermanos.com', '1 505 842 9635', 'waltwhite', '$2y$10$5.poKsFWF1vFEbt6mjzONOir8uC3DgTtV0VUgENRj6x6o/EqdpCQu', 0, 'Stay out of my territory'),
(7, 'H', 'Mulder', 'Fox', 'fox@fbi.com', '+1 5405096995', 'mulder', '$2y$10$3YvU9mYbl7qpcOZR4tx.g.sm7jyNQoFoBZ6iIJir2pIM6XYeF.1xW', 0, NULL),
(8, 'F', 'Hofstadter', 'Penny', 'penny@pasadena.com', NULL, 'pennyh', '$2y$10$O7qGHTVYjAyMzsrl9K0V5eBbVAMZimGfupZpQ3yMtvTlRsoC4BBAS', 1, ''),
(9, 'H', 'Doe', 'John', 'johndoe@nowhere.com', NULL, 'johndoe', '$2y$10$O.y8QIxvyvLo7DmuTrGMOuP0FJjNJvMfIAj1HTVktB8jsrYV1.1r.', 1, NULL),
(10, 'F', 'Musquin', 'Marie-Ange', 'madame.musquin@sosamitie.fr', '01 02 03 04 05', 'mamusquin', '$2y$10$4MjBa1PPuEBEL9UJIgVsD.Q451EqcCpb7.vj/0FNklCZ1Q8wAlcVO', 0, 'Présidente de l\'association SOS Détresse Amitié'),
(11, 'F', 'Doe', 'Jane', 'janedoe@nowhere.com', NULL, 'janedoe', '$2y$10$Q92ZekUaJ3IQzRVhekNZyuhyOi3xTYqq5fH/tF6pz9MtmYvqaDVFW', 0, '');

CREATE TABLE exemplaire (
  Numero bigint(20) NOT NULL AUTO_INCREMENT,
  DateAchat date NOT NULL,
  Empl char(2) NOT NULL,
  ISBN char(10) NOT NULL,
  IdEmpr bigint(20) NULL,
  DateEmpr date NULL,
  PRIMARY KEY (Numero),
  FOREIGN KEY (ISBN) REFERENCES ouvrage (ISBN),
  FOREIGN KEY (IdEmpr) REFERENCES utilisateur (IdUtilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO exemplaire (Numero, DateAchat, Empl, ISBN, IdEmpr, DateEmpr) VALUES
(1022, '2016-10-03', 'G6', '225314911X', NULL, NULL),
(1029, '2016-10-14', 'F3', '2226249672', 2, '2019-10-31'),
(1030, '2016-10-14', 'F3', '2226249672', 5, '2019-11-02'),
(1032, '2016-10-14', 'G5', '225314911X', NULL, NULL),
(1045, '2016-02-02', 'F3', '2021116190', 5, '2019-11-02'),
(1067, '2016-04-22', 'G5', '225314911X', NULL, NULL),
(1092, '2017-03-09', 'G8', '2226328718', 5, '2019-11-02'),
(1095, '2017-03-21', 'G8', '2870972423', NULL, NULL),
(1096, '2017-03-21', 'G8', '2205070487', 2, '2019-11-07'),
(1097, '2017-03-09', 'G8', '2203136804', 2, '2019-11-07'),
(1098, '2018-04-06', 'G9', '2870972423', NULL, NULL),
(1099, '2017-03-09', 'G8', '225314911X', NULL, NULL),
(2000, '2019-10-15', 'G7', '2205070487', 8, '2019-11-04'),
(2001, '2019-10-15', 'G7', '2205070487', NULL, NULL),
(2002, '2019-10-30', 'G7', '2870972423', NULL, NULL),
(2003, '2019-10-30', 'G7', '2870972423', 2, '2019-11-07');


ALTER TABLE auteur AUTO_INCREMENT = 10;

ALTER TABLE utilisateur AUTO_INCREMENT = 12;

ALTER TABLE exemplaire AUTO_INCREMENT = 2004;

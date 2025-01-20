--
-- Base de données :  clicom
--

-- Si la base clicom est creee si elle n'existe pas

create database if not exists clicom default character set utf8 collate utf8_general_ci ;
use clicom ;

--
-- Structure de la table client
--

create table client (
  NCli varchar(10) not null,
  Nom  varchar(30) not null,
  Prenom  varchar(30),
  Adresse varchar(80) not null,
  CP char(5) not null,
  Ville varchar(30) not null,
  CAT char(2),
  Compte decimal(10,2) not null,
  primary key (NCli)
) engine = InnoDB ;

alter table client default charset=utf8 collate utf8_general_ci ;

--
-- Structure de la table produit
--

create table produit (
  NPro varchar(16) not null,
  Libelle varchar(200) not null,
  PrixHT decimal(10,2) not null,
  QStock int not null,
  primary key (NPro)
) engine = InnoDB ;

alter table produit default charset=utf8 collate utf8_general_ci ;

--
-- Structure de la table commande
--

create table commande (
  NCom int not null,
  NCli varchar(10) not null,
  DateCom date not null,
  primary key (NCom),
  index (NCli),
  foreign key (NCli) references client (NCli)
) engine = InnoDB ;

alter table commande default charset=utf8 collate utf8_general_ci ;

--
-- Structure de la table detail
--

create table detail (
  NCom int not null,
  NPro varchar(16) not null,
  QCom int not null,
  primary key (NCom,NPro),
  index (NCom),
  index (NPro),
  foreign key (NCom) references commande (NCom),
  foreign key (NPro) references produit (NPro)
) engine = InnoDB ;

alter table detail default charset=utf8 collate utf8_general_ci ;

use clicom;

--
-- Contenu de la table client
--

insert into client (NCli, Nom, Prenom, Adresse, CP, Ville, CAT, Compte) values
('B062', 'Girard', 'Pierre', '72 rue de la Gare', '69001', 'Lyon', 'B2', '-3200.00'),
('B112', 'Herbin', 'Henry', '23 allée Dumont', '86000', 'Poitiers', 'C1', '1250.00'),
('B332', 'Monti', 'Claudine', '112  rue Neuve', '06000', 'Nice', 'B2', '0.00'),
('B512', 'Gillet', 'Jean-Claude', '14 rue d\'El Alamein', '31000', 'Toulouse', 'B1', '-8700.00'),
('C003', 'Avron', 'Maurice', '8 chemin de Cluzel', '31000', 'Toulouse', 'B1', '-1700.00'),
('C123', 'Mercier', 'Gérard', '25 rue Lemaitre', '69003', 'Lyon', 'C1', '-2300.00'),
('C400', 'Ferard', 'Pierre', '65 rue du Touffenet', '86000', 'Poitiers', 'B2', '350.00'),
('D063', 'Mercier', 'Hélène', '201 boulevard du Nord', '31000', 'Toulouse', NULL, '-2250.00'),
('F010', 'Toussaint', NULL, '5 rue Girouard', '86000', 'Poitiers', 'C1', '0.00'),
('F011', 'Poncelet', 'Christian', '17 Clos des Erables', '31000', 'Toulouse', 'B2', '0.00'),
('F400', 'Jacob', NULL, '78 chemin du Moulin', '33000', 'Bordeaux', 'C2', '0.00'),
('K111', 'Vigneau', 'Jean', '18 rue Faraday', '59000', 'Lille', 'B1', '720.00'),
('K729', 'Noirot', 'Françoise', '40 rue Fines', '31000', 'Toulouse', NULL, '0.00'),
('L422', 'Franck', NULL, '60 rue Bellecordière', '69003', 'Lyon', 'C1', '0.00'),
('S127', 'Vilminot', 'Thierry', '3 avenue des Roses', '69001', 'Lyon', 'C1', '-4580.00'),
('S712', 'Guillaume', NULL, '14a chemin des Roses', '75013', 'Paris', 'B1', '0.00');

--
-- Contenu de la table produit
--

insert into produit (NPro, Libelle, PrixHT, QStock) values
('CLA11', 'Logitech G910 Orion Spectrum RGB', '149.96', '227'),
('CLA12', 'Logitech Wireless Solar Keyboard K750', '74.96', '124'),
('CLA13', 'Microsoft Wireless Comfort Desktop 5050', '56.54', '75'),
('CLE21', 'SanDisk Extreme Go USB 3.1- 64 Go', '41.63', '121'),
('CLE22', 'Corsair Flash Voyager USB 3.0 16 Go', '14.58', '70'),
('IMP01', 'Canon i-SENSYS LBP113W', '95.79', '117'),
('SCA06', 'Canon CanoScan LiDE 300', '58.29', '44');

--
-- Contenu de la table `commande`
--

insert into commande (NCom, NCli, DateCom) values
('30178', 'K111', '2019-12-21'),
('30179', 'C400', '2019-12-22'),
('30182', 'S127', '2019-12-23'),
('30184', 'C400', '2019-12-23'),
('30185', 'F011', '2020-01-02'),
('30186', 'C400', '2020-01-02'),
('30188', 'B512', '2020-01-03');

--
-- Contenu de la table detail
--

insert into detail (NCom, NPro, QCom) values
('30178', 'CLA13', '5'),
('30179', 'CLA11', '6'),
('30179', 'CLE22', '2'),
('30182', 'CLE22', '3'),
('30184', 'CLA13', '2'),
('30184', 'CLE21', '2'),
('30185', 'CLA13', '6'),
('30185', 'CLE22', '5'),
('30185', 'SCA06', '6'),
('30186', 'CLE21', '3'),
('30188', 'CLA13', '8'),
('30188', 'CLE21', '2'),
('30188', 'CLE22', '7'),
('30188', 'IMP01', '2');
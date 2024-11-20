--  Création d'une vue pour afficher la liste des exemplaires disponibles
--  avec Titre et Auteurs afin de simplifier l'écriture de la requête en PHP
--  dans le script emprunt.php
--  
--  ATTENTION : Ce script doit être exécuté avant d'exécuter emprunt.php

create view exemplaires_titre_auteurs_disponibles as
select concat('\"' , Titre , '\" , ', 
  ( select group_concat(a.Nom,' ',coalesce(a.Prenom,'') separator ', ') as 'Auteurs' 
    from auteur a inner join auteur_ouvrage ao
      on ao.IdAuteur = a.IdAuteur inner join ouvrage o
        on ao.ISBN = o.ISBN
    where o.ISBN = exemplaire.ISBN	
    group by o.ISBN )
  , ' (Emplacement : ' , Empl , ')') TitreAuteurs, Numero
from ouvrage inner join exemplaire on exemplaire.ISBN = ouvrage.ISBN 
where exemplaire.IdEmpr is null
order by TitreAuteurs;

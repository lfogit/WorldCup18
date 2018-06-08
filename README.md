# WorldCup18

Site web permettant de faire des pronostics sur plusieurs critères.

Plusieurs sortes de pronostics sont possibles :
- Les prévisions sur chaque match où chacun est invité à parier sur le score
- Les prévisions globales où on essaie de parier sur l'équipe gagnante à l'issue de chaque phase de la compétition et ce jusqu'au vainqueur final
- Et d'autres paris divers sur des données comme le nombre de buts marqués, le nombre de cartons, etc...

Un système de points permet de dresser un classement général et par communauté.

L'administration du site se fait par l'utilisateur "admin", qui sera créé comme un utilisateur standard.
Le login "admin" le rend administrateur.

URL du site : https://pronos.fortier.fr

Sécurisation sur NGINX

Ajouter les exclusions des fichiers ci-dessous :

   location /test/config.ini {
   deny all;
   }

   location /config.ini {
   deny all;
   }

   location /WorldCup18.sql {
   deny all;
   }


# WorldCup18

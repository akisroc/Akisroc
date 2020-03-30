# Mises à jour

## v0.3.0 - À venir

Cette version n'apporte pas de grosses nouvelles fonctionnalités.
Le projet ayant été à l'abandon plus de deux ans, il a surtout fait l'objet
de profondes refontes et de mises à jour techniques.

Akisroc est notamment passé sous PHP 7.4 et Symfony 5, ce qui a nécessité
pas mal de travail. En outre, le cadre de tests automatisés a été réparé
et amélioré, avec entre autres l'ajout d'outils d'analyse statique tels
que PHPStan et PHPInsights.

### Nouveautés
- Licence (GPL-3.0)
- Messages d'erreurs précis sur les formulaires

### Changements
- Styles
- Amélioration du menu principal

### Sécurité
- Plusieurs dépendances ont été mises à jour, corrigeant ainsi
quelques sérieuses vulnérabilités

## [v0.2.0](https://github.com/Adrien-H/Akisroc/releases/tag/v0.2.0) - 2020.03.23

### Nouveautés
- Page des notes de mises à jour
- Service de pagination
- Certificat SSL
- Nom de domaine
- Fichier de changelog

---

## [v0.1.1](https://github.com/Adrien-H/Akisroc/releases/tag/v0.1.1) - 2017.01.04

### Corrections
- Le service Git affiche désormais correctement la version actuelle
- Erreur 500 à la création d'un nouveau topic

----

## [v0.1.0](https://github.com/Adrien-H/Akisroc/releases/tag/v0.1.0) - 2017.01.03

### Nouveautés
- Protagonists
    - Affichage des protagonistes dans les posts RP
    - Sécurité autour des protagonistes (restrictions RP, etc.)
- Forum basics
    - Catégories
    - Boards
    - Topics
    - Posts
    - Formulaire de nouveau topic
    - Formulaire de nouveau post
- Système d'utilisateur
    - Gestion des sessions
    - Formulaire d'authentification
    - Formulaire d'inscription
- Configurations Symfony 4
    - Travis
    - PHPUnit
    - MariaDB Docker container
    - DataFixtures
    - Assets

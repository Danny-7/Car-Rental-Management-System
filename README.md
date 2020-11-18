<div align="center">
    <h3>PROJET PWEB</h3>
    <h4>Application web de gestion de location de véhicules</h4>
</div>
</br>

## Objectif du projet

L'objectif est de développer une application web basé sur une architecture MVC.
Nous avons choisi d'utiliser le framework [Symfony](https://symfony.com/).

## Fonctionnalités disponibles

#### Fonctionnalités principales

- Connexion / Inscription (non obligatoire)
- Visualisation des véhicules disponibles
- Réserver une location si l'utilisateur est connecté
- Selection date de début et date fin de location (non obligatoire) lors d'une location
- Réduction de 10% sur la facture si le nombre de véhicules dans la flotte est > 10
- Visualisation du panier de l'utilisateur connecté
- Possibilité d'annuler la pré-réservation (l'utilisateur n'a pas validé sa commande)

### Espace utilisateur

Chaque utilisateur à un espace utilisateur dédié

- Client (Entreprise)

  - Dashboard (visualisation des statistiques)

    - Nombre de véhicules loués
    - Nombre de véhicules non payés
    - Nombre de véhicule rendu
    - Côut total de toutes les locations effectuées

  - Visualisation des véhicules loués (payées, non payées)
  - Visualisation des facturations effectuées
    </br>

- Loueur

  - Dashboard (visualisation des statistiques)

    - Nombre de véhicule loués
    - Nombre de véhicule non payés
    - Nombre de véhicule rendu
    - Côut total de toutes les locations effectuées

  - Visualisation des véhicules loués lui appartenant (payées, non payées)
  - Modifier / Supprimer un véhicule
  - Visualiser les véhicules loués par client
  - Ajouter un véhicule
  - Visualisation des facturations effectuées par nos clients
    </br>

- Administrateur

  - Dashboard (visualisation des statistiques)

    - Nombre de véhicule loués
    - Nombre de véhicules non payés
    - Nombre de véhicule rendu
    - Côut total de toutes les locations effectuées

  - Gestion des véhicules (visualisation des véhicules, ajout d'un véhicule, suppression d'un véhicule, modification d'un véhicule)
  - Gestion des locations (visualisation des locations et des facturations)
  - Gestion des utilisateurs (visualisation des utilisateurs inscrits)

## Installation

### Pré-requis

- PHP >= 7.4
- Composer
- Symfony CLI
- Serveur MySQL

Cloner le repository

```
git clone --single-branch -b master https://github.com/Danny-7/Car-Rental-Management-System.git
```

Installer les dépendances nécessaires du projet

```
composer install
```

Configurer la connexion à la base de données dans le fichier `.env`

```
DATABASE_URL='mysql://<user><password>:@127.0.0.1:3306/<database>'
```

Remplacer :

- `<user>` par votre nom utilisateur
- `<password>` par votre mot de passe
- `<database>` par le nom de votre base de données

Créer la base de données

```
php bin/console d:d:c --if-not-exists
```

Importer le fichier `db.sql` dans votre base de données sur le serveur MySQL

Lancer l'application web

```
symfony server:start
```

Profils utilisateurs disponibles

| Nom           | Adresse email             | Mot de passe | Rôle       |
| ------------- | ------------------------- | ------------ | ---------- |
| Hubert Pichet | hubert.pichet@gmail.com   | Azerty123    | Entreprise |
| Jerome Aurore | jerome.aurore@hotmail.com | Azerty123    | Entreprise |
| Easy rent     | easyrent@easyrent.com     | Azerty123    | Loueur     |
| Sixt          | sixt@sixt.com             | Azerty123    | Loueur     |

## Crédits

**Développé par Daniel Aguiar, Hugo Mikolajek et David Benibri**

## License

MIT © [Daniel Aguiar](https://github.com/Danny-7)

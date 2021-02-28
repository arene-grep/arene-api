php# Installation et Configuration

## Prérequis 
* [PHP](https://www.php.net/downloads.php)
* [Composer](https://getcomposer.org/download/)
* [Mysql](https://dev.mysql.com/downloads/installer/)

#### Paquets natifs de PHP

Laravel utilise quelques paquets de PHP qui ne sont pas activés automatiquement quand PHP est installé, donc il faut les activer. 
Il faut aller où PHP a été installé (probablement *C:/php/*) et chercher le fichier **php.ini**.

dans le fichier il faut décommenter les lignes :
```bash 
extension=fileinfo
```
```bash 
extension=pdo_mysql
```
```bash 
extension=pdo_sqlite
```

## Installation
Il suffit d'exécuter sur la racine du projet :
```bash 
composer setup
```

> Attention. Il faut verifier que le fichier généré *.env* contient les bonnes informations pour se connecter à la base de données

## Base de données
La base de données avec ses tables et ses données peut être créé (ou recréé) avec la commande : 
```bash 
composer setupdb
```

Si vous voulez faire les étapes séparément voici les commandes individuellement.
#### Créer la base de données
```bash 
php artisan db:create
```

#### Créer les tables
```bash 
php artisan migrate
```

#### Ajouter les données
```bash 
php artisan db:seed
```

## Comment faire marcher Laravel ?
il suffit d'exécuter :

```bash 
php artisan serve
```

Laravel sera servi dans le port 8000, voici le lien pour accéder aux produits.

Les autres liens pour d'autres ressources sont dans le fichier *routes/api.php*

http://localhost:8000/api/products

## Aide
[Documentation Laravel](https://laravel.com/docs/8.x)

[Aide sur l'API Laravel](https://www.youtube.com/watch?v=mgdMeXkviy8)

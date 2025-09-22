EcoRide — Guide d’installation (README)

Ce projet est une application Symfony avec front-end compilé (npm).
Voici les étapes pour l’installer et la lancer en local ou en prod.

Prérequis

PHP 8.2+
Composer

Node.js 18+ & npm

MySQL/MariaDB (ou autre SGBD compatible)

Serveur web (Apache/Nginx) pointant vers le dossier public/

1) Récupération du projet
https://github.com/Rjaouna/EcorideAppBeta.git
cd <EcorideAppBeta>

2) Dépendances back & front
composer install
npm install

3) (Apache) Pack web Symfony

Facultatif si vous utilisez Nginx, utile pour une conf Apache rapide.

composer require symfony/apache-pack

4) Configuration de l’environnement

Copiez le .env :

cp .env .env.local


Editez .env.local (exemple MySQL) :

###> symfony/framework-bundle ###
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=ChangeMeToAStrongSecret
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format MySQL : mysql://USER:PASSWORD@HOST:PORT/DB_NAME
DATABASE_URL="mysql://root:root@127.0.0.1:3306/ecoride?serverVersion=8.0"
###< doctrine/doctrine-bundle ###

###> symfony/mailer ###
# Désactiver l’envoi d’emails (dev)
MAILER_DSN=null://null
###< symfony/mailer ###

5) Base de données

Créez la BDD (si nécessaire), puis exécutez les migrations :

php bin/console doctrine:database:create   # si la BDD n'existe pas
php bin/console doctrine:migrations:migrate

6) Build des assets

npm run dev

Prod (minifié) :

npm run build

7) Démarrage

Serveur Symfony (développement) :

symfony server:start -d
# ou
php -S 127.0.0.1:8000 -t public


Commandes utiles
# Viderle cache (prod)
php bin/console cache:clear --env=prod


------------------------------------------------------

(Docker) -- Lancer le projet avec Docker
Prérequis

Docker Desktop
	installé
	Git installé


Étapes d’installation

1) Récupération du projet
https://github.com/Rjaouna/EcorideAppBeta.git
cd <EcorideAppBeta>

2) Construire et démarrer les conteneurs

docker compose up -d --build

3) Accéder à l’application
http://localhost:8080
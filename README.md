## Cat Community
Partagez des photos de votre chat au monde entier.
### Si vous êtes un recruteur, merci d'utiliser la branche "test-recruteur" car elle contient déjà une image ce qui vous fera gagner du temps.

## Choix techniques
- PHP et MySQL car ce sont des technologies solides qui ont fait leurs preuves
- Symfony car c'est le framework PHP que je maitrise le mieux
- TailwindCSS car c'est un framework CSS que je souhaite découvrir

## Setup
### Pré-requis
- PHP >=8.0.2
- mySQL ou MariaDB
- NodeJS
- Composer
- NPM ou Yarn
### Lancer le projet
Cloner ce repository
> git clone git@github.com:gregory-lebl/CatCommunity.git

Se rendre dans le dossier CatCommunity
> cd CatCommunity

Installer les dépendances via Composer et NPM ou YARN
> composer install

> npm install

Compiler le CSS
> npm run dev

Créer un fichier .env.local et coller en modifiant ce qui est nécessaire
> DATABASE_URL="mysql://root:@127.0.0.1:3306/catcommunity?serverVersion=10.4.24-MariaDB&charset=utf8mb4"

Créer la base de données
> php bin/console doctrine:create:database

Lancer les fixtures
> php bin/console doctrine:fixtures:load

## Enjoy ! :)
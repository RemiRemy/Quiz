# Qu!zzUp

## Prerequis
- php 8
- [symfony cli](https://symfony.com/download)
- [composer](https://getcomposer.org/download/)
- [git](https://git-scm.com/downloads)
- [nodejs](https://nodejs.org/en/download/)

## Premiere récupération

Télécharger les sources depuis GitHub :
```bash
git clone https://github.com/RemiRemy/QuizzUp.git
composer install
npm install
```

Créer la base de donnée ``quizzup`` dans PhpMyAdmin et faire un ``symfony console doctrine:migrations:migrate`` et charger les fixtures avec ``symfony console doctrine:fixtures:load``

## Mettre à jour les sources

Mettre à jour ta branche git
```bash
# remplacer "dev-remiL" par le nom de ta branche
git checkout dev-remiL
git pull origin master

```

Mettre à jour les dépendances Php/node et lancer les migrations et les fixtures
```bash
composer install
npm install
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

## Lancement du serveur Web

Dans un premier terminal ``symfony serve`` pour lancer le serveur web de symfony et dans un second ``npm run dev-server`` qui permet d'automatiser le build des assets et avoir le hot reload.
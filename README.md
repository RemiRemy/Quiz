# Scrum Quiz

## Prerequis
- php 8
- [symfony cli](https://symfony.com/download)
- [composer](https://getcomposer.org/download/)
- [git](https://git-scm.com/downloads)
- [nodejs](https://nodejs.org/en/download/)

## Premiere récuperation

Télécharger les sources depuis GitHub :
```bash
git clone https://github.com/RemiRemy/QuizzUp.git
```

Télécharger / Mettre à jour les dépendances php :
```bash
composer install
```

Télécharger / Mettre à jour les dépendances node :
```bash
npm install
```

Créer la base de donnée ``quizzup`` dans PhpMyAdmin et faire un ``symfony console doctrine:migrations:migrate`` et charger les fixtures avec ``symfony console doctrine:fixtures:load``

## Mettre à jour les sources

Mettre à jour ta branche git
```bash
# remplacer "dev-remiL" par le nom de ta branch
git checkout dev-remiL
git pull origin master

```

Mettre à jour les dépendances php avec
```bash
composer install
```

Et pour finir mettre à jour les dépendances node avec
```bash
npm install
```

## Lancement du serveur Web

Dans un premier terminal ``symfony serve`` pour lancer le serveur web de symfony et dans un second ``npm run dev-server`` qui permet d'automatiser le build des assets et avoir le hot reload.
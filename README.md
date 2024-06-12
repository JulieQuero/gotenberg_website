# gotenberg_website

## Installation

### Cloner le projet
```bash
git clone git@github.com:JulieQuero/gotenberg_website.git
```
### Rentrer dans le projet
```bash
cd gotenberg_website
```

### Créer un fichier .env.local
```bash
DATABASE_URL="" # URL de la base de données avec une db nommée gotenberg_website

MICROSERVICE_URL="http://mmi21a13.sae105.ovh" # URL du microservice mise en production
```

### Installer les dépendances
```bash
composer install
```

### Lancement des migrations
```bash
php bin/console doctrine:migrations:migrate
```

### Lancement des fixtures
```bash
php bin/console doctrine:fixtures:load
```

## Commande code quality

### PHP_CodeSniffer

```bash
vendor/bin/phpcs --standard=PSR2 src/
```

### PHPStan

```bash
vendor/bin/phpstan analyze src/
```

### PHPMD

```bash
vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,naming,unusedcode
```

**Warning :** Retirer "**naming**" pour retirer l'erreur "ShortVariable - Avoid variables with short names like $id. Configured minimum length is 3."
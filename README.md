# gotenberg_website

## Installation

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
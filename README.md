# PHP-Project - Steam City

## 🚀 Guide Simple de Configuration

### ❌ Problème 1: PHP ne trouve pas la "boîte à outils" (driver PDO)

**Le problème:** PHP a besoin d'un fichier de configuration (`php.ini`) pour utiliser MySQL. Sans ce fichier, PHP ne sait pas comment parler à la base de données.

**Comment le résoudre:**

#### Étape 1: Trouver où PHP est installé
```powershell
# Tape cette commande:
Get-Command php | Select-Object Source
```
Observer quelque chose comme: `C:\Users\TonNom\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5...`

**Important:** `TonNom` = ton nom d'utilisateur Windows (chez nous c'était `Dels`)

#### Étape 2: Créer le "livre d'instructions" (php.ini)
```powershell
# Remplace C:\Users\TonNom par ton vrai chemin (trouvé à l'étape 1)
Copy-Item "C:\Users\TonNom\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5*\php.ini-development" "C:\Users\TonNom\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5*\php.ini"
```

**En français:** On copie un fichier modèle et on le renomme en `php.ini`

#### Étape 3: Dire à PHP où sont ses outils
Ouvre le fichier `php.ini` (trouvé à l'étape 1) et chercher la ligne qui commence par `;extension_dir`.

**Avant (la ligne est commentée avec `;`):**
```ini
;extension_dir = "ext"
```

**Après (enlever le `;` et ajoute ton chemin):**
```ini
extension_dir = "C:\Users\TonNom\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5*\ext"
```

#### Étape 4: Activer la "boîte à outils" MySQL
Dans le même fichier `php.ini`, chercher `;extension=pdo_mysql` et le changer en:
```ini
extension=php_pdo_mysql
```

**En français:** On enlève le `;` (qui veut dire "ne lis pas cette ligne")

#### Étape 5: Vérifier que ça marche
```powershell
php -m | Select-String "pdo|mysql"
```

Si on voit `PDO` et `pdo_mysql` s'afficher, c'est fait! ✅

---

### ❌ Problème 2: La base de données existe mais les "tiroirs" (tables) sont vides

**Le problème:** MySQL a créé la base `steam_city` mais pas les tables à l'intérieur.

**Comment le résoudre:**

#### Étape 1: Vérifier les tables
```powershell
# Remplace TonNom par ton nom d'utilisateur Windows
& "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" -u root -proot steam_city -e "SHOW TABLES;"
```

Si rien n'apparaît, il faut importer les tables.

#### Étape 2: Fixer le fichier de recette (sql/database.sql)
Le fichier a des sauts de ligne au mauvais endroit. Ouvrir `sql/database.sql` et vérifier que les 2 premières lignes ressemblent à:
```sql
CREATE DATABASE IF NOT EXISTS steam_city;
USE steam_city;
```

(Pas de saut de ligne entre CREATE et DATABASE)

#### Étape 3: Remplir les "tiroirs" (importer les tables)
```powershell
# Remplace C:\YNOV\B2\PHP\Project_php par ton chemin de projet
cmd /c '"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" -u root -proot < "C:\YNOV\B2\PHP\Project_php\sql\database.sql"'
```

#### Étape 4: Vérifier que c'est fait
```powershell
& "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe" -u root -proot steam_city -e "SHOW TABLES;"
```

Résultat visible:
- achievements
- games
- user_achievements
- user_games
- users

---

## ✅ Ça marche! Comment démarrer?

```powershell
# Lancer le serveur:
php -S localhost:8080 -t public public/index.php

# Aller sur:
# http://localhost:8080
```

---

## 👥 Ce qu'il faut partager avec les collègues (Git)

**Fichiers du PROJET modifiés** - À pousser sur le repo Git:
- ✅ `sql/database.sql` - Corrigé (sauts de ligne)
- ✅ `README.md` - Guide de configuration
- ✅ `public/pages/home.php` - Refactorisé (séparation PHP/HTML)

```powershell
git add sql/database.sql README.md public/pages/home.php
git commit -m "docs: fix database.sql formatting, add setup guide, refactor home.php"
git push
```

---

## 🔧 Ce qu'il NE FAUT PAS partager (Configuration locale)

**Fichiers LOCAUX du système** - Chaque développeur les configure seul:

❌ **`php.ini` créé** 
- C'est dans: `C:\Users\NomUtilisateur\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5*\php.ini`
- Chaque ordinateur a PHP à un endroit différent
- Chaque collègue doit créer et configurer son propre

❌ **Extensions PDO activées**
- Configuration interne de PHP sur chaque poste

❌ **Base de données MySQL importée**
- C'est une instance locale sur le serveur MySQL
- Les collègues doivent importer `database.sql` eux-mêmes

---

## 👥 Ce que les collègues doivent faire à la réception du projet

1. **Cloner le projet** (récupérer les fichiers Git)
2. **Suivre l'étape 1-4 de ce guide** pour configurer leur PHP local
3. **Importer la base de données** avec la commande de l'étape 2

Chaque personne répète les étapes de configuration → tout fonctionne chez elle! 🚀

---

## 🚀 Résumé facile

| Étape | À faire | Pourquoi |
|-------|---------|---------|
| 1 | Créer `php.ini` | PHP doit savoir où sont ses outils |
| 2 | Configurer `extension_dir` | Dire à PHP où trouver MySQL |
| 3 | Activer `php_pdo_mysql` | Que MySQL fonctionne |
| 4 | Importer `database.sql` | Créer les tables dans MySQL |
| 5 | Vérifier avec `php -m` | Vérifier que tout est chargé |

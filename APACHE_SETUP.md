# Configuration du serveur Apache

Ce document explique comment configurer Apache pour ce projet.
Pour la majorité des points comme 'activation des modules requis' est normalement déjà réalisé.

## Configuration du Virtual Host

Ajoutez cette configuration dans `httpd-vhosts.conf` (généralement dans `C:\xampp\apache\conf\extra\` pour XAMPP) :

```apache
<VirtualHost *:80>
    ServerName steam-city.local
    DocumentRoot "C:/YNOV/B2/PHP/Project_php/public"
    
    <Directory "C:/YNOV/B2/PHP/Project_php/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>
    
    ErrorLog "logs/steam-city-error.log"
    CustomLog "logs/steam-city-access.log" common
</VirtualHost>
```

## Activation des modules requis

Dans `httpd.conf`, assurez-vous que ces lignes sont décommentées :

```apache
LoadModule rewrite_module modules/mod_rewrite.so
Include conf/extra/httpd-vhosts.conf
```

## Configuration du fichier hosts

Sur Windows, éditez `C:\Windows\System32\drivers\etc\hosts` (en administrateur) :

```
127.0.0.1    steam-city.local
```

## Fichier .htaccess

Le fichier `.htaccess` dans le dossier `public/` gère le routing :
- Les fichiers statiques (CSS, JS, images) sont servis directement
- Les autres requêtes sont redirigées vers `index.php`

## Redémarrage d'Apache

Après modification de la configuration, redémarrez Apache via XAMPP Control Panel ou en ligne de commande.

## Accès au site

Une fois configuré et le serveur apache lancé avec XAMPP, 
accédez au site via : `http://steam-city.local`

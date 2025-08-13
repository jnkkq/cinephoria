#!/bin/bash

# Démarrer MySQL
service mysql start

# Créer la base de données et l'utilisateur si nécessaire
mysql -e "CREATE DATABASE IF NOT EXISTS cinephoria;"
mysql -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '';"
mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;"
mysql -e "FLUSH PRIVILEGES;"

# Importer la base de données si elle existe
if [ -f /var/www/html/sql/cinephoria.sql ]; then
    mysql cinephoria < /var/www/html/sql/cinephoria.sql
fi

# Démarrer Apache en premier plan
apachectl -D FOREGROUND

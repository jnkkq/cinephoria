FROM ubuntu:22.04

# Éviter les questions interactives pendant l'installation
ENV DEBIAN_FRONTEND=noninteractive

# Mettre à jour et installer les dépendances
RUN apt-get update && apt-get install -y \
    apache2 \
    php \
    php-mysql \
    php-curl \
    php-gd \
    php-mbstring \
    php-xml \
    php-zip \
    mysql-server \
    curl \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Configurer Apache
RUN a2enmod rewrite
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copier l'application
COPY . /var/www/html/

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html

# Script de démarrage
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Ports
EXPOSE 80

# Démarrer les services
CMD ["/start.sh"]

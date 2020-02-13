# Utiliser l'image de base Ubuntu 24.04 (ou la version que vous préférez)
FROM ubuntu:24.04

# Mettre à jour les paquets et installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    zip \
    unzip \
    git \
    vim \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    apache2 \
    libapache2-mod-fcgid \
    php8.3 \
    php8.3-cli \
    php8.3-fpm \
    php8.3-mysql \
    php8.3-pgsql \
    php8.3-sqlite3 \
    php8.3-redis \
    php8.3-curl \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-zip \
    php8.3-gd \
    php8.3-intl \
    php8.3-bcmath

# Installer Xdebug
RUN apt-get install -y php8.3-xdebug

# Configurer Xdebug
RUN echo "zend_extension=$(find /usr/lib/php/ -name xdebug.so)" >> /etc/php/8.3/cli/php.ini \
    && echo "zend_extension=$(find /usr/lib/php/ -name xdebug.so)" >> /etc/php/8.3/fpm/php.ini \
    && echo "xdebug.mode=debug" >> /etc/php/8.3/cli/php.ini \
    && echo "xdebug.mode=debug" >> /etc/php/8.3/fpm/php.ini \
    && echo "xdebug.start_with_request=yes" >> /etc/php/8.3/cli/php.ini \
    && echo "xdebug.start_with_request=yes" >> /etc/php/8.3/fpm/php.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /etc/php/8.3/cli/php.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /etc/php/8.3/fpm/php.ini \
    && echo "xdebug.client_port=9003" >> /etc/php/8.3/cli/php.ini \
    && echo "xdebug.client_port=9003" >> /etc/php/8.3/fpm/php.ini

# Configurer PHP-FPM pour l'environnement web
RUN sed -i 's/;daemonize = yes/daemonize = no/' /etc/php/8.3/fpm/php-fpm.conf

# Configurer Apache pour utiliser PHP-FPM
RUN a2enmod proxy_fcgi setenvif && a2enconf php8.3-fpm

# Copier le fichier de configuration vhost
COPY project.conf /etc/apache2/sites-available/project.conf

# Activer les modules Apache nécessaires et désactiver le site par défaut
RUN a2enmod rewrite
RUN a2dissite 000-default
RUN a2ensite project

# Installer Composer (Gestionnaire de dépendances pour PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Créer un utilisateur non root
RUN useradd -m dockeruser

# Définir l'utilisateur non root pour les prochaines instructions
USER dockeruser

# Utiliser une variable d'environnement pour l'URL du dépôt GitHub
ARG GITHUB_REPO_URL

# Cloner le projet GitHub et installer les dépendances
RUN git clone ${GITHUB_REPO_URL} /home/dockeruser/app \
    && cd /home/dockeruser/app \
    && composer install

# Revenir à l'utilisateur root pour les étapes finales
USER root

# Déplacer le projet vers le répertoire web d'Apache
RUN mv /home/dockeruser/app /var/www/html \
    && chown -R www-data:www-data /var/www/html

# Exposer le port 80 pour Apache et 9003 pour Xdebug
EXPOSE 80 9003

# Commande par défaut pour démarrer Apache en premier plan
CMD ["apache2ctl", "-D", "FOREGROUND"]

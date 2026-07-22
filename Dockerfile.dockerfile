FROM php:8.2-apache

# 1. Installe les extensions PHP nécessaires (ex: PDO MySQL pour la base de données)
RUN docker-php-ext-install pdo pdo_mysql

# 2. Active le module de réécriture d'Apache (très utile pour le MVC et les jolies URLs)
RUN a2enmod rewrite

# 3. Copie notre configuration Apache personnalisée dans le conteneur
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# 4. Définit le dossier de travail dans le conteneur
WORKDIR /var/www/html

# 5. On donne les bons droits aux fichiers (optionnel mais recommandé)
RUN chown -R www-data:www-data /var/www/html
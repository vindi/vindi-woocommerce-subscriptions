FROM php:7.2-apache
MAINTAINER Vindi<comunidade@vindi.com.br>

# Variables
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV WORDPRESS_PATH /var/www/html
ENV WORDPRESS_VERSION wordpress-5.3.2-pt_BR
EXPOSE 80

# Install config dependecies
RUN apt-get update && apt-get install -y apt-utils libfreetype6-dev libjpeg62-turbo-dev libxml2-dev wget zip unzip git && apt-get autoremove -y && apt-get clean
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install gd zip mbstring dom soap mysqli pdo_mysql
RUN echo "127.0.0.1  vindi.wordpress" >> /etc/hosts

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install Wordpress CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

# Install Codeception
RUN curl -O https://codeception.com/codecept.phar && chmod +x codecept.phar && mv codecept.phar /usr/local/bin/codecept

# Install Wordpress
RUN wget https://br.wordpress.org/$WORDPRESS_VERSION.tar.gz -P $WORDPRESS_PATH
RUN tar -xvf $WORDPRESS_PATH/$WORDPRESS_VERSION.tar.gz --strip 1 && rm $WORDPRESS_PATH/$WORDPRESS_VERSION.tar.gz
RUN chown -R www-data:www-data $WORDPRESS_PATH

# Set test database config
COPY ./test_db_dump.sql $WORDPRESS_PATH/wp-content/plugins/vindi-woocommerce-subscriptions/tests/_data/

CMD ["apache2-foreground"]

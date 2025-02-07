# Usamos la imagen base Debian para ARMv7
FROM arm32v7/debian:latest

# Mantenedor
MAINTAINER ex0nuss

# Variables de entorno para evitar preguntas interactivas durante la instalación
ENV DEBIAN_FRONTEND=noninteractive

# Instalación de dependencias necesarias
RUN apt-get update && \
    apt-get install -y \
    apache2 \
    php8.2 \
    libapache2-mod-php8.2 \
    php8.2-curl \
    iputils-ping \
    wakeonlan \
    git \
    openssl \
    nano \
    && apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Habilitar módulos de Apache
RUN a2enmod proxy_fcgi setenvif ssl headers

# Clonar el repositorio de la aplicación
RUN git clone https://github.com/sciguy14/Remote-Wake-Sleep-On-LAN-Server.git /root/Remote-Wake-Sleep-On-LAN-Server

# Crear directorio para SSL
RUN mkdir -p /etc/apache2/ssl

# Generar certificados SSL autofirmados
RUN openssl genrsa -out /etc/apache2/ssl/wol.key 2048 && \
    openssl req -new -key /etc/apache2/ssl/wol.key -out /etc/apache2/ssl/wol.csr -subj "/CN=localhost" && \
    openssl x509 -req -days 10 -in /etc/apache2/ssl/wol.csr -signkey /etc/apache2/ssl/wol.key -out /etc/apache2/ssl/wol.crt

# Copiar archivos de configuración de Apache desde el repositorio
RUN cp -f /root/Remote-Wake-Sleep-On-LAN-Server/apache2_configs/ssl.conf /etc/apache2/mods-available/ssl.conf && \
    cp -f /root/Remote-Wake-Sleep-On-LAN-Server/apache2_configs/000-default_self-https.conf /etc/apache2/sites-available/000-default.conf && \
    a2ensite 000-default.conf

# Configurar PHP (PHP 8.2)
RUN sed -i.bak "s/expose_php = On/expose_php = Off/g" /etc/php/8.2/apache2/php.ini && \
    sed -i.bak "s/error_reporting = .*/error_reporting = E_ERROR/g" /etc/php/8.2/apache2/php.ini

# Configurar seguridad de Apache
RUN sed -i.bak "s/ServerSignature On/ServerSignature Off/g" /etc/apache2/conf-available/security.conf && \
    sed -i.bak "s/ServerTokens OS/ServerTokens Prod/g" /etc/apache2/conf-available/security.conf

# Mover la aplicación al directorio de Apache
RUN mkdir -p /var/www/html && \
    cp -r /root/Remote-Wake-Sleep-On-LAN-Server/www/html/* /var/www/html/ && \
    cp /root/Remote-Wake-Sleep-On-LAN-Server/www/html/.htaccess /var/www/html/ && \
    mv /var/www/html/config_sample.php /var/www/html/config.php && \
    rm -rf /root/Remote-Wake-Sleep-On-LAN-Server

# Cambiar permisos para ping (necesario para Wake-on-LAN)
RUN chmod u+s /usr/bin/ping

# Limpiar archivos innecesarios
RUN rm -f /var/www/html/index.html

# Exponer el puerto 80 (HTTP) y 443 (HTTPS)
EXPOSE 80 443

# Comando para iniciar Apache en primer plano
CMD ["apachectl", "-D", "FOREGROUND"]

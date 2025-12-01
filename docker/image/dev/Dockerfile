# syntax=docker/dockerfile:1
FROM registry.internal.workleto.com/docker/php:8.4-dev-apache

RUN export DEBIAN_FRONTEND="noninteractive"

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && apt-get install -y nodejs

# Configure Apache2
COPY docker/apache/dev/leto-app.conf /etc/apache2/sites-available/leto-app.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    a2enmod remoteip && \
    a2enmod headers && \
    a2enmod log_debug && \
    a2dissite 000-default && \
    a2ensite leto-app

# Copy the configuration files
COPY docker/init.sh /init.sh
COPY docker/healthcheck.sh /healthcheck.sh
COPY docker/php/*.ini /usr/local/etc/php/conf.d/
COPY docker/php/dev/*.ini /usr/local/etc/php/conf.d/

#
# Create the working environment of the application
#
RUN mkdir /app
WORKDIR /app

# Create a custom user with UID 1000 and GID 1000
RUN groupadd -g 1000 workleto && \
    useradd -m -u 1000 -g workleto workleto
USER 1000

# Set healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD /bin/bash /healthcheck.sh

# Expose the app on port 80
EXPOSE 80

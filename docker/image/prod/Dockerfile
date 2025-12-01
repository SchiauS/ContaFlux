# syntax=docker/dockerfile:1
FROM registry.internal.workleto.com/docker/php:8.4-prod-apache

RUN export DEBIAN_FRONTEND="noninteractive"

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && apt-get install -y nodejs

# Configure Apache2
COPY docker/apache/prod/leto-app.conf /etc/apache2/sites-available/leto-app.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    a2enmod remoteip && \
    a2enmod headers && \
    a2dissite 000-default && \
    a2ensite leto-app

# Copy the configuration files
COPY docker/init.sh /init.sh
COPY docker/healthcheck.sh /healthcheck.sh
COPY docker/php/*.ini /usr/local/etc/php/conf.d/
COPY docker/php/prod/*.ini /usr/local/etc/php/conf.d/

#
# Create the working environment of the application
#
RUN mkdir /app
WORKDIR /app

# Create a custom user with UID 1000 and GID 1000
RUN groupadd -g 1000 workleto && \
    useradd -m -u 1000 -g workleto workleto
RUN chown -R workleto:workleto /app
USER 1000

# Copy the source code
COPY --chown=workleto:workleto . .

# Install the dependencies
RUN composer install --no-ansi --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader
RUN npm ci && npm run build

# Expose the application on port 80
EXPOSE 80

# Set healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
  CMD /bin/bash /healthcheck.sh

# Set initialization script
ENTRYPOINT ["/bin/bash", "/init.sh"]

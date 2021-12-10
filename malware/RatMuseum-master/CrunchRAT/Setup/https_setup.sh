#!/bin/bash
# Run this as root

if [ "$(id -u)" != "0" ];
then
  echo "====================================================";
  echo "Error - This script must be run with root privileges";
  echo "====================================================";
else
  # Enables SSL
  a2enmod ssl

  # Restarts Apache2 service
  service apache2 restart

  # Creates certificate directory
  mkdir /etc/apache2/ssl

  # Generates self-signed certificate
  openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/CrunchRAT.key -out /etc/apache2/ssl/CrunchRAT.crt

  # Copy Crunch RAT Apache configuration file to /etc/apache2/sites-available
  cp CrunchRAT.conf /etc/apache2/sites-available

  # Enable Crunch RAT Apache configuration
  a2ensite CrunchRAT.conf

  # Uses sed to comment out "Listen 80" line in /etc/apache2/ports.conf
  sed -i -e 's/Listen 80/#Listen 80/g' /etc/apache2/ports.conf

  # Prevents "Server" header information disclosure
  echo "ServerTokens Prod" >> /etc/apache2/apache2.conf
  echo "ServerSignature Off" >> /etc/apache2/apache2.conf

  # Uses sed to disallow directory indexing
  sed -i -e 's/Options Indexes FollowSymLinks/Options -Indexes/g' /etc/apache2/apache2.conf

  # Restart Apache2 service
  service apache2 restart
fi

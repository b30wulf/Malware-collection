# Must be run as root

if [ "$(id -u)" != "0" ];
then
  echo "====================================================";
  echo "Error - This script must be run with root privileges";
  echo "====================================================";
else
  # Updates
  apt-get update

  # Upgrades
  apt-get -y upgrade

  # Installs Apache
  apt-get install -y apache2

  # Installs MySQL
  apt-get install -y mysql-server

  # Installs PHP
  apt-get install -y php libapache2-mod-php php-mcrypt php-mysql

  # Removes default index.html file
  rm -rf /var/www/html/index.html

  # Restarts Apache service
  service apache2 restart

  # Creates uploads directory
  mkdir /var/www/html/uploads

  # Changes permissions so we can write to the uploads directory
  chown www-data:www-data /var/www/html/uploads
  
  # Uses sed to increase the maximum POST size to 500 megs in php.ini
  sed -i -e 's/post_max_size = 8M/post_max_size = 500M/g' /etc/php/7.0/apache2/php.ini
  sed -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 500M/g' /etc/php/7.0/apache2/php.ini
fi

#!/bin/bash

echo "============================================================";
echo "Starting database setup...";
echo "============================================================";
echo "Please enter MySQL root password: ";
read rootPassword; # Gets MySQL root password from the user

# Creates RAT database and associated tables
statement="CREATE DATABASE RAT; ";
statement="$statement USE RAT; ";
statement="$statement CREATE TABLE users (username VARCHAR(50), password VARCHAR(50)); ";
statement="$statement INSERT INTO users (username, password) values ('admin', 'changeme'); ";
statement="$statement CREATE TABLE hosts (hostname VARCHAR(100), date VARCHAR(100), os VARCHAR(200), architecture VARCHAR(3)); ";
statement="$statement CREATE TABLE tasks (id INT(16) AUTO_INCREMENT, user VARCHAR(50), hostname VARCHAR(100), action VARCHAR(20), secondary TEXT(65535), PRIMARY KEY (id)); ";
statement="$statement CREATE TABLE output (id INT(16) AUTO_INCREMENT, user VARCHAR(50), hostname VARCHAR(100), action VARCHAR(20), secondary TEXT(65535), stdout TEXT(65535), stderr TEXT(65535), status VARCHAR(1), PRIMARY KEY (id)); ";
statement="$statement CREATE TABLE chat (user VARCHAR(50), date VARCHAR(100), message TEXT(65535)); ";

mysql -u"root" -p"$rootPassword" -e"$statement"

# If any errors during database setup
if [ "$?" != "0" ]; then
  echo "============================================================";
  echo "Error during creation";
  echo "Please make sure you entered the correct root MySQL password";
  echo "============================================================";
# Else no errors and we need to inform the user that the database creation is complete
else
  echo "============================================================";
  echo "Database setup complete";
  echo "============================================================";
fi

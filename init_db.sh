#!/bin/bash

echo "MySQL is up! Restoring database..."
cat /docker-entrypoint-initdb.d/backup.sql | mysql -u root --password=root_secret symfony
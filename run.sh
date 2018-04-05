#!/bin/bash

cd Scripts/Bash/
./MigrateDatabase.sh up
./MigrateDatabase.sh seed

cd /var/www/html

chmod -R -f 777 /var/www/html
chown -R -f www-data /var/www/html
chgrp -R -f www-data /var/www/html

/usr/sbin/apache2ctl -D FOREGROUND
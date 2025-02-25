#!/bin/bash
service filebeat start
supervisord -c /etc/supervisor.conf &
#cd /var/www/html/
#nohup php /var/www/html/artisan queue:work --queue=TIO_FILE_IMPORT &
#nohup php /var/www/html/artisan queue:work --queue=IMPORT_TIO_PTS &
#nohup php /var/www/html/artisan queue:work --queue=TIO_FILE_DELETE &
#nohup php /var/www/html/artisan queue:work --queue=DELETE_TIO_PTS &
#nohup php /var/www/html/artisan queue:work --queue=TIO_FILE_EXPORT &

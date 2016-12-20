#!/bin/sh

php /var/www/html/rpc_lock_index.php '#'
php /var/www/html/rpc_lock_store.php '#'
php /var/www/html/rpc_lock_delete.php '#'
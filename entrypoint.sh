#!/bin/sh
# https://stackoverflow.com/a/38732187/1935918

###
# In case container startup ask only for `php-fpm` or starts with `-` to assign options to `php-fpm` default cmd
# the arguments are ignore in the migrations command because there's no migration arguments
###
if [ "$EXEC_MIGRATIONS" = "true" ] && [ $1 = 'php-fpm' ] || [ "${1#-}" != "$1" ]; then
	bin/console --if-not-exists doctrine:database:create || exit 1
	bin/console --no-interaction doctrine:migrations:migrate || exit 1
fi;

### To use as option for CMD `php-fpm` -> "docker run --rm invoicer-php -ini example.ini"
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

exec "$@"

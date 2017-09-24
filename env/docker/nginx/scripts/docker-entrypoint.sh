#!/usr/bin/env bash

if [ "$NGINX_FASTCGI_PASS" ]; then
	envsubst '$NGINX_PORT:$NGINX_ROOT:$NGINX_ACCESS_LOG:$NGINX_ERROR_LOG:$NGINX_FASTCGI_PASS' \
		< /etc/nginx/conf.d/default.template \
		> /etc/nginx/conf.d/default.conf
else
	cat >&2 <<-'EOERROR'
		****************************************************
		ERROR: NGINX_FASTCGI_PASS environment variable not
		       set.
		****************************************************
	EOERROR

	exit 1
fi

exec "$@"
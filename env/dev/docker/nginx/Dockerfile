FROM nginx

ENV NGINX_PORT=80 \
	NGINX_ROOT=/usr/share/nginx/service \
	NGINX_ACCESS_LOG=/var/log/nginx/access.log \
	NGINX_ERROR_LOG=/var/log/nginx/error.log \
	NGINX_FASTCGI_PASS=php-fpm:9000

ADD config/default.template /etc/nginx/conf.d/default.template

ADD scripts/docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["nginx", "-g", "daemon off;"]
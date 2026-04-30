FROM nginx:1.27-alpine

COPY .docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY backend/public /var/www/html/public

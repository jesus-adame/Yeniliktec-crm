FROM nginx:latest

# Configura Nginx
COPY .docker/nginx/conf/nginx.conf /etc/nginx/nginx.conf

# Asegurarse de que los permisos sean adecuados
RUN chmod 644 /etc/nginx/nginx.conf

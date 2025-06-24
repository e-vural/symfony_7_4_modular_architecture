SERVER_NAME=kno.localhost \
HTTP_PORT=80 \
HTTPS_PORT=443 \
MYSQL_USER=kno_db_user \
MYSQL_PASSWORD=kno123 \
MYSQL_DATABASE=kno_v2 \
MYSQL_VERSION=10 \
MYSQL_CHARSET=utf8 \
docker compose up -d --build

echo 'run click https://kno.localhost'

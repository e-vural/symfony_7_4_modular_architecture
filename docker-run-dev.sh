SERVER_NAME='http://kno.localhost, https://kno.localhost' \
HTTP_PORT=80 \
HTTPS_PORT=443 \
MYSQL_USER=kno_db_user \
MYSQL_PASSWORD=kno123 \
MYSQL_DATABASE=kno_v2 \
MYSQL_VERSION=10 \
MYSQL_CHARSET=utf8 \
APP_SECRET=deneme123 \
CADDY_MERCURE_JWT_SECRET=ChangeThisMercureHubJWTSecretKey \
CADDY_GLOBAL_OPTIONS='auto_https disable_redirects' \
docker compose up -d --build


echo 'run click https://kno.localhost,  http://kno.localhost'
#docker compose up -d --build
#docker compose -f compose.yaml -f compose.prod.yaml up --wait



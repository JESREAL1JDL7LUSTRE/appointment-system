#!/bin/bash
set -e

# -----------------------------------------------------------------------------
# docker-entrypoint.sh
# Parses NeonDB's DATABASE_URL into individual components for CI4's Postgre driver,
# writes .env, runs migrations, then starts Apache.
# -----------------------------------------------------------------------------

echo "==> Parsing DATABASE_URL into components..."

# DATABASE_URL format: postgresql://user:password@host:port/dbname?sslmode=require
# Strip the scheme prefix (handle both postgresql:// and postgres://)
TMPURL="${DATABASE_URL#postgresql://}"
TMPURL="${TMPURL#postgres://}"

# Split user:pass from host/db
DB_USERINFO="${TMPURL%%@*}"
DB_HOSTINFO="${TMPURL#*@}"

# Extract user and password
DB_USER="${DB_USERINFO%%:*}"
DB_PASS="${DB_USERINFO#*:}"

# Extract host:port and dbname
DB_HOSTPORT="${DB_HOSTINFO%%/*}"
DB_REST="${DB_HOSTINFO#*/}"
DB_NAME="${DB_REST%%\?*}"   # strip ?sslmode=require etc.

# Extract host and port (default 5432 if not present)
if echo "$DB_HOSTPORT" | grep -q ':'; then
    DB_HOST="${DB_HOSTPORT%%:*}"
    DB_PORT="${DB_HOSTPORT##*:}"
else
    DB_HOST="$DB_HOSTPORT"
    DB_PORT="5432"
fi

echo "  Host: ${DB_HOST}"
echo "  Port: ${DB_PORT}"
echo "  DB:   ${DB_NAME}"
echo "  User: ${DB_USER}"

echo "==> Writing .env file..."

cat > /var/www/html/.env <<EOF
CI_ENVIRONMENT = production

app.baseURL = '${APP_BASE_URL}'

database.default.hostname = ${DB_HOST}
database.default.database = ${DB_NAME}
database.default.username = ${DB_USER}
database.default.password = ${DB_PASS}
database.default.DBDriver = Postgre
database.default.port     = ${DB_PORT}
database.default.charset  = utf8
database.default.DBCollat =

CLOUDINARY_URL='${CLOUDINARY_URL}'
EOF

echo "==> Running database migrations..."
php /var/www/html/spark migrate --all

echo "==> Starting Apache..."
exec apache2-foreground

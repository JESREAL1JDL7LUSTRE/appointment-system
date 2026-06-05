#!/bin/bash
set -e

# -----------------------------------------------------------------------------
# docker-entrypoint.sh
# Writes a production .env from Render environment variables (NeonDB/PostgreSQL),
# runs CI4 migrations, then starts Apache.
# -----------------------------------------------------------------------------

echo "==> Writing .env file from environment variables..."

cat > /var/www/html/.env <<EOF
CI_ENVIRONMENT = production

app.baseURL = '${APP_BASE_URL}'

database.default.DSN      = ${DATABASE_URL}
database.default.DBDriver = Postgre

CLOUDINARY_URL='${CLOUDINARY_URL}'
EOF

echo "==> Running database migrations..."
php /var/www/html/spark migrate --all

echo "==> Starting Apache..."
exec apache2-foreground

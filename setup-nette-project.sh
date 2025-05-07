#!/bin/bash

# === 1. INPUT UTENTE ===

read -p "Nome progetto (APPNAME): " APPNAME
while [ -z "$APPNAME" ]; do
    echo "APPNAME è obbligatorio."
    read -p "Nome progetto (APPNAME): " APPNAME
done

read -p "Database DSN (DB_DNS): " DB_DNS
read -p "Database User (DB_USER): " DB_USER
read -s -p "Database Password (DB_PASSWORD): " DB_PASSWORD
echo
read -p "Email (MAIL) [opzionale]: " MAIL

# === 2. INFO SISTEMA ===

PROJECT_PATH=$(pwd)
CONFIG_PATH="$PROJECT_PATH/app/config"

# === 3. CREA local.neon ===

mkdir -p "$CONFIG_PATH"

cat > "$CONFIG_PATH/local.neon" <<EOF
parameters:
	adminEmail: 
		-${MAIL}

	mail:
		smtp: TRUE
		host: "ssl0.ovh.net"
		port: 465
		username: "${MAIL}"
		password: ""
		timeout: 60
		secure: "ssl"
		clientHost: ""
		persistent: FALSE

constants: 
	APPURL: 'https://${APPNAME}.local/'
	APPNAME: '${APPNAME}'
	APPEMAIL: '${MAIL}'
	USERSTABLE: 'utenti'
	BANTABLE: 'banned'
	RECOVERYTABLE: 'recovery'
	UPLOADSDIR: 'uploads'
	DEBUG: TRUE
	BINPATH: '${PROJECT_PATH}/bin/'
	RUOLI:
		admin: 'admin'
		user: 'user'
		guest: 'guest'

database:
	remote:
		dsn: '${DB_DNS}'
		user: '${DB_USER}' 
		password: '${DB_PASSWORD}' 
EOF

# === 4. CREA VirtualHost Apache ===

VHOST_FILE="/etc/apache2/sites-available/${APPNAME}-ssl.conf"
sudo tee "$VHOST_FILE" > /dev/null <<EOF
<IfModule mod_ssl.c>
    <VirtualHost ${APPNAME}.local:443>
        ServerAdmin webmaster@localhost
        DocumentRoot ${PROJECT_PATH}/www

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        SSLEngine on
        SSLCertificateFile      /etc/ssl/certs/ssl-cert-snakeoil.pem
        SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key

        <FilesMatch "\\.(cgi|shtml|phtml|php)\$">
            SSLOptions +StdEnvVars
        </FilesMatch>
        <Directory /usr/lib/cgi-bin>
            SSLOptions +StdEnvVars
        </Directory>

        <Directory ${PROJECT_PATH}/www>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
</IfModule>
EOF

# === 5. MODIFICA /etc/hosts CON INDENTAZIONE ===

HOST_ENTRY="127.0.0.1       ${APPNAME}.local"
if ! grep -q "${APPNAME}.local" /etc/hosts; then
    sudo sed -i "/^127.0.0.1[[:space:]]\+localhost/a ${HOST_ENTRY}" /etc/hosts
    echo "Aggiunto a /etc/hosts:"
    echo "  $HOST_ENTRY"
else
    echo "/etc/hosts già contiene ${APPNAME}.local"
fi

# === 6. PERMESSI ===

sudo chmod -R 777 "${PROJECT_PATH}"

# === 7. ABILITA SITO E RIAVVIA ===

sudo a2ensite "${APPNAME}-ssl.conf"
sudo systemctl restart apache2

echo "✅ Configurazione completata per il progetto $APPNAME"

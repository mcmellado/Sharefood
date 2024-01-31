# Sharefood

![sharefood2](https://github.com/mcmellado/sharefood/assets/113931748/8365c455-48f3-4e28-ac4a-148972599175)



Sharefood es una plataforma web que conecta a los usuarios con restaurantes, permitiéndoles explorar, reservar y evaluar diferentes establecimientos. Además, ofrece funciones sociales como la interacción con otros usuarios y la gestión de perfiles.

## Funcionalidades Principales

- **Exploración de Restaurantes:** Los usuarios pueden explorar un catálogo de restaurantes, ver detalles y realizar búsquedas según sus preferencias culinarias.
  
- **Reservas:** Los usuarios logueados pueden reservar mesas en los restaurantes de su elección y cancelar reservas si es necesario.

- **Interacción Social:** Los usuarios pueden comentar y calificar restaurantes, compartir experiencias en redes sociales y gestionar sus perfiles.

- **Gestión de Conexiones:** Los usuarios pueden agregar, eliminar y bloquear contactos en la plataforma, facilitando la interacción social.

- **Funcionalidades Administrativas:** Los administradores tienen la capacidad de validar o bloquear usuarios, crear, modificar o eliminar restaurantes, y gestionar las reservas realizadas en los establecimientos.

## Objetivos Generales

Facilitar a los usuarios la exploración, reserva y evaluación de restaurantes, así como la interacción con otros usuarios y la administración de sus perfiles, proporcionando una experiencia de descubrimiento gastronómico y social en la plataforma web.

## Tecnologías Utilizadas

- **Laravel Framework:** La aplicación se construye utilizando el framework Laravel para aprovechar sus características y funcionalidades.

## Elemento de Innovación

- **Compartir Experiencias en Redes Sociales:** Los usuarios tienen la capacidad de compartir sus experiencias gastronómicas directamente en redes sociales.

# Despliegue de la Aplicación Sharefood

## 1. Instalación de PHP

```
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt install php8.1 php8.1-amqp php8.1-cgi php8.1-cli php8.1-common php8.1-curl php8.1-fpm php8.1-gd php8.1-igbinary php8.1-intl php8.1-mbstring php8.1-opcache php8.1-pgsql php8.1-readline php8.1-redis php8.1-sqlite3 php8.1-xml php8.1-zip
sudo update-alternatives --config php
```

Abre el archivo php.ini:

```
sudo nano /etc/php/8.1/cli/php.ini
```

Cambia las siguientes líneas:

```
error_reporting = E_ALL
display_errors = On
display_startup_errors = On
date.timezone = 'UTC'
```
Sin el ; de principio en dat.timezone

##2. Instalación de Composer

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
```

## 3. Instalación de GitHub CLI

```
type -p curl >/dev/null || (sudo apt update && sudo apt install curl -y)
curl -fsSL https://cli.github.com/packages/githubcli-archive-keyring.gpg | sudo dd of=/usr/share/keyrings/githubcli-archive-keyring.gpg \
&& sudo chmod go+r /usr/share/keyrings/githubcli-archive-keyring.gpg \
&& echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/githubcli-archive-keyring.gpg] https://cli.github.com/packages stable main" | sudo tee /etc/apt/sources.list.d/github-cli.list > /dev/null \
&& sudo apt update \
&& sudo apt install gh -y
```


## 4. Instalación de PostgreSQL

```
sudo apt-get update
sudo apt install postgresql
sudo service postgresql start
```

## 5. Instalación de la Aplicación Sharefood

```
git clone https://github.com/mcmellado/sharefood
cd sharefood
composer install --ignore-platform-reqs
composer dump-autoload --ignore-platform-reqs
npm install
npm audit fix
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")" [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
source ~/.bashrc
nvm install 18.1.0
nvm use 18.1.0
npm install
npm run dev
```

## 6. Configuración del Archivo .env y la Base de Datos

```
sudo nano .env
```
Cambia las siguientes líneas en el archivo .env:

```
DB_CONNECTION=pgsql 
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=sharefoodmail@gmail.com
MAIL_PASSWORD=wugxtpvcssohwopr
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=sharefoodmail@gmail.com
MAIL_FROM_NAME="Sharefood"
```
Al final del archivo, pega las siguientes líneas:

```
FACEBOOK_CLIENT_ID=your-client-id
FACEBOOK_CLIENT_SECRET=your-client-secret
FACEBOOK_REDIRECT_URI=http://your-app-url/callback
STRIPE_KEY=pk_test_51OZMHuLsmmfQt4qgVrxTP8rCMjUC7RJrbfn8gpTGFPEiKk3DvEiYP2P1d3zi9UvKHqk3RSOZUqbEj58zljDil8Zy00cF1bt58j
STRIPE_SECRET_KEY=sk_test_51OZMHuLsmmfQt4qgK5UNEnA5IN8q8A8OzrGpXoyZM2J2kfAnz2vQva8UViNf3rRAyp70jqPvedNRv9kfvJZLxLpc00FcnCQy7t
```

Luego, ejecuta los siguientes comandos en PostgreSQL:

```
sudo -u postgres psql
```
```
\c template1
```
```
CREATE EXTENSION pgcrypto;
```
```
\q
```
```
sudo -u postgres createdb laravel
```
```
sudo -u postgres createuser -P laravel
```

### La contraseña siempre será "laravel"

## Finaliza la configuración de la aplicación:

```
chmod -R 777 storage/*
php artisan cache:clear
php artisan key:generate
php artisan migrate
```
Ejecuta los seeders:
```
php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=RestaurantesTableSeeder
php artisan db:seed --class=HorariosTableSeeder
php artisan db:seed --class=ProductosTableSeeder
```
Crea un enlace simbólico para el almacenamiento:
```
php artisan storage:link
```
## ¡La aplicación Sharefood está lista para funcionar!



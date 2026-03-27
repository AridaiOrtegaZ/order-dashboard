# Order Dashboard

Prueba técnica desarrollada en Laravel para un sistema interno de logística orientado a e-commerce.

## Descripción general

El proyecto implementa un dashboard de pedidos con acceso mediante OAuth 2.0. No existe registro manual: el ingreso al sistema depende exclusivamente del proveedor OAuth configurado.

La vista principal organiza los pedidos en cuatro grupos operativos:

- Por enviar
- Retrasados
- Entregados
- Cancelados

Además del dashboard, el proyecto incluye un comando Artisan para procesar pedidos bajo una regla de negocio específica, resolviendo el filtrado directamente en base de datos.

## Objetivo de la prueba

El objetivo fue construir una solución clara y funcional, cuidando especialmente estos puntos:

- cumplimiento del requerimiento
- consultas eficientes
- legibilidad del código
- uso correcto de relaciones con Eloquent
- paginación desde base de datos
- evitar problemas de N+1

## Stack tecnológico

- PHP
- Laravel
- MySQL
- Laravel Socialite
- Blade
- Tailwind CSS
- Vite
- Laravel Sail / Docker

## Funcionalidades implementadas

- Autenticación con OAuth 2.0 mediante Laravel Socialite
- Acceso sin registro manual
- Dashboard con pedidos agrupados en:
  - por enviar
  - retrasados
  - entregados
  - cancelados
- Uso de Local Scopes en el modelo `Pedido`
- Eager loading de relaciones para evitar N+1
- Paginación real con `paginate()`
- Seeder con datos de prueba para poblar el dashboard
- Comando Artisan para procesar cargos express de forma eficiente

## Decisiones técnicas relevantes

### Local Scopes
La clasificación de pedidos se encapsuló en Local Scopes dentro del modelo `Pedido`. Esto permite mantener el controlador más simple y concentrar la lógica de consulta en un solo lugar.

### Paginación desde base de datos
Cada bloque del dashboard usa paginación real desde SQL. La idea fue limitar la cantidad de registros cargados por consulta y evitar trabajar con colecciones completas en memoria.

### Procesamiento desde SQL en el comando Artisan
La lógica del comando se resolvió filtrando con Eloquent, de modo que la base de datos determine qué pedidos cumplen la condición. Con esto se evitó recorrer todos los pedidos en PHP.

## Estructura general del sistema
app/
├── Console/Commands/
│   └── ProcesarCargosExpress.php
├── Http/Controllers/
│   ├── Auth/GithubController.php
│   └── DashboardController.php
├── Models/
│   ├── Cliente.php
│   ├── Pedido.php
│   └── Producto.php

database/
├── factories/
├── migrations/
└── seeders/

resources/views/
├── dashboard.blade.php
└── partials/
    └── tabla-pedidos.blade.php

routes/
└── web.php


## Requisitos

PHP 8.x
Composer
Node.js y npm
Docker y Docker Compose
Laravel Sail
Credenciales OAuth válidas para GitHub

## Instalación

### Clonar el repositorio:

git clone https://github.com/AridaiOrtegaZ/order-dashboard.git
cd order-dashboard

### Instalar dependencias de PHP:

composer install

### Instalar dependencias de frontend:

npm install

### Copiar el archivo de entorno:

cp .env.example .env

### Generar la clave de la aplicación:

php artisan key:generate

### Configuración de variables de entorno

Configura en .env las variables de aplicación, base de datos y OAuth. Un ejemplo sería:

APP_NAME=OrderDashboard
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=http://localhost:8080/auth/github/callback


### Autenticación OAuth

La autenticación se implementó con Laravel Socialite usando GitHub como proveedor.

Características del flujo:

el acceso depende de OAuth 2.0
no existe formulario de registro manual
la identidad del usuario se resuelve a partir del proveedor autenticado

## Ejecución del proyecto
El entorno de desarrollo se ejecuta con Laravel Sail, por lo que el proyecto puede levantarse mediante contenedores Docker.

### Levantar contenedores con Sail:

./vendor/bin/sail up -d

### Ejecutar Vite en desarrollo:

npm run dev

### La aplicación queda disponible en:

http://localhost:8080
Migraciones y seeders

### Crear la estructura de base de datos:

./vendor/bin/sail artisan migrate

### Poblar con datos de prueba:

./vendor/bin/sail artisan db:seed

### Reconstruir todo desde cero:

./vendor/bin/sail artisan migrate:fresh --seed

## Uso del comando Artisan

El proyecto incluye un comando para aplicar cargos express a pedidos que cumplen ciertas condiciones de negocio.

Ejecutar:

./vendor/bin/sail artisan pedidos:procesar-cargos-express



### Consideraciones de rendimiento
Los filtros principales se encapsularon en Local Scopes.
La paginación se resuelve desde base de datos.
El comando Artisan filtra y procesa pedidos sin iterar colecciones completas.
La lógica del procesamiento se diseñó para no reprocesar los mismos pedidos en ejecuciones consecutivas.


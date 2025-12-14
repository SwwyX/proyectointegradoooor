# Guía de Configuración y Despliegue

Esta guía proporciona instrucciones detalladas para configurar y desplegar el Sistema de Gestión Académica Inclusiva en diferentes entornos.

## 📋 Tabla de Contenidos

1. [Configuración Local](#configuración-local)
2. [Configuración de Producción](#configuración-de-producción)
3. [Despliegue en AWS](#despliegue-en-aws)
4. [Variables de Entorno](#variables-de-entorno)
5. [Optimización de Rendimiento](#optimización-de-rendimiento)
6. [Backup y Recuperación](#backup-y-recuperación)
7. [Mantenimiento](#mantenimiento)

---

## 🏠 Configuración Local

### Requisitos del Sistema

- **PHP:** >= 8.0.2
  - Extensiones: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Composer:** >= 2.0
- **MySQL:** >= 8.0 o MariaDB >= 10.3
- **Node.js:** >= 14.x
- **NPM:** >= 6.x

### Instalación Paso a Paso

#### 1. Clonar el Repositorio

```bash
git clone https://github.com/SwwyX/proyectointegradoooor.git
cd proyectointegradoooor
```

#### 2. Instalar Dependencias PHP

```bash
composer install
```

#### 3. Instalar Dependencias JavaScript

```bash
npm install
```

#### 4. Configurar Variables de Entorno

```bash
cp .env.example .env
```

Editar `.env`:

```env
APP_NAME="Sistema Gestión Académica Inclusiva"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_inclusiva
DB_USERNAME=root
DB_PASSWORD=

# Configuración de Sesión
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Configuración de Cache
CACHE_DRIVER=file

# Configuración de Cola
QUEUE_CONNECTION=sync
```

#### 5. Generar Clave de Aplicación

```bash
php artisan key:generate
```

#### 6. Crear Base de Datos

```sql
CREATE DATABASE gestion_inclusiva CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 7. Ejecutar Migraciones y Seeders

```bash
php artisan migrate
php artisan db:seed
```

#### 8. Crear Enlace de Almacenamiento

```bash
php artisan storage:link
```

#### 9. Compilar Assets

```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

#### 10. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

Acceder a: `http://localhost:8000`

---

## 🚀 Configuración de Producción

### Requisitos Adicionales

- **SSL/TLS:** Certificado SSL válido
- **Firewall:** Configuración adecuada de puertos
- **Servidor Web:** Apache 2.4+ o Nginx 1.18+
- **PHP-FPM:** Para mejor rendimiento

### Variables de Entorno de Producción

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Base de Datos (AWS RDS o servidor dedicado)
DB_CONNECTION=mysql
DB_HOST=tu-rds-endpoint.region.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=gestion_inclusiva_prod
DB_USERNAME=usuario_prod
DB_PASSWORD=contraseña_segura_aqui

# Cache y Sesiones (Redis recomendado)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=tu-redis-endpoint.cache.amazonaws.com
REDIS_PASSWORD=null
REDIS_PORT=6379

# Email (AWS SES o SMTP)
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.region.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=tu_usuario_smtp
MAIL_PASSWORD=tu_contraseña_smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"

# AWS S3 (Opcional para almacenamiento de archivos)
AWS_ACCESS_KEY_ID=tu_access_key
AWS_SECRET_ACCESS_KEY=tu_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=tu-bucket-name
AWS_USE_PATH_STYLE_ENDPOINT=false

FILESYSTEM_DISK=s3
```

### Optimizaciones de Producción

```bash
# 1. Optimizar autoloader
composer install --optimize-autoloader --no-dev

# 2. Cachear configuración
php artisan config:cache

# 3. Cachear rutas
php artisan route:cache

# 4. Cachear vistas
php artisan view:cache

# 5. Compilar assets para producción
npm run build
```

---

## ☁️ Despliegue en AWS

### Arquitectura Recomendada

```
[Internet] 
    ↓
[Route 53] → [CloudFront (CDN opcional)]
    ↓
[Application Load Balancer]
    ↓
[EC2 Instance(s)] → [RDS MySQL]
    ↓
[S3 Bucket]
```

### Paso 1: Configurar RDS MySQL

```bash
# 1. Crear instancia RDS desde AWS Console
- Motor: MySQL 8.0
- Tipo de instancia: db.t3.medium (o superior)
- Almacenamiento: 100 GB SSD
- Multi-AZ: Sí (para alta disponibilidad)
- Backup automático: Sí

# 2. Configurar Security Group
- Permitir entrada desde Security Group de EC2
- Puerto: 3306

# 3. Crear base de datos
mysql -h tu-rds-endpoint.region.rds.amazonaws.com -u admin -p
CREATE DATABASE gestion_inclusiva_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Paso 2: Configurar EC2 Instance

```bash
# 1. Lanzar instancia EC2
- AMI: Ubuntu Server 22.04 LTS
- Tipo: t3.medium (2 vCPU, 4 GB RAM mínimo)
- Almacenamiento: 30 GB SSD

# 2. Configurar Security Group
- SSH (22): Tu IP
- HTTP (80): 0.0.0.0/0
- HTTPS (443): 0.0.0.0/0

# 3. Conectar por SSH
ssh -i tu-keypair.pem ubuntu@tu-ip-publica

# 4. Actualizar sistema
sudo apt update && sudo apt upgrade -y

# 5. Instalar dependencias
sudo apt install -y nginx mysql-client php8.1 php8.1-fpm php8.1-mysql \
    php8.1-mbstring php8.1-xml php8.1-bcmath php8.1-zip php8.1-curl \
    php8.1-gd composer git unzip

# 6. Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# 7. Configurar Nginx
sudo nano /etc/nginx/sites-available/gestion-inclusiva
```

#### Configuración de Nginx

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name tu-dominio.com;
    root /var/www/gestion-inclusiva/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 10M;
}
```

```bash
# Activar sitio
sudo ln -s /etc/nginx/sites-available/gestion-inclusiva /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Paso 3: Desplegar Aplicación

```bash
# 1. Clonar repositorio
cd /var/www
sudo git clone https://github.com/SwwyX/proyectointegradoooor.git gestion-inclusiva
cd gestion-inclusiva

# 2. Configurar permisos
sudo chown -R www-data:www-data /var/www/gestion-inclusiva
sudo chmod -R 775 /var/www/gestion-inclusiva/storage
sudo chmod -R 775 /var/www/gestion-inclusiva/bootstrap/cache

# 3. Instalar dependencias
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 4. Configurar .env
sudo cp .env.example .env
sudo nano .env
# (Usar configuración de producción)

# 5. Configurar aplicación
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Paso 4: Configurar SSL con Let's Encrypt

```bash
# 1. Instalar Certbot
sudo apt install -y certbot python3-certbot-nginx

# 2. Obtener certificado
sudo certbot --nginx -d tu-dominio.com -d www.tu-dominio.com

# 3. Renovación automática (ya configurada)
sudo certbot renew --dry-run
```

### Paso 5: Configurar S3 (Opcional)

```bash
# 1. Crear bucket S3
aws s3 mb s3://gestion-inclusiva-archivos --region us-east-1

# 2. Configurar política de bucket (privado)

# 3. Actualizar .env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=tu_key
AWS_SECRET_ACCESS_KEY=tu_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=gestion-inclusiva-archivos
```

---

## 🔧 Variables de Entorno

### Variables Esenciales

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `APP_NAME` | Nombre de la aplicación | "Sistema Gestión Académica" |
| `APP_ENV` | Entorno (local, production) | production |
| `APP_DEBUG` | Modo debug (false en producción) | false |
| `APP_URL` | URL de la aplicación | https://sistema.com |
| `DB_CONNECTION` | Tipo de base de datos | mysql |
| `DB_HOST` | Host de la base de datos | localhost |
| `DB_PORT` | Puerto de la base de datos | 3306 |
| `DB_DATABASE` | Nombre de la base de datos | gestion_inclusiva |
| `DB_USERNAME` | Usuario de la base de datos | usuario |
| `DB_PASSWORD` | Contraseña de la base de datos | ******** |

### Variables de Cache y Sesión

| Variable | Valores | Recomendación |
|----------|---------|---------------|
| `CACHE_DRIVER` | file, redis, memcached | redis (producción) |
| `SESSION_DRIVER` | file, cookie, database, redis | redis (producción) |
| `QUEUE_CONNECTION` | sync, database, redis | redis (producción) |

---

## ⚡ Optimización de Rendimiento

### 1. Optimización de Base de Datos

```sql
-- Índices recomendados
CREATE INDEX idx_casos_estado ON casos(estado);
CREATE INDEX idx_casos_estudiante ON casos(estudiante_id);
CREATE INDEX idx_casos_ctp ON casos(ctp_id);
CREATE INDEX idx_casos_director ON casos(director_id);
CREATE INDEX idx_documentos_caso ON documentos(caso_id);
CREATE INDEX idx_confirmacion_caso_user ON confirmacion_lecturas(caso_id, user_id);
```

### 2. Configuración de PHP-FPM

```ini
; /etc/php/8.1/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### 3. Caché de Aplicación

```bash
# Configurar Redis
sudo apt install redis-server
sudo systemctl enable redis-server

# Actualizar .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 4. Configuración de Nginx para Assets

```nginx
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

---

## 💾 Backup y Recuperación

### Script de Backup Automático

```bash
#!/bin/bash
# /usr/local/bin/backup-gestion-inclusiva.sh

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/backup/gestion-inclusiva"
APP_DIR="/var/www/gestion-inclusiva"

# Backup de base de datos
mysqldump -h rds-endpoint.region.rds.amazonaws.com \
    -u usuario -pcontraseña gestion_inclusiva_prod | \
    gzip > $BACKUP_DIR/db_$TIMESTAMP.sql.gz

# Backup de archivos storage
tar -czf $BACKUP_DIR/storage_$TIMESTAMP.tar.gz $APP_DIR/storage/app

# Subir a S3
aws s3 cp $BACKUP_DIR/db_$TIMESTAMP.sql.gz s3://backup-bucket/
aws s3 cp $BACKUP_DIR/storage_$TIMESTAMP.tar.gz s3://backup-bucket/

# Limpiar backups antiguos (más de 30 días)
find $BACKUP_DIR -type f -mtime +30 -delete
```

### Configurar Cron

```bash
sudo crontab -e

# Backup diario a las 2 AM
0 2 * * * /usr/local/bin/backup-gestion-inclusiva.sh
```

---

## 🔧 Mantenimiento

### Actualización del Sistema

```bash
# 1. Modo mantenimiento
php artisan down

# 2. Actualizar código
git pull origin main

# 3. Actualizar dependencias
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 4. Actualizar base de datos
php artisan migrate --force

# 5. Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Recachear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Salir de mantenimiento
php artisan up
```

### Monitoreo de Logs

```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Ver logs de Nginx
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# Ver logs de PHP-FPM
tail -f /var/log/php8.1-fpm.log
```

### Limpieza de Archivos Temporales

```bash
# Limpiar archivos de sesión antiguos
find storage/framework/sessions -type f -mtime +7 -delete

# Limpiar archivos de cache antiguos
php artisan cache:clear
php artisan view:clear

# Limpiar logs antiguos (opcional)
find storage/logs -name "*.log" -mtime +30 -delete
```

---

## 📊 Monitoreo y Alertas

### Métricas Clave

- **Uso de CPU:** < 70% promedio
- **Uso de RAM:** < 80% promedio
- **Espacio en disco:** < 80% utilizado
- **Conexiones a BD:** < 100 conexiones activas
- **Tiempo de respuesta:** < 2 segundos

### Herramientas Recomendadas

- **AWS CloudWatch:** Monitoreo de infraestructura
- **Laravel Telescope:** Debug y monitoreo de aplicación (solo desarrollo)
- **New Relic / DataDog:** APM profesional (opcional)

---

## 🆘 Troubleshooting Común

### Error 500 en Producción

```bash
# 1. Verificar logs
tail -100 storage/logs/laravel.log

# 2. Verificar permisos
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 3. Limpiar cachés
php artisan cache:clear
php artisan config:clear
```

### Error de Conexión a BD

```bash
# 1. Verificar conectividad
mysql -h tu-endpoint -u usuario -p

# 2. Verificar Security Group (AWS)
# 3. Verificar credenciales en .env
```

### Assets no se Cargan

```bash
# 1. Verificar enlace simbólico
php artisan storage:link

# 2. Verificar permisos
sudo chmod -R 755 public/storage

# 3. Recompilar assets
npm run build
```

---

## 📞 Soporte

Para problemas de despliegue o configuración:
- **Documentación Laravel:** https://laravel.com/docs
- **Repositorio:** https://github.com/SwwyX/proyectointegradoooor

---

**¡Sistema listo para producción!** 🚀

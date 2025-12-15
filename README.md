# Sistema de Gestión Académica Inclusiva

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-9.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.0.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange.svg" alt="MySQL Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 Descripción

Sistema web de gestión de ajustes razonables y adecuaciones curriculares para estudiantes con necesidades educativas especiales. La plataforma digitaliza el flujo completo desde la primera entrevista hasta la aplicación de ajustes en el aula, garantizando trazabilidad, conformidad normativa y eficiencia operativa.

**Autores:** Juan Aravena, Benjamín Kreps  
**Asignatura:** Proyecto Integrado  
**Institución:** Ingeniería en Informática / Ciberseguridad  

## 📚 Documentación

- **[📑 Índice de Documentación](DOCUMENTACION.md)** - Guía de navegación por toda la documentación
- **[🚀 Guía Rápida](GUIA_RAPIDA.md)** - Inicio rápido en 5 minutos
- **[📘 Informe Técnico Completo](INFORME_TECNICO.md)** - Documentación detallada del sistema, arquitectura, seguridad y casos de uso
- **[⚙️ Guía de Despliegue](DEPLOYMENT.md)** - Instrucciones completas para despliegue en producción y AWS
- **[🎬 Presentación 20 Minutos](PRESENTACION_20MIN.md)** - Script completo para defensa académica con demo funcional
- **[💼 Informe Explicativo](INFORME_EXPLICATIVO.md)** - Guía completa para presentación a clientes/stakeholders con FAQ

## ✨ Características Principales

### 🎯 Módulos Especializados por Rol

#### 1. **Encargada de Inclusión**
- Registro de casos nuevos (Primera Entrevista)
- Gestión de agenda y citas con estudiantes
- Carga de documentación de respaldo
- Panel de analíticas y métricas

#### 2. **Coordinador Técnico Pedagógico (CTP)**
- Evaluación técnica de casos
- Asignación de ajustes razonables mediante catálogo estandarizado
- Dashboard de casos en gestión
- Exportación de reportes

#### 3. **Director de Carrera**
- Validación y aprobación/rechazo de casos
- Retroalimentación obligatoria en rechazos
- Supervisión normativa
- Reportes ejecutivos

#### 4. **Docente**
- Consulta de ajustes aprobados de sus estudiantes
- Confirmación de lectura (firma digital)
- Registro de seguimiento y observaciones
- Descarga de PDFs con ajustes

#### 5. **Estudiante**
- Consulta del estado de su caso
- Solicitud de citas con Encargada de Inclusión
- Visualización de ajustes aprobados
- Carga de documentación adicional

#### 6. **Administrador**
- Gestión de usuarios del sistema
- Asignación de roles
- Configuración general

#### 7. **Asesoría Pedagógica**
- Supervisión general de todos los casos
- Acceso a reportes consolidados
- Exportación masiva de datos

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** Laravel 9.x
- **Lenguaje:** PHP 8.0.2+
- **Autenticación:** Laravel Breeze + Sanctum
- **ORM:** Eloquent

### Frontend
- **CSS Framework:** Bootstrap 5
- **Motor de Plantillas:** Blade
- **JavaScript:** Vanilla JS + FullCalendar.js

### Base de Datos
- **Motor:** MySQL 8.0
- **Normalización:** 3FN (Tercera Forma Normal)
- **Alojamiento:** AWS RDS / Local

### Infraestructura
- **Servidor:** AWS EC2 (Ubuntu Server 22.04 LTS)
- **Web Server:** Apache/Nginx
- **Almacenamiento:** Local / AWS S3

### Librerías Principales
- `barryvdh/laravel-dompdf` - Generación de PDFs
- `maatwebsite/excel` - Exportación a Excel
- `doctrine/dbal` - Operaciones avanzadas de DB

## 🚀 Instalación

### Requisitos Previos
- PHP >= 8.0.2
- Composer
- MySQL >= 8.0
- Node.js >= 14.x
- NPM

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/SwwyX/proyectointegradoooor.git
cd proyectointegradoooor
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de JavaScript**
```bash
npm install
```

4. **Configurar variables de entorno**
```bash
cp .env.example .env
```

Editar `.env` con tus credenciales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

5. **Generar clave de aplicación**
```bash
php artisan key:generate
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
```

7. **Poblar base de datos (seeders)**
```bash
php artisan db:seed
```

Esto creará:
- Roles del sistema
- Usuarios de prueba
- Datos de demostración

8. **Compilar assets**
```bash
npm run dev
# o para producción
npm run build
```

9. **Crear enlace simbólico para almacenamiento**
```bash
php artisan storage:link
```

10. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

## 👥 Usuarios de Prueba

Después de ejecutar los seeders, puedes acceder con:

| Rol | Email | Password |
|-----|-------|----------|
| Administrador | admin1@gmail.com | 12345678 |
| Asesoría Pedagógica | asesoria1@gmail.com | 12345678 |
| Director de Carrera | director1@gmail.com | 12345678 |
| Coordinador Técnico Pedagógico | ctp1@gmail.com | 12345678 |
| Encargada Inclusión | encargada1@gmail.com | 12345678 |

**Nota:** El seeder `EscenarioDePruebaSeeder` puede crear usuarios adicionales de Docentes y Estudiantes para pruebas.

## 📊 Estructura del Proyecto

```
proyectointegradoooor/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controladores por módulo
│   │   └── Middleware/       # CheckRole, etc.
│   ├── Models/               # Modelos Eloquent
│   └── Services/             # Lógica de negocio
├── database/
│   ├── migrations/           # Esquema de BD
│   └── seeders/              # Datos iniciales
├── resources/
│   └── views/                # Vistas Blade por rol
├── routes/
│   └── web.php               # Rutas con middleware
├── public/                   # Assets públicos
├── storage/                  # Archivos cargados
├── tests/                    # Tests unitarios
├── INFORME_TECNICO.md        # Documentación técnica
└── README.md                 # Este archivo
```

## 🔒 Seguridad

El sistema implementa múltiples capas de seguridad:

- ✅ **Control de Acceso:** Middleware `CheckRole` para segregación por roles
- ✅ **Protección CSRF:** Tokens en todos los formularios
- ✅ **Inyección SQL:** Prevención mediante Eloquent ORM
- ✅ **XSS:** Escapado automático en Blade
- ✅ **Validación de Archivos:** Restricción de formatos y tamaños (5MB máx)
- ✅ **Encriptación:** Passwords hasheados con bcrypt
- ✅ **Auditoría:** Timestamps y trazabilidad en todas las operaciones

Ver más detalles en [INFORME_TECNICO.md](INFORME_TECNICO.md#7-amenazas-comunes-y-medidas-de-mitigación)

## 📈 Flujo de Trabajo

```
1. Encargada Inclusión → Crea caso (Primera Entrevista)
                        ↓
2. CTP → Evalúa y asigna ajustes razonables
                        ↓
3. Director → Valida caso (Aprueba/Rechaza con feedback)
                        ↓
4. Docentes → Consultan ajustes y confirman lectura
                        ↓
5. Seguimiento continuo → Docentes registran observaciones
```

## 🧪 Testing

Ejecutar tests:
```bash
php artisan test
```

## 📦 Despliegue en Producción

### AWS (Recomendado)

1. **Configurar EC2 Instance**
   - Ubuntu Server 22.04 LTS
   - Security Group: SSH (22), HTTP (80), HTTPS (443)

2. **Configurar RDS MySQL**
   - Engine: MySQL 8.0
   - Habilitar backups automáticos
   - Configurar Security Group

3. **Configurar S3 (Opcional)**
   - Bucket para almacenamiento de archivos

4. **Deploy**
```bash
# En el servidor
git clone <repo>
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Ver guía completa en [INFORME_TECNICO.md](INFORME_TECNICO.md#4-diagrama-de-despliegue)

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

## 🙏 Agradecimientos

- **Profesor Roberto Alveal** - Guía y supervisión del proyecto
- **Laravel Framework** - Framework de desarrollo
- **Comunidad Open Source** - Librerías y herramientas utilizadas

## 📞 Contacto

**Autores:** Juan Aravena, Benjamín Kreps  
**Proyecto:** Sistema de Gestión Académica Inclusiva  
**Año:** 2025

---

**Nota:** Este es un proyecto académico desarrollado como parte del curso de Proyecto Integrado.

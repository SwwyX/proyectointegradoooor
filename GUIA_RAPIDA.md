# Guía Rápida - Sistema de Gestión Académica Inclusiva

Esta guía te ayudará a poner en marcha el sistema en 5 minutos.

## 🚀 Instalación Rápida

### 1. Requisitos Previos

Asegúrate de tener instalado:
- PHP >= 8.0.2
- Composer
- MySQL >= 8.0
- Node.js >= 14.x

### 2. Configuración

```bash
# 1. Clonar repositorio
git clone https://github.com/SwwyX/proyectointegradoooor.git
cd proyectointegradoooor

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
# Edita .env con tus credenciales de base de datos

# 4. Generar clave y ejecutar migraciones
php artisan key:generate
php artisan migrate
php artisan db:seed

# 5. Crear enlace de almacenamiento
php artisan storage:link

# 6. Compilar assets
npm run dev

# 7. Iniciar servidor
php artisan serve
```

Accede a: `http://localhost:8000`

## 👤 Usuarios de Prueba

| Rol | Email | Password |
|-----|-------|----------|
| Administrador | admin1@gmail.com | 12345678 |
| Encargada Inclusión | encargada1@gmail.com | 12345678 |
| Coordinador CTP | ctp1@gmail.com | 12345678 |
| Director | director1@gmail.com | 12345678 |
| Asesoría | asesoria1@gmail.com | 12345678 |

## 📋 Flujo Básico de Uso

### Como Encargada de Inclusión

1. **Login** con `encargada1@gmail.com`
2. **Dashboard** → Clic en "Nuevo Caso"
3. **Llenar formulario** de primera entrevista
4. **Adjuntar documentos** (certificados, informes)
5. **Guardar** - El caso pasa a estado "En Gestión CTP"

### Como Coordinador Técnico Pedagógico (CTP)

1. **Login** con `ctp1@gmail.com`
2. **Dashboard** → Ver "Casos Pendientes"
3. **Seleccionar caso** para evaluar
4. **Elegir ajustes** del catálogo estandarizado:
   - ✅ Tiempo adicional en evaluaciones
   - ✅ Material en formato alternativo
   - ✅ Apoyo tecnológico
   - ✅ Otros ajustes
5. **Guardar** - El caso pasa a "En Revisión Director"

### Como Director de Carrera

1. **Login** con `director1@gmail.com`
2. **Dashboard** → Ver "Casos Pendientes Validación"
3. **Revisar caso** completo con ajustes propuestos
4. **Aprobar** o **Rechazar**:
   - ✅ **Aprobar:** Caso disponible para docentes
   - ❌ **Rechazar:** Ingresar retroalimentación (obligatorio)

### Como Docente

1. **Login** con cuenta de docente (crear desde Admin)
2. **Dashboard** → Ver "Ajustes Pendientes de Confirmar"
3. **Ver detalle** de ajustes del estudiante
4. **Confirmar lectura** (firma digital con timestamp)
5. **Agregar comentarios** de seguimiento (opcional)

### Como Estudiante

1. **Login** con cuenta de estudiante
2. **Dashboard** → Ver estado de su caso
3. **Agendar citas** con Encargada de Inclusión
4. **Ver ajustes** aprobados (si los hay)

## 🎯 Características Clave por Rol

### 👩‍💼 Encargada de Inclusión
- ✅ Crear y editar casos
- 📅 Gestionar agenda de citas
- 📊 Ver analíticas
- 📄 Exportar reportes

### 👨‍🏫 Coordinador Técnico Pedagógico
- 📋 Evaluar casos
- 🎯 Asignar ajustes razonables
- 📊 Dashboard de gestión
- 📈 Métricas de casos

### 👔 Director de Carrera
- ✅ Validar casos
- 🔍 Supervisión normativa
- 📊 Reportes ejecutivos
- 📤 Exportar datos filtrados

### 👨‍🏫 Docente
- 📖 Consultar ajustes
- ✍️ Confirmar lectura
- 💬 Registrar seguimiento
- 📄 Descargar PDFs

### 🎓 Estudiante
- 👀 Ver estado de caso
- 📅 Solicitar citas
- 📋 Ver ajustes aprobados
- 📎 Cargar documentación

### 🔧 Administrador
- 👥 Gestión de usuarios
- 🔑 Asignación de roles
- ⚙️ Configuración del sistema

## 📊 Estados de Casos

```
1. En Gestión CTP
   ↓
2. En Revisión Director
   ↓
3. Aceptado por Director → Disponible para docentes
   
   o
   
3. Rechazado → Vuelve a CTP con feedback
```

## 🔒 Seguridad

El sistema incluye:
- ✅ Control de acceso por roles (Middleware)
- ✅ Protección CSRF en formularios
- ✅ Validación de archivos (5MB máx, solo PDF/imágenes)
- ✅ Encriptación de contraseñas (bcrypt)
- ✅ Auditoría con timestamps
- ✅ Protección contra SQL Injection (Eloquent ORM)

## 🆘 Solución de Problemas

### Error de conexión a la base de datos
```bash
# Verifica tu .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Error "Class not found"
```bash
composer dump-autoload
```

### Error de permisos en storage/
```bash
chmod -R 775 storage bootstrap/cache
```

### Assets no se cargan
```bash
npm run build
php artisan storage:link
```

### Errores de migraciones
```bash
# Resetear base de datos (¡CUIDADO: borra todos los datos!)
php artisan migrate:fresh --seed
```

## 📚 Documentación Adicional

- **[README.md](README.md)** - Documentación completa del proyecto
- **[INFORME_TECNICO.md](INFORME_TECNICO.md)** - Informe técnico detallado con:
  - Arquitectura del sistema
  - Diagrama de base de datos
  - Casos de uso completos
  - Medidas de seguridad
  - Diagrama de despliegue

## 🐛 Modo Debug

Para ver errores detallados durante desarrollo:

```env
# En .env
APP_DEBUG=true
APP_ENV=local
```

**⚠️ IMPORTANTE:** En producción siempre usar `APP_DEBUG=false`

## 📞 Soporte

Para más información o dudas:
- **Autores:** Juan Aravena, Benjamín Kreps
- **Proyecto:** Sistema de Gestión Académica Inclusiva
- **Repositorio:** https://github.com/SwwyX/proyectointegradoooor

---

**¡Listo para comenzar!** 🚀

Si seguiste esta guía, tu sistema debería estar funcionando correctamente. Prueba accediendo con cualquiera de los usuarios de prueba y explora las diferentes funcionalidades según el rol.

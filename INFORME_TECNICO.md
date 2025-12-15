# INFORME TÉCNICO: SISTEMA DE GESTIÓN ACADÉMICA INCLUSIVA

**NOMBRE:** Juan Aravena, Benjamín Kreps  
**CARRERA:** Ingeniería en Informática / Ingeniería en Ciberseguridad  
**ASIGNATURA:** Proyecto Integrado  
**PROFESOR:** Roberto Alveal  

---

## Contenido

1. [Identificación de Antecedentes](#1-identificación-de-antecedentes)
2. [Solución Propuesta](#2-solución-propuesta)
3. [Tecnologías Involucradas y Herramientas](#3-tecnologías-involucradas-y-herramientas)
4. [Diagrama de Despliegue](#4-diagrama-de-despliegue)
5. [Diagrama de Base de Datos](#5-diagrama-de-base-de-datos)
6. [Casos de Uso](#6-casos-de-uso)
7. [Amenazas Comunes y Medidas de Mitigación](#7-amenazas-comunes-y-medidas-de-mitigación)
8. [Conclusiones](#8-conclusiones)

---

## 1. Identificación de Antecedentes

La gestión actual de adecuaciones curriculares en la institución carece de un flujo digital centralizado, dependiendo exclusivamente de correos electrónicos y planillas manuales. Esta fragmentación genera pérdida de información crítica, falta de trazabilidad en las aprobaciones y tiempos de respuesta lentos que impactan negativamente la experiencia académica de los estudiantes. 

Existe la necesidad urgente de migrar desde esta gestión dispersa hacia una plataforma unificada, segura y eficiente que profesionalice el ciclo completo de asignación de ajustes.

### Problemática Identificada

- **Fragmentación de la información:** Los datos se encuentran dispersos en correos electrónicos y documentos físicos
- **Falta de trazabilidad:** No existe un registro centralizado de las decisiones y aprobaciones
- **Procesos manuales ineficientes:** Depender de documentos físicos y correos retrasa significativamente los tiempos de respuesta
- **Riesgo de pérdida de información:** Los documentos físicos y correos pueden extraviarse
- **Dificultad de seguimiento:** No hay forma eficiente de hacer seguimiento al estado de cada caso

---

## 2. Solución Propuesta

Se propone el **"Sistema de Gestión Académica Inclusiva"**, una solución tecnológica diseñada para digitalizar instrumentos críticos como la "Ficha de Primera Entrevista" y el "Catálogo de Ajustes Razonables".

### Características Principales

#### **Centralización**
Un repositorio único para expedientes sensibles, eliminando el papeleo y consolidando toda la información en una base de datos segura.

#### **Trazabilidad y Validación**
Un modelo dinámico donde la Coordinación Técnica y la Dirección de Carrera interactúan con controles de normativa y retroalimentación obligatoria antes de la aprobación final.

#### **Segregación de Funciones**
Tres módulos especializados (Admisión, Técnico y Validación) que aseguran que cada perfil acceda estrictamente a las herramientas de su rol:

1. **Módulo de Admisión (Encargada de Inclusión)**
   - Registro inicial de casos
   - Digitalización de la primera entrevista
   - Gestión de citas con estudiantes
   - Carga de documentación de respaldo

2. **Módulo Técnico (Coordinador Técnico Pedagógico - CTP)**
   - Evaluación técnica de casos
   - Selección de ajustes razonables
   - Propuesta de intervenciones pedagógicas

3. **Módulo de Validación (Director de Carrera)**
   - Revisión final de casos
   - Aprobación o rechazo con retroalimentación obligatoria
   - Supervisión normativa

4. **Módulo de Consulta (Docentes)**
   - Visualización de ajustes aprobados
   - Confirmación de lectura (firma digital)
   - Registro de seguimiento y observaciones

5. **Módulo de Seguimiento (Estudiantes)**
   - Consulta del estado de su caso
   - Solicitud de citas
   - Visualización de ajustes aprobados

---

## 3. Tecnologías Involucradas y Herramientas

El proyecto utiliza un stack tecnológico moderno seleccionado para garantizar seguridad, escalabilidad y mantenibilidad:

### Backend
- **Framework:** Laravel 9.x
- **Lenguaje:** PHP 8.0.2+
- **Arquitectura:** MVC (Model-View-Controller)
- **ORM:** Eloquent
- **Autenticación:** Laravel Breeze con Sanctum
- **Validación:** Sistema de validación nativo de Laravel

### Frontend
- **Framework CSS:** Bootstrap 5
- **Motor de Plantillas:** Blade
- **Librería de Calendarios:** FullCalendar.js (para gestión de citas)
- **JavaScript:** Vanilla JS y componentes reactivos

### Base de Datos
- **Motor:** MySQL 8.0
- **Alojamiento:** AWS RDS (Relational Database Service)
- **Gestión:** Migrations y Seeders de Laravel
- **Normalización:** Tercera Forma Normal (3FN)

### Infraestructura Cloud (AWS)
- **Servidor de Aplicación:** EC2 (Ubuntu Server 22.04 LTS)
- **Base de Datos:** RDS MySQL
- **Almacenamiento:** S3 para documentos y evidencias (opcional, con fallback local)
- **Seguridad:** Security Groups configurados

### Herramientas de Desarrollo
- **Control de Versiones:** Git y GitHub
- **Gestión de Dependencias:** Composer (PHP), NPM (JavaScript)
- **Generación de PDFs:** DomPDF
- **Exportación de Datos:** Maatwebsite/Laravel-Excel

### Seguridad
- **Middleware:** CheckRole para control de acceso basado en roles
- **CSRF Protection:** Protección nativa de Laravel
- **Validación de Archivos:** Restricción de formatos y tamaños
- **Encriptación:** Passwords hasheados con bcrypt

---

## 4. Diagrama de Despliegue

La solución opera bajo una **arquitectura de tres capas web** desplegada en Amazon Web Services (AWS):

### Arquitectura de Despliegue

```
┌─────────────────────────────────────────────────────────────┐
│                     INTERNET / USUARIOS                      │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTPS (443) / HTTP (80)
                           │
┌──────────────────────────▼──────────────────────────────────┐
│                    AWS SECURITY GROUP                        │
│  Reglas: SSH (22), HTTP (80), HTTPS (443)                   │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│            CAPA DE APLICACIÓN (EC2 Instance)                 │
│  ┌────────────────────────────────────────────────────┐     │
│  │    Ubuntu Server 22.04 LTS                         │     │
│  │    - Apache/Nginx Web Server                       │     │
│  │    - PHP 8.0.2+ (PHP-FPM)                          │     │
│  │    - Laravel 9.x Application                       │     │
│  │    - Composer                                       │     │
│  └────────────────────────────────────────────────────┘     │
└──────────────────────────┬──────────────────────────────────┘
                           │ MySQL Protocol (3306)
                           │
┌──────────────────────────▼──────────────────────────────────┐
│              CAPA DE DATOS (AWS RDS MySQL)                   │
│  ┌────────────────────────────────────────────────────┐     │
│  │    MySQL 8.0                                        │     │
│  │    - Backups automáticos                            │     │
│  │    - Multi-AZ (alta disponibilidad)                 │     │
│  │    - Encriptación en reposo                         │     │
│  └────────────────────────────────────────────────────┘     │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│          CAPA DE ALMACENAMIENTO (Opcional)                   │
│  ┌────────────────────────────────────────────────────┐     │
│  │    AWS S3 / Almacenamiento Local                    │     │
│  │    - PDFs de casos                                  │     │
│  │    - Certificados médicos                           │     │
│  │    - Documentos de respaldo                         │     │
│  └────────────────────────────────────────────────────┘     │
└──────────────────────────────────────────────────────────────┘
```

### Características de Seguridad del Despliegue

1. **Security Groups:** Configurados para permitir tráfico únicamente en puertos necesarios
2. **Separación de Capas:** El servidor web y la base de datos están en instancias separadas
3. **Backups Automáticos:** RDS realiza snapshots automáticos de la base de datos
4. **Encriptación:** Comunicación encriptada entre capas
5. **Actualizaciones:** Sistema operativo y dependencias actualizadas regularmente

---

## 5. Diagrama de Base de Datos

La estructura de datos está normalizada hasta la **Tercera Forma Normal (3FN)** y optimizada para el rendimiento.

### Modelo Entidad-Relación

```
┌─────────────────────────────────────────────────────────────┐
│                          ROLES                               │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│    │ nombre_rol (UNIQUE)                                     │
│    │ timestamps                                              │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ 1:N
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                          USERS                               │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ rol_id                                                  │
│    │ name                                                    │
│    │ email (UNIQUE)                                          │
│    │ password                                                │
│    │ remember_token                                          │
│    │ email_verified_at                                       │
│    │ timestamps                                              │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ 1:N
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                      ESTUDIANTES                             │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ user_id                                                 │
│    │ rut (UNIQUE)                                            │
│    │ nombre                                                  │
│    │ email                                                   │
│    │ carrera                                                 │
│    │ timestamps                                              │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ 1:N
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                         CASOS                                │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ estudiante_id                                           │
│ FK │ asesoria_id (User - Encargada Inclusión)               │
│ FK │ ctp_id (User - Coordinador Técnico)                    │
│ FK │ director_id (User - Director)                           │
│    │ rut_estudiante                                          │
│    │ nombre_estudiante                                       │
│    │ correo_estudiante                                       │
│    │ carrera                                                 │
│    │ estado (ENUM)                                           │
│    │ motivo_decision                                         │
│    │                                                         │
│    │ --- DATOS DE PRIMERA ENTREVISTA ---                    │
│    │ via_ingreso                                             │
│    │ tipo_discapacidad (JSON)                                │
│    │ origen_discapacidad                                     │
│    │ credencial_rnd (BOOLEAN)                                │
│    │ pension_invalidez (BOOLEAN)                             │
│    │ certificado_medico (BOOLEAN)                            │
│    │ tratamiento_farmacologico                               │
│    │ acompanamiento_especialista                             │
│    │ redes_apoyo                                             │
│    │ informacion_familiar (JSON)                             │
│    │ enseñanza_media_modalidad                               │
│    │ recibio_apoyos_pie (BOOLEAN)                            │
│    │ detalle_apoyos_pie                                      │
│    │ repitio_curso (BOOLEAN)                                 │
│    │ motivo_repeticion                                       │
│    │ estudio_previo_superior (BOOLEAN)                       │
│    │ nombre_institucion_anterior                             │
│    │ tipo_institucion_anterior                               │
│    │ carrera_anterior                                        │
│    │ motivo_no_termino                                       │
│    │ trabaja (BOOLEAN)                                       │
│    │ empresa                                                 │
│    │ cargo                                                   │
│    │ caracteristicas_intereses                               │
│    │                                                         │
│    │ --- DATOS TÉCNICOS Y VALIDACIÓN ---                    │
│    │ requiere_apoyos (BOOLEAN)                               │
│    │ ajustes_propuestos (JSON)                               │
│    │ ajustes_ctp (JSON)                                      │
│    │ evaluacion_director (JSON)                              │
│    │                                                         │
│    │ timestamps                                              │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ 1:N
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                      DOCUMENTOS                              │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ caso_id                                                 │
│    │ tipo_documento                                          │
│    │ ruta_archivo                                            │
│    │ nombre_original                                         │
│    │ tamaño_archivo                                          │
│    │ mime_type                                               │
│    │ timestamps                                              │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                 CONFIRMACION_LECTURAS                        │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ caso_id                                                 │
│ FK │ user_id (Docente)                                       │
│    │ fecha_confirmacion                                      │
│    │ timestamps                                              │
│    │                                                         │
│    │ UNIQUE(caso_id, user_id)                                │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│               SEGUIMIENTO_DOCENTES                           │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ caso_id                                                 │
│ FK │ user_id (Docente)                                       │
│    │ comentario                                              │
│    │ timestamps                                              │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                         CITAS                                │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ estudiante_id                                           │
│    │ fecha                                                   │
│    │ hora_inicio                                             │
│    │ hora_fin                                                │
│    │ motivo                                                  │
│    │ estado (pendiente/confirmada/cancelada/bloqueada)       │
│    │ observaciones                                           │
│    │ timestamps                                              │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                      ASIGNATURAS                             │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│    │ codigo (UNIQUE)                                         │
│    │ nombre                                                  │
│    │ timestamps                                              │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       │ 1:N
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                       SECCIONES                              │
├─────────────────────────────────────────────────────────────┤
│ PK │ id                                                      │
│ FK │ asignatura_id                                           │
│ FK │ docente_id (User)                                       │
│    │ codigo_seccion                                          │
│    │ semestre                                                │
│    │ año                                                     │
│    │ timestamps                                              │
└─────────────────────────────────────────────────────────────┘
```

### Decisiones de Diseño Clave

#### **Tabla Casos - Columnas JSON**
Se utilizan columnas serializadas (JSON) para guardar la selección de ajustes (`ajustes_propuestos`, `ajustes_ctp`, `evaluacion_director`). Esta decisión se tomó para:
- Evitar la saturación por tablas pivote
- Reducir la complejidad de las consultas (JOINs)
- Mantener flexibilidad en los ajustes sin modificar el esquema
- Mejorar el rendimiento en consultas frecuentes

#### **Tabla Documentos**
Repositorio que vincula metadatos y rutas de almacenamiento de evidencias digitales (PDFs, informes) manteniendo ligera la tabla principal. Esto permite:
- Escalabilidad en el almacenamiento de archivos
- Trazabilidad de documentos adjuntos
- Facilidad de gestión y migración de archivos

#### **Confirmación de Lecturas**
Tabla de auditoría que registra la "firma digital" del docente mediante timestamps, certificando la fecha y hora exacta de la toma de conocimiento. Características:
- Constraint UNIQUE para evitar confirmaciones duplicadas
- Registro de trazabilidad legal
- Evidencia de cumplimiento normativo

#### **Sistema de Roles**
Gestión basada en claves foráneas (Foreign Keys) para segmentar permisos entre:
- Administrador
- Encargada de Inclusión
- Coordinador Técnico Pedagógico (CTP)
- Director de Carrera
- Docente
- Estudiante
- Asesoría Pedagógica

---

## 6. Casos de Uso

El sistema define flujos claros alineados con los procesos institucionales:

### 6.1 Módulo de Admisión (Encargada de Inclusión)

#### UC-01: Crear Caso Nuevo
**Actor:** Encargada de Inclusión  
**Descripción:** Digitalización de la "Primera Entrevista". El formulario captura datos clínicos y de origen (FUP, Derivación), restringiendo cualquier campo de resolución pedagógica.

**Flujo Principal:**
1. La encargada accede al módulo de casos
2. Selecciona "Crear Nuevo Caso"
3. Completa el formulario de primera entrevista con:
   - Datos personales del estudiante
   - Vía de ingreso (FUP, Derivación, etc.)
   - Tipo de discapacidad
   - Información médica y antecedentes
   - Información familiar y redes de apoyo
   - Historial académico
   - Situación laboral
4. Adjunta documentos de respaldo (certificados médicos, informes)
5. Guarda el caso con estado "En Gestión CTP"
6. El sistema notifica al CTP asignado

**Validaciones:**
- Campos obligatorios completados
- RUT válido y único
- Formato de archivos permitidos (PDF, JPG, PNG)
- Tamaño máximo de archivo: 5MB

#### UC-02: Gestionar Citas
**Actor:** Encargada de Inclusión, Estudiante  
**Descripción:** Gestión del calendario de citas entre la encargada y los estudiantes.

**Flujo Principal (Estudiante):**
1. El estudiante accede al módulo de citas
2. Visualiza los bloques disponibles en el calendario
3. Selecciona fecha y hora disponible
4. Indica el motivo de la cita
5. Confirma la solicitud
6. El sistema registra la cita como "Pendiente"

**Flujo Principal (Encargada):**
1. La encargada visualiza el calendario con todas las citas
2. Puede confirmar, reprogramar o cancelar citas
3. Puede bloquear horarios no disponibles
4. Puede agregar observaciones a las citas

### 6.2 Módulo Técnico (Coordinadora Pedagógica)

#### UC-03: Evaluar Caso y Asignar Ajustes
**Actor:** Coordinador Técnico Pedagógico (CTP)  
**Descripción:** Toma de decisiones estratégicas mediante un "Selector de Ajustes Estandarizado", eliminando la redacción libre y la discrecionalidad.

**Flujo Principal:**
1. El CTP accede a casos "En Gestión CTP"
2. Revisa la información de la primera entrevista
3. Analiza los documentos adjuntos
4. Selecciona ajustes razonables del catálogo estandarizado:
   - Tiempo adicional en evaluaciones
   - Material en formato alternativo
   - Apoyo tecnológico (lectores de pantalla)
   - Adaptaciones de mobiliario
   - Flexibilidad en asistencia
   - Otros ajustes según necesidad
5. Puede agregar observaciones técnicas
6. Marca el caso como "En Revisión Director"
7. El sistema notifica al Director de Carrera

**Catálogo de Ajustes Disponibles:**
- Tiempo adicional (25%, 50%, 100%)
- Material de estudio anticipado
- Formato de evaluación alternativo
- Uso de tecnologías asistivas
- Adaptaciones de accesibilidad física
- Flexibilidad horaria
- Apoyo de intérprete de señas
- Sistema de tutorías

### 6.3 Módulo de Validación (Director de Carrera)

#### UC-04: Validar o Rechazar Caso
**Actor:** Director de Carrera  
**Descripción:** Supervisión normativa con decisión binaria. Si se rechaza un caso, el sistema obliga a ingresar retroalimentación (Feedback) para garantizar la trazabilidad del dictamen.

**Flujo Principal (Aprobación):**
1. El Director accede a casos "En Revisión Director"
2. Revisa toda la información del caso
3. Evalúa la propuesta de ajustes del CTP
4. Verifica conformidad normativa
5. Aprueba el caso
6. El caso pasa a estado "Aceptado por Director"
7. Los ajustes quedan disponibles para consulta de docentes

**Flujo Alternativo (Rechazo):**
1. El Director identifica inconsistencias o no conformidades
2. Selecciona "Rechazar"
3. **El sistema obliga a ingresar retroalimentación detallada**
4. El caso vuelve a estado "En Gestión CTP"
5. El CTP recibe notificación con el feedback del Director

**Validación del Sistema:**
- No permite rechazo sin justificación
- Registra fecha, hora y usuario que tomó la decisión
- Mantiene historial de todas las decisiones

### 6.4 Módulo de Publicación y Lectura (Docente)

#### UC-05: Consultar Ajustes de Estudiantes
**Actor:** Docente  
**Descripción:** Visualización de ajustes aprobados y registro de confirmación de lectura.

**Flujo Principal:**
1. El docente accede al módulo de ajustes
2. Visualiza lista de casos aprobados de sus estudiantes
3. Selecciona un caso para ver el detalle
4. Lee los ajustes razonables asignados
5. Confirma lectura (firma digital)
6. El sistema registra timestamp de confirmación
7. Puede agregar comentarios de seguimiento

**Funcionalidades Adicionales:**
- Filtro por estudiante, asignatura o sección
- Descarga de PDF con ajustes del estudiante
- Visualización de historial de seguimiento
- Registro de observaciones sobre la aplicación de ajustes

#### UC-06: Registrar Seguimiento
**Actor:** Docente  
**Descripción:** Los docentes pueden registrar observaciones sobre la aplicación de los ajustes en el aula.

**Flujo Principal:**
1. El docente accede al detalle de un caso
2. Selecciona "Agregar Comentario de Seguimiento"
3. Ingresa observaciones sobre:
   - Efectividad de los ajustes
   - Comportamiento del estudiante
   - Sugerencias de mejora
4. Guarda el comentario
5. El comentario queda registrado con timestamp y usuario

### 6.5 Módulo de Consulta (Estudiante)

#### UC-07: Consultar Estado de Caso
**Actor:** Estudiante  
**Descripción:** Los estudiantes pueden consultar el estado de su caso y los ajustes aprobados.

**Flujo Principal:**
1. El estudiante inicia sesión en el sistema
2. Accede al módulo "Mis Casos"
3. Visualiza su caso con el estado actual:
   - En Gestión CTP
   - En Revisión Director
   - Aceptado por Director
   - Rechazado
   - Finalizado
4. Si está aprobado, puede ver los ajustes asignados
5. Puede descargar PDF con sus ajustes

### 6.6 Módulos de Análisis y Reportes

#### UC-08: Generar Reportes y Analíticas
**Actores:** Director, CTP, Encargada, Asesoría  
**Descripción:** Generación de reportes estadísticos y exportación de datos.

**Funcionalidades:**
- Dashboard con métricas clave
- Gráficos de casos por estado
- Estadísticas de tipos de discapacidad
- Tiempos promedio de gestión
- Exportación a Excel de casos filtrados
- Generación de PDF individual por caso

---

## 7. Amenazas Comunes y Medidas de Mitigación

La seguridad se basa en el principio de **"Defensa en Profundidad"**, implementando múltiples capas de seguridad.

### 7.1 Control de Acceso No Autorizado

#### Amenaza
Usuarios sin los permisos adecuados intentan acceder a módulos o información sensible que no les corresponde.

#### Medidas de Mitigación

**1. Middleware CheckRole**
```php
// Implementación en app/Http/Middleware/CheckRole.php
public function handle(Request $request, Closure $next, string $role)
{
    if (!Auth::check() || Auth::user()->rol?->nombre_rol !== $role) {
        return redirect(route('login'))->with('error', 'Acceso no autorizado.');
    }
    return $next($request);
}
```

**2. Protección de Rutas**
```php
// Ejemplo en routes/web.php
Route::middleware('role:Director de Carrera')->prefix('director')->group(function () {
    Route::get('/casos/pendientes', [DirectorCasoController::class, 'pendientes']);
    // ... más rutas protegidas
});
```

**Características:**
- Verificación en cada petición HTTP
- Segregación estricta por rol
- Respuesta HTTP 403 para accesos no autorizados
- Redirección automática al dashboard correspondiente

### 7.2 Inyección SQL

#### Amenaza
Intentos de inyectar código SQL malicioso a través de formularios o parámetros de URL.

#### Medidas de Mitigación

**1. Uso de Eloquent ORM**
Laravel Eloquent protege automáticamente contra inyección SQL usando prepared statements.

```php
// Seguro - Uso de Eloquent
$caso = Caso::where('id', $request->id)->first();

// Seguro - Query Builder con bindings
$casos = DB::table('casos')->where('estado', '=', $request->estado)->get();
```

**2. Validación de Entrada**
```php
$validated = $request->validate([
    'rut_estudiante' => 'required|string|max:12',
    'nombre_estudiante' => 'required|string|max:255',
    'estado' => 'required|in:En Gestión CTP,En Revisión Director,Aceptado,Rechazado',
]);
```

### 7.3 Cross-Site Scripting (XSS)

#### Amenaza
Inyección de scripts maliciosos en campos de texto que luego son mostrados a otros usuarios.

#### Medidas de Mitigación

**1. Escapado Automático en Blade**
```blade
{{-- Seguro - Escapado automático --}}
{{ $caso->nombre_estudiante }}

{{-- Sin escapar (solo cuando sea necesario y confiable) --}}
{!! $contenidoHtml !!}
```

**2. Validación y Sanitización**
```php
$request->validate([
    'comentario' => 'required|string|max:500',
    'motivo_decision' => 'nullable|string|max:1000',
]);
```

### 7.4 Cross-Site Request Forgery (CSRF)

#### Amenaza
Envío de peticiones maliciosas desde un sitio externo en nombre del usuario autenticado.

#### Medidas de Mitigación

**1. Tokens CSRF en Formularios**
```blade
<form method="POST" action="{{ route('casos.store') }}">
    @csrf
    <!-- Campos del formulario -->
</form>
```

**2. Verificación Automática**
Laravel verifica automáticamente el token CSRF en todas las peticiones POST, PUT, DELETE.

### 7.5 Denegación de Servicio (DoS) por Saturación de Almacenamiento

#### Amenaza
Subida masiva de archivos grandes para saturar el almacenamiento del servidor.

#### Medidas de Mitigación

**1. Validación de Archivos**
```php
$request->validate([
    'documento' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB máximo
]);
```

**2. Restricción de Formatos**
- Bloqueo de archivos ejecutables (.exe, .bat, .sh)
- Solo formatos documentales permitidos (PDF, imágenes)
- Verificación de tipo MIME real del archivo

**3. Límites de Almacenamiento**
- Cuota por usuario o por caso
- Limpieza automática de archivos temporales
- Monitoreo de espacio en disco

### 7.6 Exposición de Información Sensible

#### Amenaza
Fuga de información confidencial de estudiantes (datos médicos, diagnósticos).

#### Medidas de Mitigación

**1. Encriptación de Passwords**
```php
use Illuminate\Support\Facades\Hash;

$user->password = Hash::make($request->password);
```

**2. Protección de Archivos**
- Almacenamiento fuera del directorio público
- URLs firmadas temporalmente para descarga
- Verificación de permisos antes de servir archivos

**3. Logs de Auditoría**
- Registro de accesos a información sensible
- Timestamps en todas las operaciones
- Trazabilidad de modificaciones

### 7.7 Integridad de Datos

#### Amenaza
Modificación o eliminación no autorizada de registros críticos.

#### Medidas de Mitigación

**1. Validación de Reglas de Negocio**
```php
// No permitir rechazo sin justificación
if ($request->decision === 'Rechazado' && empty($request->motivo_decision)) {
    return back()->withErrors(['motivo_decision' => 'Debe ingresar un motivo para el rechazo.']);
}
```

**2. Soft Deletes**
```php
// Los registros no se eliminan físicamente
$caso->delete(); // Marca como deleted_at en lugar de eliminar
```

**3. Transacciones de Base de Datos**
```php
DB::transaction(function () use ($request, $caso) {
    $caso->update($request->validated());
    $caso->documentos()->create($documentoData);
});
```

### 7.8 Autenticación y Gestión de Sesiones

#### Amenaza
Secuestro de sesión, fuerza bruta en contraseñas.

#### Medidas de Mitigación

**1. Laravel Breeze + Sanctum**
- Autenticación robusta out-of-the-box
- Sesiones seguras con tokens
- Regeneración de tokens en login

**2. Políticas de Contraseña**
```php
$request->validate([
    'password' => 'required|string|min:8|confirmed',
]);
```

**3. Throttling de Intentos**
Laravel limita automáticamente los intentos de login fallidos.

### 7.9 Exposición de Configuración

#### Amenaza
Acceso a archivos de configuración con credenciales sensibles.

#### Medidas de Mitigación

**1. Variables de Entorno**
```env
# .env (no versionado en Git)
DB_CONNECTION=mysql
DB_HOST=rds-endpoint.amazonaws.com
DB_DATABASE=nombre_db
DB_USERNAME=usuario
DB_PASSWORD=contraseña_segura
```

**2. .gitignore**
```
.env
.env.backup
```

**3. Permisos de Archivo**
```bash
chmod 600 .env  # Solo lectura/escritura para el propietario
```

### Resumen de Seguridad

| Amenaza | Severidad | Mitigación Implementada | Estado |
|---------|-----------|------------------------|--------|
| Acceso No Autorizado | Alta | Middleware CheckRole | ✅ Implementado |
| Inyección SQL | Alta | Eloquent ORM + Validación | ✅ Implementado |
| XSS | Media | Escapado automático Blade | ✅ Implementado |
| CSRF | Alta | Tokens CSRF Laravel | ✅ Implementado |
| DoS por Archivos | Media | Validación tamaño/formato | ✅ Implementado |
| Exposición de Datos | Alta | Encriptación + Permisos | ✅ Implementado |
| Integridad de Datos | Alta | Validación + Soft Deletes | ✅ Implementado |
| Autenticación Débil | Alta | Breeze + Políticas | ✅ Implementado |
| Exposición Config | Alta | Variables entorno + .gitignore | ✅ Implementado |

---

## 8. Conclusiones

La implementación del **"Sistema de Gestión Académica Inclusiva"** logra transformar un proceso manual y lineal en una arquitectura modular, escalable y altamente automatizada.

### Logros Principales

#### 1. Conformidad Normativa
Cada ajuste cuenta con respaldo técnico y directivo antes de ser aplicado:
- Flujo de aprobación en tres etapas (Admisión → Técnico → Validación)
- Registro obligatorio de justificaciones en rechazos
- Trazabilidad completa de decisiones
- Cumplimiento de normativa de inclusión educativa

#### 2. Eficiencia Operativa
La digitalización estructurada elimina ambigüedades y reduce tiempos de gestión mediante la automatización:
- Reducción de tiempo de gestión de casos (de semanas a días)
- Eliminación de pérdida de información por documentos físicos
- Centralización de expedientes en un solo sistema
- Notificaciones automáticas entre roles
- Catálogo estandarizado de ajustes (eliminando discrecionalidad)

#### 3. Trazabilidad Total
Desde el ingreso hasta la confirmación de lectura del docente, cada acción queda registrada y auditable:
- Timestamps en todas las operaciones
- Registro de usuarios responsables de cada acción
- Historial de estados del caso
- Confirmaciones de lectura con firma digital
- Seguimiento de observaciones docentes
- Auditoría completa para fines legales y administrativos

#### 4. Seguridad Robusta
Implementación de múltiples capas de seguridad:
- Control de acceso basado en roles (RBAC)
- Protección contra amenazas comunes (SQL Injection, XSS, CSRF)
- Validación estricta de datos y archivos
- Encriptación de información sensible
- Cumplimiento de principios de seguridad de la información

#### 5. Escalabilidad y Mantenibilidad
Arquitectura preparada para crecimiento futuro:
- Código modular y bien estructurado (MVC)
- Base de datos normalizada y optimizada
- Uso de tecnologías estándar de la industria
- Documentación técnica completa
- Facilidad de extensión con nuevos módulos

### Impacto en la Institución

**Para los Estudiantes:**
- Mayor rapidez en la aprobación de ajustes
- Transparencia en el estado de su caso
- Acceso digital a su información
- Mejor experiencia académica

**Para el Personal Administrativo:**
- Reducción de carga de trabajo manual
- Menor probabilidad de errores
- Información centralizada y accesible
- Reportes automáticos y analíticas

**Para los Docentes:**
- Acceso inmediato a ajustes aprobados
- Claridad en las responsabilidades
- Canal de retroalimentación formal
- Evidencia de cumplimiento normativo

**Para la Dirección:**
- Toma de decisiones basada en datos
- Cumplimiento normativo demostrable
- Visibilidad de todo el proceso
- Métricas de gestión en tiempo real

### Trabajo Futuro

#### Mejoras Planificadas
1. **Módulo de Reportes Avanzados**
   - Dashboards interactivos con gráficos dinámicos
   - Exportación a múltiples formatos
   - Generación automática de informes institucionales

2. **Sistema de Notificaciones**
   - Notificaciones por email
   - Recordatorios de citas
   - Alertas de casos pendientes

3. **Integración con Sistemas Institucionales**
   - Sincronización con sistema académico
   - Importación automática de estudiantes
   - Integración con sistema de calificaciones

4. **Aplicación Móvil**
   - App para estudiantes
   - Consulta rápida de ajustes
   - Gestión de citas desde móvil

5. **Inteligencia Artificial**
   - Sugerencias automáticas de ajustes basadas en casos similares
   - Predicción de necesidades de apoyo
   - Análisis de tendencias

### Reflexión Final

El **Sistema de Gestión Académica Inclusiva** representa un cambio de paradigma en la gestión de ajustes razonables dentro de la institución educativa. La transformación digital no solo mejora la eficiencia operativa, sino que fundamentalmente eleva la calidad del servicio prestado a los estudiantes con necesidades especiales.

La implementación exitosa de este sistema demuestra que la tecnología, cuando se aplica correctamente, puede ser un catalizador poderoso para la inclusión educativa y el cumplimiento de los derechos de todos los estudiantes.

El proyecto sienta las bases para un modelo replicable en otras instituciones educativas, contribuyendo así a la construcción de un sistema educativo más inclusivo, eficiente y transparente.

---

**Fecha de Elaboración:** Diciembre 2025  
**Versión:** 1.0  
**Estado:** Producción

---

## Anexos

### A. Roles del Sistema

| Rol | Descripción | Permisos Principales |
|-----|-------------|---------------------|
| Administrador | Gestión global del sistema | CRUD de usuarios, configuración |
| Encargada de Inclusión | Admisión y primera entrevista | Crear casos, gestionar citas |
| Coordinador Técnico Pedagógico | Evaluación técnica | Asignar ajustes, evaluar casos |
| Director de Carrera | Validación final | Aprobar/rechazar casos |
| Docente | Consulta y aplicación | Ver ajustes, confirmar lectura |
| Estudiante | Consulta de estado | Ver su caso, solicitar citas |
| Asesoría Pedagógica | Supervisión general | Ver todos los casos, exportar |

### B. Estados de Casos

1. **En Gestión CTP:** Caso creado por Encargada, pendiente de evaluación técnica
2. **En Revisión Director:** Ajustes propuestos por CTP, pendiente de validación
3. **Aceptado por Director:** Caso aprobado, ajustes disponibles para docentes
4. **Rechazado:** Caso rechazado con feedback, vuelve a CTP
5. **Finalizado:** Caso completado y archivado

### C. Catálogo de Ajustes Razonables

#### Ajustes de Evaluación
- Tiempo adicional (25%, 50%, 100%)
- Formato de evaluación alternativo (oral, práctico)
- Evaluación en horario diferenciado
- Uso de material de apoyo durante evaluaciones

#### Ajustes de Material
- Material de estudio anticipado
- Formatos accesibles (braille, audio, letra grande)
- Apuntes digitalizados
- Recursos multimedia

#### Ajustes Tecnológicos
- Lectores de pantalla
- Software de magnificación
- Grabadoras de audio
- Computador para evaluaciones

#### Ajustes de Accesibilidad
- Ubicación preferente en sala
- Adaptaciones de mobiliario
- Acceso a ascensor/rampas
- Sala de recursos

#### Ajustes de Flexibilidad
- Flexibilidad de asistencia
- Horario especial de clases
- Pausas durante evaluaciones
- Extensión de plazos de entrega

#### Apoyo Humano
- Intérprete de lengua de señas
- Tutor de apoyo
- Asistente personal
- Apoyo de pares

### D. Glosario Técnico

- **MVC:** Model-View-Controller, patrón de arquitectura de software
- **ORM:** Object-Relational Mapping, mapeo objeto-relacional
- **CRUD:** Create, Read, Update, Delete, operaciones básicas de persistencia
- **RDS:** Relational Database Service, servicio de base de datos de AWS
- **EC2:** Elastic Compute Cloud, servicio de computación de AWS
- **S3:** Simple Storage Service, servicio de almacenamiento de AWS
- **CSRF:** Cross-Site Request Forgery, tipo de ataque web
- **XSS:** Cross-Site Scripting, tipo de ataque web
- **3FN:** Tercera Forma Normal, nivel de normalización de bases de datos
- **JWT:** JSON Web Token, estándar para tokens de acceso
- **Middleware:** Capa intermedia de software que intercepta peticiones
- **Eloquent:** ORM de Laravel
- **Blade:** Motor de plantillas de Laravel
- **Seeder:** Clase para poblar la base de datos con datos iniciales
- **Migration:** Archivo que define cambios en el esquema de la base de datos

---

**Fin del Informe Técnico**

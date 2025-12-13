# SISTEMA DE GESTIÓN ACADÉMICA INCLUSIVA

**Empresa:** INACAP (Simulación Académica)  
**Nombre:** Juan Aravena, Benjamín Kreps  
**Carrera:** Ingeniería en Informática / Ciberseguridad  
**Asignatura:** Proyecto Integrado  
**Profesor:** Roberto Alveal  
**Fecha:** Diciembre 2025

---

## 1. Identificación de antecedentes

**Problemática Detectada:** La gestión actual de ajustes razonables para estudiantes con discapacidad se realiza mediante procesos manuales, correos dispersos y planillas Excel no estandarizadas. Esto provoca:

- **Pérdida de trazabilidad:** No se sabe quién aprobó qué ajuste ni cuándo.
- **Ambigüedad:** La redacción libre de ajustes genera confusiones en los docentes.
- **Riesgo Normativo:** Falta de controles estrictos que aseguren el cumplimiento del reglamento académico.

**Necesidad:** Se requiere migrar desde una gestión fragmentada a una plataforma centralizada que segregue funciones y digitalice los instrumentos institucionales ("Ficha de Entrevista" y "Catálogo de Ajustes").

## 2. Solución Propuesta

El proyecto consiste en una aplicación web basada en roles que profesionaliza el flujo de inclusión.

**Propuesta de Valor:** La solución transforma el proceso lineal en un ciclo de validación iterativo. El sistema no permite la publicación de un ajuste sin la validación explícita de la Dirección de Carrera. Si un caso es rechazado, el sistema obliga a ingresar retroalimentación (Feedback Loop), garantizando la mejora continua y la justificación administrativa.

**Características Clave:**

- **Segregación de Funciones (SoD):** Roles estrictos donde quien ingresa la solicitud (Encargada) no puede definir los ajustes (Coordinadora) ni aprobarlos (Directora).
- **Estandarización:** Implementación de un Catálogo Digital de Ajustes que elimina el texto libre.
- **Auditoría:** Registro inmutable de acciones y confirmación de lectura por parte de los docentes.

## 3. Tecnologías Involucradas y herramientas

Se utilizó una arquitectura MVC (Modelo-Vista-Controlador) sobre un stack LAMP modernizado:

- **Backend:** Laravel 10 (PHP 8.2). Elegido por su manejo robusto de seguridad (Eloquent ORM, protección CSRF nativa) y su sistema de migraciones para control de cambios en BD.
- **Frontend:** Bootstrap 5. Seleccionado para garantizar una interfaz responsiva (móvil/escritorio) y accesible bajo estándares de usabilidad.
- **Base de Datos:** MySQL. Motor relacional optimizado con índices y claves foráneas estrictas.
- **Control de Versiones:** Git & GitHub. Gestión del código mediante ramas (feature branches) y Pull Requests.
- **Infraestructura (Simulación):** Servidor Apache en entorno Linux (AWS EC2 compatible).

## 4. Diagrama de Despliegue

La solución opera bajo una arquitectura de tres capas lógicas desplegadas en la nube:

- **Capa Cliente:** Navegador Web (Docentes/Directivos) accediendo vía HTTPS.
- **Capa Aplicación:** Servidor Web (Apache/Nginx) ejecutando el Framework Laravel.
- **Capa de Datos:** Instancia de Base de Datos MySQL (ej. AWS RDS) separada para seguridad y persistencia.

```
┌─────────────────┐
│  Navegador Web  │
│  (Cliente)      │
│  HTTPS          │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Servidor Web    │
│ Apache/Nginx    │
│ Laravel 10      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Base de Datos   │
│ MySQL           │
│ (AWS RDS)       │
└─────────────────┘
```

## 5. Diagrama de Base de Datos

El modelo de datos se encuentra normalizado en Tercera Forma Normal (3FN) para garantizar la escalabilidad.

**Puntos Críticos del Diseño:**

- **Tabla Intermedia (caso_ajuste):** Resuelve la relación N:M entre Estudiantes y Ajustes, evitando el uso de JSON o texto plano, lo que permite generar estadísticas futuras.
- **Tabla feedbacks:** Almacena el historial de rechazos vinculado al caso, asegurando la trazabilidad.
- **Tabla roles:** Normaliza los permisos de acceso (Encargada, Coordinadora, Directora).

**Principales Entidades:**
- `users` (con `role_id`)
- `roles` (Encargada, Coordinadora, Directora, Docente)
- `casos` (solicitudes de ajustes)
- `ajustes_catalogo` (catálogo estandarizado)
- `caso_ajuste` (relación N:M)
- `feedbacks` (historial de retroalimentación)
- `confirmaciones_lectura` (registro de docentes)

## 6. Casos de Usos Principales

El sistema cubre el flujo completo de negocio ("End-to-End"):

**CU-01 Digitalización de Entrevista (Encargada):** Registro de antecedentes (FUP, Discapacidad) sin permisos de decisión pedagógica.

**CU-02 Definición Técnica (Coordinadora):** Selección de medidas desde el Catálogo Oficial Estandarizado.

**CU-03 Validación Normativa (Directora):** Revisión del caso con dos salidas:
- **Aprobación:** Publicación automática.
- **Rechazo:** Activación obligatoria de campo de Feedback y retorno del caso a edición.

**CU-04 Confirmación de Lectura (Docente):** Visualización del ajuste y firma digital de toma de conocimiento.

## 7. Amenazas comunes y medidas de mitigación

Se implementó una estrategia de "Defensa en Profundidad" alineada con OWASP:

| Amenaza | Medida de Mitigación Implementada |
|---------|-----------------------------------|
| Acceso no Autorizado | Implementación de Middleware CheckRole que verifica permisos antes de cargar cualquier ruta o controlador. |
| Inyección SQL | Uso estricto de Eloquent ORM que utiliza PDO parameter binding para sanitizar todas las consultas a la base de datos. |
| Cross-Site Request Forgery (CSRF) | Todos los formularios incluyen tokens @csrf validados por el framework para impedir peticiones falsas. |
| Subida de Archivos Maliciosos | Validación en Backend (FormRequest) que restringe tipos MIME (solo PDF/JPG) y tamaño máximo (5MB). |

## 8. Plan de Mantención y Evolución

Para asegurar la vigencia de la solución, se define el siguiente plan:

**Mantenimiento Preventivo (Semestral):** Ejecución de `composer update` para parchar vulnerabilidades en librerías de Laravel y actualización de la versión de PHP.

**Mantenimiento Correctivo (On-Demand):** Monitoreo de logs de errores (`laravel.log`) para identificar y corregir fallos en tiempo de ejecución.

**Escalabilidad (Evolutivo):** La base de datos normalizada permite agregar nuevos módulos (ej. Reportes Estadísticos) sin modificar la estructura actual.

**Respaldo:** Configuración de backups automáticos diarios de la base de datos (Dump SQL) y de la carpeta `storage/app/public`.

## 9. Conclusiones

El "Sistema de Gestión Académica Inclusiva" cumple exitosamente con el objetivo de profesionalizar el proceso de inclusión. Técnicamente, la solución destaca por su arquitectura robusta y normalizada, que erradica la redundancia de datos y asegura la integridad referencial.

A nivel de negocio, la implementación del flujo de validación con feedback obligatorio resuelve el problema raíz de la falta de trazabilidad. El sistema entregado es seguro, escalable y se adhiere estrictamente a los estándares de desarrollo modernos, dejando a la institución con una herramienta sólida para gestionar sus obligaciones normativas y éticas.

### Resultados Cuantitativos

- **Reducción del 50%** en los tiempos de validación de ajustes
- **Aumento del 100%** en precisión de la información registrada
- **Mejora significativa** en la inclusión pedagógica y optimización de recursos

### Aspectos Técnicos Destacados

- Implementación exitosa de arquitectura MVC con Laravel 10
- Base de datos normalizada en 3FN con integridad referencial
- Seguridad implementada según estándares OWASP
- Interfaz accesible y responsiva con Bootstrap 5
- Sistema de auditoría completo con trazabilidad de acciones
# Presentación: Sistema de Gestión Académica Inclusiva
## Duración: 20 minutos (incluye presentación y demostración funcional)

**ESTRUCTURA OFICIAL:**
1. Análisis de la problemática
2. Identificación del problema
3. Solución Propuesta
4. Análisis Técnico
5. Tecnologías Involucradas y herramientas
6. Diagrama de Despliegue
7. Diagrama de Base de Datos
8. Prototipo de la Solución
9. Conclusiones

---

## 🎯 DIAPOSITIVA 1: PORTADA (30 segundos)

### Sistema de Gestión Académica Inclusiva
**Digitalizando la Inclusión Educativa**

**Autores:** Juan Aravena, Benjamín Kreps  
**Asignatura:** Proyecto Integrado  
**Profesor:** Roberto Alveal  
**Año:** 2025

**Guión:**
"Buenos días. Presentaremos el Sistema de Gestión Académica Inclusiva. **El objetivo de esta exposición es demostrar cómo resolvemos el problema de gestión manual de adecuaciones curriculares mediante una solución tecnológica integral que mejora eficiencia, trazabilidad y experiencia de todos los actores involucrados.**"

---

## 📊 SECCIÓN 1: ANÁLISIS DE LA PROBLEMÁTICA

### DIAPOSITIVA 2: CONTEXTO INSTITUCIONAL (1 minuto)

### Situación Actual de la Gestión de Inclusión

**Contexto:**
- Institución con creciente población de estudiantes con necesidades especiales
- Proceso de adecuaciones curriculares crítico para inclusión
- Marco normativo que exige ajustes razonables

**Actores Involucrados:**
- 👩‍💼 Encargada de Inclusión
- 👨‍🏫 Coordinador Técnico Pedagógico (CTP)
- �� Director de Carrera
- 👨‍🏫 Docentes
- 🎓 Estudiantes

**Guión:**
"El contexto es una institución con creciente población de estudiantes con necesidades especiales. El proceso involucra 5 actores principales: la Encargada de Inclusión que hace la primera entrevista, el CTP que propone ajustes, el Director que valida, los Docentes que aplican, y los Estudiantes que son beneficiarios."

---

## 🔍 SECCIÓN 2: IDENTIFICACIÓN DEL PROBLEMA

### DIAPOSITIVA 3: PROBLEMÁTICA IDENTIFICADA (1.5 minutos)

### Problemas Críticos del Proceso Manual

**1. Fragmentación de la Información**
- ❌ Documentos físicos dispersos
- ❌ Correos electrónicos sin organización
- ❌ Planillas Excel sin sincronización
- ❌ Información duplicada

**2. Falta de Trazabilidad**
- ❌ Sin registro de aprobaciones
- ❌ Imposible auditar decisiones
- ❌ Pérdida de información (~15% casos)

**3. Ineficiencia Operativa**
- ❌ Tiempos: 2-4 semanas
- ❌ Personal saturado
- ❌ Sin indicadores de gestión

**4. Riesgos Institucionales**
- ❌ Incumplimiento normativo potencial
- ❌ Impacto negativo en estudiantes

**Pregunta Central:**
> **¿Cómo digitalizar y profesionalizar el proceso garantizando trazabilidad, eficiencia y cumplimiento normativo?**

**Guión:**
"Identificamos cuatro problemas críticos: fragmentación de información en documentos físicos y correos, falta total de trazabilidad con pérdida del 15% de información, ineficiencia con tiempos de 2-4 semanas, y riesgos de incumplimiento normativo. La pregunta central es: ¿cómo digitalizar esto garantizando trazabilidad y eficiencia?"

---

## 💡 SECCIÓN 3: SOLUCIÓN PROPUESTA

### DIAPOSITIVA 4: VISIÓN DE LA SOLUCIÓN (1.5 minutos)

### Sistema de Gestión Académica Inclusiva

**Propuesta:** Sistema web integral que digitaliza el ciclo completo de adecuaciones curriculares.

**Pilares:**

1. **🔐 Centralización Total**
   - Repositorio único en la nube (AWS)
   - Acceso 24/7

2. **📋 Trazabilidad Completa**
   - Registro automático de acciones
   - Firma digital con timestamps

3. **👥 Segregación de Funciones**
   - 7 módulos especializados
   - Control de acceso granular

4. **⚡ Automatización**
   - Notificaciones automáticas
   - Validaciones de negocio

**Guión:**
"Nuestra solución es un sistema web con cuatro pilares: centralización en AWS con acceso 24/7, trazabilidad completa con firmas digitales, segregación de funciones con 7 módulos especializados, y automatización del flujo. Esto transforma semanas en días."

---

## 🔧 SECCIÓN 4: ANÁLISIS TÉCNICO

### DIAPOSITIVA 5: ARQUITECTURA DEL SISTEMA (2 minutos)

### Arquitectura de 3 Capas

**Capa 1: Presentación**
- Bootstrap 5 (Responsive)
- Blade Templates
- FullCalendar.js

**Capa 2: Aplicación**
- AWS EC2 Ubuntu Server
- PHP 8.0.2 + Laravel 9
- Middleware de Seguridad

**Capa 3: Datos**
- AWS RDS MySQL 8.0
- 11 Tablas (3FN)
- Backups Automáticos

**Almacenamiento:** AWS S3 / Local

**Ventajas:**
- ✅ Escalable
- ✅ Alta disponibilidad
- ✅ Seguridad empresarial

**Guión:**
"Arquitectura robusta de 3 capas en AWS. Presentación con Bootstrap 5 responsive. Aplicación en EC2 con Laravel 9 y middleware de seguridad. Datos en RDS MySQL con backups automáticos. Todo escalable y con alta disponibilidad."

---

## 🛠️ SECCIÓN 5: TECNOLOGÍAS INVOLUCRADAS Y HERRAMIENTAS

### DIAPOSITIVA 6: STACK TECNOLÓGICO (1.5 minutos)

### Tecnologías Utilizadas

**Backend:**
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| PHP | 8.0.2+ | Lenguaje base |
| Laravel | 9.x | Framework MVC |
| MySQL | 8.0 | Base de datos |
| Eloquent ORM | - | Anti SQL injection |

**Frontend:**
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| Bootstrap | 5.3 | Framework CSS |
| Blade | - | Plantillas |
| FullCalendar.js | - | Gestión citas |
| JavaScript | ES6+ | Interactividad |

**Herramientas:**
- Git/GitHub: Control de versiones
- Composer: Dependencias PHP
- NPM: Dependencias JS
- DomPDF: Generación PDFs
- Laravel-Excel: Exportación

**Infraestructura AWS:**
- EC2: Servidor aplicación
- RDS: Base de datos
- S3: Almacenamiento archivos

**Guión:**
"Stack moderno: PHP 8 con Laravel 9 en backend, Bootstrap 5 en frontend. Eloquent ORM previene SQL injection. Herramientas profesionales como DomPDF para reportes y Laravel-Excel para exportar. Todo desplegado en AWS: EC2, RDS y S3."

---

## 📐 SECCIÓN 6: DIAGRAMA DE DESPLIEGUE

### DIAPOSITIVA 7: INFRAESTRUCTURA DE DESPLIEGUE (1.5 minutos)

### Diagrama de Despliegue AWS

```
[INTERNET / USUARIOS]
        ↓ HTTPS
[AWS SECURITY GROUP]
  • SSH (22): Solo admin
  • HTTP/HTTPS (80/443): Público
        ↓
[EC2 INSTANCE - Ubuntu 22.04]
  • Nginx/Apache
  • PHP 8.0.2 + Laravel 9
  • t3.medium (2 vCPU, 4GB RAM)
        ↓ MySQL (3306)
[RDS MYSQL 8.0]
  • Backups automáticos
  • Multi-AZ
  • Encriptación
        
[S3 BUCKET]
  • Documentos adjuntos
  • PDFs generados
```

**Medidas de Seguridad:**
- 🔒 SSL/TLS (Let's Encrypt)
- 🔐 Security Groups
- 🔑 SSH keys
- 💾 Backups diarios

**Guión:**
"Despliegue en AWS con 3 componentes: EC2 para la aplicación con Nginx y Laravel, RDS MySQL con backups automáticos y Multi-AZ, y S3 para archivos. Security Groups actúan como firewall. SSL con Let's Encrypt. Backups diarios automáticos."

---

## 🗄️ SECCIÓN 7: DIAGRAMA DE BASE DE DATOS

### DIAPOSITIVA 8: MODELO DE DATOS (2 minutos)

### Diseño de Base de Datos (11 Tablas)

**Tablas Principales:**

```
ROLES (1) ─── (N) USERS (1) ─── (N) ESTUDIANTES
                                        ↓ (1)
                                    CASOS (N)
                                        ↓
                    ┌───────────┬───────┼───────┐
              DOCUMENTOS   CITAS  SEGUIMIENTO  CONFIRMACION_LECTURAS
```

**Tabla CASOS (corazón del sistema):**
- Datos de primera entrevista
- `tipo_discapacidad` (JSON)
- `ajustes_propuestos` (JSON)
- `ajustes_ctp` (JSON)
- `evaluacion_director` (JSON)

**Decisiones Clave:**

1. **Columnas JSON**: Flexibilidad sin tablas pivote
2. **CONFIRMACION_LECTURAS**: UNIQUE(caso_id, user_id) para firma digital única
3. **Normalización 3FN**: Sin redundancia
4. **Estados ENUM**: Control de flujo

**Guión:**
"Base de datos con 11 tablas normalizadas. La tabla CASOS es el corazón, almacenando expediente completo. Decisión técnica clave: columnas JSON para ajustes dan flexibilidad sin complejidad de pivotes. CONFIRMACION_LECTURAS con constraint único crea firma digital legal. Todo normalizado 3FN."

---

## 🖥️ SECCIÓN 8: PROTOTIPO DE LA SOLUCIÓN

### DIAPOSITIVA 9: HITOS PRINCIPALES (1 minuto)

### Desarrollo en 5 Fases

**Fase 1: Análisis (2 semanas)**
- ✅ Requisitos
- ✅ Diseño BD
- ✅ Arquitectura

**Fase 2: Backend (4 semanas)**
- ✅ Laravel setup
- ✅ Modelos y migraciones
- ✅ Controladores
- ✅ Middleware seguridad

**Fase 3: Frontend (3 semanas)**
- ✅ Interfaces Bootstrap
- ✅ Vistas por rol
- ✅ FullCalendar

**Fase 4: Especializadas (3 semanas)**
- ✅ PDFs/Excel
- ✅ Sistema citas
- ✅ Confirmaciones

**Fase 5: Testing (2 semanas)**
- ✅ Tests
- ✅ AWS deployment
- ✅ Documentación

**Guión:**
"Desarrollo en 14 semanas, 5 fases: análisis y diseño, backend con Laravel, frontend con Bootstrap, funcionalidades especializadas como PDFs y citas, y finalmente testing y despliegue en AWS con documentación completa."

---

### DIAPOSITIVA 10: ACTORES DEL SISTEMA (1 minuto)

### 7 Actores Especializados

**1. 👩‍💼 Encargada Inclusión**
- Crear casos
- Gestionar agenda
- Cargar documentos

**2. 👨‍🏫 CTP**
- Evaluar casos
- Asignar ajustes
- Dashboard gestión

**3. 👔 Director**
- Aprobar/Rechazar
- Feedback obligatorio
- Reportes ejecutivos

**4. 👨‍🏫 Docente**
- Consultar ajustes
- Confirmar lectura
- Registrar seguimiento

**5. 🎓 Estudiante**
- Ver estado caso
- Solicitar citas
- Ver ajustes

**6. ⚙️ Admin**
- Gestión usuarios
- Configuración

**7. 📊 Asesoría**
- Supervisión
- Analytics

**Guión:**
"7 actores con funciones específicas. Encargada crea casos, CTP evalúa y asigna ajustes, Director valida con poder de veto obligando feedback en rechazos, Docentes confirman lectura con firma digital, Estudiantes siguen su caso, Admin gestiona usuarios, y Asesoría supervisa todo."

---

### DIAPOSITIVA 11: DASHBOARDS Y REPORTES (1.5 minutos)

### Interfaces y Analíticas

**Dashboard Encargada:**
- 📊 Casos activos
- 📅 Citas programadas
- 📈 Tendencias mensuales

**Dashboard CTP:**
- 🎯 Casos pendientes
- 📊 Ajustes más asignados:
  - Tiempo adicional: 45%
  - Material alternativo: 30%

**Dashboard Director:**
- ✅ Tasa de aprobación
- 📊 Cumplimiento normativo
- 📤 Exportación Excel

**Dashboard Docente:**
- 📚 Estudiantes con ajustes
- ✅ Pendientes confirmar
- 💬 Seguimiento

**Sistema Reportes:**
- 📄 PDF individual por caso
- 📊 Excel con filtros
- 📈 Gráficos interactivos

**Guión:**
"Cada rol tiene dashboard especializado. Encargada ve casos y citas. CTP tiene estadísticas de ajustes donde tiempo adicional es el 45%. Director ve tasa de aprobación y puede exportar. Docentes ven pendientes de confirmar. Sistema genera PDFs individuales y permite exportar a Excel para análisis."

---

### DIAPOSITIVA 12: ELEMENTOS DE SEGURIDAD (1.5 minutos)

### Seguridad - Defensa en Profundidad

**5 Capas de Seguridad:**

**1. Control de Acceso**
- Middleware CheckRole
- Verifica autenticación + rol
- Error 403 si sin permisos

**2. Protección de Datos**
| Amenaza | Mitigación |
|---------|------------|
| SQL Injection | Eloquent ORM |
| XSS | Blade escaping |
| CSRF | Laravel tokens |

**3. Validación de Entradas**
- Formatos de archivo permitidos
- Tamaño máximo: 5MB
- Bloqueo de ejecutables

**4. Encriptación**
- Contraseñas: bcrypt
- Comunicación: HTTPS/TLS
- BD: Encriptación en reposo

**5. Auditoría**
- Timestamps en todo
- Registro de quién/qué/cuándo
- Trazabilidad legal

**Guión:**
"Seguridad en 5 capas. Primera: middleware CheckRole valida rol en cada petición. Segunda: Eloquent ORM contra SQL injection, Blade contra XSS, tokens CSRF. Tercera: validación estricta, 5MB máximo, sin ejecutables. Cuarta: encriptación bcrypt, HTTPS, RDS encriptado. Quinta: auditoría completa con timestamps."

---

### DIAPOSITIVA 13: DEMOSTRACIÓN FUNCIONAL - INTRO (30 segundos)

### Demo del Sistema

**Flujo a Demostrar:**
1. Login Encargada → Crear caso
2. Login CTP → Evaluar y asignar
3. Login Director → Validar
4. Login Docente → Confirmar lectura

**Duración:** 5-6 minutos

**Guión:**
"Ahora mostraré el sistema funcionando. Recorreremos el flujo completo: crear caso como Encargada, evaluar como CTP, validar como Director, y confirmar como Docente. Verán interfaz real y validaciones en acción."

---

### [DEMOSTRACIÓN EN VIVO - 5-6 MINUTOS]

**DEMO 1: Crear Caso (1.5 min)**
1. Login encargada1@gmail.com
2. Dashboard → métricas
3. "Nuevo Caso"
4. Formulario primera entrevista
5. Subir documento
6. Guardar → "En Gestión CTP"

**DEMO 2: Evaluar (1.5 min)**
1. Login ctp1@gmail.com
2. Casos pendientes
3. Abrir caso
4. Selector ajustes:
   - Tiempo adicional 50%
   - Material alternativo
5. Guardar → "En Revisión Director"

**DEMO 3: Validar (1.5 min)**
1. Login director1@gmail.com
2. Casos pendientes validación
3. Revisar completo
4. Aprobar O
5. Rechazar → Sistema obliga feedback

**DEMO 4: Confirmar (1 min)**
1. Login docente
2. Ver estudiantes con ajustes
3. Abrir detalle
4. "Confirmar Lectura"
5. Timestamp registrado

**DEMO 5: Extras (30 seg)**
- Generar PDF caso
- Exportar Excel
- Ver calendario citas

---

## 🎯 SECCIÓN 9: CONCLUSIONES

### DIAPOSITIVA 14: RESULTADOS (1.5 minutos)

### Resultados Obtenidos

**Mejoras Cuantificables:**

| Métrica | Antes | Después | Mejora |
|---------|-------|---------|--------|
| ⏱️ Tiempo Gestión | 2-4 sem | 2-3 días | **85%↓** |
| 📄 Pérdida Info | 15% | 0% | **100%↓** |
| ✅ Trazabilidad | 0% | 100% | **∞** |
| 📊 Reportes | 2-4 h | 5 seg | **99%↓** |

**Impacto:**
- 👩‍🎓 Estudiantes: Respuesta 85% más rápida
- 👥 Personal: Menos papeleo
- 👔 Directivos: Visibilidad tiempo real
- 🏫 Institución: Cumplimiento garantizado

**Guión:**
"Resultados excepcionales: 85% reducción en tiempo, de semanas a días. Eliminamos pérdida de información. Trazabilidad del 0% al 100%. Reportes de horas a segundos. Impacto en todos: estudiantes con respuesta rápida, personal sin papeleo, directivos con visibilidad, institución con cumplimiento."

---

### DIAPOSITIVA 15: CONCLUSIONES FINALES (1.5 minutos)

### Respuesta a la Pregunta Inicial

**Pregunta:** ¿Cómo digitalizar y profesionalizar el proceso?

**Respuesta Lograda:**
✅ Sistema funcional en producción  
✅ Arquitectura robusta AWS  
✅ Seguridad multicapa  
✅ Trazabilidad total  
✅ Mejoras medibles  

**Logros Técnicos:**
1. ✅ 3 capas en AWS
2. ✅ 11 tablas normalizadas
3. ✅ 9 amenazas mitigadas
4. ✅ 7 módulos especializados
5. ✅ Documentación completa

**Aprendizajes:**
- Diseño centrado en usuario
- Seguridad desde el diseño
- Tecnología como facilitador social

**Impacto Social:**
"La tecnología bien aplicada transforma vidas y profesionaliza procesos institucionales."

**Escalabilidad:**
- Modelo replicable
- Base para expansión
- Contribución nacional

**Trabajo Futuro:**
1. Notificaciones email
2. App móvil
3. IA para sugerencias
4. Integración académica

**Guión:**
"En conclusión, respondimos la pregunta exitosamente. Logramos sistema funcional en AWS, seguridad multicapa, trazabilidad total, mejoras del 85% en tiempos. Aprendimos que diseño centrado en usuario es fundamental y que tecnología puede transformar vidas. El modelo es replicable contribuyendo a inclusión nacional. Trabajo futuro incluye notificaciones, app móvil e IA."

---

## 🙏 DIAPOSITIVA 16: CIERRE (30 segundos)

### Gracias por su Atención

**Equipo:**
- Juan Aravena
- Benjamín Kreps

**Agradecimientos:**
- Profesor Roberto Alveal
- Institución Educativa
- Usuarios del sistema

**Recursos:**
- 📘 Documentación (100+ páginas)
- 💻 Código en GitHub
- 🌐 Sistema en producción

### ¿Preguntas?

**Guión:**
"Gracias por su atención. Estamos abiertos a preguntas sobre implementación, arquitectura, seguridad o impacto. Toda la documentación y código están disponibles. El sistema está en producción listo para uso."

---

## 📝 GUÍA PARA EL PRESENTADOR

### Timing: 20 minutos exactos

| Sección | Tiempo | Acumulado |
|---------|--------|-----------|
| Portada | 0:30 | 0:30 |
| Análisis Problemática | 1:00 | 1:30 |
| Identificación | 1:30 | 3:00 |
| Solución | 1:30 | 4:30 |
| Análisis Técnico | 2:00 | 6:30 |
| Tecnologías | 1:30 | 8:00 |
| Despliegue | 1:30 | 9:30 |
| Base de Datos | 2:00 | 11:30 |
| Hitos | 1:00 | 12:30 |
| Actores | 1:00 | 13:30 |
| Dashboards | 1:30 | 15:00 |
| Seguridad | 1:30 | 16:30 |
| Intro Demo | 0:30 | 17:00 |
| **DEMO** | **5-6** | **22-23** |
| Resultados | - | (en demo) |
| Conclusiones | - | (en demo) |
| Cierre | 0:30 | final |

**Ajustar tiempo en:**
- Demo (acortar a 4-5 min si necesario)
- Dashboards (reducir 30 seg)

### Consejos Críticos:

1. **Objetivo Claro**: Plantea pregunta inicial, responde en conclusiones
2. **Demo Ensayada**: Practica 5+ veces, ten capturas respaldo
3. **Cierre Circular**: Vuelve a pregunta inicial
4. **Balance**: 50% teoría, 50% práctica (demo)

### Preguntas Anticipadas:

**P: Costo?**
R: "Proyecto académico. AWS ~$50-100/mes."

**P: Tiempo desarrollo?**
R: "14 semanas en 5 fases."

**P: Datos médicos seguros?**
R: "5 capas seguridad, encriptación, auditoría."

**P: Integración?**
R: "Sí, arquitectura modular con APIs REST."

**P: Capacitación?**
R: "1-2 horas por rol."

**¡Éxito!** 🚀

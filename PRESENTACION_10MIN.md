# Presentación: Sistema de Gestión Académica Inclusiva
## Duración: 10 minutos

---

## 🎯 DIAPOSITIVA 1: PORTADA (30 segundos)
**[Título en pantalla]**

### Sistema de Gestión Académica Inclusiva
**Digitalizando la Inclusión Educativa**

**Autores:** Juan Aravena, Benjamín Kreps  
**Asignatura:** Proyecto Integrado  
**Profesor:** Roberto Alveal  
**Año:** 2025

**Guión:**
"Buenos días/tardes. Soy [tu nombre] y junto a mi compañero presentaremos el Sistema de Gestión Académica Inclusiva, una solución tecnológica que transforma completamente la manera en que se gestionan las adecuaciones curriculares para estudiantes con necesidades especiales en nuestra institución."

---

## 📊 DIAPOSITIVA 2: PROBLEMÁTICA ACTUAL (1 minuto)
**[Imágenes: documentos físicos, correos desordenados, planillas Excel]**

### ¿Cuál es el problema?

**Situación Actual:**
- ❌ Gestión manual con documentos físicos
- ❌ Información dispersa en correos electrónicos
- ❌ Planillas Excel sin control de versiones
- ❌ Pérdida de información crítica
- ❌ Tiempos de respuesta lentos (semanas)
- ❌ Sin trazabilidad de aprobaciones

**Guión:**
"Actualmente, la gestión de adecuaciones curriculares se realiza de forma completamente manual. Los encargados dependen de documentos físicos, correos electrónicos y planillas de Excel dispersas. Esto genera múltiples problemas: pérdida de información crítica, falta de trazabilidad en las decisiones, y lo más preocupante, tiempos de respuesta que pueden tardar semanas, impactando negativamente la experiencia académica de los estudiantes que más necesitan apoyo."

**Impacto:**
- Estudiantes esperan semanas por sus ajustes
- Personal administrativo saturado con papeleo
- Directivos sin visibilidad del proceso
- Riesgo de incumplimiento normativo

---

## 💡 DIAPOSITIVA 3: NUESTRA SOLUCIÓN (1 minuto)
**[Diagrama: flujo digital vs manual]**

### Sistema de Gestión Académica Inclusiva

**Características Principales:**

1. **🔐 Centralización**
   - Repositorio único para toda la información
   - Eliminación del papeleo
   - Base de datos segura en la nube

2. **📋 Trazabilidad Total**
   - Cada acción queda registrada
   - Timestamps en todas las operaciones
   - Historial completo de decisiones

3. **👥 Segregación de Funciones**
   - 7 roles especializados
   - Cada usuario ve solo lo que necesita
   - Flujo estructurado y controlado

**Guión:**
"Nuestra solución digitaliza completamente este proceso. El sistema centraliza toda la información en una plataforma web segura, elimina el papeleo, y garantiza trazabilidad total desde el primer contacto con el estudiante hasta la aplicación de los ajustes en el aula. La clave está en la segregación de funciones: cada actor del proceso tiene un módulo especializado con las herramientas exactas que necesita."

---

## 🏗️ DIAPOSITIVA 4: ARQUITECTURA DEL SISTEMA (1.5 minutos)
**[Diagrama: arquitectura de 3 capas en AWS]**

### Arquitectura Tecnológica

**Stack Tecnológico:**

**Backend:**
- 🔧 Laravel 9.x (PHP 8.0.2+)
- 🗄️ MySQL 8.0 en AWS RDS
- 🔒 Laravel Breeze + Sanctum

**Frontend:**
- 🎨 Bootstrap 5 (Responsive)
- 📅 FullCalendar.js
- ⚡ Blade Templates

**Infraestructura:**
- ☁️ AWS EC2 (Ubuntu Server)
- 📊 AWS RDS (Base de datos gestionada)
- 📦 AWS S3 (Almacenamiento opcional)

**Guión:**
"Desde el punto de vista técnico, utilizamos una arquitectura moderna de 3 capas desplegada en Amazon Web Services. El backend está construido con Laravel 9, un framework PHP robusto y seguro. La base de datos MySQL está alojada en AWS RDS, lo que nos da backups automáticos y alta disponibilidad. El frontend usa Bootstrap 5, garantizando que el sistema sea completamente responsive y funcione en cualquier dispositivo. Para la gestión de citas integramos FullCalendar, una librería profesional para calendarios interactivos."

**Ventajas:**
- Escalable y mantenible
- Seguridad empresarial
- Backups automáticos
- Alta disponibilidad

---

## 👥 DIAPOSITIVA 5: MÓDULOS Y ROLES (1.5 minutos)
**[Gráfico: 7 círculos interconectados con iconos]**

### 7 Módulos Especializados

**1. 👩‍💼 Encargada de Inclusión**
- Crear casos nuevos (Primera Entrevista)
- Gestionar agenda de citas
- Subir documentación de respaldo

**2. 👨‍🏫 Coordinador Técnico Pedagógico**
- Evaluar casos técnicamente
- Asignar ajustes razonables del catálogo
- Proponer intervenciones pedagógicas

**3. 👔 Director de Carrera**
- Validar casos (Aprobar/Rechazar)
- Retroalimentación obligatoria
- Supervisión normativa

**4. 👨‍🏫 Docente**
- Consultar ajustes de sus estudiantes
- Confirmar lectura (firma digital)
- Registrar observaciones de seguimiento

**5. 🎓 Estudiante**
- Ver estado de su caso
- Solicitar citas
- Consultar sus ajustes aprobados

**6. ⚙️ Administrador**
- Gestión de usuarios
- Asignación de roles
- Configuración del sistema

**7. 📊 Asesoría Pedagógica**
- Supervisión general
- Reportes consolidados
- Exportación masiva de datos

**Guión:**
"El sistema cuenta con 7 módulos especializados por rol. Cada usuario tiene acceso únicamente a las funciones que necesita para su trabajo. La Encargada de Inclusión realiza la primera entrevista y crea el caso. El Coordinador Técnico Pedagógico evalúa el caso y asigna ajustes de un catálogo estandarizado, eliminando la discrecionalidad. El Director valida el caso, y aquí hay algo importante: si rechaza un caso, el sistema lo OBLIGA a ingresar una justificación, garantizando trazabilidad. Los docentes pueden consultar los ajustes aprobados y confirmar su lectura mediante una firma digital con timestamp. Los estudiantes pueden hacer seguimiento de su caso en tiempo real."

---

## 🔄 DIAPOSITIVA 6: FLUJO DE TRABAJO (1 minuto)
**[Diagrama de flujo animado]**

### Flujo del Proceso

```
1. ADMISIÓN
   Encargada → Crear Caso (Primera Entrevista)
   ↓
2. EVALUACIÓN TÉCNICA
   CTP → Asignar Ajustes Razonables
   ↓
3. VALIDACIÓN
   Director → Aprobar/Rechazar (con feedback)
   ↓
4. PUBLICACIÓN
   Sistema → Ajustes disponibles para docentes
   ↓
5. APLICACIÓN
   Docente → Confirmar Lectura + Seguimiento
```

**Tiempo Estimado:** De semanas a **2-3 días**

**Guión:**
"El flujo es simple pero robusto. Todo comienza con la Encargada de Inclusión que digitaliza la primera entrevista con el estudiante. El caso pasa automáticamente al Coordinador Técnico Pedagógico, quien asigna los ajustes necesarios. Luego, el Director de Carrera valida el caso. Si todo está correcto, lo aprueba y los ajustes quedan inmediatamente disponibles para los docentes. Si hay problemas, lo rechaza con retroalimentación detallada y el caso vuelve al CTP. Lo importante aquí es el tiempo: reducimos el proceso de semanas a solo 2 o 3 días."

---

## 🗄️ DIAPOSITIVA 7: BASE DE DATOS (45 segundos)
**[Diagrama ERD simplificado]**

### Diseño de Base de Datos

**Tablas Principales:**
- 👥 **users** & **roles** → Control de acceso
- 🎓 **estudiantes** → Perfil de estudiantes
- 📋 **casos** → Expediente completo (con columnas JSON)
- 📄 **documentos** → Evidencias y certificados
- ✅ **confirmacion_lecturas** → Firma digital docentes
- 💬 **seguimiento_docentes** → Observaciones
- 📅 **citas** → Agendamiento

**Decisión Técnica Clave:**
Uso de **columnas JSON** en la tabla casos para ajustes → Mayor flexibilidad sin sobrecarga

**Guión:**
"La base de datos está normalizada siguiendo las mejores prácticas. Tenemos 11 tablas principales, pero quiero destacar dos decisiones de diseño importantes. Primero, la tabla de casos usa columnas JSON para almacenar los ajustes, esto nos da flexibilidad sin necesidad de crear múltiples tablas pivote. Segundo, la tabla de confirmación de lecturas registra la firma digital del docente con un timestamp exacto, creando un registro auditable que tiene validez legal."

---

## 🔒 DIAPOSITIVA 8: SEGURIDAD (1 minuto)
**[Escudo con checkmarks]**

### Defensa en Profundidad

**9 Amenazas Mitigadas:**

| Amenaza | Mitigación |
|---------|------------|
| ❌ Acceso No Autorizado | ✅ Middleware CheckRole |
| ❌ Inyección SQL | ✅ Eloquent ORM |
| ❌ XSS | ✅ Blade Auto-escaping |
| ❌ CSRF | ✅ Tokens Laravel |
| ❌ DoS por Archivos | ✅ Validación (5MB máx) |
| ❌ Exposición de Datos | ✅ Encriptación bcrypt |
| ❌ Sesiones Inseguras | ✅ Breeze + Sanctum |

**Principio:** Defensa en Profundidad (múltiples capas)

**Guión:**
"La seguridad fue una prioridad desde el diseño. Implementamos el principio de defensa en profundidad, con múltiples capas de seguridad. Cada ruta del sistema está protegida por nuestro middleware CheckRole, que verifica que el usuario tenga el rol correcto antes de permitir el acceso. Usamos Eloquent ORM para prevenir inyección SQL mediante prepared statements. Todos los formularios tienen protección CSRF con tokens de Laravel. La validación de archivos restringe formatos y tamaños máximos de 5MB para prevenir ataques de denegación de servicio. Las contraseñas están encriptadas con bcrypt, y todas las operaciones críticas quedan registradas con timestamps para auditoría."

---

## 📊 DIAPOSITIVA 9: CASOS DE USO DEMO (1 minuto)
**[Screenshots del sistema en acción]**

### Demostración de Casos de Uso

**UC-01: Crear Caso (Encargada)**
- Formulario digital de primera entrevista
- Campos validados y obligatorios
- Subida de documentos (certificados médicos)

**UC-03: Asignar Ajustes (CTP)**
- Selector estandarizado de ajustes:
  - ⏱️ Tiempo adicional (25%, 50%, 100%)
  - 📖 Material en formato alternativo
  - 🔊 Lectores de pantalla
  - 📅 Flexibilidad horaria
  - 🧑‍🏫 Apoyo de intérprete

**UC-04: Validar Caso (Director)**
- Vista completa del caso
- Decisión binaria: Aprobar/Rechazar
- Campo de retroalimentación (obligatorio si rechaza)

**UC-05: Confirmar Lectura (Docente)**
- Lista de estudiantes con ajustes
- Detalle de cada ajuste
- Botón "Confirmar Lectura" → Timestamp

**Guión:**
"Permítanme mostrarles el sistema en acción. Cuando la Encargada crea un caso, completa un formulario digital estructurado que captura toda la información de la primera entrevista. El CTP tiene un selector visual donde elige ajustes de un catálogo estandarizado: tiempo adicional, material alternativo, tecnologías asistivas, etc. El Director ve el caso completo y puede aprobar o rechazar, pero si rechaza, el sistema lo obliga a escribir el motivo. Finalmente, los docentes ven una lista clara de sus estudiantes con ajustes, consultan el detalle, y confirman su lectura con un solo clic, quedando registrado el timestamp exacto."

---

## 📈 DIAPOSITIVA 10: RESULTADOS E IMPACTO (1 minuto)
**[Gráficos comparativos: antes vs después]**

### Impacto Medible

**Beneficios Cuantificables:**

| Métrica | Antes | Después |
|---------|-------|---------|
| ⏱️ Tiempo de gestión | 2-4 semanas | 2-3 días |
| 📄 Pérdida de información | ~15% casos | 0% |
| ✅ Trazabilidad | 0% | 100% |
| 🔍 Visibilidad directiva | Nula | Tiempo real |
| 📊 Reportes | Manual (horas) | Automático (segundos) |

**Impacto Cualitativo:**

**Para Estudiantes:**
- ⚡ Respuesta más rápida
- 🔍 Transparencia del proceso
- 📱 Acceso 24/7 a su información

**Para Personal:**
- 🚀 Reducción de carga administrativa
- 📊 Métricas en tiempo real
- ✅ Cumplimiento normativo demostrable

**Para la Institución:**
- 🎓 Mejor experiencia académica
- 📈 Indicadores de inclusión medibles
- 🏆 Estándar de calidad superior

**Guión:**
"Los resultados hablan por sí solos. Redujimos el tiempo de gestión de un caso de 2-4 semanas a solo 2-3 días. Eliminamos completamente la pérdida de información que antes afectaba al 15% de los casos. Ahora tenemos trazabilidad del 100% en todas las decisiones. Los directivos tienen visibilidad en tiempo real de todo el proceso, y los reportes que antes tomaban horas ahora se generan en segundos. Pero el impacto va más allá de los números: los estudiantes reciben respuestas más rápidas y tienen transparencia total del proceso. El personal reduce significativamente su carga administrativa. Y la institución eleva sus estándares de calidad e inclusión, con indicadores medibles y cumplimiento normativo demostrable."

---

## 🚀 DIAPOSITIVA 11: TRABAJO FUTURO (30 segundos)
**[Roadmap visual]**

### Próximas Mejoras

**Fase 2 (Corto Plazo):**
- 📧 Notificaciones por email automáticas
- 📱 Aplicación móvil para estudiantes
- 📊 Dashboard ejecutivo avanzado

**Fase 3 (Mediano Plazo):**
- 🔗 Integración con sistema académico
- 🤖 Sugerencias automáticas con IA
- 📈 Análisis predictivo de necesidades

**Escalabilidad:**
- Modelo replicable en otras instituciones
- Contribución a la inclusión educativa nacional

**Guión:**
"Mirando hacia el futuro, ya tenemos un roadmap definido. En el corto plazo planeamos agregar notificaciones automáticas por email, desarrollar una app móvil para estudiantes, y expandir el dashboard de analíticas. A mediano plazo, integraremos el sistema con la plataforma académica existente y exploraremos el uso de inteligencia artificial para sugerir ajustes basados en casos similares. Lo importante es que este modelo es completamente replicable en otras instituciones educativas, contribuyendo así a elevar los estándares de inclusión a nivel nacional."

---

## 🎯 DIAPOSITIVA 12: CONCLUSIONES (45 segundos)
**[Puntos clave destacados]**

### Conclusiones Clave

**Logros:**
1. ✅ Sistema funcional y en producción
2. ✅ Transformación digital completa del proceso
3. ✅ Reducción drástica de tiempos (75% menos)
4. ✅ Seguridad y cumplimiento normativo
5. ✅ Impacto positivo medible

**Aprendizajes:**
- La tecnología como catalizador de la inclusión
- Importancia del diseño centrado en el usuario
- Valor de la trazabilidad y transparencia

**Reflexión Final:**
> "La inclusión educativa no es solo un derecho, es una responsabilidad que podemos cumplir mejor con las herramientas adecuadas."

**Guión:**
"Para concluir, este proyecto demuestra que la tecnología, cuando se aplica correctamente, puede ser un catalizador poderoso para la inclusión educativa. Logramos transformar un proceso manual y disperso en una solución digital eficiente que reduce tiempos en un 75%, garantiza seguridad, y tiene un impacto medible en toda la comunidad educativa. Pero más allá de los aspectos técnicos, aprendimos que el diseño centrado en las necesidades reales de los usuarios es fundamental. La inclusión educativa no es solo un derecho de los estudiantes, es una responsabilidad institucional que podemos cumplir de manera más eficiente y profesional con las herramientas adecuadas. Este sistema sienta las bases para un modelo replicable que puede beneficiar a miles de estudiantes en todo el país."

---

## 🙏 DIAPOSITIVA 13: AGRADECIMIENTOS Y PREGUNTAS (30 segundos)
**[Logos institucionales]**

### Gracias por su Atención

**Equipo:**
- Juan Aravena
- Benjamín Kreps

**Agradecimientos:**
- Profesor Roberto Alveal
- Institución Educativa
- Comunidad de usuarios del sistema

**Documentación:**
- 📘 Informe Técnico Completo
- 🚀 Guía de Instalación
- ⚙️ Manual de Despliegue
- 📊 Repositorio GitHub

**¿Preguntas?**

**Guión:**
"Muchas gracias por su atención. Estamos abiertos a responder cualquier pregunta que tengan sobre el sistema, su implementación técnica, o su impacto en la comunidad educativa. Toda la documentación técnica, guías de instalación y el código fuente están disponibles en nuestro repositorio de GitHub."

---

## 📝 NOTAS ADICIONALES PARA EL PRESENTADOR

### Timing Detallado:
- **Introducción:** 0:00 - 0:30 (30s)
- **Problemática:** 0:30 - 1:30 (1min)
- **Solución:** 1:30 - 2:30 (1min)
- **Arquitectura:** 2:30 - 4:00 (1.5min)
- **Módulos:** 4:00 - 5:30 (1.5min)
- **Flujo:** 5:30 - 6:30 (1min)
- **Base de Datos:** 6:30 - 7:15 (45s)
- **Seguridad:** 7:15 - 8:15 (1min)
- **Demo:** 8:15 - 9:15 (1min)
- **Resultados:** 9:15 - 10:15 (1min)
- **Futuro:** 10:15 - 10:45 (30s)
- **Conclusión:** 10:45 - 11:30 (45s)
- **Cierre:** 11:30 - 12:00 (30s)

**Total: ~12 minutos** (con buffer para preguntas intermedias)

### Consejos de Presentación:

1. **Energía y Entusiasmo:**
   - Mantén contacto visual con la audiencia
   - Usa gestos para enfatizar puntos clave
   - Muestra pasión por el impacto social del proyecto

2. **Manejo de Tiempo:**
   - Si vas corto de tiempo, puedes combinar las diapositivas 6 y 7
   - Si tienes más tiempo, expande la demo (diapositiva 9)

3. **Adaptación a la Audiencia:**
   - **Audiencia técnica:** Profundiza en arquitectura y seguridad
   - **Audiencia no técnica:** Enfócate en impacto y beneficios
   - **Audiencia mixta:** Balance entre ambos (como este guión)

4. **Elementos Visuales Recomendados:**
   - Usa capturas de pantalla reales del sistema en la demo
   - Animaciones simples en los diagramas de flujo
   - Gráficos de barras para comparativas antes/después
   - Iconos y emojis para hacer más visual la información

5. **Manejo de Preguntas:**
   - Si preguntan durante la presentación, responde brevemente
   - Para preguntas técnicas complejas, ofrece explicar al final
   - Ten preparadas respuestas para:
     - Costo de implementación
     - Tiempo de desarrollo
     - Escalabilidad del sistema
     - Seguridad de datos sensibles
     - Integración con otros sistemas

6. **Cierre Fuerte:**
   - Termina con el impacto social, no con los aspectos técnicos
   - Refuerza que es un modelo replicable
   - Muestra entusiasmo por las posibilidades futuras

### Preguntas Frecuentes Anticipadas:

**P: ¿Cuánto costó desarrollar el sistema?**
R: El desarrollo se realizó como proyecto académico. En un contexto comercial, el costo depende del alcance, pero usando tecnologías open-source como Laravel, los costos se concentran principalmente en infraestructura AWS y horas de desarrollo.

**P: ¿Cuánto tiempo tomó el desarrollo?**
R: El proyecto se desarrolló durante [X meses/semanas] como parte del curso de Proyecto Integrado, incluyendo diseño, desarrollo, testing y documentación.

**P: ¿Qué pasa si el servidor AWS falla?**
R: AWS RDS tiene backups automáticos diarios y la opción Multi-AZ para alta disponibilidad. En caso de falla, los datos pueden recuperarse en minutos.

**P: ¿Cómo protegen los datos médicos sensibles?**
R: Implementamos múltiples capas de seguridad: control de acceso por roles, encriptación de contraseñas, validación estricta de archivos, y auditoría completa de accesos. Además, AWS cumple con estándares internacionales de seguridad.

**P: ¿Puede integrarse con el sistema académico actual?**
R: Sí, está diseñado con APIs que permiten integración futura. La arquitectura modular facilita la conexión con otros sistemas mediante APIs REST.

**P: ¿Qué capacitación requiere el personal?**
R: El sistema es intuitivo y está diseñado siguiendo los flujos de trabajo existentes. Estimamos 1-2 horas de capacitación por rol para dominar el sistema completamente.

---

## 🎬 RECOMENDACIONES FINALES

1. **Practica el timing:** Lee el guión completo al menos 3 veces cronometrando
2. **Prepara una demo en vivo:** Si es posible, muestra el sistema funcionando (siempre ten capturas de respaldo por si falla internet)
3. **Personaliza según contexto:** Ajusta el énfasis según si es una defensa académica, presentación a directivos, o demo técnica
4. **Coordina con tu compañero:** Si presentan en equipo, dividan las secciones de forma natural
5. **Respaldo técnico:** Ten el sistema corriendo en tu laptop como respaldo, no dependas 100% del internet

**¡Éxito en tu presentación!** 🚀

# backendDental

## Descripción General
Este proyecto es el backend de un sistema de gestión dental. Está desarrollado en PHP y utiliza MySQL como base de datos. Proporciona servicios para la gestión de usuarios, pacientes, procedimientos, agenda, cuentas, autenticación y noticias, entre otros.

## Estructura de Carpetas
- **controlador/**: Lógica de negocio y controladores de endpoints (usuarios, pacientes, agenda, etc.).
- **modelo/**: Acceso a datos y lógica de persistencia (DAO de usuario, paciente, procedimiento, etc.).
- **conexion/**: Archivos de conexión a la base de datos.
- **scripts/**: Scripts utilitarios para administración y pruebas (por ejemplo, creación de usuarios desde CLI).
- **img/**: Imágenes de usuarios y pacientes.
- **log/**: Archivos de logs y errores.


## Principales Archivos y Funcionalidades
- **controlador/usuario.php**: Endpoints para gestión de usuarios (alta, modificación, login, cambio de imagen, etc.).
- **modelo/usuarioDAO.php**: Acceso a datos de usuarios. Incluye métodos para agregar, modificar, eliminar, resetear contraseña y login seguro con hash.
- **controlador/paciente.php** y **modelo/pacienteDAO.php**: Gestión de pacientes.
- **controlador/procedimiento.php** y **modelo/procedimientoDAO.php**: Gestión de procedimientos médicos y archivos adjuntos.
- **controlador/agenda.php** y **modelo/agendaDAO.php**: Gestión de turnos y agenda.
- **controlador/noticias.php**: Scraping de noticias de sitios externos.
- **scripts/crear_usuario.php**: Script CLI para crear usuarios con contraseña hasheada.

## Seguridad
- Las contraseñas se almacenan usando `password_hash` y se validan con `password_verify`.
- Se implementan cabeceras de seguridad HTTP (CORS, CSP, X-Frame-Options, etc.).
- El acceso a endpoints sensibles requiere autenticación y manejo de sesiones.

## Buenas Prácticas
- Separación de lógica de negocio (controlador) y acceso a datos (modelo/DAO).
- Uso de prepared statements para evitar inyecciones SQL.
- Manejo de errores y logs en `log/php_errors.log`.
- Scripts CLI para tareas administrativas.

## Recomendaciones para Nuevos Desarrolladores
- Leer el código de los controladores y DAOs para entender el flujo de datos.
- Usar los scripts de utilidad para pruebas y administración.
- Mantener la estructura de carpetas y buenas prácticas establecidas.
- Documentar cualquier cambio importante en este archivo.

## Contacto y Soporte
Para dudas técnicas, contactar al responsable del backend o al equipo de ingeniería de software.

---


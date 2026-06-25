# Gestión Académica - API Challenge

Aplicación web desarrollada con CodeIgniter 4 para administrar estudiantes,
materias, periodos y espacios curriculares.

## Funcionalidades

- CRUD de estudiantes.
- CRUD de materias.
- CRUD de periodos.
- CRUD de estados de espacios curriculares.
- CRUD de espacios curriculares.
- Asociación entre materias y espacios curriculares.
- Asociación entre estudiantes y espacios curriculares.
- Bajas lógicas mediante `deletedBy` y `deletedAt`.
- Reactivación de relaciones dadas de baja.
- Interfaz web con navegación lateral y respuestas JSON para las operaciones.

## Requisitos

- PHP 8.2 o superior.
- Composer.
- MySQL o MariaDB.
- Extensiones de PHP `intl`, `mbstring`, `mysqli` y `json`.

Para ejecutar los tests de ejemplo también se necesita la extensión `sqlite3`.

## Instalación

1. Clonar o descargar el proyecto.
2. Abrir una terminal en la carpeta `api-challenge`.
3. Instalar las dependencias:

```bash
composer install
```

4. Crear una base de datos MySQL llamada `api-challenge`.
5. Ejecutar en phpMyAdmin el script ubicado en `../db_api-challenge`.

## Configuración

Copiar el archivo `env` como `.env` y configurar la conexión:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = 127.0.0.1
database.default.database = api-challenge
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Los valores deben ajustarse según la instalación local de MySQL.

## Ejecución

Desde la carpeta `api-challenge`, iniciar el servidor:

```bash
php spark serve
```

La aplicación estará disponible en:

```text
http://localhost:8080
```

La ruta principal abre el panel de inicio, desde donde se puede acceder a todos
los módulos.

## Módulos y rutas

| Módulo | Ruta |
| --- | --- |
| Inicio | `/` |
| Estudiantes | `/estudiante` |
| Periodos | `/periodo` |
| Materias | `/materia` |
| Estados | `/estado-espacio-curricular` |
| Espacios curriculares | `/espacio-curricular` |
| Materias por espacio | `/materia-espacio-curricular` |
| Estudiantes por espacio | `/estudiante-espacio-curricular` |

## Endpoints del challenge

Los endpoints sugeridos en el enunciado están disponibles para probarse desde
Postman. Todas las solicitudes con body deben enviarse como JSON con el header
`Content-Type: application/json`.

### Crear estudiante

```http
POST /students
```

```json
{
    "nombre": "Juan",
    "apellido": "Pérez",
    "dni": "40123456",
    "fechaNacimiento": "2000-05-10"
}
```

El DNI y la fecha de nacimiento son campos adicionales obligatorios incorporados
al modelo del proyecto.

### Crear materia tradicional

```http
POST /subjects
```

```json
{
    "nombre": "Historia",
    "anio": 1
}
```

### Crear espacio curricular

```http
POST /curricular-spaces
```

```json
{
    "nombre": "Laboratorio de Ciencias Sociales I",
    "periodo": "anual"
}
```

Los períodos iniciales disponibles son `anual`, `primer_cuatrimestre` y
`segundo_cuatrimestre`.

### Asociar materia y espacio curricular

```http
POST /subjects/{subjectId}/curricular-spaces/{spaceId}
```

Esta solicitud no requiere body.

### Registrar o actualizar el estado

```http
POST /students/{studentId}/curricular-spaces/{spaceId}/status
```

```json
{
    "status": "aprobado"
}
```

Los valores permitidos son `sin_calificar`, `no_iniciado`, `aprobado` y
`en_proceso`.

## Colección de Postman

El directorio `postman` contiene una colección y un environment importables:

- `postman/API-Challenge.postman_collection.json`
- `postman/Local.postman_environment.json`

La colección ejecuta el flujo completo y comprueba automáticamente los cuatro
casos mínimos solicitados por el challenge. Las instrucciones están disponibles
en `postman/README.md`.

## Consulta de estados finales

El endpoint solicitado por el challenge calcula el estado de las materias de un
estudiante y conserva el detalle de los espacios curriculares considerados:

```http
GET /students/{studentId}/subjects-status
```

Ejemplo de respuesta:

```json
[
    {
        "materia": "Historia 1° Año",
        "estado": "en_proceso",
        "espacios_curriculares": [
            {
                "nombre": "Laboratorio de Ciencias Sociales I",
                "estado": "aprobado"
            },
            {
                "nombre": "Laboratorio de Ciencias Sociales II",
                "estado": "en_proceso"
            }
        ]
    }
]
```

Reglas aplicadas:

- Si todos los espacios están `aprobado`, la materia queda `aprobado`.
- Si alguno está `en_proceso`, la materia queda `en_proceso`.
- Si ninguno está en proceso y alguno está `no_iniciado`, queda `no_iniciado`.
- Los espacios sin estado registrado se consideran `sin_calificar`.

## Pruebas

Para ejecutar PHPUnit:

```bash
composer test
```

Los tests incluidos por CodeIgniter utilizan SQLite en memoria, por lo que la
extensión `sqlite3` debe estar habilitada en PHP.

## Estructura principal

- `app/Controllers`: recepción de solicitudes y respuestas HTTP.
- `app/Libraries`: reglas de negocio y construcción de respuestas.
- `app/Models`: acceso y persistencia de datos.
- `app/Entities`: representación de las entidades.
- `app/Views`: interfaz web.
- `app/Config/Routes.php`: definición de rutas.
- `../db_api-challenge`: script SQL de creación de la base de datos.

# Pruebas con Postman

## Importación

1. Levantar MySQL y ejecutar la aplicación:

```bash
php spark serve
```

2. En Postman, seleccionar **Import**.
3. Importar estos dos archivos:

- `API-Challenge.postman_collection.json`
- `Local.postman_environment.json`

4. Opcionalmente, seleccionar el environment **API Challenge - Local**. La
   colección también incluye `http://localhost:8080` como URL predeterminada.
5. Abrir la colección **API Challenge - Flujo completo**.
6. Presionar **Run collection** y ejecutar las solicitudes en el orden definido.

## Cobertura

La colección crea datos únicos en cada ejecución y valida automáticamente:

- creación de estudiante, materias y espacios curriculares;
- asociación de dos espacios con Historia y Geografía;
- registro de un espacio aprobado y otro en proceso;
- estado final `en_proceso`;
- trazabilidad de los espacios curriculares;
- impacto de un espacio compartido en dos materias;
- actualización del espacio pendiente a aprobado;
- recálculo final de ambas materias como `aprobado`.

Los registros creados quedan almacenados en la base de datos para poder
inspeccionarlos desde la interfaz.

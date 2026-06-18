<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Estudiantes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Estudiantes</h1>
                <p class="text-muted mb-0">Listado de estudiantes registrados</p>
            </div>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearEstudiante">
                Nuevo estudiante
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <strong>Listado</strong>
            </div>

            <div class="card-body" id="contenedorListado">

                <?php if (empty($estudiantes)): ?>

                    <div class="alert alert-info mb-0" id="mensajeSinEstudiantes">
                        No hay estudiantes registrados.
                    </div>

                <?php else: ?>

                    <div class="table-responsive" id="contenedorTablaEstudiantes">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaEstudiantes">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstudiantes">
                                <?php foreach ($estudiantes as $estudiante): ?>
                                    <tr>
                                        <td><?= esc($estudiante['id']) ?></td>
                                        <td><?= esc($estudiante['nombre']) ?></td>
                                        <td><?= esc($estudiante['apellido']) ?></td>
                                        <td><?= esc($estudiante['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($estudiante['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($estudiante['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- Modal crear estudiante -->
    <div class="modal fade" id="modalCrearEstudiante" tabindex="-1" aria-labelledby="modalCrearEstudianteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearEstudiante">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearEstudianteLabel">Nuevo estudiante</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                maxlength="50"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input
                                type="text"
                                class="form-control"
                                id="apellido"
                                name="apellido"
                                maxlength="50"
                                required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarEstudiante">
                            Guardar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Toasts -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toastMensaje" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastTexto">
                    Mensaje
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script>
        const formCrearEstudiante = document.getElementById('formCrearEstudiante');
        const btnGuardarEstudiante = document.getElementById('btnGuardarEstudiante');

        const modalCrearEstudianteElement = document.getElementById('modalCrearEstudiante');
        const modalCrearEstudiante = new bootstrap.Modal(modalCrearEstudianteElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        formCrearEstudiante.addEventListener('submit', async function(event) {
            event.preventDefault();

            const nombre = document.getElementById('nombre').value.trim();
            const apellido = document.getElementById('apellido').value.trim();

            if (!nombre || !apellido) {
                mostrarToast('El nombre y el apellido son obligatorios.', 'error');
                return;
            }

            btnGuardarEstudiante.disabled = true;
            btnGuardarEstudiante.innerText = 'Guardando...';

            try {
                const response = await fetch('/estudiante/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        apellido: apellido
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo crear el estudiante.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const estudiante = result.data;

                agregarEstudianteATabla(estudiante);

                formCrearEstudiante.reset();
                modalCrearEstudiante.hide();

                mostrarToast('Estudiante creado correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al crear el estudiante.', 'error');
            } finally {
                btnGuardarEstudiante.disabled = false;
                btnGuardarEstudiante.innerText = 'Guardar';
            }
        });

        function agregarEstudianteATabla(estudiante) {
            let contenedorTabla = document.getElementById('contenedorTablaEstudiantes');
            let tbody = document.getElementById('tbodyEstudiantes');
            const mensajeSinEstudiantes = document.getElementById('mensajeSinEstudiantes');

            if (mensajeSinEstudiantes) {
                mensajeSinEstudiantes.remove();
            }

            if (!contenedorTabla) {
                const contenedorListado = document.getElementById('contenedorListado');

                contenedorListado.innerHTML = `
                    <div class="table-responsive" id="contenedorTablaEstudiantes">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaEstudiantes">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstudiantes"></tbody>
                        </table>
                    </div>
                `;

                tbody = document.getElementById('tbodyEstudiantes');
            }

            const fila = document.createElement('tr');

            fila.innerHTML = `
                <td>${escapeHtml(estudiante.id)}</td>
                <td>${escapeHtml(estudiante.nombre)}</td>
                <td>${escapeHtml(estudiante.apellido)}</td>
                <td>${escapeHtml(estudiante.createdBy ?? '-')}</td>
                <td>${formatearFecha(estudiante.createdAt)}</td>
            `;

            tbody.appendChild(fila);
        }

        function mostrarToast(mensaje, tipo) {
            toastTexto.innerText = mensaje;

            toastElement.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-info');

            if (tipo === 'success') {
                toastElement.classList.add('text-bg-success');
            } else if (tipo === 'error') {
                toastElement.classList.add('text-bg-danger');
            } else {
                toastElement.classList.add('text-bg-info');
            }

            toast.show();
        }

        function formatearFecha(fecha) {
            if (!fecha) {
                return '-';
            }

            const date = new Date(fecha);

            if (isNaN(date.getTime())) {
                return fecha;
            }

            return date.toLocaleString('es-AR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }
    </script>

</body>

</html>
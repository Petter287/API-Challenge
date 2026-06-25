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

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light with-app-shell">

    <?= view('layout/sidebar') ?>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Estudiantes</h1>
                <p class="text-muted mb-0">Listado de estudiantes registrados</p>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnNuevoEstudiante" data-bs-toggle="modal" data-bs-target="#modalCrearEstudiante">
                    <i class="bi bi-person-plus"></i>
                    Nuevo
                </button>
                <button type="button" class="btn btn-outline-secondary" id="btnActualizarGrilla">
                    <i class="bi bi-arrow-clockwise"></i>
                    Actualizar
                </button>
            </div>
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
                                    <th>DNI</th>
                                    <th>Fecha nacimiento</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstudiantes">
                                <?php foreach ($estudiantes as $estudiante): ?>
                                    <tr data-id="<?= esc($estudiante['id']) ?>">
                                        <td><?= esc($estudiante['id']) ?></td>
                                        <td><?= esc($estudiante['nombre']) ?></td>
                                        <td><?= esc($estudiante['apellido']) ?></td>
                                        <td><?= esc($estudiante['dni']) ?></td>
                                        <td>
                                            <?= !empty($estudiante['fechaNacimiento'])
                                                ? date('d/m/Y', strtotime($estudiante['fechaNacimiento']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td><?= esc($estudiante['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($estudiante['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($estudiante['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm btn-editar-estudiante"
                                                title="Editar"
                                                data-id="<?= esc($estudiante['id']) ?>"
                                                data-nombre="<?= esc($estudiante['nombre']) ?>"
                                                data-apellido="<?= esc($estudiante['apellido']) ?>"
                                                data-dni="<?= esc($estudiante['dni']) ?>"
                                                data-fecha-nacimiento="<?= esc($estudiante['fechaNacimiento']) ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-eliminar-estudiante"
                                                title="Eliminar"
                                                data-id="<?= esc($estudiante['id']) ?>"
                                                data-nombre="<?= esc($estudiante['nombre']) ?>"
                                                data-apellido="<?= esc($estudiante['apellido']) ?>"
                                                data-dni="<?= esc($estudiante['dni']) ?>"
                                                data-fecha-nacimiento="<?= esc($estudiante['fechaNacimiento']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
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

    <!-- Modal eliminar estudiante -->
    <div class="modal fade" id="modalEliminarEstudiante" tabindex="-1" aria-labelledby="modalEliminarEstudianteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarEstudianteLabel">Eliminar estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="idEstudianteEliminar">
                    <p class="mb-0">
                        ¿Seguro que querés dar de baja a
                        <strong id="nombreEstudianteEliminar"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarEstudiante">
                        <i class="bi bi-trash"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal crear estudiante -->
    <div class="modal fade" id="modalCrearEstudiante" tabindex="-1" aria-labelledby="modalCrearEstudianteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearEstudiante">
                    <input type="hidden" id="idEstudiante" name="idEstudiante">

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

                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input
                                type="text"
                                class="form-control"
                                id="dni"
                                name="dni"
                                maxlength="20"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                            <input
                                type="date"
                                class="form-control"
                                id="fechaNacimiento"
                                name="fechaNacimiento"
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
        let modoForm = 'crear';

        const formCrearEstudiante = document.getElementById('formCrearEstudiante');
        const btnGuardarEstudiante = document.getElementById('btnGuardarEstudiante');
        const btnNuevoEstudiante = document.getElementById('btnNuevoEstudiante');
        const btnConfirmarEliminarEstudiante = document.getElementById('btnConfirmarEliminarEstudiante');
        const modalCrearEstudianteLabel = document.getElementById('modalCrearEstudianteLabel');

        const modalCrearEstudianteElement = document.getElementById('modalCrearEstudiante');
        const modalCrearEstudiante = new bootstrap.Modal(modalCrearEstudianteElement);

        const modalEliminarEstudianteElement = document.getElementById('modalEliminarEstudiante');
        const modalEliminarEstudiante = new bootstrap.Modal(modalEliminarEstudianteElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        btnNuevoEstudiante.addEventListener('click', function() {
            prepararModalCrear();
        });

        const btnActualizarGrilla = document.getElementById('btnActualizarGrilla');

        btnActualizarGrilla.addEventListener('click', async function() {
            btnActualizarGrilla.disabled = true;

            try {
                const response = await fetch('/students/list', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    mostrarToast(result.message ?? 'No se pudo actualizar la grilla.', 'error');
                    return;
                }

                renderizarTablaEstudiantes(result.data);
                mostrarToast('Grilla actualizada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error al actualizar la grilla.', 'error');
            } finally {
                btnActualizarGrilla.disabled = false;
            }
        });

        document.addEventListener('click', function(event) {
            const botonEditar = event.target.closest('.btn-editar-estudiante');

            if (!botonEditar) {
                return;
            }

            prepararModalEditar({
                id: botonEditar.dataset.id,
                nombre: botonEditar.dataset.nombre,
                apellido: botonEditar.dataset.apellido,
                dni: botonEditar.dataset.dni,
                fechaNacimiento: botonEditar.dataset.fechaNacimiento
            });
        });

        document.addEventListener('click', function(event) {
            const botonEliminar = event.target.closest('.btn-eliminar-estudiante');

            if (!botonEliminar) {
                return;
            }

            prepararModalEliminar({
                id: botonEliminar.dataset.id,
                nombre: botonEliminar.dataset.nombre,
                apellido: botonEliminar.dataset.apellido,
                dni: botonEliminar.dataset.dni,
                fechaNacimiento: botonEliminar.dataset.fechaNacimiento
            });
        });

        formCrearEstudiante.addEventListener('submit', async function(event) {
            event.preventDefault();

            const idEstudiante = document.getElementById('idEstudiante').value;
            const nombre = document.getElementById('nombre').value.trim();
            const apellido = document.getElementById('apellido').value.trim();
            const dni = document.getElementById('dni').value.trim();
            const fechaNacimiento = document.getElementById('fechaNacimiento').value;

            if (!nombre || !apellido || !dni || !fechaNacimiento) {
                mostrarToast('El nombre, el apellido, el DNI y la fecha de nacimiento son obligatorios.', 'error');
                return;
            }

            btnGuardarEstudiante.disabled = true;
            btnGuardarEstudiante.innerText = 'Guardando...';

            try {
                const esEdicion = modoForm === 'editar';
                const url = modoForm === 'editar' ?
                    `/students/${idEstudiante}` :
                    '/students';

                const response = await fetch(url, {
                    method: esEdicion ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        apellido: apellido,
                        dni: dni,
                        fechaNacimiento: fechaNacimiento
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo crear el estudiante.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const estudiante = result.data;

                if (esEdicion) {
                    actualizarEstudianteEnTabla(estudiante);
                } else {
                    agregarEstudianteATabla(estudiante);
                }

                formCrearEstudiante.reset();
                modalCrearEstudiante.hide();
                prepararModalCrear();

                mostrarToast(
                    esEdicion ?
                    'Estudiante actualizado correctamente.' :
                    'Estudiante creado correctamente.',
                    'success'
                );

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al guardar el estudiante.', 'error');
            } finally {
                btnGuardarEstudiante.disabled = false;
                btnGuardarEstudiante.innerText = modoForm === 'editar' ? 'Actualizar' : 'Guardar';
            }
        });

        btnConfirmarEliminarEstudiante.addEventListener('click', async function() {
            const idEstudiante = document.getElementById('idEstudianteEliminar').value;

            if (!idEstudiante) {
                mostrarToast('No se pudo identificar el estudiante a eliminar.', 'error');
                return;
            }

            btnConfirmarEliminarEstudiante.disabled = true;
            btnConfirmarEliminarEstudiante.innerText = 'Eliminando...';

            try {
                const response = await fetch(`/students/${idEstudiante}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo eliminar el estudiante.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                eliminarEstudianteDeTabla(idEstudiante);
                modalEliminarEstudiante.hide();

                mostrarToast('Estudiante eliminado correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al eliminar el estudiante.', 'error');
            } finally {
                btnConfirmarEliminarEstudiante.disabled = false;
                btnConfirmarEliminarEstudiante.innerHTML = `
                    <i class="bi bi-trash"></i>
                    Eliminar
                `;
            }
        });

        function prepararModalCrear() {
            modoForm = 'crear';
            formCrearEstudiante.reset();
            document.getElementById('idEstudiante').value = '';
            modalCrearEstudianteLabel.innerText = 'Nuevo estudiante';
            btnGuardarEstudiante.innerText = 'Guardar';
        }

        function prepararModalEditar(estudiante) {
            modoForm = 'editar';
            document.getElementById('idEstudiante').value = estudiante.id;
            document.getElementById('nombre').value = estudiante.nombre;
            document.getElementById('apellido').value = estudiante.apellido;
            document.getElementById('dni').value = estudiante.dni;
            document.getElementById('fechaNacimiento').value = estudiante.fechaNacimiento;
            modalCrearEstudianteLabel.innerText = 'Editar estudiante';
            btnGuardarEstudiante.innerText = 'Actualizar';
            modalCrearEstudiante.show();
        }

        function prepararModalEliminar(estudiante) {
            document.getElementById('idEstudianteEliminar').value = estudiante.id;
            document.getElementById('nombreEstudianteEliminar').innerText = `${estudiante.nombre} ${estudiante.apellido}`;
            modalEliminarEstudiante.show();
        }

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
                                    <th>DNI</th>
                                    <th>Fecha nacimiento</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstudiantes"></tbody>
                        </table>
                    </div>
                `;

                tbody = document.getElementById('tbodyEstudiantes');
            }

            const fila = document.createElement('tr');
            fila.dataset.id = estudiante.id;

            fila.innerHTML = obtenerHtmlFilaEstudiante(estudiante);

            tbody.appendChild(fila);
        }

        function actualizarEstudianteEnTabla(estudiante) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(estudiante.id))}"]`);

            if (!fila) {
                return;
            }

            fila.innerHTML = obtenerHtmlFilaEstudiante(estudiante);
        }

        function eliminarEstudianteDeTabla(idEstudiante) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(idEstudiante))}"]`);

            if (!fila) {
                return;
            }

            fila.remove();
            mostrarMensajeSinEstudiantesSiCorresponde();
        }

        function mostrarMensajeSinEstudiantesSiCorresponde() {
            const tbody = document.getElementById('tbodyEstudiantes');

            if (!tbody || tbody.children.length > 0) {
                return;
            }

            const contenedorTabla = document.getElementById('contenedorTablaEstudiantes');
            const contenedorListado = document.getElementById('contenedorListado');

            if (contenedorTabla) {
                contenedorTabla.remove();
            }

            contenedorListado.innerHTML = `
                <div class="alert alert-info mb-0" id="mensajeSinEstudiantes">
                    No hay estudiantes registrados.
                </div>
            `;
        }

        function obtenerHtmlFilaEstudiante(estudiante) {
            return `
                <td>${escapeHtml(estudiante.id)}</td>
                <td>${escapeHtml(estudiante.nombre)}</td>
                <td>${escapeHtml(estudiante.apellido)}</td>
                <td>${escapeHtml(estudiante.dni)}</td>
                <td>${formatearFechaNacimiento(estudiante.fechaNacimiento)}</td>
                <td>${escapeHtml(estudiante.createdBy ?? '-')}</td>
                <td>${formatearFecha(estudiante.createdAt)}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-editar-estudiante"
                        title="Editar estudiante"
                        data-id="${escapeHtml(estudiante.id)}"
                        data-nombre="${escapeHtml(estudiante.nombre)}"
                        data-apellido="${escapeHtml(estudiante.apellido)}"
                        data-dni="${escapeHtml(estudiante.dni)}"
                        data-fecha-nacimiento="${escapeHtml(estudiante.fechaNacimiento)}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-eliminar-estudiante"
                        title="Eliminar estudiante"
                        data-id="${escapeHtml(estudiante.id)}"
                        data-nombre="${escapeHtml(estudiante.nombre)}"
                        data-apellido="${escapeHtml(estudiante.apellido)}"
                        data-dni="${escapeHtml(estudiante.dni)}"
                        data-fecha-nacimiento="${escapeHtml(estudiante.fechaNacimiento)}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
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

            const dia = String(date.getDate()).padStart(2, '0');
            const mes = String(date.getMonth() + 1).padStart(2, '0');
            const anio = date.getFullYear();
            const hora = String(date.getHours()).padStart(2, '0');
            const minutos = String(date.getMinutes()).padStart(2, '0');

            return `${dia}/${mes}/${anio} ${hora}:${minutos}`;
        }

        function formatearFechaNacimiento(fecha) {
            if (!fecha) {
                return '-';
            }

            const partes = String(fecha).split('-');

            if (partes.length !== 3) {
                return fecha;
            }

            return `${partes[2]}/${partes[1]}/${partes[0]}`;
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

        function renderizarTablaEstudiantes(estudiantes) {
            const contenedorListado = document.getElementById('contenedorListado');

            if (!estudiantes || estudiantes.length === 0) {
                contenedorListado.innerHTML = `
                    <div class="alert alert-info mb-0" id="mensajeSinEstudiantes">
                        No hay estudiantes registrados.
                    </div>
                `;
                return;
            }

            contenedorListado.innerHTML = `
                <div class="table-responsive" id="contenedorTablaEstudiantes">
                    <table class="table table-striped table-hover align-middle mb-0" id="tablaEstudiantes">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>DNI</th>
                                <th>Fecha nacimiento</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyEstudiantes"></tbody>
                    </table>
                </div>
            `;

            const tbody = document.getElementById('tbodyEstudiantes');

            estudiantes.forEach(function(estudiante) {
                const fila = document.createElement('tr');
                fila.dataset.id = estudiante.id;
                fila.innerHTML = obtenerHtmlFilaEstudiante(estudiante);
                tbody.appendChild(fila);
            });
        }
    </script>

</body>

</html>

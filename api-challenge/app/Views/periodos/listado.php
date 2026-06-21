<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Periodos</title>
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
                <h1 class="h3 mb-1">Periodos</h1>
                <p class="text-muted mb-0">Listado de periodos registrados</p>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnNuevoPeriodo" data-bs-toggle="modal" data-bs-target="#modalCrearPeriodo">
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

                <?php if (empty($periodos)): ?>

                    <div class="alert alert-info mb-0" id="mensajeSinPeriodos">
                        No hay periodos registrados.
                    </div>

                <?php else: ?>

                    <div class="table-responsive" id="contenedorTablaPeriodos">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaPeriodos">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Nombre</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPeriodos">
                                <?php foreach ($periodos as $periodo): ?>
                                    <tr data-id="<?= esc($periodo['id']) ?>">
                                        <td><?= esc($periodo['id']) ?></td>
                                        <td><?= esc($periodo['nombre']) ?></td>
                                        <td><?= esc($periodo['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($periodo['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($periodo['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm btn-editar-periodo"
                                                title="Editar"
                                                data-id="<?= esc($periodo['id']) ?>"
                                                data-nombre="<?= esc($periodo['nombre']) ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-eliminar-periodo"
                                                title="Eliminar"
                                                data-id="<?= esc($periodo['id']) ?>"
                                                data-nombre="<?= esc($periodo['nombre']) ?>">
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

    <!-- Modal eliminar periodo -->
    <div class="modal fade" id="modalEliminarPeriodo" tabindex="-1" aria-labelledby="modalEliminarPeriodoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarPeriodoLabel">Eliminar periodo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="idPeriodoEliminar">
                    <p class="mb-0">
                        ¿Seguro que querés dar de baja a
                        <strong id="nombrePeriodoEliminar"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarPeriodo">
                        <i class="bi bi-trash"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal crear periodo -->
    <div class="modal fade" id="modalCrearPeriodo" tabindex="-1" aria-labelledby="modalCrearPeriodoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearPeriodo">
                    <input type="hidden" id="idPeriodo" name="idPeriodo">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearPeriodoLabel">Nuevo periodo</h5>
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

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarPeriodo">
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

        const formCrearPeriodo = document.getElementById('formCrearPeriodo');
        const btnGuardarPeriodo = document.getElementById('btnGuardarPeriodo');
        const btnNuevoPeriodo = document.getElementById('btnNuevoPeriodo');
        const btnConfirmarEliminarPeriodo = document.getElementById('btnConfirmarEliminarPeriodo');
        const modalCrearPeriodoLabel = document.getElementById('modalCrearPeriodoLabel');

        const modalCrearPeriodoElement = document.getElementById('modalCrearPeriodo');
        const modalCrearPeriodo = new bootstrap.Modal(modalCrearPeriodoElement);

        const modalEliminarPeriodoElement = document.getElementById('modalEliminarPeriodo');
        const modalEliminarPeriodo = new bootstrap.Modal(modalEliminarPeriodoElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        btnNuevoPeriodo.addEventListener('click', function() {
            prepararModalCrear();
        });

        const btnActualizarGrilla = document.getElementById('btnActualizarGrilla');

        btnActualizarGrilla.addEventListener('click', async function() {
            btnActualizarGrilla.disabled = true;

            try {
                const response = await fetch('/periodo/list', {
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

                renderizarTablaPeriodos(result.data);
                mostrarToast('Grilla actualizada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error al actualizar la grilla.', 'error');
            } finally {
                btnActualizarGrilla.disabled = false;
            }
        });

        document.addEventListener('click', function(event) {
            const botonEditar = event.target.closest('.btn-editar-periodo');

            if (!botonEditar) {
                return;
            }

            prepararModalEditar({
                id: botonEditar.dataset.id,
                nombre: botonEditar.dataset.nombre,
            });
        });

        document.addEventListener('click', function(event) {
            const botonEliminar = event.target.closest('.btn-eliminar-periodo');

            if (!botonEliminar) {
                return;
            }

            prepararModalEliminar({
                id: botonEliminar.dataset.id,
                nombre: botonEliminar.dataset.nombre,
            });
        });

        formCrearPeriodo.addEventListener('submit', async function(event) {
            event.preventDefault();

            const idPeriodo = document.getElementById('idPeriodo').value;
            const nombre = document.getElementById('nombre').value.trim();

            if (!nombre) {
                mostrarToast('El nombre es obligatorio.', 'error');
                return;
            }

            btnGuardarPeriodo.disabled = true;
            btnGuardarPeriodo.innerText = 'Guardando...';

            try {
                const esEdicion = modoForm === 'editar';
                const url = modoForm === 'editar' ?
                    `/periodo/update/${idPeriodo}` :
                    '/periodo/create';

                const response = await fetch(url, {
                    method: esEdicion ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nombre: nombre
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo crear el periodo.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const periodo = result.data;

                if (esEdicion) {
                    actualizarPeriodoEnTabla(periodo);
                } else {
                    agregarPeriodoATabla(periodo);
                }

                formCrearPeriodo.reset();
                modalCrearPeriodo.hide();
                prepararModalCrear();

                mostrarToast(
                    esEdicion ?
                    'Periodo actualizado correctamente.' :
                    'Periodo creado correctamente.',
                    'success'
                );

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al guardar el periodo.', 'error');
            } finally {
                btnGuardarPeriodo.disabled = false;
                btnGuardarPeriodo.innerText = modoForm === 'editar' ? 'Actualizar' : 'Guardar';
            }
        });

        btnConfirmarEliminarPeriodo.addEventListener('click', async function() {
            const idPeriodo = document.getElementById('idPeriodoEliminar').value;

            if (!idPeriodo) {
                mostrarToast('No se pudo identificar el periodo a eliminar.', 'error');
                return;
            }

            btnConfirmarEliminarPeriodo.disabled = true;
            btnConfirmarEliminarPeriodo.innerText = 'Eliminando...';

            try {
                const response = await fetch(`/periodo/delete/${idPeriodo}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo eliminar el periodo.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                eliminarPeriodoDeTabla(idPeriodo);
                modalEliminarPeriodo.hide();

                mostrarToast('Periodo eliminado correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al eliminar el periodo.', 'error');
            } finally {
                btnConfirmarEliminarPeriodo.disabled = false;
                btnConfirmarEliminarPeriodo.innerHTML = `
                    <i class="bi bi-trash"></i>
                    Eliminar
                `;
            }
        });

        function prepararModalCrear() {
            modoForm = 'crear';
            formCrearPeriodo.reset();
            document.getElementById('idPeriodo').value = '';
            modalCrearPeriodoLabel.innerText = 'Nuevo periodo';
            btnGuardarPeriodo.innerText = 'Guardar';
        }

        function prepararModalEditar(periodo) {
            modoForm = 'editar';
            document.getElementById('idPeriodo').value = periodo.id;
            document.getElementById('nombre').value = periodo.nombre;
            modalCrearPeriodoLabel.innerText = 'Editar periodo';
            btnGuardarPeriodo.innerText = 'Actualizar';
            modalCrearPeriodo.show();
        }

        function prepararModalEliminar(periodo) {
            document.getElementById('idPeriodoEliminar').value = periodo.id;
            document.getElementById('nombrePeriodoEliminar').innerText = periodo.nombre;
            modalEliminarPeriodo.show();
        }

        function agregarPeriodoATabla(periodo) {
            let contenedorTabla = document.getElementById('contenedorTablaPeriodos');
            let tbody = document.getElementById('tbodyPeriodos');
            const mensajeSinPeriodos = document.getElementById('mensajeSinPeriodos');

            if (mensajeSinPeriodos) {
                mensajeSinPeriodos.remove();
            }

            if (!contenedorTabla) {
                const contenedorListado = document.getElementById('contenedorListado');

                contenedorListado.innerHTML = `
                    <div class="table-responsive" id="contenedorTablaPeriodos">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaPeriodos">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Nombre</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPeriodos"></tbody>
                        </table>
                    </div>
                `;

                tbody = document.getElementById('tbodyPeriodos');
            }

            const fila = document.createElement('tr');
            fila.dataset.id = periodo.id;

            fila.innerHTML = obtenerHtmlFilaPeriodo(periodo);

            tbody.appendChild(fila);
        }

        function actualizarPeriodoEnTabla(periodo) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(periodo.id))}"]`);

            if (!fila) {
                return;
            }

            fila.innerHTML = obtenerHtmlFilaPeriodo(periodo);
        }

        function eliminarPeriodoDeTabla(idPeriodo) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(idPeriodo))}"]`);

            if (!fila) {
                return;
            }

            fila.remove();
            mostrarMensajeSinPeriodosSiCorresponde();
        }

        function mostrarMensajeSinPeriodosSiCorresponde() {
            const tbody = document.getElementById('tbodyPeriodos');

            if (!tbody || tbody.children.length > 0) {
                return;
            }

            const contenedorTabla = document.getElementById('contenedorTablaPeriodos');
            const contenedorListado = document.getElementById('contenedorListado');

            if (contenedorTabla) {
                contenedorTabla.remove();
            }

            contenedorListado.innerHTML = `
                <div class="alert alert-info mb-0" id="mensajeSinPeriodos">
                    No hay Periodos registrados.
                </div>
            `;
        }

        function obtenerHtmlFilaPeriodo(periodo) {
            return `
                <td>${escapeHtml(periodo.id)}</td>
                <td>${escapeHtml(periodo.nombre)}</td>
                <td>${escapeHtml(periodo.createdBy ?? '-')}</td>
                <td>${formatearFecha(periodo.createdAt)}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-editar-periodo"
                        title="Editar periodo"
                        data-id="${escapeHtml(periodo.id)}"
                        data-nombre="${escapeHtml(periodo.nombre)}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-eliminar-periodo"
                        title="Eliminar periodo"
                        data-id="${escapeHtml(periodo.id)}"
                        data-nombre="${escapeHtml(periodo.nombre)}">
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

        function renderizarTablaPeriodos(periodos) {
            const contenedorListado = document.getElementById('contenedorListado');

            if (!periodos || periodos.length === 0) {
                contenedorListado.innerHTML = `
                    <div class="alert alert-info mb-0" id="mensajeSinPeriodos">
                        No hay periodos registrados.
                    </div>
                `;
                return;
            }

            contenedorListado.innerHTML = `
                <div class="table-responsive" id="contenedorTablaPeriodos">
                    <table class="table table-striped table-hover align-middle mb-0" id="tablaPeriodos">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nombre</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyPeriodos"></tbody>
                    </table>
                </div>
            `;

            const tbody = document.getElementById('tbodyPeriodos');

            periodos.forEach(function(periodo) {
                const fila = document.createElement('tr');
                fila.dataset.id = periodo.id;
                fila.innerHTML = obtenerHtmlFilaPeriodo(periodo);
                tbody.appendChild(fila);
            });
        }
    </script>

</body>

</html>

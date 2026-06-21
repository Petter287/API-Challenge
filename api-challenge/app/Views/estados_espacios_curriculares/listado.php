<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Estados de Espacio Curricular</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                <h1 class="h3 mb-1">Estados de espacio curricular</h1>
                <p class="text-muted mb-0">Listado de estados registrados</p>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnNuevoEstado" data-bs-toggle="modal" data-bs-target="#modalCrearEstado">
                    <i class="bi bi-plus-circle"></i>
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

                <?php if (empty($estados)): ?>

                    <div class="alert alert-info mb-0" id="mensajeSinEstados">
                        No hay estados registrados.
                    </div>

                <?php else: ?>

                    <div class="table-responsive" id="contenedorTablaEstados">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaEstados">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Estado</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEstados">
                                <?php foreach ($estados as $estado): ?>
                                    <tr data-id="<?= esc($estado['id']) ?>">
                                        <td><?= esc($estado['id']) ?></td>
                                        <td><?= esc($estado['estado']) ?></td>
                                        <td><?= esc($estado['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($estado['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($estado['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm btn-editar-estado"
                                                title="Editar"
                                                data-id="<?= esc($estado['id']) ?>"
                                                data-estado="<?= esc($estado['estado']) ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-eliminar-estado"
                                                title="Eliminar"
                                                data-id="<?= esc($estado['id']) ?>"
                                                data-estado="<?= esc($estado['estado']) ?>">
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

    <div class="modal fade" id="modalEliminarEstado" tabindex="-1" aria-labelledby="modalEliminarEstadoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarEstadoLabel">Eliminar estado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="idEstadoEliminar">
                    <p class="mb-0">
                        ¿Seguro que querés dar de baja a
                        <strong id="nombreEstadoEliminar"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarEstado">
                        <i class="bi bi-trash"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrearEstado" tabindex="-1" aria-labelledby="modalCrearEstadoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearEstado">
                    <input type="hidden" id="idEstado" name="idEstado">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearEstadoLabel">Nuevo estado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input
                                type="text"
                                class="form-control"
                                id="estado"
                                name="estado"
                                maxlength="50"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarEstado">
                            Guardar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

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

        const formCrearEstado = document.getElementById('formCrearEstado');
        const btnGuardarEstado = document.getElementById('btnGuardarEstado');
        const btnNuevoEstado = document.getElementById('btnNuevoEstado');
        const btnConfirmarEliminarEstado = document.getElementById('btnConfirmarEliminarEstado');
        const modalCrearEstadoLabel = document.getElementById('modalCrearEstadoLabel');

        const modalCrearEstadoElement = document.getElementById('modalCrearEstado');
        const modalCrearEstado = new bootstrap.Modal(modalCrearEstadoElement);

        const modalEliminarEstadoElement = document.getElementById('modalEliminarEstado');
        const modalEliminarEstado = new bootstrap.Modal(modalEliminarEstadoElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        btnNuevoEstado.addEventListener('click', function() {
            prepararModalCrear();
        });

        const btnActualizarGrilla = document.getElementById('btnActualizarGrilla');

        btnActualizarGrilla.addEventListener('click', async function() {
            btnActualizarGrilla.disabled = true;

            try {
                const response = await fetch('/estado-espacio-curricular/list', {
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

                renderizarTablaEstados(result.data);
                mostrarToast('Grilla actualizada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error al actualizar la grilla.', 'error');
            } finally {
                btnActualizarGrilla.disabled = false;
            }
        });

        document.addEventListener('click', function(event) {
            const botonEditar = event.target.closest('.btn-editar-estado');

            if (!botonEditar) {
                return;
            }

            prepararModalEditar({
                id: botonEditar.dataset.id,
                estado: botonEditar.dataset.estado
            });
        });

        document.addEventListener('click', function(event) {
            const botonEliminar = event.target.closest('.btn-eliminar-estado');

            if (!botonEliminar) {
                return;
            }

            prepararModalEliminar({
                id: botonEliminar.dataset.id,
                estado: botonEliminar.dataset.estado
            });
        });

        formCrearEstado.addEventListener('submit', async function(event) {
            event.preventDefault();

            const idEstado = document.getElementById('idEstado').value;
            const estado = document.getElementById('estado').value.trim();

            if (!estado) {
                mostrarToast('El estado es obligatorio.', 'error');
                return;
            }

            btnGuardarEstado.disabled = true;
            btnGuardarEstado.innerText = 'Guardando...';

            try {
                const esEdicion = modoForm === 'editar';
                const url = esEdicion
                    ? `/estado-espacio-curricular/update/${idEstado}`
                    : '/estado-espacio-curricular/create';

                const response = await fetch(url, {
                    method: esEdicion ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        estado: estado
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo guardar el estado.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const estadoEspacioCurricular = result.data;

                if (esEdicion) {
                    actualizarEstadoEnTabla(estadoEspacioCurricular);
                } else {
                    agregarEstadoATabla(estadoEspacioCurricular);
                }

                formCrearEstado.reset();
                modalCrearEstado.hide();
                prepararModalCrear();

                mostrarToast(
                    esEdicion
                        ? 'Estado actualizado correctamente.'
                        : 'Estado creado correctamente.',
                    'success'
                );

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al guardar el estado.', 'error');
            } finally {
                btnGuardarEstado.disabled = false;
                btnGuardarEstado.innerText = modoForm === 'editar' ? 'Actualizar' : 'Guardar';
            }
        });

        btnConfirmarEliminarEstado.addEventListener('click', async function() {
            const idEstado = document.getElementById('idEstadoEliminar').value;

            if (!idEstado) {
                mostrarToast('No se pudo identificar el estado a eliminar.', 'error');
                return;
            }

            btnConfirmarEliminarEstado.disabled = true;
            btnConfirmarEliminarEstado.innerText = 'Eliminando...';

            try {
                const response = await fetch(`/estado-espacio-curricular/delete/${idEstado}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo eliminar el estado.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                eliminarEstadoDeTabla(idEstado);
                modalEliminarEstado.hide();

                mostrarToast('Estado eliminado correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al eliminar el estado.', 'error');
            } finally {
                btnConfirmarEliminarEstado.disabled = false;
                btnConfirmarEliminarEstado.innerHTML = `
                    <i class="bi bi-trash"></i>
                    Eliminar
                `;
            }
        });

        function prepararModalCrear() {
            modoForm = 'crear';
            formCrearEstado.reset();
            document.getElementById('idEstado').value = '';
            modalCrearEstadoLabel.innerText = 'Nuevo estado';
            btnGuardarEstado.innerText = 'Guardar';
        }

        function prepararModalEditar(estadoEspacioCurricular) {
            modoForm = 'editar';
            document.getElementById('idEstado').value = estadoEspacioCurricular.id;
            document.getElementById('estado').value = estadoEspacioCurricular.estado;
            modalCrearEstadoLabel.innerText = 'Editar estado';
            btnGuardarEstado.innerText = 'Actualizar';
            modalCrearEstado.show();
        }

        function prepararModalEliminar(estadoEspacioCurricular) {
            document.getElementById('idEstadoEliminar').value = estadoEspacioCurricular.id;
            document.getElementById('nombreEstadoEliminar').innerText = estadoEspacioCurricular.estado;
            modalEliminarEstado.show();
        }

        function agregarEstadoATabla(estadoEspacioCurricular) {
            let contenedorTabla = document.getElementById('contenedorTablaEstados');
            let tbody = document.getElementById('tbodyEstados');
            const mensajeSinEstados = document.getElementById('mensajeSinEstados');

            if (mensajeSinEstados) {
                mensajeSinEstados.remove();
            }

            if (!contenedorTabla) {
                renderizarTablaEstados([estadoEspacioCurricular]);
                return;
            }

            const fila = document.createElement('tr');
            fila.dataset.id = estadoEspacioCurricular.id;
            fila.innerHTML = obtenerHtmlFilaEstado(estadoEspacioCurricular);
            tbody.appendChild(fila);
        }

        function actualizarEstadoEnTabla(estadoEspacioCurricular) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(estadoEspacioCurricular.id))}"]`);

            if (!fila) {
                return;
            }

            fila.innerHTML = obtenerHtmlFilaEstado(estadoEspacioCurricular);
        }

        function eliminarEstadoDeTabla(idEstado) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(idEstado))}"]`);

            if (!fila) {
                return;
            }

            fila.remove();
            mostrarMensajeSinEstadosSiCorresponde();
        }

        function mostrarMensajeSinEstadosSiCorresponde() {
            const tbody = document.getElementById('tbodyEstados');

            if (!tbody || tbody.children.length > 0) {
                return;
            }

            const contenedorTabla = document.getElementById('contenedorTablaEstados');
            const contenedorListado = document.getElementById('contenedorListado');

            if (contenedorTabla) {
                contenedorTabla.remove();
            }

            contenedorListado.innerHTML = `
                <div class="alert alert-info mb-0" id="mensajeSinEstados">
                    No hay estados registrados.
                </div>
            `;
        }

        function obtenerHtmlFilaEstado(estadoEspacioCurricular) {
            return `
                <td>${escapeHtml(estadoEspacioCurricular.id)}</td>
                <td>${escapeHtml(estadoEspacioCurricular.estado)}</td>
                <td>${escapeHtml(estadoEspacioCurricular.createdBy ?? '-')}</td>
                <td>${formatearFecha(estadoEspacioCurricular.createdAt)}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-editar-estado"
                        title="Editar estado"
                        data-id="${escapeHtml(estadoEspacioCurricular.id)}"
                        data-estado="${escapeHtml(estadoEspacioCurricular.estado)}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-eliminar-estado"
                        title="Eliminar estado"
                        data-id="${escapeHtml(estadoEspacioCurricular.id)}"
                        data-estado="${escapeHtml(estadoEspacioCurricular.estado)}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
        }

        function renderizarTablaEstados(estados) {
            const contenedorListado = document.getElementById('contenedorListado');

            if (!estados || estados.length === 0) {
                contenedorListado.innerHTML = `
                    <div class="alert alert-info mb-0" id="mensajeSinEstados">
                        No hay estados registrados.
                    </div>
                `;
                return;
            }

            contenedorListado.innerHTML = `
                <div class="table-responsive" id="contenedorTablaEstados">
                    <table class="table table-striped table-hover align-middle mb-0" id="tablaEstados">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Estado</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyEstados"></tbody>
                    </table>
                </div>
            `;

            const tbody = document.getElementById('tbodyEstados');

            estados.forEach(function(estadoEspacioCurricular) {
                const fila = document.createElement('tr');
                fila.dataset.id = estadoEspacioCurricular.id;
                fila.innerHTML = obtenerHtmlFilaEstado(estadoEspacioCurricular);
                tbody.appendChild(fila);
            });
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

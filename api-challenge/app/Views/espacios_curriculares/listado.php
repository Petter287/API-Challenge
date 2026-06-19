<!doctype html>
<?php
$espaciosCurriculares = $espaciosCurriculares ?? [];
$periodos = $periodos ?? [];
?>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Espacios Curriculares</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Espacios curriculares</h1>
                <p class="text-muted mb-0">Listado de espacios curriculares registrados</p>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnNuevoEspacioCurricular" data-bs-toggle="modal" data-bs-target="#modalCrearEspacioCurricular">
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

                <?php if (empty($espaciosCurriculares)): ?>

                    <div class="alert alert-info mb-0" id="mensajeSinEspaciosCurriculares">
                        No hay espacios curriculares registrados.
                    </div>

                <?php else: ?>

                    <div class="table-responsive" id="contenedorTablaEspaciosCurriculares">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaEspaciosCurriculares">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Nombre</th>
                                    <th>Período</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyEspaciosCurriculares">
                                <?php foreach ($espaciosCurriculares as $espacioCurricular): ?>
                                    <tr data-id="<?= esc($espacioCurricular['id']) ?>">
                                        <td><?= esc($espacioCurricular['id']) ?></td>
                                        <td><?= esc($espacioCurricular['nombre']) ?></td>
                                        <td><?= esc($espacioCurricular['periodoNombre']) ?></td>
                                        <td><?= esc($espacioCurricular['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($espacioCurricular['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($espacioCurricular['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm btn-editar-espacio-curricular"
                                                title="Editar"
                                                data-id="<?= esc($espacioCurricular['id']) ?>"
                                                data-nombre="<?= esc($espacioCurricular['nombre']) ?>"
                                                data-id-periodo="<?= esc($espacioCurricular['idPeriodo']) ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-eliminar-espacio-curricular"
                                                title="Eliminar"
                                                data-id="<?= esc($espacioCurricular['id']) ?>"
                                                data-nombre="<?= esc($espacioCurricular['nombre']) ?>"
                                                data-id-periodo="<?= esc($espacioCurricular['idPeriodo']) ?>">
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

    <div class="modal fade" id="modalEliminarEspacioCurricular" tabindex="-1" aria-labelledby="modalEliminarEspacioCurricularLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarEspacioCurricularLabel">Eliminar espacio curricular</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="idEspacioCurricularEliminar">
                    <p class="mb-0">
                        ¿Seguro que querés dar de baja a
                        <strong id="nombreEspacioCurricularEliminar"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarEspacioCurricular">
                        <i class="bi bi-trash"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrearEspacioCurricular" tabindex="-1" aria-labelledby="modalCrearEspacioCurricularLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearEspacioCurricular">
                    <input type="hidden" id="idEspacioCurricular" name="idEspacioCurricular">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearEspacioCurricularLabel">Nuevo espacio curricular</h5>
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
                            <label for="idPeriodo" class="form-label">Período</label>
                            <select class="form-select" id="idPeriodo" name="idPeriodo" required>
                                <option value="">Seleccionar período</option>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?= esc($periodo['id']) ?>">
                                        <?= esc($periodo['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarEspacioCurricular">
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

        const formCrearEspacioCurricular = document.getElementById('formCrearEspacioCurricular');
        const btnGuardarEspacioCurricular = document.getElementById('btnGuardarEspacioCurricular');
        const btnNuevoEspacioCurricular = document.getElementById('btnNuevoEspacioCurricular');
        const btnConfirmarEliminarEspacioCurricular = document.getElementById('btnConfirmarEliminarEspacioCurricular');
        const modalCrearEspacioCurricularLabel = document.getElementById('modalCrearEspacioCurricularLabel');

        const modalCrearEspacioCurricularElement = document.getElementById('modalCrearEspacioCurricular');
        const modalCrearEspacioCurricular = new bootstrap.Modal(modalCrearEspacioCurricularElement);

        const modalEliminarEspacioCurricularElement = document.getElementById('modalEliminarEspacioCurricular');
        const modalEliminarEspacioCurricular = new bootstrap.Modal(modalEliminarEspacioCurricularElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        btnNuevoEspacioCurricular.addEventListener('click', function() {
            prepararModalCrear();
        });

        const btnActualizarGrilla = document.getElementById('btnActualizarGrilla');

        btnActualizarGrilla.addEventListener('click', async function() {
            btnActualizarGrilla.disabled = true;

            try {
                await actualizarPeriodosSelect();

                const response = await fetch('/espacio-curricular/list', {
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

                renderizarTablaEspaciosCurriculares(result.data);
                mostrarToast('Grilla actualizada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error al actualizar la grilla.', 'error');
            } finally {
                btnActualizarGrilla.disabled = false;
            }
        });

        document.addEventListener('click', function(event) {
            const botonEditar = event.target.closest('.btn-editar-espacio-curricular');

            if (!botonEditar) {
                return;
            }

            prepararModalEditar({
                id: botonEditar.dataset.id,
                nombre: botonEditar.dataset.nombre,
                idPeriodo: botonEditar.dataset.idPeriodo
            });
        });

        document.addEventListener('click', function(event) {
            const botonEliminar = event.target.closest('.btn-eliminar-espacio-curricular');

            if (!botonEliminar) {
                return;
            }

            prepararModalEliminar({
                id: botonEliminar.dataset.id,
                nombre: botonEliminar.dataset.nombre,
                idPeriodo: botonEliminar.dataset.idPeriodo
            });
        });

        formCrearEspacioCurricular.addEventListener('submit', async function(event) {
            event.preventDefault();

            const idEspacioCurricular = document.getElementById('idEspacioCurricular').value;
            const nombre = document.getElementById('nombre').value.trim();
            const idPeriodo = document.getElementById('idPeriodo').value;

            if (!nombre || !idPeriodo) {
                mostrarToast('El nombre y el período son obligatorios.', 'error');
                return;
            }

            btnGuardarEspacioCurricular.disabled = true;
            btnGuardarEspacioCurricular.innerText = 'Guardando...';

            try {
                const esEdicion = modoForm === 'editar';
                const url = esEdicion
                    ? `/espacio-curricular/update/${idEspacioCurricular}`
                    : '/espacio-curricular/create';

                const response = await fetch(url, {
                    method: esEdicion ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        idPeriodo: idPeriodo
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo guardar el espacio curricular.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const espacioCurricular = result.data;

                if (esEdicion) {
                    actualizarEspacioCurricularEnTabla(espacioCurricular);
                } else {
                    agregarEspacioCurricularATabla(espacioCurricular);
                }

                formCrearEspacioCurricular.reset();
                modalCrearEspacioCurricular.hide();
                prepararModalCrear();

                mostrarToast(
                    esEdicion
                        ? 'Espacio curricular actualizado correctamente.'
                        : 'Espacio curricular creado correctamente.',
                    'success'
                );

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al guardar el espacio curricular.', 'error');
            } finally {
                btnGuardarEspacioCurricular.disabled = false;
                btnGuardarEspacioCurricular.innerText = modoForm === 'editar' ? 'Actualizar' : 'Guardar';
            }
        });

        btnConfirmarEliminarEspacioCurricular.addEventListener('click', async function() {
            const idEspacioCurricular = document.getElementById('idEspacioCurricularEliminar').value;

            if (!idEspacioCurricular) {
                mostrarToast('No se pudo identificar el espacio curricular a eliminar.', 'error');
                return;
            }

            btnConfirmarEliminarEspacioCurricular.disabled = true;
            btnConfirmarEliminarEspacioCurricular.innerText = 'Eliminando...';

            try {
                const response = await fetch(`/espacio-curricular/delete/${idEspacioCurricular}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo eliminar el espacio curricular.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                eliminarEspacioCurricularDeTabla(idEspacioCurricular);
                modalEliminarEspacioCurricular.hide();

                mostrarToast('Espacio curricular eliminado correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al eliminar el espacio curricular.', 'error');
            } finally {
                btnConfirmarEliminarEspacioCurricular.disabled = false;
                btnConfirmarEliminarEspacioCurricular.innerHTML = `
                    <i class="bi bi-trash"></i>
                    Eliminar
                `;
            }
        });

        async function actualizarPeriodosSelect() {
            const response = await fetch('/espacio-curricular/periodos', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (!response.ok || result.success === false) {
                throw new Error(result.message ?? 'No se pudieron obtener los períodos.');
            }

            renderizarOpcionesPeriodos(result.data);
        }

        function renderizarOpcionesPeriodos(periodos) {
            const select = document.getElementById('idPeriodo');
            const valorSeleccionado = select.value;

            select.innerHTML = '<option value="">Seleccionar período</option>';

            periodos.forEach(function(periodo) {
                const option = document.createElement('option');
                option.value = periodo.id;
                option.textContent = periodo.nombre;
                select.appendChild(option);
            });

            select.value = valorSeleccionado;
        }

        function prepararModalCrear() {
            modoForm = 'crear';
            formCrearEspacioCurricular.reset();
            document.getElementById('idEspacioCurricular').value = '';
            modalCrearEspacioCurricularLabel.innerText = 'Nuevo espacio curricular';
            btnGuardarEspacioCurricular.innerText = 'Guardar';
        }

        function prepararModalEditar(espacioCurricular) {
            modoForm = 'editar';
            document.getElementById('idEspacioCurricular').value = espacioCurricular.id;
            document.getElementById('nombre').value = espacioCurricular.nombre;
            document.getElementById('idPeriodo').value = espacioCurricular.idPeriodo;
            modalCrearEspacioCurricularLabel.innerText = 'Editar espacio curricular';
            btnGuardarEspacioCurricular.innerText = 'Actualizar';
            modalCrearEspacioCurricular.show();
        }

        function prepararModalEliminar(espacioCurricular) {
            document.getElementById('idEspacioCurricularEliminar').value = espacioCurricular.id;
            document.getElementById('nombreEspacioCurricularEliminar').innerText = espacioCurricular.nombre;
            modalEliminarEspacioCurricular.show();
        }

        function agregarEspacioCurricularATabla(espacioCurricular) {
            let contenedorTabla = document.getElementById('contenedorTablaEspaciosCurriculares');
            let tbody = document.getElementById('tbodyEspaciosCurriculares');
            const mensajeSinEspaciosCurriculares = document.getElementById('mensajeSinEspaciosCurriculares');

            if (mensajeSinEspaciosCurriculares) {
                mensajeSinEspaciosCurriculares.remove();
            }

            if (!contenedorTabla) {
                renderizarTablaEspaciosCurriculares([espacioCurricular]);
                return;
            }

            const fila = document.createElement('tr');
            fila.dataset.id = espacioCurricular.id;
            fila.innerHTML = obtenerHtmlFilaEspacioCurricular(espacioCurricular);
            tbody.appendChild(fila);
        }

        function actualizarEspacioCurricularEnTabla(espacioCurricular) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(espacioCurricular.id))}"]`);

            if (!fila) {
                return;
            }

            fila.innerHTML = obtenerHtmlFilaEspacioCurricular(espacioCurricular);
        }

        function eliminarEspacioCurricularDeTabla(idEspacioCurricular) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(idEspacioCurricular))}"]`);

            if (!fila) {
                return;
            }

            fila.remove();
            mostrarMensajeSinEspaciosCurricularesSiCorresponde();
        }

        function mostrarMensajeSinEspaciosCurricularesSiCorresponde() {
            const tbody = document.getElementById('tbodyEspaciosCurriculares');

            if (!tbody || tbody.children.length > 0) {
                return;
            }

            const contenedorTabla = document.getElementById('contenedorTablaEspaciosCurriculares');
            const contenedorListado = document.getElementById('contenedorListado');

            if (contenedorTabla) {
                contenedorTabla.remove();
            }

            contenedorListado.innerHTML = `
                <div class="alert alert-info mb-0" id="mensajeSinEspaciosCurriculares">
                    No hay espacios curriculares registrados.
                </div>
            `;
        }

        function obtenerHtmlFilaEspacioCurricular(espacioCurricular) {
            return `
                <td>${escapeHtml(espacioCurricular.id)}</td>
                <td>${escapeHtml(espacioCurricular.nombre)}</td>
                <td>${escapeHtml(espacioCurricular.periodoNombre)}</td>
                <td>${escapeHtml(espacioCurricular.createdBy ?? '-')}</td>
                <td>${formatearFecha(espacioCurricular.createdAt)}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-editar-espacio-curricular"
                        title="Editar espacio curricular"
                        data-id="${escapeHtml(espacioCurricular.id)}"
                        data-nombre="${escapeHtml(espacioCurricular.nombre)}"
                        data-id-periodo="${escapeHtml(espacioCurricular.idPeriodo)}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-eliminar-espacio-curricular"
                        title="Eliminar espacio curricular"
                        data-id="${escapeHtml(espacioCurricular.id)}"
                        data-nombre="${escapeHtml(espacioCurricular.nombre)}"
                        data-id-periodo="${escapeHtml(espacioCurricular.idPeriodo)}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
        }

        function renderizarTablaEspaciosCurriculares(espaciosCurriculares) {
            const contenedorListado = document.getElementById('contenedorListado');

            if (!espaciosCurriculares || espaciosCurriculares.length === 0) {
                contenedorListado.innerHTML = `
                    <div class="alert alert-info mb-0" id="mensajeSinEspaciosCurriculares">
                        No hay espacios curriculares registrados.
                    </div>
                `;
                return;
            }

            contenedorListado.innerHTML = `
                <div class="table-responsive" id="contenedorTablaEspaciosCurriculares">
                    <table class="table table-striped table-hover align-middle mb-0" id="tablaEspaciosCurriculares">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nombre</th>
                                <th>Período</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyEspaciosCurriculares"></tbody>
                    </table>
                </div>
            `;

            const tbody = document.getElementById('tbodyEspaciosCurriculares');

            espaciosCurriculares.forEach(function(espacioCurricular) {
                const fila = document.createElement('tr');
                fila.dataset.id = espacioCurricular.id;
                fila.innerHTML = obtenerHtmlFilaEspacioCurricular(espacioCurricular);
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

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Materias</title>
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
                <h1 class="h3 mb-1">Materias</h1>
                <p class="text-muted mb-0">Listado de materias registradas</p>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnNuevaMateria" data-bs-toggle="modal" data-bs-target="#modalCrearMateria">
                    <i class="bi bi-journal-plus"></i>
                    Nueva
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

                <?php if (empty($materias)): ?>

                    <div class="alert alert-info mb-0" id="mensajeSinMaterias">
                        No hay materias registradas.
                    </div>

                <?php else: ?>

                    <div class="table-responsive" id="contenedorTablaMaterias">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaMaterias">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Nombre</th>
                                    <th style="width: 120px;">Año</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMaterias">
                                <?php foreach ($materias as $materia): ?>
                                    <tr data-id="<?= esc($materia['id']) ?>">
                                        <td><?= esc($materia['id']) ?></td>
                                        <td><?= esc($materia['nombre']) ?></td>
                                        <td><?= esc($materia['anio']) ?></td>
                                        <td><?= esc($materia['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($materia['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($materia['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm btn-editar-materia"
                                                title="Editar"
                                                data-id="<?= esc($materia['id']) ?>"
                                                data-nombre="<?= esc($materia['nombre']) ?>"
                                                data-anio="<?= esc($materia['anio']) ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-eliminar-materia"
                                                title="Eliminar"
                                                data-id="<?= esc($materia['id']) ?>"
                                                data-nombre="<?= esc($materia['nombre']) ?>"
                                                data-anio="<?= esc($materia['anio']) ?>">
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

    <div class="modal fade" id="modalEliminarMateria" tabindex="-1" aria-labelledby="modalEliminarMateriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarMateriaLabel">Eliminar materia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="idMateriaEliminar">
                    <p class="mb-0">
                        ¿Seguro que querés dar de baja a
                        <strong id="nombreMateriaEliminar"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarMateria">
                        <i class="bi bi-trash"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrearMateria" tabindex="-1" aria-labelledby="modalCrearMateriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearMateria">
                    <input type="hidden" id="idMateria" name="idMateria">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearMateriaLabel">Nueva materia</h5>
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
                            <label for="anio" class="form-label">Año</label>
                            <input
                                type="number"
                                class="form-control"
                                id="anio"
                                name="anio"
                                min="1"
                                step="1"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarMateria">
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

        const formCrearMateria = document.getElementById('formCrearMateria');
        const btnGuardarMateria = document.getElementById('btnGuardarMateria');
        const btnNuevaMateria = document.getElementById('btnNuevaMateria');
        const btnConfirmarEliminarMateria = document.getElementById('btnConfirmarEliminarMateria');
        const modalCrearMateriaLabel = document.getElementById('modalCrearMateriaLabel');

        const modalCrearMateriaElement = document.getElementById('modalCrearMateria');
        const modalCrearMateria = new bootstrap.Modal(modalCrearMateriaElement);

        const modalEliminarMateriaElement = document.getElementById('modalEliminarMateria');
        const modalEliminarMateria = new bootstrap.Modal(modalEliminarMateriaElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        btnNuevaMateria.addEventListener('click', function() {
            prepararModalCrear();
        });

        const btnActualizarGrilla = document.getElementById('btnActualizarGrilla');

        btnActualizarGrilla.addEventListener('click', async function() {
            btnActualizarGrilla.disabled = true;

            try {
                const response = await fetch('/materia/list', {
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

                renderizarTablaMaterias(result.data);
                mostrarToast('Grilla actualizada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error al actualizar la grilla.', 'error');
            } finally {
                btnActualizarGrilla.disabled = false;
            }
        });

        document.addEventListener('click', function(event) {
            const botonEditar = event.target.closest('.btn-editar-materia');

            if (!botonEditar) {
                return;
            }

            prepararModalEditar({
                id: botonEditar.dataset.id,
                nombre: botonEditar.dataset.nombre,
                anio: botonEditar.dataset.anio
            });
        });

        document.addEventListener('click', function(event) {
            const botonEliminar = event.target.closest('.btn-eliminar-materia');

            if (!botonEliminar) {
                return;
            }

            prepararModalEliminar({
                id: botonEliminar.dataset.id,
                nombre: botonEliminar.dataset.nombre,
                anio: botonEliminar.dataset.anio
            });
        });

        formCrearMateria.addEventListener('submit', async function(event) {
            event.preventDefault();

            const idMateria = document.getElementById('idMateria').value;
            const nombre = document.getElementById('nombre').value.trim();
            const anio = document.getElementById('anio').value.trim();

            if (!nombre || !anio) {
                mostrarToast('El nombre y el año son obligatorios.', 'error');
                return;
            }

            btnGuardarMateria.disabled = true;
            btnGuardarMateria.innerText = 'Guardando...';

            try {
                const esEdicion = modoForm === 'editar';
                const url = esEdicion
                    ? `/materia/update/${idMateria}`
                    : '/materia/create';

                const response = await fetch(url, {
                    method: esEdicion ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        nombre: nombre,
                        anio: anio
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo guardar la materia.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const materia = result.data;

                if (esEdicion) {
                    actualizarMateriaEnTabla(materia);
                } else {
                    agregarMateriaATabla(materia);
                }

                formCrearMateria.reset();
                modalCrearMateria.hide();
                prepararModalCrear();

                mostrarToast(
                    esEdicion
                        ? 'Materia actualizada correctamente.'
                        : 'Materia creada correctamente.',
                    'success'
                );

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al guardar la materia.', 'error');
            } finally {
                btnGuardarMateria.disabled = false;
                btnGuardarMateria.innerText = modoForm === 'editar' ? 'Actualizar' : 'Guardar';
            }
        });

        btnConfirmarEliminarMateria.addEventListener('click', async function() {
            const idMateria = document.getElementById('idMateriaEliminar').value;

            if (!idMateria) {
                mostrarToast('No se pudo identificar la materia a eliminar.', 'error');
                return;
            }

            btnConfirmarEliminarMateria.disabled = true;
            btnConfirmarEliminarMateria.innerText = 'Eliminando...';

            try {
                const response = await fetch(`/materia/delete/${idMateria}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo eliminar la materia.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                eliminarMateriaDeTabla(idMateria);
                modalEliminarMateria.hide();

                mostrarToast('Materia eliminada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al eliminar la materia.', 'error');
            } finally {
                btnConfirmarEliminarMateria.disabled = false;
                btnConfirmarEliminarMateria.innerHTML = `
                    <i class="bi bi-trash"></i>
                    Eliminar
                `;
            }
        });

        function prepararModalCrear() {
            modoForm = 'crear';
            formCrearMateria.reset();
            document.getElementById('idMateria').value = '';
            modalCrearMateriaLabel.innerText = 'Nueva materia';
            btnGuardarMateria.innerText = 'Guardar';
        }

        function prepararModalEditar(materia) {
            modoForm = 'editar';
            document.getElementById('idMateria').value = materia.id;
            document.getElementById('nombre').value = materia.nombre;
            document.getElementById('anio').value = materia.anio;
            modalCrearMateriaLabel.innerText = 'Editar materia';
            btnGuardarMateria.innerText = 'Actualizar';
            modalCrearMateria.show();
        }

        function prepararModalEliminar(materia) {
            document.getElementById('idMateriaEliminar').value = materia.id;
            document.getElementById('nombreMateriaEliminar').innerText = `${materia.nombre} (${materia.anio})`;
            modalEliminarMateria.show();
        }

        function agregarMateriaATabla(materia) {
            let contenedorTabla = document.getElementById('contenedorTablaMaterias');
            let tbody = document.getElementById('tbodyMaterias');
            const mensajeSinMaterias = document.getElementById('mensajeSinMaterias');

            if (mensajeSinMaterias) {
                mensajeSinMaterias.remove();
            }

            if (!contenedorTabla) {
                renderizarTablaMaterias([materia]);
                return;
            }

            const fila = document.createElement('tr');
            fila.dataset.id = materia.id;
            fila.innerHTML = obtenerHtmlFilaMateria(materia);
            tbody.appendChild(fila);
        }

        function actualizarMateriaEnTabla(materia) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(materia.id))}"]`);

            if (!fila) {
                return;
            }

            fila.innerHTML = obtenerHtmlFilaMateria(materia);
        }

        function eliminarMateriaDeTabla(idMateria) {
            const fila = document.querySelector(`tr[data-id="${CSS.escape(String(idMateria))}"]`);

            if (!fila) {
                return;
            }

            fila.remove();
            mostrarMensajeSinMateriasSiCorresponde();
        }

        function mostrarMensajeSinMateriasSiCorresponde() {
            const tbody = document.getElementById('tbodyMaterias');

            if (!tbody || tbody.children.length > 0) {
                return;
            }

            const contenedorTabla = document.getElementById('contenedorTablaMaterias');
            const contenedorListado = document.getElementById('contenedorListado');

            if (contenedorTabla) {
                contenedorTabla.remove();
            }

            contenedorListado.innerHTML = `
                <div class="alert alert-info mb-0" id="mensajeSinMaterias">
                    No hay materias registradas.
                </div>
            `;
        }

        function obtenerHtmlFilaMateria(materia) {
            return `
                <td>${escapeHtml(materia.id)}</td>
                <td>${escapeHtml(materia.nombre)}</td>
                <td>${escapeHtml(materia.anio)}</td>
                <td>${escapeHtml(materia.createdBy ?? '-')}</td>
                <td>${formatearFecha(materia.createdAt)}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-editar-materia"
                        title="Editar materia"
                        data-id="${escapeHtml(materia.id)}"
                        data-nombre="${escapeHtml(materia.nombre)}"
                        data-anio="${escapeHtml(materia.anio)}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-eliminar-materia"
                        title="Eliminar materia"
                        data-id="${escapeHtml(materia.id)}"
                        data-nombre="${escapeHtml(materia.nombre)}"
                        data-anio="${escapeHtml(materia.anio)}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
        }

        function renderizarTablaMaterias(materias) {
            const contenedorListado = document.getElementById('contenedorListado');

            if (!materias || materias.length === 0) {
                contenedorListado.innerHTML = `
                    <div class="alert alert-info mb-0" id="mensajeSinMaterias">
                        No hay materias registradas.
                    </div>
                `;
                return;
            }

            contenedorListado.innerHTML = `
                <div class="table-responsive" id="contenedorTablaMaterias">
                    <table class="table table-striped table-hover align-middle mb-0" id="tablaMaterias">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Nombre</th>
                                <th style="width: 120px;">Año</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyMaterias"></tbody>
                    </table>
                </div>
            `;

            const tbody = document.getElementById('tbodyMaterias');

            materias.forEach(function(materia) {
                const fila = document.createElement('tr');
                fila.dataset.id = materia.id;
                fila.innerHTML = obtenerHtmlFilaMateria(materia);
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

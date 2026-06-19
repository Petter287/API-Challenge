<!doctype html>
<?php
$materiasEspaciosCurriculares = $materiasEspaciosCurriculares ?? [];
$materias = $materias ?? [];
$espaciosCurriculares = $espaciosCurriculares ?? [];
?>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Listado de Materias por Espacio Curricular</title>
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
                <h1 class="h3 mb-1">Materias por espacio curricular</h1>
                <p class="text-muted mb-0">Listado de relaciones registradas</p>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnNuevaRelacion" data-bs-toggle="modal" data-bs-target="#modalCrearRelacion">
                    <i class="bi bi-plus-circle"></i>
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

                <?php if (empty($materiasEspaciosCurriculares)): ?>

                    <div class="alert alert-info mb-0" id="mensajeSinRelaciones">
                        No hay relaciones registradas.
                    </div>

                <?php else: ?>

                    <div class="table-responsive" id="contenedorTablaRelaciones">
                        <table class="table table-striped table-hover align-middle mb-0" id="tablaRelaciones">
                            <thead class="table-dark">
                                <tr>
                                    <th>Materia</th>
                                    <th>Espacio curricular</th>
                                    <th>Creado por</th>
                                    <th>Fecha creación</th>
                                    <th style="width: 140px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyRelaciones">
                                <?php foreach ($materiasEspaciosCurriculares as $relacion): ?>
                                    <tr data-key="<?= esc($relacion['idMateria']) ?>-<?= esc($relacion['idEspCurr']) ?>">
                                        <td><?= esc($relacion['nombreMateria']) ?></td>
                                        <td><?= esc($relacion['nombreEspacioCurricular']) ?></td>
                                        <td><?= esc($relacion['createdBy'] ?? '-') ?></td>
                                        <td>
                                            <?= !empty($relacion['createdAt'])
                                                ? date('d/m/Y H:i', strtotime($relacion['createdAt']))
                                                : '-'
                                            ?>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm btn-editar-relacion"
                                                title="Editar"
                                                data-id-materia="<?= esc($relacion['idMateria']) ?>"
                                                data-id-esp-curr="<?= esc($relacion['idEspCurr']) ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-eliminar-relacion"
                                                title="Eliminar"
                                                data-id-materia="<?= esc($relacion['idMateria']) ?>"
                                                data-id-esp-curr="<?= esc($relacion['idEspCurr']) ?>"
                                                data-nombre-materia="<?= esc($relacion['nombreMateria']) ?>"
                                                data-nombre-espacio="<?= esc($relacion['nombreEspacioCurricular']) ?>">
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

    <div class="modal fade" id="modalEliminarRelacion" tabindex="-1" aria-labelledby="modalEliminarRelacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarRelacionLabel">Eliminar relación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="idMateriaEliminar">
                    <input type="hidden" id="idEspCurrEliminar">
                    <p class="mb-0">
                        ¿Seguro que querés dar de baja la relación
                        <strong id="nombreRelacionEliminar"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminarRelacion">
                        <i class="bi bi-trash"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCrearRelacion" tabindex="-1" aria-labelledby="modalCrearRelacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="formCrearRelacion">
                    <input type="hidden" id="idMateriaActual">
                    <input type="hidden" id="idEspCurrActual">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearRelacionLabel">Nueva relación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="idMateria" class="form-label">Materia</label>
                            <select class="form-select" id="idMateria" name="idMateria" required>
                                <option value="">Seleccionar materia</option>
                                <?php foreach ($materias as $materia): ?>
                                    <option value="<?= esc($materia['id']) ?>">
                                        <?= esc($materia['nombre']) ?> (<?= esc($materia['anio']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="idEspCurr" class="form-label">Espacio curricular</label>
                            <select class="form-select" id="idEspCurr" name="idEspCurr" required>
                                <option value="">Seleccionar espacio curricular</option>
                                <?php foreach ($espaciosCurriculares as $espacioCurricular): ?>
                                    <option value="<?= esc($espacioCurricular['id']) ?>">
                                        <?= esc($espacioCurricular['nombre']) ?> - <?= esc($espacioCurricular['periodoNombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-primary" id="btnGuardarRelacion">
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

        const formCrearRelacion = document.getElementById('formCrearRelacion');
        const btnGuardarRelacion = document.getElementById('btnGuardarRelacion');
        const btnNuevaRelacion = document.getElementById('btnNuevaRelacion');
        const btnConfirmarEliminarRelacion = document.getElementById('btnConfirmarEliminarRelacion');
        const modalCrearRelacionLabel = document.getElementById('modalCrearRelacionLabel');

        const modalCrearRelacionElement = document.getElementById('modalCrearRelacion');
        const modalCrearRelacion = new bootstrap.Modal(modalCrearRelacionElement);

        const modalEliminarRelacionElement = document.getElementById('modalEliminarRelacion');
        const modalEliminarRelacion = new bootstrap.Modal(modalEliminarRelacionElement);

        const toastElement = document.getElementById('toastMensaje');
        const toastTexto = document.getElementById('toastTexto');
        const toast = new bootstrap.Toast(toastElement);

        btnNuevaRelacion.addEventListener('click', function() {
            prepararModalCrear();
        });

        const btnActualizarGrilla = document.getElementById('btnActualizarGrilla');

        btnActualizarGrilla.addEventListener('click', async function() {
            btnActualizarGrilla.disabled = true;

            try {
                await actualizarOpcionesSelect();

                const response = await fetch('/materia-espacio-curricular/list', {
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

                renderizarTablaRelaciones(result.data);
                mostrarToast('Grilla actualizada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error al actualizar la grilla.', 'error');
            } finally {
                btnActualizarGrilla.disabled = false;
            }
        });

        document.addEventListener('click', function(event) {
            const botonEditar = event.target.closest('.btn-editar-relacion');

            if (!botonEditar) {
                return;
            }

            prepararModalEditar({
                idMateria: botonEditar.dataset.idMateria,
                idEspCurr: botonEditar.dataset.idEspCurr
            });
        });

        document.addEventListener('click', function(event) {
            const botonEliminar = event.target.closest('.btn-eliminar-relacion');

            if (!botonEliminar) {
                return;
            }

            prepararModalEliminar({
                idMateria: botonEliminar.dataset.idMateria,
                idEspCurr: botonEliminar.dataset.idEspCurr,
                nombreMateria: botonEliminar.dataset.nombreMateria,
                nombreEspacio: botonEliminar.dataset.nombreEspacio
            });
        });

        formCrearRelacion.addEventListener('submit', async function(event) {
            event.preventDefault();

            const idMateriaActual = document.getElementById('idMateriaActual').value;
            const idEspCurrActual = document.getElementById('idEspCurrActual').value;
            const idMateria = document.getElementById('idMateria').value;
            const idEspCurr = document.getElementById('idEspCurr').value;

            if (!idMateria || !idEspCurr) {
                mostrarToast('La materia y el espacio curricular son obligatorios.', 'error');
                return;
            }

            btnGuardarRelacion.disabled = true;
            btnGuardarRelacion.innerText = 'Guardando...';

            try {
                const esEdicion = modoForm === 'editar';
                const url = esEdicion
                    ? `/materia-espacio-curricular/update/${idMateriaActual}/${idEspCurrActual}`
                    : '/materia-espacio-curricular/create';

                const response = await fetch(url, {
                    method: esEdicion ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        idMateria: idMateria,
                        idEspCurr: idEspCurr
                    })
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo guardar la relación.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                const relacion = result.data;

                if (esEdicion) {
                    actualizarRelacionEnTabla(idMateriaActual, idEspCurrActual, relacion);
                } else {
                    agregarRelacionATabla(relacion);
                }

                formCrearRelacion.reset();
                modalCrearRelacion.hide();
                prepararModalCrear();

                mostrarToast(
                    esEdicion
                        ? 'Relación actualizada correctamente.'
                        : 'Relación creada correctamente.',
                    'success'
                );

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al guardar la relación.', 'error');
            } finally {
                btnGuardarRelacion.disabled = false;
                btnGuardarRelacion.innerText = modoForm === 'editar' ? 'Actualizar' : 'Guardar';
            }
        });

        btnConfirmarEliminarRelacion.addEventListener('click', async function() {
            const idMateria = document.getElementById('idMateriaEliminar').value;
            const idEspCurr = document.getElementById('idEspCurrEliminar').value;

            if (!idMateria || !idEspCurr) {
                mostrarToast('No se pudo identificar la relación a eliminar.', 'error');
                return;
            }

            btnConfirmarEliminarRelacion.disabled = true;
            btnConfirmarEliminarRelacion.innerText = 'Eliminando...';

            try {
                const response = await fetch(`/materia-espacio-curricular/delete/${idMateria}/${idEspCurr}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || result.success === false) {
                    const mensaje = result.message ?? 'No se pudo eliminar la relación.';
                    mostrarToast(mensaje, 'error');
                    return;
                }

                eliminarRelacionDeTabla(idMateria, idEspCurr);
                modalEliminarRelacion.hide();

                mostrarToast('Relación eliminada correctamente.', 'success');

            } catch (error) {
                mostrarToast('Ocurrió un error inesperado al eliminar la relación.', 'error');
            } finally {
                btnConfirmarEliminarRelacion.disabled = false;
                btnConfirmarEliminarRelacion.innerHTML = `
                    <i class="bi bi-trash"></i>
                    Eliminar
                `;
            }
        });

        async function actualizarOpcionesSelect() {
            const response = await fetch('/materia-espacio-curricular/options', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (!response.ok || result.success === false) {
                throw new Error(result.message ?? 'No se pudieron obtener las opciones.');
            }

            renderizarOpcionesMaterias(result.data.materias);
            renderizarOpcionesEspaciosCurriculares(result.data.espaciosCurriculares);
        }

        function renderizarOpcionesMaterias(materias) {
            const select = document.getElementById('idMateria');
            const valorSeleccionado = select.value;

            select.innerHTML = '<option value="">Seleccionar materia</option>';

            materias.forEach(function(materia) {
                const option = document.createElement('option');
                option.value = materia.id;
                option.textContent = `${materia.nombre} (${materia.anio})`;
                select.appendChild(option);
            });

            select.value = valorSeleccionado;
        }

        function renderizarOpcionesEspaciosCurriculares(espaciosCurriculares) {
            const select = document.getElementById('idEspCurr');
            const valorSeleccionado = select.value;

            select.innerHTML = '<option value="">Seleccionar espacio curricular</option>';

            espaciosCurriculares.forEach(function(espacioCurricular) {
                const option = document.createElement('option');
                option.value = espacioCurricular.id;
                option.textContent = `${espacioCurricular.nombre} - ${espacioCurricular.periodoNombre}`;
                select.appendChild(option);
            });

            select.value = valorSeleccionado;
        }

        function prepararModalCrear() {
            modoForm = 'crear';
            formCrearRelacion.reset();
            document.getElementById('idMateriaActual').value = '';
            document.getElementById('idEspCurrActual').value = '';
            modalCrearRelacionLabel.innerText = 'Nueva relación';
            btnGuardarRelacion.innerText = 'Guardar';
        }

        function prepararModalEditar(relacion) {
            modoForm = 'editar';
            document.getElementById('idMateriaActual').value = relacion.idMateria;
            document.getElementById('idEspCurrActual').value = relacion.idEspCurr;
            document.getElementById('idMateria').value = relacion.idMateria;
            document.getElementById('idEspCurr').value = relacion.idEspCurr;
            modalCrearRelacionLabel.innerText = 'Editar relación';
            btnGuardarRelacion.innerText = 'Actualizar';
            modalCrearRelacion.show();
        }

        function prepararModalEliminar(relacion) {
            document.getElementById('idMateriaEliminar').value = relacion.idMateria;
            document.getElementById('idEspCurrEliminar').value = relacion.idEspCurr;
            document.getElementById('nombreRelacionEliminar').innerText = `${relacion.nombreMateria} - ${relacion.nombreEspacio}`;
            modalEliminarRelacion.show();
        }

        function agregarRelacionATabla(relacion) {
            let contenedorTabla = document.getElementById('contenedorTablaRelaciones');
            let tbody = document.getElementById('tbodyRelaciones');
            const mensajeSinRelaciones = document.getElementById('mensajeSinRelaciones');

            if (mensajeSinRelaciones) {
                mensajeSinRelaciones.remove();
            }

            if (!contenedorTabla) {
                renderizarTablaRelaciones([relacion]);
                return;
            }

            const fila = document.createElement('tr');
            fila.dataset.key = obtenerClaveRelacion(relacion.idMateria, relacion.idEspCurr);
            fila.innerHTML = obtenerHtmlFilaRelacion(relacion);
            tbody.appendChild(fila);
        }

        function actualizarRelacionEnTabla(idMateriaActual, idEspCurrActual, relacion) {
            const fila = document.querySelector(`tr[data-key="${CSS.escape(obtenerClaveRelacion(idMateriaActual, idEspCurrActual))}"]`);

            if (!fila) {
                return;
            }

            fila.dataset.key = obtenerClaveRelacion(relacion.idMateria, relacion.idEspCurr);
            fila.innerHTML = obtenerHtmlFilaRelacion(relacion);
        }

        function eliminarRelacionDeTabla(idMateria, idEspCurr) {
            const fila = document.querySelector(`tr[data-key="${CSS.escape(obtenerClaveRelacion(idMateria, idEspCurr))}"]`);

            if (!fila) {
                return;
            }

            fila.remove();
            mostrarMensajeSinRelacionesSiCorresponde();
        }

        function mostrarMensajeSinRelacionesSiCorresponde() {
            const tbody = document.getElementById('tbodyRelaciones');

            if (!tbody || tbody.children.length > 0) {
                return;
            }

            const contenedorTabla = document.getElementById('contenedorTablaRelaciones');
            const contenedorListado = document.getElementById('contenedorListado');

            if (contenedorTabla) {
                contenedorTabla.remove();
            }

            contenedorListado.innerHTML = `
                <div class="alert alert-info mb-0" id="mensajeSinRelaciones">
                    No hay relaciones registradas.
                </div>
            `;
        }

        function obtenerHtmlFilaRelacion(relacion) {
            return `
                <td>${escapeHtml(relacion.nombreMateria)}</td>
                <td>${escapeHtml(relacion.nombreEspacioCurricular)}</td>
                <td>${escapeHtml(relacion.createdBy ?? '-')}</td>
                <td>${formatearFecha(relacion.createdAt)}</td>
                <td>
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-editar-relacion"
                        title="Editar relación"
                        data-id-materia="${escapeHtml(relacion.idMateria)}"
                        data-id-esp-curr="${escapeHtml(relacion.idEspCurr)}">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-eliminar-relacion"
                        title="Eliminar relación"
                        data-id-materia="${escapeHtml(relacion.idMateria)}"
                        data-id-esp-curr="${escapeHtml(relacion.idEspCurr)}"
                        data-nombre-materia="${escapeHtml(relacion.nombreMateria)}"
                        data-nombre-espacio="${escapeHtml(relacion.nombreEspacioCurricular)}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
        }

        function renderizarTablaRelaciones(relaciones) {
            const contenedorListado = document.getElementById('contenedorListado');

            if (!relaciones || relaciones.length === 0) {
                contenedorListado.innerHTML = `
                    <div class="alert alert-info mb-0" id="mensajeSinRelaciones">
                        No hay relaciones registradas.
                    </div>
                `;
                return;
            }

            contenedorListado.innerHTML = `
                <div class="table-responsive" id="contenedorTablaRelaciones">
                    <table class="table table-striped table-hover align-middle mb-0" id="tablaRelaciones">
                        <thead class="table-dark">
                            <tr>
                                <th>Materia</th>
                                <th>Espacio curricular</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th style="width: 140px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyRelaciones"></tbody>
                    </table>
                </div>
            `;

            const tbody = document.getElementById('tbodyRelaciones');

            relaciones.forEach(function(relacion) {
                const fila = document.createElement('tr');
                fila.dataset.key = obtenerClaveRelacion(relacion.idMateria, relacion.idEspCurr);
                fila.innerHTML = obtenerHtmlFilaRelacion(relacion);
                tbody.appendChild(fila);
            });
        }

        function obtenerClaveRelacion(idMateria, idEspCurr) {
            return `${idMateria}-${idEspCurr}`;
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

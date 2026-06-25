<!doctype html>
<?php $estudiantes = $estudiantes ?? []; ?>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Estados finales de materias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .status-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(120px, 1fr));
            gap: .75rem;
        }

        .status-summary-item {
            min-height: 74px;
            padding: .875rem 1rem;
            border: 1px solid #dee2e6;
            border-left-width: 4px;
            background: #fff;
        }

        .status-summary-item[data-status="aprobado"] {
            border-left-color: #198754;
        }

        .status-summary-item[data-status="en_proceso"] {
            border-left-color: #fd7e14;
        }

        .status-summary-item[data-status="no_iniciado"] {
            border-left-color: #6c757d;
        }

        .status-summary-item[data-status="sin_calificar"] {
            border-left-color: #0dcaf0;
        }

        .subject-status {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            white-space: nowrap;
        }

        .subject-status::before {
            width: .65rem;
            height: .65rem;
            border-radius: 50%;
            background: #6c757d;
            content: "";
        }

        .subject-status[data-status="aprobado"]::before {
            background: #198754;
        }

        .subject-status[data-status="en_proceso"]::before {
            background: #fd7e14;
        }

        .subject-status[data-status="sin_calificar"]::before {
            background: #0dcaf0;
        }

        .curricular-spaces {
            display: grid;
            gap: .35rem;
            min-width: 280px;
        }

        .curricular-space {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        @media (max-width: 767.98px) {
            .status-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .query-actions,
            .query-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-light with-app-shell">

    <?= view('layout/sidebar') ?>

    <main class="container py-5">
        <div class="mb-4">
            <h1 class="h3 mb-1">Estados finales de materias</h1>
            <p class="text-muted mb-0">Consulta del resultado académico por estudiante</p>
        </div>

        <section class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <strong>Seleccionar estudiante</strong>
            </div>

            <div class="card-body">
                <form id="formConsulta" class="row g-3 align-items-end">
                    <div class="col-12 col-lg">
                        <label for="idEstudiante" class="form-label">Estudiante</label>
                        <select class="form-select" id="idEstudiante" required>
                            <option value="">Seleccionar estudiante</option>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <option value="<?= esc($estudiante['id']) ?>">
                                    <?= esc($estudiante['apellido']) ?>, <?= esc($estudiante['nombre']) ?>
                                    - DNI <?= esc($estudiante['dni'] ?? '-') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-lg-auto query-actions">
                        <button type="submit" class="btn btn-primary" id="btnConsultar">
                            <i class="bi bi-search"></i>
                            Consultar
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <div class="alert alert-info" id="estadoInicial">
            Seleccioná un estudiante para consultar el estado final de sus materias.
        </div>

        <div class="alert alert-danger d-none" id="estadoError" role="alert"></div>

        <div class="text-center py-5 d-none" id="estadoCarga">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando</span>
            </div>
            <div class="text-muted mt-3">Calculando estados finales...</div>
        </div>

        <section class="d-none" id="resultadoConsulta">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h2 class="h5 mb-1" id="tituloResultado">Resultado</h2>
                    <p class="text-muted mb-0" id="detalleResultado"></p>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnActualizar">
                    <i class="bi bi-arrow-clockwise"></i>
                    Actualizar
                </button>
            </div>

            <div class="status-summary mb-4">
                <div class="status-summary-item" data-status="aprobado">
                    <div class="text-muted small">Aprobadas</div>
                    <div class="fs-4 fw-semibold" data-counter="aprobado">0</div>
                </div>
                <div class="status-summary-item" data-status="en_proceso">
                    <div class="text-muted small">En proceso</div>
                    <div class="fs-4 fw-semibold" data-counter="en_proceso">0</div>
                </div>
                <div class="status-summary-item" data-status="no_iniciado">
                    <div class="text-muted small">No iniciadas</div>
                    <div class="fs-4 fw-semibold" data-counter="no_iniciado">0</div>
                </div>
                <div class="status-summary-item" data-status="sin_calificar">
                    <div class="text-muted small">Sin calificar</div>
                    <div class="fs-4 fw-semibold" data-counter="sin_calificar">0</div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <strong>Materias</strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Materia</th>
                                    <th>Estado final</th>
                                    <th>Espacios curriculares considerados</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMaterias"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const formConsulta = document.getElementById('formConsulta');
        const selectEstudiante = document.getElementById('idEstudiante');
        const btnConsultar = document.getElementById('btnConsultar');
        const btnActualizar = document.getElementById('btnActualizar');
        const estadoInicial = document.getElementById('estadoInicial');
        const estadoError = document.getElementById('estadoError');
        const estadoCarga = document.getElementById('estadoCarga');
        const resultadoConsulta = document.getElementById('resultadoConsulta');
        const tbodyMaterias = document.getElementById('tbodyMaterias');
        const tituloResultado = document.getElementById('tituloResultado');
        const detalleResultado = document.getElementById('detalleResultado');

        formConsulta.addEventListener('submit', function(event) {
            event.preventDefault();
            consultarEstados();
        });

        btnActualizar.addEventListener('click', consultarEstados);

        async function consultarEstados() {
            const idEstudiante = selectEstudiante.value;

            if (!idEstudiante) {
                mostrarError('Seleccioná un estudiante.');
                return;
            }

            prepararCarga(true);

            try {
                const response = await fetch(`/students/${encodeURIComponent(idEstudiante)}/subjects-status`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message ?? 'No se pudieron consultar los estados finales.');
                }

                renderizarResultado(Array.isArray(data) ? data : []);
            } catch (error) {
                mostrarError(error.message);
            } finally {
                prepararCarga(false);
            }
        }

        function renderizarResultado(materias) {
            estadoInicial.classList.add('d-none');
            estadoError.classList.add('d-none');
            resultadoConsulta.classList.remove('d-none');

            const estudiante = selectEstudiante.options[selectEstudiante.selectedIndex].text.trim();
            tituloResultado.textContent = estudiante;
            detalleResultado.textContent = materias.length === 1
                ? '1 materia calculada'
                : `${materias.length} materias calculadas`;

            actualizarResumen(materias);
            tbodyMaterias.innerHTML = '';

            if (materias.length === 0) {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td colspan="3" class="text-center text-muted py-4">
                        El estudiante no tiene materias asociadas.
                    </td>
                `;
                tbodyMaterias.appendChild(fila);
                return;
            }

            materias.forEach(function(materia) {
                const fila = document.createElement('tr');
                const espacios = Array.isArray(materia.espacios_curriculares)
                    ? materia.espacios_curriculares
                    : [];

                fila.innerHTML = `
                    <td class="fw-semibold">${escapeHtml(materia.materia ?? '-')}</td>
                    <td>
                        <span class="subject-status" data-status="${escapeHtml(materia.estado ?? '')}">
                            ${escapeHtml(formatearEstado(materia.estado))}
                        </span>
                    </td>
                    <td>
                        <div class="curricular-spaces">
                            ${espacios.map(function(espacio) {
                                return `
                                    <div class="curricular-space">
                                        <span>${escapeHtml(espacio.nombre ?? '-')}</span>
                                        <span class="badge ${obtenerClaseBadge(espacio.estado)}">
                                            ${escapeHtml(formatearEstado(espacio.estado))}
                                        </span>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </td>
                `;

                tbodyMaterias.appendChild(fila);
            });
        }

        function actualizarResumen(materias) {
            const contadores = {
                aprobado: 0,
                en_proceso: 0,
                no_iniciado: 0,
                sin_calificar: 0
            };

            materias.forEach(function(materia) {
                if (Object.hasOwn(contadores, materia.estado)) {
                    contadores[materia.estado]++;
                }
            });

            Object.entries(contadores).forEach(function([estado, cantidad]) {
                document.querySelector(`[data-counter="${estado}"]`).textContent = cantidad;
            });
        }

        function prepararCarga(cargando) {
            btnConsultar.disabled = cargando;
            btnActualizar.disabled = cargando;
            estadoCarga.classList.toggle('d-none', !cargando);

            if (cargando) {
                estadoInicial.classList.add('d-none');
                estadoError.classList.add('d-none');
                resultadoConsulta.classList.add('d-none');
            }
        }

        function mostrarError(mensaje) {
            estadoInicial.classList.add('d-none');
            resultadoConsulta.classList.add('d-none');
            estadoError.textContent = mensaje;
            estadoError.classList.remove('d-none');
        }

        function formatearEstado(estado) {
            const nombres = {
                aprobado: 'Aprobado',
                en_proceso: 'En proceso',
                no_iniciado: 'No iniciado',
                sin_calificar: 'Sin calificar'
            };

            return nombres[estado] ?? estado ?? '-';
        }

        function obtenerClaseBadge(estado) {
            const clases = {
                aprobado: 'text-bg-success',
                en_proceso: 'text-bg-warning',
                no_iniciado: 'text-bg-secondary',
                sin_calificar: 'text-bg-info'
            };

            return clases[estado] ?? 'text-bg-secondary';
        }

        function escapeHtml(valor) {
            const div = document.createElement('div');
            div.textContent = String(valor ?? '');
            return div.innerHTML;
        }
    </script>
</body>

</html>

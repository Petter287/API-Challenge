<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Gestión Académica</title>
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
                <h1 class="h3 mb-1">Inicio</h1>
                <p class="text-muted mb-0">Panel principal de gestión académica</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-4">
                <a href="/estudiante" class="card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="btn btn-primary disabled">
                                <i class="bi bi-people"></i>
                            </span>
                            <div>
                                <h2 class="h5 mb-1">Estudiantes</h2>
                                <p class="text-muted mb-0">Alta, edición y baja de estudiantes.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <a href="/materia" class="card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="btn btn-primary disabled">
                                <i class="bi bi-journal-bookmark"></i>
                            </span>
                            <div>
                                <h2 class="h5 mb-1">Materias</h2>
                                <p class="text-muted mb-0">Administración de materias tradicionales.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <a href="/espacio-curricular" class="card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="btn btn-primary disabled">
                                <i class="bi bi-diagram-3"></i>
                            </span>
                            <div>
                                <h2 class="h5 mb-1">Espacios curriculares</h2>
                                <p class="text-muted mb-0">Gestión de espacios y periodos asociados.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <a href="/estudiante-espacio-curricular" class="card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="btn btn-primary disabled">
                                <i class="bi bi-mortarboard"></i>
                            </span>
                            <div>
                                <h2 class="h5 mb-1">Cursadas</h2>
                                <p class="text-muted mb-0">Relación entre estudiantes, espacios y estados.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <a href="/materia-espacio-curricular" class="card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="btn btn-primary disabled">
                                <i class="bi bi-link-45deg"></i>
                            </span>
                            <div>
                                <h2 class="h5 mb-1">Materia por espacio</h2>
                                <p class="text-muted mb-0">Asociación entre materias y espacios curriculares.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <a href="/estado-espacio-curricular" class="card h-100 text-decoration-none text-body shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="btn btn-primary disabled">
                                <i class="bi bi-check2-circle"></i>
                            </span>
                            <div>
                                <h2 class="h5 mb-1">Estados</h2>
                                <p class="text-muted mb-0">Estados disponibles para las cursadas.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>

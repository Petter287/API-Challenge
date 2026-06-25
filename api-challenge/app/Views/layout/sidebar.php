<?php
$currentPath = trim(service('uri')->getPath(), '/');

$navItems = [
    [
        'label' => 'Home',
        'href' => '/',
        'icon' => 'bi-house',
        'match' => '/'
    ],
    [
        'label' => 'Estudiantes',
        'href' => '/students',
        'icon' => 'bi-people',
        'match' => 'students',
        'exclude' => ['students/subjects-status'],
    ],
    [
        'label' => 'Periodos',
        'href' => '/periods',
        'icon' => 'bi-calendar3',
        'match' => 'periods',
    ],
    [
        'label' => 'Estados',
        'href' => '/curricular-space-statuses',
        'icon' => 'bi-check2-circle',
        'match' => 'curricular-space-statuses',
    ],
    [
        'label' => 'Materias',
        'href' => '/subjects',
        'icon' => 'bi-journal-bookmark',
        'match' => 'subjects',
    ],
    [
        'label' => 'Espacios curriculares',
        'href' => '/curricular-spaces',
        'icon' => 'bi-diagram-3',
        'match' => 'curricular-spaces',
    ],
    [
        'label' => 'Materias por espacio',
        'href' => '/subject-curricular-spaces',
        'icon' => 'bi-link-45deg',
        'match' => 'subject-curricular-spaces',
    ],
    [
        'label' => 'Estudiantes por espacio',
        'href' => '/student-curricular-spaces',
        'icon' => 'bi-mortarboard',
        'match' => 'student-curricular-spaces',
    ],
    [
        'label' => 'Estados finales',
        'href' => '/students/subjects-status',
        'icon' => 'bi-clipboard-data',
        'match' => 'students/subjects-status',
    ],
];
?>

<style>
    body.with-app-shell {
        min-height: 100vh;
        padding-top: 76px;
        padding-left: 280px;
        padding-bottom: 54px;
    }

    .app-sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1030;
        width: 280px;
        overflow-y: auto;
        background: #212529;
        color: #f8f9fa;
    }

    .app-brand {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-height: 76px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, .1);
    }

    .app-brand-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: .5rem;
        background: #0d6efd;
        color: #fff;
        flex: 0 0 auto;
    }

    .app-nav {
        padding: 1rem .875rem;
    }

    .app-nav-link {
        display: flex;
        align-items: center;
        gap: .75rem;
        width: 100%;
        min-height: 42px;
        padding: .625rem .75rem;
        margin-bottom: .25rem;
        border-radius: .5rem;
        color: rgba(255, 255, 255, .78);
        text-decoration: none;
    }

    .app-nav-link:hover,
    .app-nav-link.active {
        color: #fff;
        background: rgba(13, 110, 253, .95);
    }

    .app-nav-link i {
        width: 1.25rem;
        text-align: center;
    }

    .app-topbar,
    .app-footer {
        position: fixed;
        right: 0;
        left: 280px;
        z-index: 1020;
        background: #fff;
        border-color: #dee2e6;
    }

    .app-topbar {
        top: 0;
        min-height: 76px;
        border-bottom: 1px solid #dee2e6;
    }

    .app-footer {
        bottom: 0;
        min-height: 54px;
        border-top: 1px solid #dee2e6;
    }

    @media (max-width: 991.98px) {
        body.with-app-shell {
            padding: 0;
        }

        .app-sidebar,
        .app-topbar,
        .app-footer {
            position: static;
            width: auto;
        }

        .app-sidebar {
            max-height: none;
        }

        .app-nav {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: .25rem;
        }

        .app-nav-link {
            margin-bottom: 0;
        }
    }
</style>

<aside class="app-sidebar">
    <div class="app-brand">
        <span class="app-brand-icon">
            <i class="bi bi-grid-1x2"></i>
        </span>
        <div>
            <div class="fw-semibold">Gestión Académica</div>
            <small class="text-white-50">API Challenge</small>
        </div>
    </div>

    <nav class="app-nav" aria-label="Navegación principal">
        <?php foreach ($navItems as $item): ?>
            <?php
            $excluded = in_array($currentPath, $item['exclude'] ?? [], true);
            $active = !$excluded
                && ($currentPath === $item['match'] || str_starts_with($currentPath, $item['match'] . '/'));
            ?>
            <a class="app-nav-link<?= $active ? ' active' : '' ?>" href="<?= esc($item['href']) ?>">
                <i class="bi <?= esc($item['icon']) ?>"></i>
                <span><?= esc($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>

<header class="app-topbar d-flex align-items-center justify-content-between px-4">
    <div>
        <div class="fw-semibold">Panel administrativo</div>
        <small class="text-muted">Administración de estudiantes, materias y espacios curriculares</small>
    </div>
    <div class="text-end d-none d-sm-block">
        <small class="text-muted">Fecha</small>
        <div class="fw-semibold"><?= esc(date('d/m/Y')) ?></div>
    </div>
</header>

<footer class="app-footer d-flex align-items-center justify-content-between px-4">
    <small class="text-muted">API Challenge</small>
    <small class="text-muted">Quinttos · Gestión académica</small>
</footer>

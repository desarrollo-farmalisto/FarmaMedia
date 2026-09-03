<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="admin-welcome">
            <div>
                <p class="eyebrow">Gestión de contenido</p>
                <h1>Biblioteca de <span>recursos.</span></h1>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= APP_URL ?>/admin" class="btn btn-outline-admin">
                    <i class="bi bi-arrow-left"></i> Panel
                </a>
                <a href="<?= APP_URL ?>/admin/recursos/crear" class="btn btn-coral">
                    <i class="bi bi-plus-lg"></i> Nuevo recurso
                </a>
            </div>
        </div>

        <?php if (isset($_GET['ok'])): ?>
            <div class="alert-success-custom mb-4">
                <i class="bi bi-check-circle"></i>
                <?= $_GET['ok'] === 'creado' ? 'Recurso creado correctamente.' : 'Recurso actualizado correctamente.' ?>
            </div>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Link</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($recursos)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color:var(--muted)">
                            No hay recursos aún. <a href="<?= APP_URL ?>/admin/recursos/crear">Crea el primero.</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recursos as $r): ?>
                    <tr>
                        <td>
                            <div class="resource-preview">
                                <?php
                                $ext = strtolower(pathinfo($r['archivo'] ?? '', PATHINFO_EXTENSION));
                                $url = UPLOAD_URL . '/' . $r['archivo'];
                                if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])): ?>
                                    <img src="<?= htmlspecialchars($url) ?>" alt="preview">
                                <?php elseif (in_array($ext, ['mp4','webm','mov'])): ?>
                                    <video src="<?= htmlspecialchars($url) ?>" muted preload="metadata" data-preview></video>
                                <?php elseif ($ext === 'gif'): ?>
                                    <img src="<?= htmlspecialchars($url) ?>" alt="gif">
                                <?php else: ?>
                                    <div class="preview-icon"><i class="bi bi-file-earmark"></i></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><strong><?= htmlspecialchars($r['nombre'] ?? '—') ?></strong></td>
                        <td class="desc-cell"><?= htmlspecialchars($r['descripcion'] ?? '') ?></td>
                        <td><span class="badge-type"><?= htmlspecialchars($r['tipo']) ?></span></td>
                        <td>
                            <?php if ($r['link']): ?>
                                <a href="<?= htmlspecialchars($r['link']) ?>" target="_blank" class="text-link">
                                    <i class="bi bi-link-45deg"></i> Ver
                                </a>
                            <?php else: ?>
                                <span style="color:var(--muted)">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="<?= APP_URL ?>/admin/recursos/<?= $r['id'] ?>/editar" class="btn-action btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn-action btn-delete" title="Eliminar"
                                    data-id="<?= $r['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($r['nombre']) ?>"
                                    data-url="<?= APP_URL ?>/admin/recursos/<?= $r['id'] ?>/borrar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<form id="delete-form" method="POST" style="display:none"></form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ── SweetAlert borrar ───────────────────────────────────
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', () => {
        Swal.fire({
            title: '¿Eliminar recurso?',
            html: `<span style="color:#7F7F7F">Se eliminará <strong>${btn.dataset.nombre}</strong> de forma permanente.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF5A6B',
            cancelButtonColor: '#018F9B',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            borderRadius: '1rem',
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = btn.dataset.url;
                form.submit();
            }
        });
    });
});

// ── Preview animado (estilo YouTube) ────────────────────
const PREVIEW_DURATION = 4;   // segundos que se reproduce
const PREVIEW_INTERVAL = 8;   // segundos entre cada ciclo

document.querySelectorAll('video[data-preview]').forEach(video => {
    let playTimer, stopTimer;

    const startCycle = () => {
        video.currentTime = 0;
        video.play();
        stopTimer = setTimeout(() => {
            video.pause();
            video.currentTime = 0;
        }, PREVIEW_DURATION * 1000);
    };

    // Primer ciclo al cargar
    startCycle();

    // Repetir cada PREVIEW_INTERVAL segundos
    playTimer = setInterval(startCycle, PREVIEW_INTERVAL * 1000);

    // Limpiar al salir de la página
    window.addEventListener('beforeunload', () => {
        clearInterval(playTimer);
        clearTimeout(stopTimer);
    });
});
</script>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>

<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="admin-welcome">
            <div>
                <p class="eyebrow">Panel de control · 2026</p>
                <h1>Bienvenido al <span>panel.</span></h1>
            </div>
            <a class="btn btn-coral" href="<?= APP_URL ?>/admin/recursos">
                Ver recursos <i class="bi bi-arrow-up-right"></i>
            </a>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <?php foreach ($stats as $i => $stat): ?>
                <div class="col-12 col-sm-6">
                    <div class="stat-card stat-tone-<?= $i + 1 ?>">
                        <small><?= htmlspecialchars($stat['label']) ?></small>
                        <span class="stat-value"><?= htmlspecialchars($stat['value']) ?></span>
                        <p class="stat-change"><?= htmlspecialchars($stat['change']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Actividad reciente -->
        <div class="section-heading mb-3">
            <div>
                <p class="eyebrow">Últimos movimientos</p>
                <h2>Actividad <span>reciente.</span></h2>
            </div>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Nombre</th>
                        <th>Link</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($ultimos)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4" style="color:var(--muted)">
                            Aún no hay recursos. <a href="<?= APP_URL ?>/admin/recursos/crear">Crea el primero.</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ultimos as $r): ?>
                    <tr>
                        <td>
                            <div class="resource-preview">
                                <?php
                                $ext = strtolower(pathinfo($r['archivo'] ?? '', PATHINFO_EXTENSION));
                                $url = UPLOAD_URL . '/' . $r['archivo'];
                                if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])): ?>
                                    <img src="<?= htmlspecialchars($url) ?>" alt="preview">
                                <?php elseif (in_array($ext, ['mp4','webm','mov'])): ?>
                                    <video src="<?= htmlspecialchars($url) ?>" muted></video>
                                <?php elseif ($ext === 'gif'): ?>
                                    <img src="<?= htmlspecialchars($url) ?>" alt="gif">
                                <?php else: ?>
                                    <div class="preview-icon"><i class="bi bi-file-earmark"></i></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><strong><?= htmlspecialchars($r['nombre']) ?></strong></td>
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
                                <button class="btn-action btn-comments" title="Comentarios"
                                    data-id="<?= $r['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($r['nombre']) ?>"
                                    data-url="<?= APP_URL ?>/admin/recursos/<?= $r['id'] ?>/comentarios">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
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

<!-- Modal Comentarios -->
<div class="modal fade" id="modalComentarios" tabindex="-1" aria-labelledby="modalComentariosLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalComentariosLabel">Comentarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalComentariosBody">
                <p class="text-center" style="color:var(--muted)">Cargando...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-comments').forEach(btn => {
    btn.addEventListener('click', () => {
        const body   = document.getElementById('modalComentariosBody');
        const title  = document.getElementById('modalComentariosLabel');
        title.textContent = 'Comentarios · ' + btn.dataset.nombre;
        body.innerHTML = '<p class="text-center" style="color:var(--muted)">Cargando...</p>';

        const modal = new bootstrap.Modal(document.getElementById('modalComentarios'));
        modal.show();

        fetch(btn.dataset.url)
            .then(r => r.json())
            .then(comments => {
                if (!comments.length) {
                    body.innerHTML = '<p class="text-center" style="color:var(--muted)">Sin comentarios aún.</p>';
                    return;
                }
                body.innerHTML = comments.map(c => `
                    <div class="comment-item">
                        <p class="comment-text">${c.description}</p>
                        <small class="comment-date">${new Date(c.created_at).toLocaleString('es-CO')}</small>
                    </div>
                `).join('');
            })
            .catch(() => {
                body.innerHTML = '<p class="text-center text-danger">Error al cargar comentarios.</p>';
            });
    });
});
</script>
<script>
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
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = btn.dataset.url;
                form.submit();
            }
        });
    });
});
</script>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>

<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="admin-welcome">
            <div>
                <p class="eyebrow">Nuevo recurso</p>
                <h1>Crear <span>recurso.</span></h1>
            </div>
            <a href="<?= APP_URL ?>/admin/recursos" class="btn btn-outline-admin">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="form-card">
            <form method="POST" action="<?= APP_URL ?>/admin/recursos/crear" enctype="multipart/form-data" novalidate>

                <div class="form-group <?= isset($errors['nombre']) ? 'has-error' : '' ?>">
                    <label for="nombre">Nombre de la publicación</label>
                    <input type="text" id="nombre" name="nombre" class="form-input"
                           value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
                           placeholder="Ej. Lanzamiento de temporada">
                    <?php if (isset($errors['nombre'])): ?>
                        <span class="field-error"><?= $errors['nombre'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['descripcion']) ? 'has-error' : '' ?>">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input" rows="3"
                              placeholder="Describe brevemente el recurso"><?= htmlspecialchars($old['descripcion'] ?? '') ?></textarea>
                    <?php if (isset($errors['descripcion'])): ?>
                        <span class="field-error"><?= $errors['descripcion'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['tipo']) ? 'has-error' : '' ?>">
                    <label for="tipo">Tipo de recurso</label>
                    <select id="tipo" name="tipo" class="form-input">
                        <option value="">— Selecciona un tipo —</option>
                        <option value="imagen"     <?= ($old['tipo'] ?? '') === 'imagen'     ? 'selected' : '' ?>>🖼 Imagen</option>
                        <option value="video"      <?= ($old['tipo'] ?? '') === 'video'      ? 'selected' : '' ?>>🎬 Video</option>
                        <option value="gif"        <?= ($old['tipo'] ?? '') === 'gif'        ? 'selected' : '' ?>>🎞 GIF</option>
                        <option value="audio"      <?= ($old['tipo'] ?? '') === 'audio'      ? 'selected' : '' ?>>🎵 Audio</option>
                        <option value="documento"  <?= ($old['tipo'] ?? '') === 'documento'  ? 'selected' : '' ?>>📄 Documento</option>
                        <option value="otro"       <?= ($old['tipo'] ?? '') === 'otro'       ? 'selected' : '' ?>>📦 Otro</option>
                    </select>
                    <?php if (isset($errors['tipo'])): ?>
                        <span class="field-error"><?= $errors['tipo'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($errors['archivo']) ? 'has-error' : '' ?>" id="file-group" style="display:none">
                    <label for="archivo" id="file-label">Archivo</label>
                    <input type="file" id="archivo" name="archivo" class="form-input form-input-file">
                    <small class="field-hint" id="file-hint"></small>
                    <?php if (isset($errors['archivo'])): ?>
                        <span class="field-error"><?= $errors['archivo'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="link">Link externo <span class="optional">(opcional)</span></label>
                    <input type="url" id="link" name="link" class="form-input"
                           value="<?= htmlspecialchars($old['link'] ?? '') ?>"
                           placeholder="https://...">
                </div>

                <div class="form-actions">
                    <a href="<?= APP_URL ?>/admin/recursos" class="btn btn-outline-admin">Cancelar</a>
                    <button type="submit" class="btn btn-coral">
                        <i class="bi bi-check-lg"></i> Guardar recurso
                    </button>
                </div>

            </form>
        </div>

    </div>
</main>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>

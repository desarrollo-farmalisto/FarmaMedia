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
                    <label for="descripcion">Descripción <span class="optional">(opcional)</span></label>
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
                    <label for="archivo" id="file-label">Archivos</label>
                    <input type="file" id="archivo" name="archivo[]" class="form-input form-input-file" multiple>
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

                <!-- Modo cuaderno -->
                <div class="form-group" id="cuaderno-group" style="display:none">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="modo_cuaderno" name="modo_cuaderno" value="1" role="switch">
                            <label class="form-check-label fw-600" for="modo_cuaderno">Modo cuaderno</label>
                        </div>
                    </div>
                    <small id="cuaderno-msg" class="field-hint" style="display:none;color:var(--highlight)">No puedes activar esta funcionalidad sin tener recursos cargados.</small>
                </div>

                <!-- Lista de orden -->
                <div id="orden-list" style="display:none">
                    <p class="eyebrow mb-2">Asigna el orden de cada recurso</p>
                    <div id="orden-items"></div>
                </div>

                <!-- Info individual -->
                <div id="info-individual-group" style="display:none">
                    <button type="button" id="btn-info-individual" class="btn-add-info">
                        <span class="btn-add-info-icon"><i class="bi bi-plus-lg"></i></span>
                        Agregar información a recursos
                    </button>
                    <input type="hidden" name="info_individual" id="info_individual" value="0">
                    <div id="info-individual-list" style="display:none"></div>
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

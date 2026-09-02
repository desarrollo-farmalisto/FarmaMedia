<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<main>

    <!-- Hero -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-end g-5">
                <div class="col-lg-8">
                    <p class="eyebrow">Biblioteca creativa · 2026</p>
                    <h1>Recursos que<br><span>se hacen notar.</span></h1>
                    <?php if (isAdmin()): ?>
                    <a class="btn btn-coral" href="<?= APP_URL ?>/admin">Entrar al panel <i class="bi bi-arrow-up-right"></i></a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Recursos -->
    <section class="pub-section">
        <div class="container">

            <div class="section-heading mb-4">
                <div>
                    <p class="eyebrow">Publicaciones</p>
                </div>
            </div>

            <?php if (empty($recursos)): ?>
                <div class="pub-empty">
                    <i class="bi bi-inbox"></i>
                    <p>Aún no hay recursos publicados.</p>
                </div>
            <?php else: ?>
                <div class="row g-4 justify-content-center">
                    <?php foreach ($recursos as $r): ?>
                    <?php
                        $ext = strtolower(pathinfo($r['archivo'] ?? '', PATHINFO_EXTENSION));
                        $url = UPLOAD_URL . '/' . $r['archivo'];
                        $isVideo = in_array($ext, ['mp4','webm','mov','avi']);
                        $isImg   = in_array($ext, ['jpg','jpeg','png','webp','svg','gif']);
                    ?>
                    <div class="col-12 col-sm-6 col-xl-4">
                        <article class="pub-card" id="recurso-<?= $r['id'] ?>">

                            <!-- Título -->
                            <div class="pub-card-header">
                                <span class="badge-type"><?= htmlspecialchars($r['tipo']) ?></span>
                                <h3 class="pub-title"><?= htmlspecialchars($r['nombre']) ?></h3>
                            </div>

                            <!-- Media -->
                            <div class="pub-media">
                                <?php if ($isVideo): ?>
                                    <video src="<?= htmlspecialchars($url) ?>" muted preload="metadata" data-preview class="pub-video"></video>
                                    <div class="pub-play-icon"><i class="bi bi-play-circle-fill"></i></div>
                                <?php elseif ($isImg): ?>
                                    <img src="<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars($r['nombre']) ?>" class="pub-img">
                                <?php else: ?>
                                    <div class="pub-file-icon">
                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                        <span><?= strtoupper($ext) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Descripción -->
                            <?php if ($r['descripcion']): ?>
                                <p class="pub-desc"><?= htmlspecialchars($r['descripcion']) ?></p>
                            <?php endif; ?>

                            <!-- Link -->
                            <?php if (!empty($r['link'])): ?>
                                <a href="<?= htmlspecialchars($r['link']) ?>" target="_blank" rel="noopener noreferrer" class="pub-link">
                                    <i class="bi bi-box-arrow-up-right"></i> Ver publicación en redes
                                </a>
                            <?php endif; ?>

                            <!-- Comentarios -->
                            <div class="pub-comment-box">
                                <form method="POST" action="<?= APP_URL ?>/recursos/<?= $r['id'] ?>/comentar">
                                    <textarea name="description" class="comment-input" rows="2" placeholder="Escribe un comentario…" required></textarea>
                                    <button type="submit" class="comment-btn">
                                        <i class="bi bi-send"></i> Publicar
                                    </button>
                                </form>
                            </div>

                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<script>
const PREVIEW_DURATION = 4;
const PREVIEW_INTERVAL = 8;

document.querySelectorAll('video[data-preview]').forEach(video => {
    let stopTimer;

    const startCycle = () => {
        video.currentTime = 0;
        video.play();
        stopTimer = setTimeout(() => {
            video.pause();
            video.currentTime = 0;
        }, PREVIEW_DURATION * 1000);
    };

    startCycle();
    setInterval(startCycle, PREVIEW_INTERVAL * 1000);
});
</script>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>

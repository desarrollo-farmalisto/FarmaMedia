<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<main>

    <!-- Hero -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-end g-5">
                <div class="col-lg-8">
                    <p class="eyebrow">Biblioteca creativa · 2026</p>
                    <h1>Recursos que<br><span>se hacen notar.</span></h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Publicaciones -->
    <section class="pub-section">
        <div class="container">

            <div class="section-heading mb-4">
                <div><p class="eyebrow">Publicaciones</p></div>
            </div>

            <?php if (empty($recursos)): ?>
                <div class="pub-empty">
                    <i class="bi bi-inbox"></i>
                    <p>Aún no hay recursos publicados.</p>
                </div>
            <?php else: ?>
                <div class="row g-4 justify-content-center">
                <?php foreach ($recursos as $r):
                    $archivos = $r['archivos'];
                    if (empty($archivos)) continue;
                    $primero = $archivos[0];
                ?>
                <div class="col-12">

                <?php if ($r['modo_cuaderno']): ?>

                    <!-- Cuaderno -->
                    <div class="cuaderno-book" id="cuaderno-<?= $r['id'] ?>">
                        <div class="cuaderno-lomo"></div>
                        <div class="cuaderno-pages">
                        <?php foreach ($archivos as $i => $a):
                            $ext     = strtolower(pathinfo($a['archivo'] ?? '', PATHINFO_EXTENSION));
                            $url     = UPLOAD_URL . '/' . $a['archivo'];
                            $isVideo = in_array($ext, ['mp4','webm','mov','avi']);
                            $isAudio = in_array($ext, ['mp3','wav','ogg']);
                            $isImg   = in_array($ext, ['jpg','jpeg','png','webp','svg','gif']);
                        ?>
                            <div class="cuaderno-slide<?= $i === 0 ? ' active' : '' ?>" data-index="<?= $i ?>">
                                <div class="cuaderno-media-wrap">
                                    <?php if ($isVideo): ?>
                                        <video src="<?= htmlspecialchars($url) ?>" class="cuaderno-media" autoplay loop muted playsinline></video>
                                    <?php elseif ($isAudio): ?>
                                        <div class="cuaderno-audio">
                                            <div class="audio-anim"><i class="bi bi-music-note-beamed"></i></div>
                                            <audio src="<?= htmlspecialchars($url) ?>" autoplay loop></audio>
                                        </div>
                                    <?php elseif ($isImg): ?>
                                        <img src="<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars($a['nombre']) ?>" class="cuaderno-media">
                                    <?php else: ?>
                                        <div class="cuaderno-file"><i class="bi bi-file-earmark"></i><span><?= strtoupper($ext) ?></span></div>
                                    <?php endif; ?>
                                </div>
                                <div class="cuaderno-info">
                                    <div class="cuaderno-info-header">
                                        <span class="badge-type"><?= htmlspecialchars($r['tipo']) ?></span>
                                        <h3 class="pub-title"><?= htmlspecialchars($a['nombre']) ?></h3>
                                        <small class="pub-date"><i class="bi bi-calendar3"></i> <?= date('d \d\e F \d\e Y', strtotime($r['created_at'])) ?></small>
                                    </div>
                                    <?php if ($a['descripcion']): ?>
                                        <p class="pub-desc"><?= htmlspecialchars($a['descripcion']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($a['link'])): ?>
                                        <a href="<?= htmlspecialchars($a['link']) ?>" target="_blank" rel="noopener noreferrer" class="pub-link">
                                            <i class="bi bi-box-arrow-up-right"></i> Ver publicación en redes
                                        </a>
                                    <?php endif; ?>
                                    <div class="pub-comment-box">
                                        <form method="POST" action="<?= APP_URL ?>/recursos/<?= $r['id'] ?>/comentar">
                                            <textarea name="description" class="comment-input" rows="2" placeholder="Escribe un comentario…" required></textarea>
                                            <button type="submit" class="comment-btn"><i class="bi bi-send"></i> Publicar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                        <?php if (count($archivos) > 1): ?>
                        <button class="cuaderno-arrow cuaderno-prev"><i class="bi bi-chevron-left"></i></button>
                        <button class="cuaderno-arrow cuaderno-next"><i class="bi bi-chevron-right"></i></button>
                        <div class="cuaderno-dots">
                            <?php foreach ($archivos as $i => $a): ?>
                                <span class="cuaderno-dot<?= $i === 0 ? ' active' : '' ?>" data-index="<?= $i ?>"></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                <?php else: ?>

                    <!-- Recursos normales — uno por archivo -->
                    <?php foreach ($archivos as $a):
                        $ext     = strtolower(pathinfo($a['archivo'] ?? '', PATHINFO_EXTENSION));
                        $url     = UPLOAD_URL . '/' . $a['archivo'];
                        $isVideo = in_array($ext, ['mp4','webm','mov','avi']);
                        $isAudio = in_array($ext, ['mp3','wav','ogg']);
                        $isImg   = in_array($ext, ['jpg','jpeg','png','webp','svg','gif']);
                    ?>
                    <article class="pub-card" id="recurso-<?= $r['id'] ?>">
                        <div class="pub-media">
                            <?php if ($isVideo): ?>
                                <video src="<?= htmlspecialchars($url) ?>" controls class="pub-video"></video>
                            <?php elseif ($isImg): ?>
                                <img src="<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars($a['nombre']) ?>" class="pub-img">
                            <?php elseif ($isAudio): ?>
                                <div class="pub-file-icon">
                                    <i class="bi bi-music-note-beamed"></i>
                                    <audio src="<?= htmlspecialchars($url) ?>" controls style="width:100%;margin-top:.5rem"></audio>
                                </div>
                            <?php else: ?>
                                <div class="pub-file-icon">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                    <span><?= strtoupper($ext) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pub-card-body">
                            <div class="pub-card-header">
                                <span class="badge-type"><?= htmlspecialchars($r['tipo']) ?></span>
                                <h3 class="pub-title"><?= htmlspecialchars($a['nombre']) ?></h3>
                                <small class="pub-date"><i class="bi bi-calendar3"></i> <?= date('d \d\e F \d\e Y', strtotime($r['created_at'])) ?></small>
                            </div>
                            <?php if ($a['descripcion']): ?>
                                <p class="pub-desc"><?= htmlspecialchars($a['descripcion']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($a['link'])): ?>
                                <a href="<?= htmlspecialchars($a['link']) ?>" target="_blank" rel="noopener noreferrer" class="pub-link">
                                    <i class="bi bi-box-arrow-up-right"></i> Ver publicación en redes
                                </a>
                            <?php endif; ?>
                            <div class="pub-comment-box">
                                <form method="POST" action="<?= APP_URL ?>/recursos/<?= $r['id'] ?>/comentar">
                                    <textarea name="description" class="comment-input" rows="2" placeholder="Escribe un comentario…" required></textarea>
                                    <button type="submit" class="comment-btn"><i class="bi bi-send"></i> Publicar</button>
                                </form>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>

                <?php endif; ?>
                </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<script>
// Cuaderno — un carrusel por cada .cuaderno-book
document.querySelectorAll('.cuaderno-book').forEach(book => {
    const slides = book.querySelectorAll('.cuaderno-slide');
    const dots   = book.querySelectorAll('.cuaderno-dot');
    const prev   = book.querySelector('.cuaderno-prev');
    const next   = book.querySelector('.cuaderno-next');
    let current  = 0;

    if (slides.length <= 1) return;

    function goTo(n) {
        const prevMedia = slides[current].querySelector('video, audio');
        if (prevMedia) { prevMedia.pause(); prevMedia.currentTime = 0; }
        slides[current].classList.remove('active');
        if (dots[current]) dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        if (dots[current]) dots[current].classList.add('active');
        const newMedia = slides[current].querySelector('video, audio');
        if (newMedia) newMedia.play();
    }

    prev?.addEventListener('click', () => goTo(current - 1));
    next?.addEventListener('click', () => goTo(current + 1));
    dots.forEach(dot => dot.addEventListener('click', () => goTo(parseInt(dot.dataset.index))));

    let startX = 0;
    book.addEventListener('touchstart', e => startX = e.touches[0].clientX, { passive: true });
    book.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) goTo(diff > 0 ? current + 1 : current - 1);
    }, { passive: true });
});
</script>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>

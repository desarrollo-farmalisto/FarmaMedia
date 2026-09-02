<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="admin-welcome">
            <div>
                <p class="eyebrow">Biblioteca de recursos · 2026</p>
                <h1>Todos los <span>recursos.</span></h1>
            </div>
            <a class="btn btn-coral" href="<?= APP_URL ?>/admin">
                <i class="bi bi-arrow-left"></i> Volver al panel
            </a>
        </div>

        
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Recurso</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>Lanzamiento de temporada</td>
                        <td><span class="badge-type">Campaña</span></td>
                        <td><span class="badge-status status-active">Publicado</span></td>
                        <td>08 ene 2026</td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>Historias que conectan</td>
                        <td><span class="badge-type">Video</span></td>
                        <td><span class="badge-status status-review">En revisión</span></td>
                        <td>07 ene 2026</td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td>Identidad en movimiento</td>
                        <td><span class="badge-type">Kit visual</span></td>
                        <td><span class="badge-status status-active">Publicado</span></td>
                        <td>05 ene 2026</td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td>Guía de marca 2026</td>
                        <td><span class="badge-type">Documento</span></td>
                        <td><span class="badge-status status-draft">Borrador</span></td>
                        <td>03 ene 2026</td>
                    </tr>
                    <tr>
                        <td>05</td>
                        <td>Pack redes sociales Q1</td>
                        <td><span class="badge-type">Kit visual</span></td>
                        <td><span class="badge-status status-active">Publicado</span></td>
                        <td>02 ene 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>

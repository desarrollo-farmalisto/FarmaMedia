// FarmaMedia · app.js

const tipoConfig = {
    imagen:    { label: 'Imagen',    hint: 'Permitido: jpg, jpeg, png, gif, webp, svg', accept: '.jpg,.jpeg,.png,.gif,.webp,.svg' },
    video:     { label: 'Video',     hint: 'Permitido: mp4, webm, mov, avi',            accept: '.mp4,.webm,.mov,.avi' },
    gif:       { label: 'GIF',       hint: 'Permitido: gif',                            accept: '.gif' },
    audio:     { label: 'Audio',     hint: 'Permitido: mp3, wav, ogg',                  accept: '.mp3,.wav,.ogg' },
    documento: { label: 'Documento', hint: 'Permitido: pdf, doc, docx, ppt, pptx, xls, xlsx', accept: '.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx' },
    otro:      { label: 'Archivo',   hint: 'Permitido: zip, rar',                       accept: '.zip,.rar' },
};

const tipoSelect = document.getElementById('tipo');
const fileGroup  = document.getElementById('file-group');
const fileLabel  = document.getElementById('file-label');
const fileInput  = document.getElementById('archivo');
const fileHint   = document.getElementById('file-hint');


if (tipoSelect) {
    const updateFileInput = (val) => {
        if (!val || !tipoConfig[val]) {
            if (fileGroup) fileGroup.style.display = 'none';
            return;
        }
        const cfg = tipoConfig[val];
        if (fileLabel) fileLabel.textContent = cfg.label;
        if (fileInput) fileInput.accept = cfg.accept;
        if (fileHint)  fileHint.textContent = cfg.hint;
        if (fileGroup) fileGroup.style.display = 'block';
    };

    tipoSelect.addEventListener('change', e => updateFileInput(e.target.value));

    // Al cargar (editar), mostrar si ya hay tipo seleccionado
    updateFileInput(tipoSelect.value);
}

// FarmaMedia · app.js

const tipoConfig = {
    imagen:    { label: 'Imágenes',   hint: 'Permitido: jpg, jpeg, png, gif, webp, svg · Puedes seleccionar varios archivos', accept: '.jpg,.jpeg,.png,.gif,.webp,.svg' },
    video:     { label: 'Videos',     hint: 'Permitido: mp4, webm, mov, avi · Puedes seleccionar varios archivos',            accept: '.mp4,.webm,.mov,.avi' },
    gif:       { label: 'GIFs',       hint: 'Permitido: gif · Puedes seleccionar varios archivos',                            accept: '.gif' },
    audio:     { label: 'Audios',     hint: 'Permitido: mp3, wav, ogg · Puedes seleccionar varios archivos',                  accept: '.mp3,.wav,.ogg' },
    documento: { label: 'Documentos', hint: 'Permitido: pdf, doc, docx, ppt, pptx, xls, xlsx · Puedes seleccionar varios archivos', accept: '.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx' },
    otro:      { label: 'Archivos',   hint: 'Permitido: zip, rar · Puedes seleccionar varios archivos',                       accept: '.zip,.rar' },
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

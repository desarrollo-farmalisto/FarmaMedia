// FarmaMedia · app.js

const tipoConfig = {
    imagen:    { label: 'Imágenes',   hint: 'Permitido: jpg, jpeg, png, gif, webp, svg · Puedes seleccionar varios archivos', accept: '.jpg,.jpeg,.png,.gif,.webp,.svg' },
    video:     { label: 'Videos',     hint: 'Permitido: mp4, webm, mov, avi · Puedes seleccionar varios archivos',            accept: '.mp4,.webm,.mov,.avi' },
    gif:       { label: 'GIFs',       hint: 'Permitido: gif · Puedes seleccionar varios archivos',                            accept: '.gif' },
    audio:     { label: 'Audios',     hint: 'Permitido: mp3, wav, ogg · Puedes seleccionar varios archivos',                  accept: '.mp3,.wav,.ogg' },
    documento: { label: 'Documentos', hint: 'Permitido: pdf, doc, docx, ppt, pptx, xls, xlsx · Puedes seleccionar varios archivos', accept: '.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx' },
    otro:      { label: 'Archivos',   hint: 'Permitido: zip, rar · Puedes seleccionar varios archivos',                       accept: '.zip,.rar' },
};

const tipoSelect   = document.getElementById('tipo');
const fileGroup    = document.getElementById('file-group');
const fileLabel    = document.getElementById('file-label');
const fileInput    = document.getElementById('archivo');
const fileHint     = document.getElementById('file-hint');
const cuadernoToggle = document.getElementById('modo_cuaderno');
const cuadernoMsg    = document.getElementById('cuaderno-msg');
const cuadernoGroup  = document.getElementById('cuaderno-group');
const ordenList      = document.getElementById('orden-list');
const ordenItems     = document.getElementById('orden-items');

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
    updateFileInput(tipoSelect.value);
}

// ── Modo cuaderno ──────────────────────────────────────

function resolveOrden() {
    const inputs = ordenItems ? [...ordenItems.querySelectorAll('input[type=number]')] : [];
    inputs.forEach(inp => {
        let val = parseInt(inp.value) || 1;
        if (val < 1) val = 1;
        inp.value = val;
    });

    // Resolver duplicados con cascada
    let changed = true;
    while (changed) {
        changed = false;
        const vals = inputs.map(i => parseInt(i.value));
        for (let i = 0; i < inputs.length; i++) {
            for (let j = i + 1; j < inputs.length; j++) {
                if (vals[i] === vals[j]) {
                    vals[j]++;
                    inputs[j].value = vals[j];
                    changed = true;
                }
            }
        }
    }
}

function buildOrdenList() {
    if (!ordenItems || !fileInput) return;
    ordenItems.innerHTML = '';
    const files = fileInput.files;
    if (!files || files.length === 0) return;

    [...files].forEach((file, i) => {
        const row = document.createElement('div');
        row.className = 'orden-row';
        row.innerHTML = `
            <span class="orden-filename">${file.name}</span>
            <input type="number" name="orden[]" class="form-input orden-input" value="${i + 1}" min="1">
        `;
        ordenItems.appendChild(row);
    });

    ordenItems.querySelectorAll('input[type=number]').forEach(inp => {
        inp.addEventListener('change', resolveOrden);
        inp.addEventListener('blur', resolveOrden);
        inp.addEventListener('input', () => {
            if (parseInt(inp.value) < 1 || inp.value === '0') inp.value = 1;
        });
    });
}

if (cuadernoToggle) {
    cuadernoToggle.addEventListener('change', () => {
        const hasFiles = fileInput && fileInput.files && fileInput.files.length > 0;
        if (cuadernoToggle.checked && !hasFiles) {
            cuadernoToggle.checked = false;
            cuadernoMsg.style.display = 'block';
            ordenList.style.display = 'none';
            return;
        }
        cuadernoMsg.style.display = 'none';
        if (cuadernoToggle.checked) {
            buildOrdenList();
            ordenList.style.display = 'block';
        } else {
            ordenList.style.display = 'none';
        }
    });
}

if (fileInput) {
    const onFileChange = () => {
        const hasFiles = fileInput.files && fileInput.files.length > 0;

        if (cuadernoGroup) cuadernoGroup.style.display = hasFiles ? 'block' : 'none';

        const infoGroup = document.getElementById('info-individual-group');
        if (infoGroup) infoGroup.style.display = hasFiles ? 'block' : 'none';

        const infoIndividual = document.getElementById('info_individual');
        if (infoIndividual && infoIndividual.value === '1') buildInfoList();

        if (cuadernoToggle && cuadernoToggle.checked) {
            if (!hasFiles) {
                cuadernoToggle.checked = false;
                if (ordenList) ordenList.style.display = 'none';
                if (cuadernoMsg) cuadernoMsg.style.display = 'block';
            } else {
                buildOrdenList();
            }
        }
    };

    fileInput.addEventListener('change', onFileChange);
    fileInput.addEventListener('input', onFileChange);
}

// ── Info individual ─────────────────────────────────────

function buildInfoList() {
    const list = document.getElementById('info-individual-list');
    if (!list || !fileInput) return;
    list.innerHTML = '';
    if (!fileInput.files || fileInput.files.length === 0) return;

    [...fileInput.files].forEach((file, i) => {
        const card = document.createElement('div');
        card.className = 'info-individual-card';
        card.innerHTML = `
            <p class="info-individual-filename"><i class="bi bi-paperclip"></i> ${file.name}</p>
            <div class="form-group">
                <label>Título <span class="optional">(opcional)</span></label>
                <input type="text" name="titulo_individual[]" class="form-input" placeholder="Título del recurso">
            </div>
            <div class="form-group">
                <label>Descripción <span class="optional">(opcional)</span></label>
                <textarea name="descripcion_individual[]" class="form-input" rows="2" placeholder="Descripción del recurso"></textarea>
            </div>
            <div class="form-group">
                <label>Link <span class="optional">(opcional)</span></label>
                <input type="url" name="link_individual[]" class="form-input" placeholder="https://...">
            </div>
        `;
        list.appendChild(card);
    });
}

const btnInfoIndividual = document.getElementById('btn-info-individual');
const infoIndividualInput = document.getElementById('info_individual');
const infoIndividualList  = document.getElementById('info-individual-list');

if (btnInfoIndividual) {
    btnInfoIndividual.addEventListener('click', () => {
        const active = infoIndividualInput.value === '1';
        if (active) {
            infoIndividualInput.value = '0';
            infoIndividualList.style.display = 'none';
            btnInfoIndividual.innerHTML = '<span class="btn-add-info-icon"><i class="bi bi-plus-lg"></i></span> Agregar información a recursos';
        } else {
            buildInfoList();
            infoIndividualInput.value = '1';
            infoIndividualList.style.display = 'block';
            btnInfoIndividual.innerHTML = '<span class="btn-add-info-icon"><i class="bi bi-dash-lg"></i></span> Quitar información individual';
        }
    });
}

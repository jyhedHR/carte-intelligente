{{-- Shared form fields used by create.blade.php and edit.blade.php --}}

{{-- Department --}}
<div class="form-group" style="margin-bottom:20px;">
    <label class="form-label" style="display:block;font-size:13px;color:var(--text2,#ccc);margin-bottom:6px;font-weight:500;">
        Département <span style="color:#f87171;">*</span>
    </label>
    <select name="department_id" id="department_id" class="form-select" required
            style="width:100%;padding:10px 14px;background:var(--bg3,rgba(255,255,255,0.05));border:1px solid var(--border,rgba(255,255,255,0.12));border-radius:8px;color:var(--text,#fff);font-size:14px;">
        <option value="">— Choisir un département —</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}"
                {{ old('department_id', $reference->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name_fr ?? $dept->name }}
            </option>
        @endforeach
    </select>
    @error('department_id')<p class="form-error">{{ $message }}</p>@enderror
</div>

{{-- Formulaire (optional) --}}
<div class="form-group" style="margin-bottom:20px;">
    <label class="form-label" style="display:block;font-size:13px;color:var(--text2,#ccc);margin-bottom:6px;font-weight:500;">
        Formulaire <span style="color:var(--text3,#888);font-weight:400;">(optionnel — laisser vide pour s'appliquer à tout le département)</span>
    </label>
    <select name="formulaire_id" id="formulaire_id" class="form-select"
            style="width:100%;padding:10px 14px;background:var(--bg3,rgba(255,255,255,0.05));border:1px solid var(--border,rgba(255,255,255,0.12));border-radius:8px;color:var(--text,#fff);font-size:14px;">
        <option value="">— Tous les formulaires du département —</option>
        @foreach($formulaires as $form)
            <option value="{{ $form->id }}"
                    data-dept="{{ $form->department_id }}"
                {{ old('formulaire_id', $reference->formulaire_id ?? '') == $form->id ? 'selected' : '' }}>
                {{ $form->titre }}
                @if($form->department)
                    ({{ $form->department->name_fr ?? $form->department->name }})
                @endif
            </option>
        @endforeach
    </select>
    @error('formulaire_id')<p class="form-error">{{ $message }}</p>@enderror
</div>

<hr style="border:none;border-top:1px solid var(--border,rgba(255,255,255,0.08));margin:24px 0;">
<p style="margin:0 0 20px;font-size:13px;color:var(--text3,#888);font-style:italic;">
    Le format final sera : <strong style="font-family:monospace;" id="livePreview" style="color:var(--gold,#D4AF37);">—</strong>
</p>

{{-- Prefix --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
    <div class="form-group">
        <label class="form-label" style="display:block;font-size:13px;color:var(--text2,#ccc);margin-bottom:6px;font-weight:500;">
            Préfixe <span style="color:#f87171;">*</span>
        </label>
        <input type="text" name="prefix" id="prefix" class="form-input preview-trigger"
               value="{{ old('prefix', $reference->prefix ?? '') }}"
               maxlength="10" placeholder="ex : DRH, FIN, REC" required
               style="width:100%;padding:10px 14px;background:var(--bg3,rgba(255,255,255,0.05));border:1px solid var(--border,rgba(255,255,255,0.12));border-radius:8px;color:var(--text,#fff);font-size:14px;box-sizing:border-box;"
               oninput="this.value=this.value.toUpperCase()">
        @error('prefix')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" style="display:block;font-size:13px;color:var(--text2,#ccc);margin-bottom:6px;font-weight:500;">
            Séparateur <span style="color:#f87171;">*</span>
        </label>
        <select name="separator" id="separator" class="form-select preview-trigger"
                style="width:100%;padding:10px 14px;background:var(--bg3,rgba(255,255,255,0.05));border:1px solid var(--border,rgba(255,255,255,0.12));border-radius:8px;color:var(--text,#fff);font-size:14px;">
            @foreach(['-' => 'Tiret  —  DRH-2025-0001', '/' => 'Slash  —  DRH/2025/0001', '_' => 'Underscore  —  DRH_2025_0001', '.' => 'Point  —  DRH.2025.0001'] as $val => $label)
                <option value="{{ $val }}" {{ old('separator', $reference->separator ?? '-') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('separator')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Year / Month toggles --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
    <label class="toggle-label" style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:12px 14px;background:var(--bg3,rgba(255,255,255,0.03));border:1px solid var(--border,rgba(255,255,255,0.08));border-radius:8px;">
        <div class="toggle-wrap">
            <input type="hidden" name="include_year" value="0">
            <input type="checkbox" name="include_year" id="include_year" value="1" class="sr-only preview-trigger"
                {{ old('include_year', $reference->include_year ?? true) ? 'checked' : '' }}>
            <div class="toggle-track" id="track_year">
                <div class="toggle-thumb"></div>
            </div>
        </div>
        <div>
            <div style="font-size:13px;color:var(--text,#fff);font-weight:500;">Inclure l'année</div>
            <div style="font-size:11px;color:var(--text3,#888);">ex. 2025</div>
        </div>
    </label>

    <label class="toggle-label" style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:12px 14px;background:var(--bg3,rgba(255,255,255,0.03));border:1px solid var(--border,rgba(255,255,255,0.08));border-radius:8px;">
        <div class="toggle-wrap">
            <input type="hidden" name="include_month" value="0">
            <input type="checkbox" name="include_month" id="include_month" value="1" class="sr-only preview-trigger"
                {{ old('include_month', $reference->include_month ?? false) ? 'checked' : '' }}>
            <div class="toggle-track" id="track_month">
                <div class="toggle-thumb"></div>
            </div>
        </div>
        <div>
            <div style="font-size:13px;color:var(--text,#fff);font-weight:500;">Inclure le mois</div>
            <div style="font-size:11px;color:var(--text3,#888);">ex. 06</div>
        </div>
    </label>
</div>

{{-- Padding & start --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
    <div class="form-group">
        <label class="form-label" style="display:block;font-size:13px;color:var(--text2,#ccc);margin-bottom:6px;font-weight:500;">
            Largeur du numéro séquentiel
        </label>
        <select name="sequence_padding" id="sequence_padding" class="form-select preview-trigger"
                style="width:100%;padding:10px 14px;background:var(--bg3,rgba(255,255,255,0.05));border:1px solid var(--border,rgba(255,255,255,0.12));border-radius:8px;color:var(--text,#fff);font-size:14px;">
            @foreach([3 => '3 chiffres — 001', 4 => '4 chiffres — 0001', 5 => '5 chiffres — 00001', 6 => '6 chiffres — 000001'] as $val => $label)
                <option value="{{ $val }}" {{ old('sequence_padding', $reference->sequence_padding ?? 4) == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" style="display:block;font-size:13px;color:var(--text2,#ccc);margin-bottom:6px;font-weight:500;">
            Numéro de départ
        </label>
        <input type="number" name="sequence_start" id="sequence_start" class="form-input preview-trigger"
               value="{{ old('sequence_start', $reference->sequence_start ?? 1) }}"
               min="1" required
               style="width:100%;padding:10px 14px;background:var(--bg3,rgba(255,255,255,0.05));border:1px solid var(--border,rgba(255,255,255,0.12));border-radius:8px;color:var(--text,#fff);font-size:14px;box-sizing:border-box;">
    </div>
</div>

{{-- Active toggle --}}
<label class="toggle-label" style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:12px 14px;background:var(--bg3,rgba(255,255,255,0.03));border:1px solid var(--border,rgba(255,255,255,0.08));border-radius:8px;margin-bottom:8px;">
    <div class="toggle-wrap">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" id="active" value="1" class="sr-only"
            {{ old('active', $reference->active ?? true) ? 'checked' : '' }}>
        <div class="toggle-track" id="track_active">
            <div class="toggle-thumb"></div>
        </div>
    </div>
    <div>
        <div style="font-size:13px;color:var(--text,#fff);font-weight:500;">Format actif</div>
        <div style="font-size:11px;color:var(--text3,#888);">Seuls les formats actifs sont utilisés lors de la soumission de demandes</div>
    </div>
</label>

<style>
.toggle-wrap { position:relative; flex-shrink:0; }
.sr-only     { position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0); }
.toggle-track {
    width:40px;height:22px;border-radius:11px;
    background:rgba(255,255,255,0.12);
    border:1px solid rgba(255,255,255,0.15);
    position:relative;transition:background 0.2s,border-color 0.2s;cursor:pointer;
}
input[type=checkbox]:checked + .toggle-track {
    background:var(--gold,#D4AF37);border-color:var(--gold,#D4AF37);
}
.toggle-thumb {
    position:absolute;top:2px;left:2px;width:16px;height:16px;
    border-radius:50%;background:#fff;
    transition:transform 0.2s cubic-bezier(.34,1.56,.64,1);
    box-shadow:0 1px 3px rgba(0,0,0,0.3);
}
input[type=checkbox]:checked + .toggle-track .toggle-thumb {
    transform:translateX(18px);
}
.form-error { margin:4px 0 0;font-size:12px;color:#f87171; }
</style>

<script>
(function () {
    const year  = {{ date('Y') }};
    const month = '{{ date('m') }}';

    function buildPreview() {
        const prefix  = document.getElementById('prefix')?.value?.toUpperCase() || '';
        const sep     = document.getElementById('separator')?.value || '-';
        const incYear = document.getElementById('include_year')?.checked;
        const incMonth= document.getElementById('include_month')?.checked;
        const pad     = parseInt(document.getElementById('sequence_padding')?.value) || 4;
        const start   = parseInt(document.getElementById('sequence_start')?.value) || 1;

        const num = String(start).padStart(pad, '0');
        const parts = [prefix, incYear ? year : null, incMonth ? month : null, num]
            .filter(Boolean);
        return parts.join(sep) || '—';
    }

    function updatePreview() {
        const el = document.getElementById('livePreview');
        if (el) {
            const p = buildPreview();
            el.textContent = p;
            el.style.color = p === '—' ? '#888' : 'var(--gold,#D4AF37)';
        }
    }

    // Filter formulaires by department
    document.getElementById('department_id')?.addEventListener('change', function () {
        const deptId = this.value;
        const sel = document.getElementById('formulaire_id');
        if (!sel) return;
        Array.from(sel.options).forEach(opt => {
            if (!opt.value) return; // keep the "all" option
            opt.hidden = opt.dataset.dept !== deptId;
        });
        // Reset if current selection is now hidden
        if (sel.selectedOptions[0]?.hidden) sel.value = '';
    });

    // Trigger filter on load
    document.getElementById('department_id')?.dispatchEvent(new Event('change'));

    // Live preview
    document.querySelectorAll('.preview-trigger').forEach(el => {
        el.addEventListener('change', updatePreview);
        el.addEventListener('input', updatePreview);
    });
    updatePreview();
})();
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div style="width:100%; margin: 8px 0 4px;">
    <label style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:10px;">
        Ügyfél aláírása
    </label>

    <div id="signature-pad" class="signature-pad">
        <div class="signature-pad--body">
            <canvas></canvas>
        </div>
        <div class="signature-pad--footer">
            <div class="signature-pad--actions">
                <div class="column">
                    <button type="button" class="button clear" data-action="clear">
                        <i class="fas fa-trash-alt"></i> Törlés
                    </button>
                    <button type="button" class="button undo" data-action="undo">
                        <i class="fas fa-undo"></i> Visszavonás
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/signature-pad.css') }}">
<script src="{{ asset('js/signature_pad.umd.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

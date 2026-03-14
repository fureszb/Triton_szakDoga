@extends('ujlayout')

@section('content')

<div style="max-width:520px;margin:60px auto;text-align:center;">
    <div style="width:80px;height:80px;border-radius:50%;background:rgba(239,68,68,0.1);border:2px solid rgba(239,68,68,0.25);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:36px;color:#dc2626;">
        <i class="fas fa-times-circle"></i>
    </div>

    <h1 style="font-size:24px;font-weight:800;color:#1e293b;margin:0 0 8px;">Fizetés megszakítva</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 32px;line-height:1.7;">
        A fizetési folyamat megszakadt vagy sikertelen volt.<br>
        Semmi sem lett levonva a kártyáról.
    </p>

    <div style="background:#fff;border-radius:12px;border:1px solid #fecaca;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:12px;">
        <i class="fas fa-info-circle" style="color:#dc2626;font-size:16px;flex-shrink:0;"></i>
        <p style="margin:0;font-size:13px;color:#64748b;text-align:left;">
            Ha a probléma ismétlődik, kérjük vegye fel a kapcsolatot ügyfélszolgálatunkkal.
        </p>
    </div>

    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('payment.checkout', $megrendeles->Megrendeles_ID) }}" class="btn-save" style="text-decoration:none;">
            <i class="fas fa-redo"></i> Próbálja újra
        </a>
        @if(auth()->user()->role === 'Ugyfel')
            <a href="{{ route('ugyfel.megrendelesek') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Vissza
            </a>
        @else
            <a href="{{ route('megrendeles.show', $megrendeles->Megrendeles_ID) }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Megrendelés
            </a>
        @endif
    </div>
</div>

@endsection

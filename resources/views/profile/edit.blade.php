@extends('ujlayout')

@section('content')

<div class="page-header">
    <div>
        <h1 style="margin:0;font-size:20px;font-weight:700;color:#1e293b;">
            <i class="fas fa-user-circle" style="color:#c9a97a;margin-right:8px;"></i>Fiókom
        </h1>
        <p style="margin:4px 0 0;font-size:13px;color:#64748b;">Profiladatok és jelszókezelés</p>
    </div>
</div>

<div style="max-width:680px;display:flex;flex-direction:column;gap:20px;">
    @include('profile.partials.update-profile-information-form')
    @include('profile.partials.update-password-form')
</div>

@endsection

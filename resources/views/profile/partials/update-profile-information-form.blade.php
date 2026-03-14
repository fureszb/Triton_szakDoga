<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    {{-- Hidden name field (not used in UI) --}}
    <input type="hidden" name="name" value="{{ old('name', $user->name) }}">

    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-id-card"></i></div>
            <span class="fc-htitle">Fiókom adatai</span>
        </div>
        <div class="fc-body">

            {{-- Bejelentkezett felhasználó neve (csak olvasható) --}}
            <div class="f-group">
                <label class="f-label"><i class="fas fa-user"></i> Megjelenített név</label>
                <input type="text" class="f-input" value="{{ $user->nev ?? $user->name }}" disabled
                       style="background:#f8fafc;color:#94a3b8;cursor:not-allowed;">
                <span style="font-size:11px;color:#94a3b8;margin-top:2px;">A nevet az admin tudja módosítani.</span>
            </div>

            {{-- Email --}}
            <div class="f-group">
                <label class="f-label"><i class="fas fa-at"></i> Email cím</label>
                <input type="email" name="email" class="f-input"
                       value="{{ old('email', $user->email) }}"
                       required autocomplete="username">
                @error('email')
                    <span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="margin-top:8px;padding:10px 14px;background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;font-size:12px;color:#92400e;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>
                            Az e-mail cím nincs megerősítve.
                            <button form="send-verification"
                                    style="background:none;border:none;color:#92400e;text-decoration:underline;cursor:pointer;font-size:12px;padding:0;font-family:inherit;">
                                Ellenőrző e-mail újraküldése
                            </button>
                        </span>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div style="margin-top:6px;font-size:12px;color:#16a34a;display:flex;align-items:center;gap:5px;">
                            <i class="fas fa-check-circle"></i> Ellenőrző e-mailt elküldtük.
                        </div>
                    @endif
                @endif
            </div>

            {{-- Szerepkör (csak olvasható) --}}
            <div class="f-group">
                <label class="f-label"><i class="fas fa-shield-alt"></i> Szerepkör</label>
                <div style="padding-top:4px;">
                    @php $role = auth()->user()->role; @endphp
                    @if($role === 'Admin')
                        <span class="role-admin"><i class="fas fa-crown"></i> Admin</span>
                    @elseif($role === 'Uzletkoto')
                        <span class="role-uzlet"><i class="fas fa-briefcase"></i> Üzletkötő</span>
                    @else
                        <span class="role-ugyfel"><i class="fas fa-user"></i> Ügyfél</span>
                    @endif
                </div>
            </div>

        </div>
        <div class="fc-submit">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Adatok mentése
            </button>
            @if (session('status') === 'profile-updated')
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;color:#16a34a;">
                    <i class="fas fa-check-circle"></i> Sikeresen mentve!
                </span>
            @endif
        </div>
    </div>
</form>

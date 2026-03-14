<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="fc-card">
        <div class="fc-header">
            <div class="fc-hicon"><i class="fas fa-lock"></i></div>
            <span class="fc-htitle">Jelszó frissítése</span>
        </div>
        <div class="fc-body">

            <div class="f-group">
                <label class="f-label"><i class="fas fa-key"></i> Jelenlegi jelszó</label>
                <input type="password" name="current_password" class="f-input"
                       autocomplete="current-password" placeholder="••••••••">
                @error('current_password', 'updatePassword')
                    <span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="fc-row">
                <div class="f-group">
                    <label class="f-label"><i class="fas fa-lock"></i> Új jelszó</label>
                    <input type="password" name="password" class="f-input"
                           autocomplete="new-password" placeholder="••••••••">
                    @error('password', 'updatePassword')
                        <span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="f-group">
                    <label class="f-label"><i class="fas fa-lock"></i> Új jelszó megerősítése</label>
                    <input type="password" name="password_confirmation" class="f-input"
                           autocomplete="new-password" placeholder="••••••••">
                    @error('password_confirmation', 'updatePassword')
                        <span style="color:#ef4444;font-size:12px;margin-top:2px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="padding:10px 14px;background:rgba(201,169,122,0.06);border:1px solid rgba(201,169,122,0.2);border-radius:8px;font-size:12px;color:#64748b;display:flex;align-items:flex-start;gap:8px;">
                <i class="fas fa-info-circle" style="color:#c9a97a;margin-top:1px;flex-shrink:0;"></i>
                <span>Használj legalább 8 karakter hosszú, véletlenszerű jelszót a biztonság érdekében.</span>
            </div>

        </div>
        <div class="fc-submit">
            <button type="submit" class="btn-save">
                <i class="fas fa-lock"></i> Jelszó frissítése
            </button>
            @if (session('status') === 'password-updated')
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;color:#16a34a;">
                    <i class="fas fa-check-circle"></i> Jelszó sikeresen frissítve!
                </span>
            @endif
        </div>
    </div>
</form>

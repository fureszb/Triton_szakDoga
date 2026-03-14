<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Fizetési emlékeztető</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

  {{-- Fejléc --}}
  <tr>
    <td style="background:#1e293b;padding:28px 36px;border-bottom:4px solid #c9a97a;">
      <span style="font-size:22px;font-weight:800;color:#c9a97a;letter-spacing:1px;">TRITON SECURITY</span><br>
      <span style="font-size:12px;color:#94a3b8;letter-spacing:2px;">FIZETÉSI EMLÉKEZTETŐ</span>
    </td>
  </tr>

  @php
    $hatarido  = $szamla->fizetesi_hatarido;
    $napokHatra = $hatarido ? (int) now()->diffInDays($hatarido, false) : null;
    $tipus = $szamla->szamla_tipus === 'dijbekero' ? 'Díjbekérő' : 'Számla';
    $szamlaNum = $szamla->billingo_szam ?? 'TRITON-' . str_pad($szamla->szamla_id, 6, '0', STR_PAD_LEFT);
  @endphp

  {{-- Figyelmeztető sáv --}}
  <tr>
    <td style="padding:32px 36px 16px;">
      @if($napokHatra !== null && $napokHatra < 0)
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
          <span style="color:#dc2626;font-weight:700;font-size:13px;">⚠ FIGYELEM: A fizetési határidő {{ abs($napokHatra) }} napja lejárt!</span>
        </div>
      @elseif($napokHatra !== null && $napokHatra <= 1)
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
          <span style="color:#dc2626;font-weight:700;font-size:13px;">⚠ FIGYELEM: A fizetési határidő HOLNAP lejár!</span>
        </div>
      @elseif($napokHatra !== null)
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
          <span style="color:#d97706;font-weight:700;font-size:13px;">🔔 Emlékeztető: {{ $napokHatra }} nap múlva lejár a fizetési határidő</span>
        </div>
      @else
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
          <span style="color:#2563eb;font-weight:700;font-size:13px;">🔔 Fizetési emlékeztető</span>
        </div>
      @endif

      {{-- Egyedi üzenet --}}
      @if($egyediUzenet)
      <div style="background:#f8fafc;border-left:4px solid #c9a97a;padding:14px 18px;border-radius:0 8px 8px 0;margin-bottom:20px;">
        <p style="margin:0;font-size:13px;color:#334155;line-height:1.6;font-style:italic;">{{ $egyediUzenet }}</p>
      </div>
      @endif

      <p style="margin:0;font-size:14px;color:#64748b;line-height:1.7;">
        Tisztelt Ügyfelünk!<br><br>
        Emlékeztetjük, hogy az alábbi {{ strtolower($tipus) }}hoz tartozó összeg rendezése szükséges.
        Kérjük, szíveskedjen azt mielőbb teljesíteni.
      </p>
    </td>
  </tr>

  {{-- Számla adatok --}}
  <tr>
    <td style="padding:0 36px 24px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#fdf6ee;border-radius:10px;border:1px solid rgba(201,169,122,0.25);overflow:hidden;">
        <tr style="background:rgba(201,169,122,0.12);">
          <td colspan="2" style="padding:12px 18px;font-size:12px;font-weight:700;color:#a07848;text-transform:uppercase;letter-spacing:0.8px;">
            {{ $tipus }} adatai
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;width:40%;border-bottom:1px solid #f8fafc;">{{ $tipus }} száma</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;font-weight:700;border-bottom:1px solid #f8fafc;">{{ $szamlaNum }}</td>
        </tr>
        @if($szamla->megrendeles)
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;border-bottom:1px solid #f8fafc;">Megrendelés</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;font-weight:600;border-bottom:1px solid #f8fafc;">
            #{{ str_pad($szamla->megrendeles->Megrendeles_ID, 5, '0', STR_PAD_LEFT) }} – {{ $szamla->megrendeles->Megrendeles_Nev }}
          </td>
        </tr>
        @endif
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;border-bottom:1px solid #f8fafc;">Fizetendő összeg</td>
          <td style="padding:11px 18px;font-size:17px;color:#a07848;font-weight:800;border-bottom:1px solid #f8fafc;">
            {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;">Fizetési határidő</td>
          <td style="padding:11px 18px;font-size:13px;
               color:{{ ($napokHatra !== null && $napokHatra <= 1) ? '#dc2626' : '#334155' }};
               font-weight:{{ ($napokHatra !== null && $napokHatra <= 1) ? '700' : '500' }};">
            {{ $hatarido?->format('Y. m. d.') ?? '—' }}
            @if($napokHatra !== null)
              @if($napokHatra < 0)
                <span style="color:#dc2626;">({{ abs($napokHatra) }} napja lejárt)</span>
              @elseif($napokHatra === 0)
                <span style="color:#dc2626;">(MA jár le!)</span>
              @else
                <span style="color:#64748b;">({{ $napokHatra }} nap múlva)</span>
              @endif
            @endif
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Fizetés gomb --}}
  @if($szamla->statusz !== 'fizetve' && $szamla->megrendeles_id)
  <tr>
    <td style="padding:0 36px 32px;text-align:center;">
      <a href="{{ route('payment.checkout', $szamla->megrendeles_id) }}"
         style="display:inline-block;background:linear-gradient(135deg,#c9a97a,#a07848);color:#fff;font-size:14px;font-weight:700;padding:14px 36px;border-radius:8px;text-decoration:none;letter-spacing:0.5px;">
        💳 Fizetek most
      </a>
    </td>
  </tr>
  @endif

  {{-- Footer --}}
  <tr>
    <td style="background:#1e293b;padding:16px 36px;border-top:3px solid #c9a97a;">
      <p style="margin:0;font-size:11px;color:#64748b;text-align:center;">
        © {{ date('Y') }} TRITON SECURITY KFT. – Ez egy manuálisan küldött emlékeztető email.
      </p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Számla kiállítva</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

  {{-- Fejléc --}}
  <tr>
    <td style="background:#1e293b;padding:28px 36px;border-bottom:4px solid #c9a97a;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <span style="font-size:22px;font-weight:800;color:#c9a97a;letter-spacing:1px;">TRITON SECURITY</span><br>
            <span style="font-size:12px;color:#94a3b8;letter-spacing:2px;">SZÁMLA ÉRTESÍTŐ</span>
          </td>
          <td align="right">
            <span style="display:inline-block;background:rgba(34,197,94,0.15);color:#16a34a;font-size:12px;font-weight:700;padding:6px 14px;border-radius:6px;border:1px solid rgba(34,197,94,0.3);">
              ✅ FIZETÉS SIKERES
            </span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  @php
    $mr        = $szamla->megrendeles;
    $szamlaNum = $szamla->billingo_szam
               ?? 'TRITON-' . str_pad($szamla->szamla_id, 6, '0', STR_PAD_LEFT);
    $dijbekero = $szamla->dijbekero;
    $fizMod    = match($szamla->fizetesi_mod) {
        'stripe'         => 'Bankkártyával (online)',
        'banki_atutalas' => 'Banki átutalással',
        'keszpenz'       => 'Készpénzzel',
        default          => 'Egyéb',
    };
  @endphp

  {{-- Bevezető --}}
  <tr>
    <td style="padding:32px 36px 16px;">
      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:14px 18px;margin-bottom:20px;">
        <span style="color:#16a34a;font-weight:700;font-size:14px;">✅ Köszönjük a fizetést! Számlája elkészült.</span>
      </div>
      <p style="margin:0;font-size:14px;color:#64748b;line-height:1.7;">
        Tisztelt Ügyfelünk!<br><br>
        Kifizetett megrendeléséhez az alábbi számlát állítottuk ki.
        @if($szamla->billingo_pdf_url)
          A számlát az alábbi gombra kattintva töltheti le PDF formátumban.
        @else
          A számlát az ügyfél portálon tekintheti meg és töltheti le.
        @endif
      </p>
    </td>
  </tr>

  {{-- Számla adatai --}}
  <tr>
    <td style="padding:0 36px 24px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#fdf6ee;border-radius:10px;border:1px solid rgba(201,169,122,0.25);overflow:hidden;">
        <tr style="background:rgba(201,169,122,0.12);">
          <td colspan="2" style="padding:12px 18px;font-size:12px;font-weight:700;color:#a07848;text-transform:uppercase;letter-spacing:0.8px;">
            Számla adatai
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;width:40%;border-bottom:1px solid #f8fafc;">Számlaszám</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;font-weight:700;border-bottom:1px solid #f8fafc;">{{ $szamlaNum }}</td>
        </tr>
        @if($mr)
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;border-bottom:1px solid #f8fafc;">Megrendelés</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;border-bottom:1px solid #f8fafc;">
            #{{ str_pad($mr->id, 5, '0', STR_PAD_LEFT) }} – {{ $mr->megrendeles_nev }}
          </td>
        </tr>
        @endif
        @if($dijbekero)
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;border-bottom:1px solid #f8fafc;">Díjbekérő alapján</td>
          <td style="padding:11px 18px;font-size:12px;color:#64748b;border-bottom:1px solid #f8fafc;">
            #{{ str_pad($dijbekero->szamla_id, 5, '0', STR_PAD_LEFT) }}
            @if($dijbekero->billingo_szam) ({{ $dijbekero->billingo_szam }}) @endif
          </td>
        </tr>
        @endif
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;border-bottom:1px solid #f8fafc;">Fizetett összeg</td>
          <td style="padding:11px 18px;font-size:18px;color:#16a34a;font-weight:800;border-bottom:1px solid #f8fafc;">
            {{ number_format($szamla->brutto_osszeg, 0, ',', ' ') }} Ft
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;border-bottom:1px solid #f8fafc;">Fizetés módja</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;border-bottom:1px solid #f8fafc;">{{ $fizMod }}</td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;">Kiállítás dátuma</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;">{{ $szamla->kiallitas_datum?->format('Y. m. d.') ?? now()->format('Y. m. d.') }}</td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- PDF letöltés gomb --}}
  @if($szamla->billingo_pdf_url)
  <tr>
    <td style="padding:0 36px 32px;text-align:center;">
      <a href="{{ $szamla->billingo_pdf_url }}"
         style="display:inline-block;background:linear-gradient(135deg,#c9a97a,#a07848);color:#fff;font-size:14px;font-weight:700;padding:14px 36px;border-radius:8px;text-decoration:none;letter-spacing:0.5px;">
        📄 Számla letöltése (PDF)
      </a>
    </td>
  </tr>
  @else
  <tr>
    <td style="padding:0 36px 32px;text-align:center;">
      <a href="{{ route('ugyfel.szamlak') }}"
         style="display:inline-block;background:linear-gradient(135deg,#64748b,#475569);color:#fff;font-size:13px;font-weight:600;padding:12px 28px;border-radius:8px;text-decoration:none;">
        🔐 Megtekintés az ügyfél portálon
      </a>
    </td>
  </tr>
  @endif

  {{-- Footer --}}
  <tr>
    <td style="background:#1e293b;padding:16px 36px;border-top:3px solid #c9a97a;">
      <p style="margin:0;font-size:11px;color:#64748b;text-align:center;">
        © {{ date('Y') }} TRITON SECURITY KFT. – Automatikusan generált értesítő email.
      </p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>

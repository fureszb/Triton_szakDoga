<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fizetési visszaigazolás</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:32px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">

  {{-- Header --}}
  <tr>
    <td style="background:#1e293b;padding:28px 36px;border-bottom:4px solid #c9a97a;">
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <span style="font-size:22px;font-weight:800;color:#c9a97a;letter-spacing:1px;">TRITON SECURITY</span><br>
            <span style="font-size:12px;color:#94a3b8;letter-spacing:2px;">OKOS OTTHON RENDSZEREK</span>
          </td>
          <td align="right">
            <span style="display:inline-block;background:rgba(34,197,94,0.15);color:#16a34a;font-size:12px;font-weight:700;padding:6px 14px;border-radius:6px;border:1px solid rgba(34,197,94,0.3);">
              ✓ FIZETVE
            </span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Üdvözlés --}}
  <tr>
    <td style="padding:32px 36px 0;">
      <h2 style="margin:0 0 8px;font-size:20px;color:#1e293b;">Fizetési visszaigazolás</h2>
      <p style="margin:0;font-size:14px;color:#64748b;">
        Köszönjük! A fizetés sikeresen megérkezett.
        Az alábbiakban találja a megrendelés részleteit.
      </p>
    </td>
  </tr>

  {{-- Megrendelés adatok --}}
  <tr>
    <td style="padding:24px 36px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#fdf6ee;border-radius:10px;border:1px solid rgba(201,169,122,0.25);overflow:hidden;">
        <tr style="background:rgba(201,169,122,0.12);">
          <td colspan="2" style="padding:12px 18px;font-size:12px;font-weight:700;color:#a07848;text-transform:uppercase;letter-spacing:0.8px;">
            Megrendelés részletei
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;width:40%;border-bottom:1px solid #f8fafc;">Megrendelés száma</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;font-weight:600;border-bottom:1px solid #f8fafc;">
            #{{ str_pad($megrendeles->id, 5, '0', STR_PAD_LEFT) }}
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #f8fafc;">Megrendelő neve</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;border-bottom:1px solid #f8fafc;">{{ $megrendeles->megrendeles_nev }}</td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #f8fafc;">Fizetett összeg</td>
          <td style="padding:11px 18px;font-size:16px;color:#a07848;font-weight:700;border-bottom:1px solid #f8fafc;">
            {{ number_format($megrendeles->Vegosszeg, 0, ',', ' ') }} Ft
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #f8fafc;">Fizetés módja</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;border-bottom:1px solid #f8fafc;">
            {{ $megrendeles->FizetesiMod === 'stripe' ? 'Bankkártya (online)' : 'Átutalás' }}
          </td>
        </tr>
        <tr>
          <td style="padding:11px 18px;font-size:12px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Fizetés időpontja</td>
          <td style="padding:11px 18px;font-size:13px;color:#334155;">
            {{ $megrendeles->Fizetve_Idopontja?->format('Y. m. d. H:i') ?? now()->format('Y. m. d. H:i') }}
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- Számla info (ha van) --}}
  @if($megrendeles->Billingo_Szam)
  <tr>
    <td style="padding:0 36px 24px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border-radius:10px;border:1px solid rgba(34,197,94,0.2);padding:16px 18px;">
        <tr>
          <td>
            <p style="margin:0 0 4px;font-size:12px;font-weight:700;color:#16a34a;text-transform:uppercase;letter-spacing:0.6px;">Számla kiállítva</p>
            <p style="margin:0;font-size:13px;color:#334155;">
              Számlaszám: <strong>{{ $megrendeles->Billingo_Szam }}</strong>
              @if($megrendeles->Billingo_Pdf_Url)
              &nbsp;–&nbsp;
              <a href="{{ $megrendeles->Billingo_Pdf_Url }}" style="color:#c9a97a;text-decoration:none;font-weight:600;">📄 Számla letöltése</a>
              @endif
            </p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  @endif

  {{-- Footer --}}
  <tr>
    <td style="padding:20px 36px 32px;border-top:1px solid #f1f5f9;">
      <p style="margin:0;font-size:12px;color:#94a3b8;line-height:1.6;">
        Ha kérdése van, keressen minket:<br>
        <a href="mailto:{{ config('mail.from.address') }}" style="color:#c9a97a;text-decoration:none;">{{ config('mail.from.address') }}</a>
      </p>
    </td>
  </tr>
  <tr>
    <td style="background:#1e293b;padding:16px 36px;border-top:3px solid #c9a97a;">
      <p style="margin:0;font-size:11px;color:#64748b;text-align:center;">
        © {{ date('Y') }} TRITON SECURITY KFT. – Automatikusan generált email, kérjük ne válaszoljon erre az üzenetre.
      </p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>

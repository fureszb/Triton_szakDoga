@extends('ujLayout')
@section('content')
    @include('breadcrumbs')

    <script src="https://kit.fontawesome.com/86a7bd8db7.js" crossorigin="anonymous"></script>

    <div class="page-header">
        <h1><i class="fas fa-clipboard-list"></i> Megrendelések</h1>
    </div>

    <div class="page-toolbar">
        <a href="{{ route('megrendeles.index', ['sort_by' => 'megrendeles_nev', 'sort_dir' => 'asc']) }}"
           class="sort-btn" title="Növekvő sorrend">
            <i class="fas fa-sort-alpha-up"></i>
        </a>
        <a href="{{ route('megrendeles.index', ['sort_by' => 'megrendeles_nev', 'sort_dir' => 'desc']) }}"
           class="sort-btn" title="Csökkenő sorrend">
            <i class="fas fa-sort-alpha-down-alt"></i>
        </a>
        <a href="{{ route('megrendeles.create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Új megrendelés
        </a>
        <form class="search-form-inline" action="{{ route('megrendeles.index') }}" method="GET">
            <input type="text" name="search" placeholder="Keresés név vagy azonosító alapján"
                   value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Keresés</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:flex-start;gap:12px;">
            <i class="fas fa-exclamation-triangle" style="color:#dc2626;margin-top:2px;flex-shrink:0;"></i>
            <div style="flex:1;">
                <div style="font-weight:600;color:#991b1b;font-size:13px;margin-bottom:4px;">Email küldési hiba</div>
                <div style="color:#7f1d1d;font-size:13px;">{{ session('error') }}</div>
                @if (session('email_hiba'))
                    <a href="{{ route('megrendeles.show', ['id' => session('email_hiba')]) }}"
                       style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:6px 14px;border-radius:7px;background:#dc2626;color:#fff;font-size:12px;font-weight:600;text-decoration:none;">
                        <i class="fas fa-paper-plane"></i> Megrendelés megnyitása → Email újraküldéséhez
                    </a>
                @endif
            </div>
        </div>
    @endif

    <div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Megrendelő neve</th>
                <th>Ügyfél</th>
                <th>Helyszín</th>
                <th>Státusz</th>
                <th>Számla / Díjbekérő</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($megrendelesek as $megrendeles)
                <tr>
                    <td><span class="table-id">#{{ $megrendeles->id }}</span></td>
                    <td><strong>{{ $megrendeles->megrendeles_nev }}</strong></td>
                    <td>{{ $megrendeles->ugyfel->nev ?? '-' }}</td>
                    <td>
                        @if($megrendeles->varos)
                            {{ $megrendeles->varos->Irny_szam }} {{ $megrendeles->varos->nev }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($megrendeles->statusz)
                            <span class="badge badge-active">Folyamatban</span>
                        @else
                            <span class="badge badge-done">Befejezve</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:4px;">
                            {{-- Számlák --}}
                            @foreach($megrendeles->tobbSzamla as $szamlaItem)
                                <a href="{{ route('szamlak.show', $szamlaItem->szamla_id) }}"
                                   style="display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;background:rgba(201,169,122,0.18);border:1.5px solid rgba(201,169,122,0.45);color:#92622a;font-size:11px;font-weight:700;text-decoration:none;white-space:nowrap;"
                                   title="Számla megtekintése">
                                    <i class="fas fa-file-invoice"></i> Számla #{{ $szamlaItem->szamla_id }}
                                </a>
                            @endforeach

                            {{-- Díjbekérők --}}
                            @foreach($megrendeles->tobbDijbekero as $dijbekeroItem)
                                <a href="{{ route('szamlak.show', $dijbekeroItem->szamla_id) }}"
                                   style="display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;background:rgba(99,102,241,0.12);border:1.5px solid rgba(99,102,241,0.3);color:#4f46e5;font-size:11px;font-weight:700;text-decoration:none;white-space:nowrap;"
                                   title="Díjbekérő megtekintése">
                                    <i class="fas fa-file-alt"></i> Díjbekérő #{{ $dijbekeroItem->szamla_id }}
                                </a>
                            @endforeach

                            {{-- Egyik sem --}}
                            @if($megrendeles->tobbSzamla->isEmpty() && $megrendeles->tobbDijbekero->isEmpty())
                                <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;background:#1f2937;color:#6b7280;font-size:11px;font-weight:600;">
                                    <i class="fas fa-minus"></i> Nincs
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('megrendeles.show', ['id' => $megrendeles->id]) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Megtekint
                            </a>
                            <a href="{{ route('megrendeles.edit', ['megrendeles' => $megrendeles->id]) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Szerkeszt
                            </a>
                            <form class="form-delete" action="{{ route('megrendeles.destroy', ['megrendeles' => $megrendeles->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit"
                                    onclick="return confirm('Biztos törölni kívánja a megrendelést?')">
                                    <i class="fas fa-trash-alt"></i> Töröl
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Nincsenek rögzített megrendelések.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @include('custom_pagination', ['paginator' => $megrendelesek])
@endsection

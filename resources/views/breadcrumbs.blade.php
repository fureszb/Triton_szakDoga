@if (count($breadcrumbs = Breadcrumbs::generate()) > 0)
<nav aria-label="breadcrumb" style="margin-bottom: 18px;">
    <ol style="
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        list-style: none;
        margin: 0;
        padding: 8px 14px;
        background: #fff;
        border: 1px solid #e8edf2;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    ">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                <li style="display:flex;align-items:center;gap:4px;">
                    <span style="
                        font-size: 12px;
                        font-weight: 700;
                        color: #c9a97a;
                        padding: 3px 10px;
                        background: rgba(201,169,122,0.1);
                        border-radius: 6px;
                    ">{{ $breadcrumb->title }}</span>
                </li>
            @else
                <li style="display:flex;align-items:center;gap:4px;">
                    <a href="{{ $breadcrumb->url }}" style="
                        font-size: 12px;
                        font-weight: 600;
                        color: #64748b;
                        text-decoration: none;
                        padding: 3px 8px;
                        border-radius: 6px;
                        transition: background 0.15s, color 0.15s;
                    "
                    onmouseover="this.style.background='#f1f5f9';this.style.color='#1e293b'"
                    onmouseout="this.style.background='transparent';this.style.color='#64748b'"
                    >{{ $breadcrumb->title }}</a>
                    <span style="color:#cbd5e1;font-size:11px;line-height:1;">
                        <i class="fas fa-chevron-right" style="font-size:9px;"></i>
                    </span>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif

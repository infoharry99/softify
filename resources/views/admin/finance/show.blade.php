@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Finance Requirement Details')
@section('page_title', 'Requirement Details')

@section('content')
<div class="card" style="max-width: 820px; margin: 0 auto;">
    <div class="card-header" style="background: linear-gradient(135deg, #2d8a7c 0%, #155e54 100%); color: #ffffff;">
        <h3 class="card-title" style="color: #ffffff; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-receipt"></i> Requirement Details: {{ $finance->vendor_name }}
        </h3>
        <div>
            @if(auth()->user()->hasPermission('finance.edit') || auth()->user()->hasRole('super-admin'))
            <a href="{{ route('admin.finance.edit', $finance->id) }}" class="btn btn-secondary btn-sm" style="background-color: rgba(255,255,255,0.2); border: none; color: #ffffff;">
                <i class="fa-solid fa-pen"></i> Edit Requirement
            </a>
            @endif
            <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary btn-sm" style="background-color: rgba(255,255,255,0.2); border: none; color: #ffffff;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Vendor Name</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $finance->vendor_name }}</div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Vendor Location</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $finance->vendor_location ?? '-' }}</div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Company Name</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $finance->company_name ?? '-' }}</div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Selected Candidates</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #0284c7; margin-top: 4px;">{{ $finance->selected_candidates_count }}</div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Budget Amount</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #059669; margin-top: 4px;">₹{{ number_format($finance->budget, 2) }}</div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Remaining Payment</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: {{ $finance->remaining_payment > 0 ? '#ef4444' : '#10b981' }}; margin-top: 4px;">
                    ₹{{ number_format($finance->remaining_payment, 2) }}
                </div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Requirement Date</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-top: 4px;">{{ $finance->date->format('l, M d, Y') }}</div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: var(--radius); border: 1px solid var(--border-color);">
                <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Requirement Status</div>
                <div style="margin-top: 6px;">
                    <span class="badge {{ $finance->status === 'Closed' ? 'badge-success' : ($finance->status === 'In Progress' ? 'badge-warning' : 'badge-secondary') }}" style="font-size: 0.9rem; padding: 6px 14px;">
                        {{ $finance->status }}
                    </span>
                </div>
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: var(--radius); border: 1px solid var(--border-color);">
            <div style="font-weight: 600; font-size: 0.9rem; color: #334155; margin-bottom: 6px;">Additional Requirement Notes</div>
            <div style="font-size: 0.9rem; color: #475569; line-height: 1.6;">
                {{ $finance->note ?? 'No additional notes provided for this finance requirement.' }}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 12px;">
                Record Created By: <strong>{{ $finance->creator->name ?? 'System User' }}</strong> on {{ $finance->created_at->format('M d, Y h:i A') }}
            </div>
        </div>
    </div>
</div>
@endsection

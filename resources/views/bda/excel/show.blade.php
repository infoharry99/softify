@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'View BDA Work - ' . $bdaWork->title)
@section('page_title', 'BDA Work Details')

@section('content')
<div style="max-width: 850px; margin: 0 auto;">
    <div class="card" style="margin-bottom: 25px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #f0fdf4;">
            <div>
                <h3 class="card-title" style="font-size: 1.25rem; font-weight: 700; color: #065f46; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-file-excel" style="color: #10b981;"></i> {{ $bdaWork->title }}
                </h3>
                <div style="font-size: 0.82rem; color: #047857; margin-top: 4px;">
                    Uploaded by: <strong>{{ $bdaWork->uploadedBy->name ?? 'User' }}</strong> on {{ $bdaWork->created_at->format('M d, Y \a\t h:i A') }}
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="{{ route('bda.excel.edit', $bdaWork->id) }}" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Edit Upload
                </a>
                <a href="{{ route('bda.excel.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Directory
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Metadata Cards Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 25px;">
                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Uploaded By</label>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #0f172a;">{{ $bdaWork->uploadedBy->name ?? 'N/A' }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">{{ $bdaWork->uploadedBy->email ?? '' }}</div>
                </div>

                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">User Role</label>
                    @php
                        $roleName = $bdaWork->uploadedBy->roles->pluck('name')->first() ?? 'User';
                    @endphp
                    <span class="badge badge-primary" style="font-size: 0.8rem;">{{ $roleName }}</span>
                </div>

                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Upload Date</label>
                    <div style="font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $bdaWork->created_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">{{ $bdaWork->created_at->format('h:i:s A') }}</div>
                </div>

                <div>
                    <label style="font-size: 0.76rem; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Last Updated</label>
                    <div style="font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $bdaWork->updated_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.78rem; color: #64748b;">{{ $bdaWork->updated_at->format('h:i:s A') }}</div>
                </div>
            </div>

            <!-- Work Description -->
            <div style="margin-bottom: 25px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: #0f172a; display: block; margin-bottom: 8px;">Description / Notes:</label>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; font-size: 0.9rem; color: #334155; line-height: 1.6; min-height: 80px;">
                    {!! nl2br(e($bdaWork->description ?? 'No description provided for this upload.')) !!}
                </div>
            </div>

            <!-- Excel File Card -->
            <div style="background: #f0fdf4; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 52px; height: 52px; background: #10b981; color: #ffffff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                        <i class="fa-solid fa-file-excel"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.98rem; font-weight: 700; color: #065f46;">{{ $bdaWork->file_name }}</div>
                        <div style="font-size: 0.78rem; color: #047857; margin-top: 2px;">
                            Format: <strong>.{{ $bdaWork->file_extension }}</strong> | Ready for download
                        </div>
                    </div>
                </div>

                <a href="{{ route('bda.excel.download', $bdaWork->id) }}" class="btn btn-primary" style="background-color: #10b981; border-color: #10b981; border-radius: 8px; font-weight: 700; padding: 10px 20px;">
                    <i class="fa-solid fa-download"></i> Download Excel File
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

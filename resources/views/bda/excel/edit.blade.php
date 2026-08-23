@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Edit BDA Work - ' . $bdaWork->title)
@section('page_title', 'Edit BDA Work Upload')

@section('content')
<div style="max-width: 750px; margin: 0 auto;">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Edit BDA Work Upload
            </h3>

            <a href="{{ route('bda.excel.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Directory
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('bda.excel.update', $bdaWork->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title (Required) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Work Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $bdaWork->title) }}" required placeholder="e.g. Q3 Sales Prospecting Report">
                    @error('title')
                        <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Description / Work Notes</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Enter key details or work notes...">{{ old('description', $bdaWork->description) }}</textarea>
                    @error('description')
                        <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Current Excel File Box -->
                <div style="background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-file-excel" style="font-size: 1.5rem; color: #10b981;"></i>
                        <div>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #065f46;">Current File: {{ $bdaWork->file_name }}</div>
                            <div style="font-size: 0.76rem; color: #047857;">Leave replacement field empty to keep this existing file.</div>
                        </div>
                    </div>

                    <a href="{{ route('bda.excel.download', $bdaWork->id) }}" class="btn btn-secondary btn-sm" style="border-radius: 6px; font-size: 0.78rem; font-weight: 600; color: #10b981;">
                        <i class="fa-solid fa-download"></i> Download
                    </a>
                </div>

                <!-- Replace Excel File (Optional) -->
                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Replace Excel File (Optional)</label>
                    <div style="border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 12px; padding: 20px; text-align: center;">
                        <div style="font-size: 0.88rem; font-weight: 600; color: #334155; margin-bottom: 4px;">Upload New Excel File to Replace Current File</div>
                        <div style="font-size: 0.78rem; color: #64748b; margin-bottom: 12px;">Accepted formats: .xlsx, .xls, .csv (Max 10 MB)</div>
                        
                        <input type="file" name="excel_file" id="excel_file" class="form-control @error('excel_file') is-invalid @enderror" accept=".xlsx,.xls,.csv" style="max-width: 380px; margin: 0 auto;">
                    </div>
                    @error('excel_file')
                        <span class="text-danger" style="font-size: 0.8rem; margin-top: 6px; display: block; text-align: center;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Footer Buttons -->
                <div style="display: flex; gap: 12px; justify-content: flex-end; pt: 15px; border-top: 1px solid var(--border-color);">
                    <a href="{{ route('bda.excel.index') }}" class="btn btn-secondary" style="border-radius: 8px; padding: 10px 20px;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="background-color: #00a884; border-color: #00a884; border-radius: 8px; padding: 10px 24px; font-weight: 700;">
                        <i class="fa-solid fa-floppy-disk"></i> Save & Update Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

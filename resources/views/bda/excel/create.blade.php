@extends(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('bda-team-lead') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Upload BDA Work Excel')
@section('page_title', 'Upload New BDA Work Excel File')

@section('content')
<div style="max-width: 750px; margin: 0 auto;">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-cloud-arrow-up" style="color: #10b981;"></i> Upload New BDA Work Excel File
            </h3>

            <a href="{{ route('bda.excel.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Directory
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('bda.excel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Title (Required) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Work Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Q3 Sales Prospecting & Client Acquisition Report">
                    @error('title')
                        <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description (Optional/Required) -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Description / Work Notes</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Enter key details, lead summary, or work notes regarding this Excel file...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger" style="font-size: 0.8rem; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Excel File (Required) -->
                <div class="form-group" style="margin-bottom: 25px;">
                    <label class="form-label" style="font-weight: 700; color: #0f172a;">Excel / CSV File <span style="color: #ef4444;">*</span></label>
                    <div style="border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 12px; padding: 25px 20px; text-align: center; position: relative;">
                        <i class="fa-solid fa-file-excel" style="font-size: 2.5rem; color: #10b981; margin-bottom: 10px;"></i>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #334155; margin-bottom: 4px;">Choose Excel or CSV File</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 15px;">Accepted formats: <strong>.xlsx, .xls, .csv</strong> (Max size: 10 MB)</div>
                        
                        <input type="file" name="excel_file" id="excel_file" class="form-control @error('excel_file') is-invalid @enderror" required accept=".xlsx,.xls,.csv" style="max-width: 380px; margin: 0 auto;">
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
                        <i class="fa-solid fa-upload"></i> Upload BDA Work
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

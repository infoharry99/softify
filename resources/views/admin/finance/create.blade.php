@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Finance Management')
@section('page_title', 'Add Finance Requirement')

@section('styles')
<style>
    .finance-header-banner {
        background: linear-gradient(135deg, #2d8a7c 0%, #155e54 100%);
        color: #ffffff;
        padding: 30px;
        border-radius: 16px 16px 0 0;
        text-align: center;
    }
    .finance-header-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .finance-header-sub {
        font-size: 0.95rem;
        color: #a7f3d0;
    }

    .finance-card-container {
        max-width: 820px;
        margin: 0 auto 30px auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .finance-form-body {
        padding: 35px 40px;
    }

    .teal-input {
        background-color: #f0faf7 !important;
        border: 1px solid #99f6e4 !important;
        border-radius: 10px !important;
        padding: 12px 14px !important;
        font-size: 0.95rem !important;
        color: #0f172a !important;
    }
    .teal-input:focus {
        background-color: #ffffff !important;
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15) !important;
    }

    .prefix-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    .prefix-icon {
        position: absolute;
        left: 14px;
        font-weight: 700;
        color: #0d9488;
        font-size: 1.05rem;
        pointer-events: none;
    }
    .prefix-input {
        padding-left: 36px !important;
    }

    .btn-teal-submit {
        background-color: #389685;
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        padding: 15px;
        border-radius: 10px;
        border: none;
        width: 100%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.2s ease;
        margin-top: 15px;
    }
    .btn-teal-submit:hover {
        background-color: #2d7a6c;
        box-shadow: 0 4px 12px rgba(45, 122, 108, 0.3);
    }
</style>
@endsection

@section('content')
<div class="finance-card-container">
    <!-- Banner matching Screenshot 1 -->
    <div class="finance-header-banner">
        <div class="finance-header-title">
            <i class="fa-solid fa-chart-line"></i> Finance Management
        </div>
        <div class="finance-header-sub">
            Add new finance requirement with detailed information
        </div>
    </div>

    <div class="finance-form-body">
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 25px;">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.finance.store') }}" method="POST">
            @csrf

            <!-- Row 1: Vendor Name & Vendor Location -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-user" style="color: #0d9488;"></i> Vendor Name *
                    </label>
                    <input type="text" name="vendor_name" class="form-control teal-input" value="{{ old('vendor_name') }}" required placeholder="Enter vendor name">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-location-dot" style="color: #0d9488;"></i> Vendor Location
                    </label>
                    <input type="text" name="vendor_location" class="form-control teal-input" value="{{ old('vendor_location') }}" placeholder="Enter location">
                </div>
            </div>

            <!-- Row 2: Company Name & Selected Candidates -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-building" style="color: #0d9488;"></i> Company Name
                    </label>
                    <input type="text" name="company_name" class="form-control teal-input" value="{{ old('company_name') }}" placeholder="Enter company name">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-users-viewfinder" style="color: #0d9488;"></i> Selected Candidates
                    </label>
                    <input type="number" name="selected_candidates_count" class="form-control teal-input" value="{{ old('selected_candidates_count', 0) }}" min="0" required placeholder="0">
                </div>
            </div>

            <!-- Row 3: Budget & Date -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-sack-dollar" style="color: #0d9488;"></i> Budget *
                    </label>
                    <div class="prefix-input-group">
                        <span class="prefix-icon">₹</span>
                        <input type="number" step="0.01" name="budget" class="form-control teal-input prefix-input" value="{{ old('budget') }}" required placeholder="Enter budget amount">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-calendar-days" style="color: #0d9488;"></i> Date *
                    </label>
                    <input type="date" name="date" class="form-control teal-input" value="{{ old('date', \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d')) }}" required>
                </div>
            </div>

            <!-- Row 4: Remaining Payment & Status -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-credit-card" style="color: #0d9488;"></i> Remaining Payment
                    </label>
                    <div class="prefix-input-group">
                        <span class="prefix-icon">₹</span>
                        <input type="number" step="0.01" name="remaining_payment" class="form-control teal-input prefix-input" value="{{ old('remaining_payment', 0) }}" placeholder="Enter remaining payment amount">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">
                        <i class="fa-solid fa-flag" style="color: #0d9488;"></i> Status
                    </label>
                    <select name="status" class="form-control teal-input" required>
                        <option value="No Update" {{ old('status', 'No Update') === 'No Update' ? 'selected' : '' }}>No Update</option>
                        <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Closed" {{ old('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-weight: 600;">
                    <i class="fa-solid fa-note-sticky" style="color: #0d9488;"></i> Additional Notes
                </label>
                <textarea name="note" class="form-control teal-input" rows="3" placeholder="Enter optional requirement notes...">{{ old('note') }}</textarea>
            </div>

            <button type="submit" class="btn-teal-submit">
                <i class="fa-solid fa-floppy-disk"></i> SUBMIT FINANCE REQUIREMENT
            </button>
        </form>
    </div>
</div>
@endsection

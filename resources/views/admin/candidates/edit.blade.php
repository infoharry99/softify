@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : 'layouts.employee')

@section('title', 'Edit Candidate - ' . $candidate->name)
@section('page_title', 'Edit Candidate Record')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Candidate: {{ $candidate->name }}</h3>
        <a href="{{ route('admin.candidates.show', $candidate->id) }}" class="btn btn-secondary btn-sm">⬅️ Back to Profile</a>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $candidate->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $candidate->email) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone Number *</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $candidate->phone) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $candidate->location) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Hiring Company Name (Client Company) *</label>
                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $candidate->company_name) }}" placeholder="e.g. Client Hiring Company">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Skills * (comma separated)</label>
                    <input type="text" name="skills" class="form-control" value="{{ old('skills', $candidate->skills) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Experience (Years) *</label>
                    <input type="number" step="0.5" min="0" name="experience" class="form-control" value="{{ old('experience', $candidate->experience) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Job Type *</label>
                    <select name="job_type" class="form-control" required>
                        <option value="Full Time" {{ old('job_type', $candidate->job_type) === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Part Time" {{ old('job_type', $candidate->job_type) === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Contract" {{ old('job_type', $candidate->job_type) === 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Remote" {{ old('job_type', $candidate->job_type) === 'Remote' ? 'selected' : '' }}>Remote</option>
                        <option value="Hybrid" {{ old('job_type', $candidate->job_type) === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Notice Period *</label>
                    <select name="notice_period" class="form-control" required>
                        <option value="Immediate" {{ old('notice_period', $candidate->notice_period) === 'Immediate' ? 'selected' : '' }}>Immediate</option>
                        <option value="15 Days" {{ old('notice_period', $candidate->notice_period) === '15 Days' ? 'selected' : '' }}>15 Days</option>
                        <option value="30 Days" {{ old('notice_period', $candidate->notice_period) === '30 Days' ? 'selected' : '' }}>30 Days</option>
                        <option value="60 Days" {{ old('notice_period', $candidate->notice_period) === '60 Days' ? 'selected' : '' }}>60 Days</option>
                        <option value="90 Days" {{ old('notice_period', $candidate->notice_period) === '90 Days' ? 'selected' : '' }}>90 Days</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Current CTC (Annual ₹)</label>
                    <input type="number" step="1000" name="current_ctc" class="form-control" value="{{ old('current_ctc', $candidate->current_ctc) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Expected CTC (Annual ₹)</label>
                    <input type="number" step="1000" name="expected_ctc" class="form-control" value="{{ old('expected_ctc', $candidate->expected_ctc) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Recruitment Status / Stage *</label>
                <select name="status" class="form-control" required>
                    <option value="Applied" {{ old('status', $candidate->status) === 'Applied' ? 'selected' : '' }}>Applied</option>
                    <option value="Screening" {{ old('status', $candidate->status) === 'Screening' ? 'selected' : '' }}>Screening</option>
                    <option value="Interview Scheduled" {{ old('status', $candidate->status) === 'Interview Scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                    <option value="Offered" {{ old('status', $candidate->status) === 'Offered' ? 'selected' : '' }}>Offered</option>
                    <option value="Hired" {{ old('status', $candidate->status) === 'Hired' ? 'selected' : '' }}>Hired</option>
                    <option value="Rejected" {{ old('status', $candidate->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Replace Resume File (Optional)</label>
                <input type="file" name="resume_file" class="form-control" accept=".pdf,.doc,.docx">
            </div>

            <div class="form-group">
                <label class="form-label">HR Evaluation Notes</label>
                <textarea name="note" class="form-control" rows="3">{{ old('note', $candidate->note) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary" style="background-color: #0d9488; border-color: #0d9488;">
                    Update Candidate Record
                </button>
                <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

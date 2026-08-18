@extends('layouts.admin')

@section('title', 'Employee Directory')
@section('page_title', 'Employee Directory & Records')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Employees</h3>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
            ➕ Add New Employee
        </a>
    </div>

    <!-- Filters -->
    <div style="padding: 16px 20px; background-color: #ffffff; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('admin.employees.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <div class="search-input-box" style="flex: 1; min-width: 240px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Search by Emp Code, Name, Email, Dept..." value="{{ request('search') }}">
            </div>

            <div style="width: 180px;">
                <select name="status" class="form-control" style="border-radius: 10px;">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Probation" {{ request('status') === 'Probation' ? 'selected' : '' }}>Probation</option>
                    <option value="Notice Period" {{ request('status') === 'Notice Period' ? 'selected' : '' }}>Notice Period</option>
                    <option value="Resigned" {{ request('status') === 'Resigned' ? 'selected' : '' }}>Resigned</option>
                    <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn-vibrant-blue">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Emp Code</th>
                    <th>Employee Name</th>
                    <th>Department & Designation</th>
                    <th>Employment Type</th>
                    <th>Status</th>
                    <th>Joining Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                @if(!$emp->user) @continue @endif
                <tr>
                    <td><code>{{ $emp->employee_code }}</code></td>
                    <td>
                        <strong style="color: var(--text-main);">{{ $emp->user->name ?? 'N/A' }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $emp->user->email ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div>{{ $emp->user->department ?? 'General' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $emp->user->designation ?? 'Staff' }}</div>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $emp->joiningDetail->employment_type ?? 'Full Time' }}</span>
                    </td>
                    <td>
                        <span class="badge {{ ($emp->joiningDetail->employment_status ?? 'Active') === 'Active' ? 'badge-success' : 'badge-warning' }}">
                            {{ $emp->joiningDetail->employment_status ?? 'Active' }}
                        </span>
                    </td>
                    <td>{{ $emp->joiningDetail ? $emp->joiningDetail->joining_date->format('M d, Y') : '-' }}</td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 5px;">
                            <a href="{{ route('admin.employees.show', $emp->id) }}" class="btn btn-secondary btn-sm" title="View 360° Profile">
                                👁️ 360° View
                            </a>
                            <a href="{{ route('admin.employees.edit', $emp->id) }}" class="btn btn-secondary btn-sm" title="Edit Employee">
                                ✏️ Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No employee records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-footer">
        {{ $employees->withQueryString()->links() }}
    </div>
</div>
@endsection

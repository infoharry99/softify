@extends('layouts.admin')

@section('title', 'Break Violations')
@section('page_title', 'Exceeded Break Time Violations Log')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">🚨 Break Exceeded Audit Log</h3>
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-sm">⬅️ Back to Attendance</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee Name</th>
                    <th>Break Started</th>
                    <th>Break Ended</th>
                    <th>Total Duration</th>
                    <th>Exceeded By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($violations as $v)
                <tr>
                    <td><strong>{{ $v->started_at->format('M d, Y') }}</strong></td>
                    <td>
                        <strong>{{ $v->employee->user->name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $v->employee->employee_code }}</div>
                    </td>
                    <td>{{ $v->started_at->format('h:i A') }}</td>
                    <td>{{ $v->ended_at ? $v->ended_at->format('h:i A') : 'Running' }}</td>
                    <td><strong>{{ $v->duration_minutes }} mins</strong></td>
                    <td>
                        <span class="badge badge-danger" style="font-size: 0.8rem;">
                            +{{ $v->exceeded_minutes }} mins overdue
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-warning">Violation Logged</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No break violations logged.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 15px 20px;">
        {{ $violations->links() }}
    </div>
</div>
@endsection

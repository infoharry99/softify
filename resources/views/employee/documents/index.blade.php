@extends('layouts.employee')

@section('title', 'My Documents')
@section('page_title', 'My Official Documents')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">📁 Company & Employment Documents</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Document Type</th>
                    <th>Version</th>
                    <th>Uploaded Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
                    <td><strong>{{ $doc->document_name }}</strong></td>
                    <td>
                        <span class="badge badge-primary">{{ $doc->document_type }}</span>
                    </td>
                    <td>v{{ $doc->version }}</td>
                    <td>{{ $doc->created_at->format('M d, Y') }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('employee.documents.download', $doc->id) }}" class="btn btn-secondary btn-sm">
                            📥 Download Document
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No official documents uploaded yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

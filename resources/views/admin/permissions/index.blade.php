@extends('layouts.admin')

@section('title', 'Custom Permissions Management')
@section('page_title', 'Permissions Directory & Management')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 25px;">
    <!-- Left Column: Permissions List Grouped by Module -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">System Permissions Matrix</h3>
            </div>
            <div class="card-body">
                @forelse($permissionsByModule as $module => $permissions)
                    <div class="permission-module-box">
                        <div class="permission-module-header">
                            <span>📂 {{ $module }} Module</span>
                            <span class="badge badge-secondary">{{ count($permissions) }} Permissions</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Permission Name</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Assigned Roles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $perm)
                                    <tr>
                                        <td><strong>{{ $perm->name }}</strong></td>
                                        <td><code>{{ $perm->slug }}</code></td>
                                        <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $perm->description ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $perm->roles_count }} Roles</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-muted); padding: 30px;">
                        No permissions found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Add Custom Permission Form -->
    <div>
        @if(auth()->user()->hasPermission('permissions.create'))
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">➕ Add Custom Permission</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Module Category *</label>
                            <input type="text" name="module" class="form-control" placeholder="e.g. Inventory / Support / Marketing" required value="{{ old('module') }}">
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Select or type a new module name</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Permission Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Export Stock Data" required value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Purpose of this permission">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Custom Permission</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Role - ' . $role->name)
@section('page_title', 'Edit Role: ' . $role->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Role Details & Permissions</h3>
        <div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllCheckboxes(true)">Select All</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllCheckboxes(false)">Deselect All</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">⬅️ Back to Roles</a>
        </div>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Role Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required {{ $role->slug === 'super-admin' ? 'readonly' : '' }}>
                </div>

                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required {{ $role->slug === 'super-admin' ? 'disabled' : '' }}>
                        <option value="active" {{ old('status', $role->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $role->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Role Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $role->description) }}</textarea>
            </div>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid var(--border-color);">

            <div class="form-group">
                <label class="form-label" style="font-size: 1.05rem; font-weight: 700; margin-bottom: 15px;">
                    Module Permission Matrix
                </label>

                @foreach($permissionsByModule as $module => $modulePermissions)
                    @php $safeModuleId = \Illuminate\Support\Str::slug($module); @endphp
                    <div class="permission-module-box">
                        <div class="permission-module-header">
                            <span>📂 {{ $module }} Module</span>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleModuleCheckboxes('{{ $safeModuleId }}')">
                                Select Module
                            </button>
                        </div>
                        <div class="permission-grid">
                            @foreach($modulePermissions as $perm)
                                @php $isAssigned = in_array($perm->id, old('permissions', $rolePermissionIds)); @endphp
                                <label class="checkbox-label">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="perm-checkbox module-{{ $safeModuleId }}" {{ $isAssigned ? 'checked' : '' }}>
                                    <span>
                                        <strong>{{ $perm->name }}</strong>
                                        <small style="display: block; color: var(--text-muted);">{{ $perm->slug }}</small>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update Role Permissions</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleAllCheckboxes(checked) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = checked);
    }

    function toggleModuleCheckboxes(moduleId) {
        const checkboxes = document.querySelectorAll('.module-' + moduleId);
        const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
        checkboxes.forEach(cb => cb.checked = anyUnchecked);
    }
</script>
@endsection

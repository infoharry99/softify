@extends('layouts.admin')

@section('title', 'Direct Permissions - ' . $user->name)
@section('page_title', 'Assign Direct Permissions for ' . $user->name)

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Manage Direct Permissions: {{ $user->name }}</h3>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
                Roles assigned:
                @foreach($user->roles as $r)
                    <span class="badge badge-primary">{{ $r->name }}</span>
                @endforeach
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllCheckboxes(true)">Select All</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllCheckboxes(false)">Deselect All</button>
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">⬅️ Back to Details</a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.users.permissions.update', $user->id) }}" method="POST">
            @csrf

            @foreach($permissionsByModule as $module => $modulePermissions)
                @php $safeModuleId = \Illuminate\Support\Str::slug($module); @endphp
                <div class="permission-module-box">
                    <div class="permission-module-header">
                        <span>📂 {{ $module }} Module</span>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleModuleCheckboxes('{{ $safeModuleId }}')">
                            Toggle Module
                        </button>
                    </div>
                    <div class="permission-grid" id="module-group-{{ $safeModuleId }}">
                        @foreach($modulePermissions as $perm)
                            @php
                                $inheritedFromRole = in_array($perm->id, $rolePermissionIds);
                                $isDirectAssigned = in_array($perm->id, $directPermissionIds);
                            @endphp
                            <label class="checkbox-label" style="{{ $inheritedFromRole ? 'opacity: 0.7;' : '' }}">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" class="perm-checkbox module-{{ $safeModuleId }}" {{ $isDirectAssigned ? 'checked' : '' }}>
                                <span>
                                    <strong>{{ $perm->name }}</strong>
                                    <small style="display: block; color: var(--text-muted);">{{ $perm->slug }}</small>
                                    @if($inheritedFromRole)
                                        <span class="badge badge-secondary" style="font-size: 0.65rem; padding: 2px 5px;">Inherited via Role</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Save Direct Permissions</button>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Cancel</a>
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

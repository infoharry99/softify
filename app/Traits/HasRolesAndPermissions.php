<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions
{
    /**
     * Roles relationship.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Direct User Permissions relationship.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    /**
     * Check if user has a specific role or any of multiple roles.
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        return $this->roles()
            ->where('status', 'active')
            ->where(function ($query) use ($roles) {
                $query->whereIn('slug', $roles)
                      ->orWhereIn('name', $roles);
            })->exists();
    }

    /**
     * Check if user has a specific permission (either via role or direct permission).
     *
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Super Admin has full access by default
        if ($this->hasRole('super-admin')) {
            return true;
        }

        // 1. Check direct user permissions
        if ($this->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // 2. Check role permissions across active roles
        foreach ($this->roles()->where('status', 'active')->get() as $role) {
            if ($role->permissions()->where('slug', $permissionSlug)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get array of direct permission slugs.
     */
    public function getDirectPermissionSlugs(): array
    {
        return $this->permissions()->pluck('slug')->toArray();
    }

    /**
     * Get array of permission slugs inherited from active roles.
     */
    public function getRolePermissionSlugs(): array
    {
        $slugs = [];
        foreach ($this->roles()->where('status', 'active')->get() as $role) {
            $slugs = array_merge($slugs, $role->permissions()->pluck('slug')->toArray());
        }
        return array_unique($slugs);
    }

    /**
     * Get array of all unique permission slugs (direct + roles).
     */
    public function getAllPermissionSlugs(): array
    {
        if ($this->hasRole('super-admin')) {
            return Permission::pluck('slug')->toArray();
        }

        return array_unique(array_merge(
            $this->getDirectPermissionSlugs(),
            $this->getRolePermissionSlugs()
        ));
    }
}

<?php

namespace RCV\Core\Services\Security;

use Illuminate\Contracts\Auth\Authenticatable;

class RbacManager
{
    /** @var array<string,array<string,bool>> role => [permission => true] */
    protected array $rolePermissions = [];

    /** @var array<string,array<string,bool>> userId => [role => true] */
    protected array $userRoles = [];

    /** @var array<string,array<string,bool>> tenantId => [role => true] */
    protected array $tenantRoles = [];

    public function defineRole(string $role, array $permissions): void
    {
        $map = [];
        foreach ($permissions as $perm) {
            $map[$perm] = true;
        }
        $this->rolePermissions[$role] = $map;
    }

    public function assignRoleToUser(Authenticatable|string|int $user, string $role): void
    {
        $userId = is_object($user) ? (string) $user->getAuthIdentifier() : (string) $user;
        $this->userRoles[$userId][$role] = true;
    }

    public function assignRoleToTenant(string $tenantId, string $role): void
    {
        $this->tenantRoles[$tenantId][$role] = true;
    }

    public function userHasPermission(Authenticatable|string|int $user, string $permission, ?string $tenantId = null): bool
    {
        $userId = is_object($user) ? (string) $user->getAuthIdentifier() : (string) $user;
        $roles = array_keys($this->userRoles[$userId] ?? []);
        if ($tenantId !== null) {
            $roles = array_unique(array_merge($roles, array_keys($this->tenantRoles[$tenantId] ?? [])));
        }
        foreach ($roles as $role) {
            if (!empty($this->rolePermissions[$role][$permission])) {
                return true;
            }
        }
        return false;
    }
}



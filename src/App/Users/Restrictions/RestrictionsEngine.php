<?php
namespace App\Users\Restrictions;

use App\Users\Models\User;

interface RestrictionsEngine
{
    public function canUpload(User $user, int $fileSize = 0): bool;

    public function canDownload(User $user): bool;

    public function canCreateFolder(User $user): bool;

    public function canCreateTeamFolder(User $user): bool;

    public function canInviteTeamMembers(User $user, array $newInvites = []): bool;

    public function canVisitShared(User $user): bool;

    public function getRestrictionReason(User $user): string|null;
}

<?php
namespace App\Users\Restrictions;

use App\Users\Models\User;

interface RestrictionsEngine
{
    public function canUpload(User $user, int $fileSize = 0): bool;

    public function canDownload(User $user): bool;

    public function canCreateFolder(User $user): bool;

    public function canVisitShared(User $user): bool;
}

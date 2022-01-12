<?php
namespace Domain\Traffic\Actions;

use Domain\Traffic\Models\Traffic;

class RecordUploadAction
{
    /**
     * Record user upload filesize
     */
    public function __invoke(
        int $file_size,
        string $user_id,
    ): void {
        $record = Traffic::currentDay()
            ->firstOrCreate([
                'user_id' => $user_id,
            ]);

        $record->increment('upload', $file_size);
    }
}

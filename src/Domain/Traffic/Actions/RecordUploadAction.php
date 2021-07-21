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
        $record = Traffic::currentMonth()
            ->firstOrCreate([
                'user_id' => $user_id,
            ]);

        $record->update([
            'upload' => $record->upload + $file_size,
        ]);
    }
}

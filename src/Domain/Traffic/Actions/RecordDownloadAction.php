<?php
namespace Domain\Traffic\Actions;

use Domain\Traffic\Models\Traffic;

class RecordDownloadAction
{
    /**
     * Record user download filesize
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
            'download' => $record->download + $file_size,
        ]);
    }
}

<?php

namespace Domain\Files\Actions;

use Domain\Files\Models\File;

class StoreFileExifMetadataAction
{
    public function __invoke(File $file)
    {
        // Get exif metadata
        $data = readExifData("files/$file->user_id/$file->basename");

        if (is_null($data)) {
            return;
        }

        $file
            ->exif()
            ->create([
                'date_time_original' => $data->DateTimeOriginal ?? null,
                'artist'             => $data->OwnerName ?? null,
                'width'              => $data->COMPUTED->Width ?? null,
                'height'             => $data->COMPUTED->Height ?? null,
                'x_resolution'       => $data->XResolution ?? null,
                'y_resolution'       => $data->YResolution ?? null,
                'color_space'        => $data->ColorSpace ?? null,
                'camera'             => $data->Make ?? null,
                'model'              => $data->Model ?? null,
                'aperture_value'     => $data->ApertureValue ?? null,
                'exposure_time'      => $data->ExposureTime ?? null,
                'focal_length'       => $data->FocalLength ?? null,
                'iso'                => $data->ISOSpeedRatings ?? null,
                'aperture_f_number'  => $data->COMPUTED->ApertureFNumber ?? null,
                'ccd_width'          => $data->COMPUTED->CCDWidth ?? null,
                'longitude'          => $data->GPSLongitude ?? null,
                'latitude'           => $data->GPSLatitude ?? null,
                'longitude_ref'      => $data->GPSLongitudeRef ?? null,
                'latitude_ref'       => $data->GPSLatitudeRef ?? null,
            ]);
    }
}

<?php
namespace Domain\Files\Actions;

class StoreFileExifMetadataAction
{
    public function __invoke($item, $file)
    {
        // Get exif metadata
        $exif_data = get_image_meta_data($file);

        if ($exif_data) {
            // Convert array to collection
            $data = json_decode(json_encode($exif_data));

            $item->exif()->create([
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
}

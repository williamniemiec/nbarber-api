<?php

namespace App\Services;
use App\Models\BarberPhoto;
use App\Models\Dto\BarberPhotoDto;

/**
 * Responsible for providing barber services.
 */
class BarberPhotoService
{
    // ------------------------------------------------------------------------
    //         Methods
    // ------------------------------------------------------------------------
    public function findAllByBarberId(int $barberId): array
    {
        $photos = BarberPhoto::select(['id', 'url'])
            ->where('id_barber', $barberId)
            ->get()
            ->toArray();

        $this->completePhotosUrl($photos);

        return $this->toDto($photos);
    }

    private function completePhotosUrl(array $photos): void
    {
        foreach ($photos as $photo) {
            $this->completePhotoUrl($photo);
        }
    }

    private function completePhotoUrl(BarberPhoto $photo): void
    {
        $photo->url = url('media/uploads/' . $photo->url);
    }

    private function toDto(array $photos): array
    {
        return array_map(fn($photo) => new BarberPhotoDto($photo), $photos);
    }
}

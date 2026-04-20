<?php

namespace App\Actions\Room;

use App\Models\Perahu;
use App\Models\RoomReview;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;

final class DeleteRoomAction
{
    public function __invoke(Perahu $room): void
    {
        try {
            // 1. Delete contents
            $room->room_content()->delete();

            // 2. Delete feature
            $room->room_feature()->delete();

            // 3. Delete prices
            $room->room_prices()->delete();

            // 4. Delete feature image
            if ($room->feature_image) {
                $featurePath = public_path('assets/img/perahu/featureImage/') . $room->feature_image;
                if (file_exists($featurePath)) {
                    @unlink($featurePath);
                }
            }

            // 5. Delete gallery images
            $galleries = $room->room_galleries;
            foreach ($galleries as $gallery) {
                $galleryPath = public_path('assets/img/perahu/room-gallery/') . $gallery->image;
                if (file_exists($galleryPath)) {
                    @unlink($galleryPath);
                }
                $gallery->delete();
            }

            // 6. Delete reviews
            RoomReview::where('room_id', $room->id)->delete();

            // 7. Delete visitors
            Visitor::where('room_id', $room->id)->delete();

            // 8. Finally, delete the boat
            $room->delete();
            
        } catch (\Exception $e) {
            Log::error("[DeleteRoomAction] Error deleting boat ID {$room->id}: " . $e->getMessage());
            throw $e;
        }
    }
}

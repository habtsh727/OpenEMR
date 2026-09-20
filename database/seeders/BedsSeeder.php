<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bed;
use App\Models\Room;
use App\Models\BedType;

class BedsSeeder extends Seeder
{
    public function run(): void
    {
        $bedTypes = BedType::pluck('id', 'code');
        $rooms = Room::with('ward')->get();

        foreach ($rooms as $room) {
            $bedTypeCode = match ($room->ward->code) {
                'ICU' => 'ICU',
                'PED' => 'PED',
                default => 'GEN',
            };

            $bedTypeId = $bedTypes[$bedTypeCode] ?? $bedTypes['GEN'];

            for ($i = 1; $i <= rand(2, 4); $i++) {
                Bed::updateOrCreate(
                    [
                        'room_id' => $room->id,
                        'bed_number' => $room->room_number . '-B' . $i,
                    ],
                    [
                        'bed_type_id' => $bedTypeId,
                        'status' => 'available',
                    ]
                );
            }
        }
    }
}

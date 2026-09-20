<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Ward;
use App\Models\BedClass;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $general = BedClass::where('code', 'GEN')->first();
        $vip = BedClass::where('code', 'VIP')->first();

        $wards = Ward::all();

        foreach ($wards as $ward) {
            for ($i = 1; $i <= 5; $i++) {
                Room::updateOrCreate(
                    [
                        'room_number' => $ward->code . '-' . $i,
                        'ward_id' => $ward->id,
                    ],
                    [
                        'bed_class_id' => $i <= 2 ? $vip->id : $general->id,
                        'floor' => rand(1, 3),
                    ]
                );
            }
        }
    }
}

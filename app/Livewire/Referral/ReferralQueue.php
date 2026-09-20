<?php

// app/Livewire/Referral/ReferralQueue.php
namespace App\Livewire\Referral;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Referral;
use App\Enums\ReferralStatus;
use App\Enums\ReferralUrgency;

class ReferralQueue extends Component
{
    use WithPagination;

    public $filters = [
        'status' => '',
        'urgency' => '',
        'start_date' => '',
        'end_date' => '',
        'search' => '',
    ];

    protected $queryString = ['filters'];

    public function resetFilters()
    {
        $this->filters = [
            'status' => '',
            'urgency' => '',
            'start_date' => '',
            'end_date' => '',
            'search' => '',
        ];
    }

    public function render()
    {
        $query = Referral::with(['encounter.patient'])
            ->latest('referred_at');

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['urgency'])) {
            $query->where('urgency', $this->filters['urgency']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('referred_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('referred_at', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('facility_name', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('encounter.patient', function ($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $referrals = $query->paginate(20);

        return view('livewire.referral.referral-queue', [
            'referrals' => $referrals,
            'statuses' => ReferralStatus::cases(),
            'urgencies' => ReferralUrgency::cases(),
        ]);
    }
}
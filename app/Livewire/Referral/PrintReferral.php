<?php

namespace App\Livewire\Referral;

use Livewire\Component;
use App\Models\Referral;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class PrintReferral extends Component
{
    public $referral;
    public $referralId;

    public $includeAttachments = true;
    public $includeClinicalSummary = true;
    public $includeTimeline = true;
    public $letterhead = 'hospital'; // hospital, clinic, plain
       public $showPreview = true;

    public function mount($referral)
    {
        if ($referral instanceof Referral) {
            $this->referral = $referral->load([
                'encounter.patient',
                'attachments.uploadedBy',
                'uploadedBy'
            ]);
        } else {
            $this->referral = Referral::with([
                'encounter.patient',
                'attachments.uploadedBy',
                'uploadedBy'
            ])->findOrFail($referral);
        }

        $this->referralId = $this->referral->id;
    }

    public function print()
    {
        // This will open the print dialog
        $this->dispatch('open-print-dialog');
    }

    public function downloadPDF()
    {
        $data = [
            'referral' => $this->referral,
            'includeAttachments' => $this->includeAttachments,
            'includeClinicalSummary' => $this->includeClinicalSummary,
            'includeTimeline' => $this->includeTimeline,
            'letterhead' => $this->letterhead,
            'printDate' => now()->format('F j, Y H:i'),
        ];

        $pdf = Pdf::loadView('referrals.print.referral-pdf', $data);

        $filename = "Referral-{$this->referralId}-" . now()->format('Y-m-d') . ".pdf";

        return Response::streamDownload(
            fn() => print($pdf->output()),
            $filename
        );
    }
     public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
    }


    public function downloadReferralLetter()
    {
        $data = [
            'referral' => $this->referral,
            'letterhead' => $this->letterhead,
            'printDate' => now()->format('F j, Y H:i'),
        ];

        $pdf = Pdf::loadView('referrals.print.referral-letter', $data);

        $filename = "Referral-Letter-{$this->referralId}.pdf";

        return Response::streamDownload(
            fn() => print($pdf->output()),
            $filename
        );
    }
    public function toJSON()
    {
        return $this->referral->toJson();
    }

    public function render()
    {
        return view('livewire.referral.print-referral');
    }
}

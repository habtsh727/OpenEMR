<?php
// app/Livewire/Referral/ViewReferral.php
namespace App\Livewire\Referral;

use Livewire\Component;
use App\Models\Referral;
use Illuminate\Support\Facades\Storage;

class ViewReferral extends Component
{
    public $referral;
    public $referralId;

    public function mount(Referral $referral) // Use model binding
    {
        $this->referral = $referral->load([
            'encounter.patient',
            'attachments.uploadedBy',
            'uploadedBy'
        ]);
        $this->referralId = $referral->id;
    }

    public function downloadAttachment($attachmentId)
    {
        $attachment = $this->referral->attachments->find($attachmentId);
        
        if (!$attachment) {
            session()->flash('error', 'Attachment not found');
            return;
        }

        if (!Storage::disk('private')->exists($attachment->file_path)) {
            session()->flash('error', 'File not found on server');
            return;
        }

        return Storage::disk('private')->download(
            $attachment->file_path, 
            $attachment->file_name
        );
    }

    public function render()
    {
        return view('livewire.referral.view-referral');
    }
}
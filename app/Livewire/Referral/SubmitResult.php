<?php

// app/Livewire/Referral/SubmitResult.php
namespace App\Livewire\Referral;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Referral;
use App\Models\ReferralAttachment;
use App\Enums\ReferralStatus;
use App\Enums\AttachmentType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubmitResult extends Component
{
    use WithFileUploads;

    public $referral;
    public $referralId;
    public $result_notes = '';
    public $result_attachments = [];

    protected $rules = [
        'result_notes' => 'required|string|min:20',
        'result_attachments' => 'array|max:10',
        'result_attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
    ];

    protected $messages = [
        'result_attachments.max' => 'Maximum 10 files allowed',
        'result_attachments.*.max' => 'File size must not exceed 10MB',
        'result_attachments.*.mimes' => 'Allowed file types: images, PDF, Word documents',
    ];

    public function mount($referral)
    {
        if ($referral instanceof Referral) {
            $this->referral = $referral;
        } else {
            $this->referral = Referral::findOrFail($referral);
        }
        
        $this->referralId = $this->referral->id;
        
        // Check if referral can accept results
        // if ($this->referral->status !== ReferralStatus::SENT) {
        //     abort(403, 'Only referrals with status "sent" can accept results');
        // }
    }

    // IMPORTANT: Rename this to "submit" to match what the form is calling
    public function submit()
    {
        $this->validate();

        try {
            // Update referral status
            $this->referral->update([
                'status' => ReferralStatus::COMPLETED->value,
            ]);

            // Add result notes as a special attachment
            ReferralAttachment::create([
                'referral_id' => $this->referral->id,
                'uploaded_by' => Auth::id(),
                'attachment_type' => AttachmentType::REPORT->value,
                'file_path' => 'result_notes',
                'file_name' => 'Result_Notes.txt',
                'additional_notes' => $this->result_notes,
                'is_result' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Handle file uploads
            if ($this->result_attachments) {
                foreach ($this->result_attachments as $file) {
                    // Use private disk if configured, otherwise use local
                    $disk = config('filesystems.disks.private') ? 'private' : 'local';
                    $filePath = $file->store("referrals/{$this->referral->id}/results", $disk);
                    
                    ReferralAttachment::create([
                        'referral_id' => $this->referral->id,
                        'uploaded_by' => Auth::id(),
                        'attachment_type' => $this->guessAttachmentType($file),
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'is_result' => true,
                    ]);
                }
            }

            session()->flash('success', 'Result submitted successfully!');
            return redirect()->route('referrals.view', $this->referral->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit result: ' . $e->getMessage());
        }
    }

    /**
     * Guess the attachment type based on file mime type
     */
    private function guessAttachmentType($file)
    {
        // Check if it's a file object
        if (is_string($file)) {
            // Default to OTHER if we can't determine
            return AttachmentType::OTHER->value;
        }
        
        // It's a file object
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Check for images
        if (Str::startsWith($mimeType, 'image/') || 
            in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'])) {
            return AttachmentType::IMAGING->value;
        }
        
        // Check for PDF
        if ($mimeType === 'application/pdf' || $extension === 'pdf') {
            return AttachmentType::REPORT->value;
        }
        
        // Check for documents
        if (Str::contains($mimeType, 'document') || 
            in_array($extension, ['doc', 'docx', 'txt', 'rtf'])) {
            return AttachmentType::REPORT->value;
        }
        
        // Check for lab results (Excel, CSV)
        if (Str::contains($mimeType, 'spreadsheet') || 
            Str::contains($mimeType, 'csv') ||
            in_array($extension, ['xls', 'xlsx', 'csv'])) {
            return AttachmentType::LAB->value;
        }
        
        // Default to other
        return AttachmentType::OTHER->value;
    }

    /**
     * Remove an attachment from the upload queue
     */
    public function removeAttachment($index)
    {
        if (isset($this->result_attachments[$index])) {
            unset($this->result_attachments[$index]);
            $this->result_attachments = array_values($this->result_attachments);
        }
    }

    public function render()
    {
        return view('livewire.referral.submit-result');
    }
}
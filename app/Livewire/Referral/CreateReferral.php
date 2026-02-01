<?php
// app/Livewire/Referral/CreateReferral.php
// app/Livewire/Referral/CreateReferral.php
namespace App\Livewire\Referral;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Encounter;
use App\Models\Referral;
use App\Models\ReferralAttachment;
use App\Enums\ReferralUrgency;
use App\Enums\AttachmentType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CreateReferral extends Component
{
    use WithFileUploads;

    public $encounterId;
    public $encounter;
    public $patientName;
    
    // Form fields
    public $facility_name = '';
    public $facility_type = '';
    public $facility_location = '';
    public $urgency = 'routine';
    public $reason = '';
    public $clinical_summary = '';
    public $attachments = [];
    
    protected $rules = [
        'facility_name' => 'required|string|max:255',
        'facility_type' => 'required|string|max:100',
        'facility_location' => 'required|string|max:255',
        'urgency' => 'required|in:routine,urgent,emergency',
        'reason' => 'required|string|max:500',
        'clinical_summary' => 'required|string|min:20',
        'attachments' => 'array|max:10',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB max
    ];

    protected $messages = [
        'attachments.max' => 'Maximum 10 files allowed',
        'attachments.*.max' => 'File size must not exceed 10MB',
        'attachments.*.mimes' => 'Allowed file types: images, PDF, Word documents',
    ];

    public function mount($encounter = null) // Changed from $encounterId
    {
        // Accept either encounter ID or model
        if ($encounter instanceof Encounter) {
            $this->encounterId = $encounter->id;
            $this->encounter = $encounter;
        } else {
            $this->encounterId = $encounter;
            $this->encounter = Encounter::with('patient')->findOrFail($encounter);
        }
        
        $this->patientName = $this->encounter->patient->first_name ?? 'Unknown';
        
        // Check if user is authorized (doctor or admin)
        if (!Auth::user()->hasRole(['doctor', 'admin', 'super_admin'])) {
            abort(403, 'Only doctors and administrators can create referrals');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Create referral
            $referral = Referral::create([
                'encounter_id' => $this->encounterId,
                'facility_name' => $this->facility_name,
                'facility_type' => $this->facility_type,
                'facility_location' => $this->facility_location,
                'urgency' => $this->urgency,
                'reason' => $this->reason,
                'clinical_summary' => $this->clinical_summary,
                'status' => 'sent', // Automatically mark as sent when created
                'referred_at' => now(),
            ]);

            // Handle file uploads
            if ($this->attachments) {
                foreach ($this->attachments as $file) {
                    $filePath = $file->store("referrals/{$referral->id}", 'private');
                    
                    ReferralAttachment::create([
                        'referral_id' => $referral->id,
                        'uploaded_by' => Auth::id(),
                        'attachment_type' => $this->guessAttachmentType($file->getMimeType()),
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            session()->flash('success', 'Referral created and sent successfully!');
            return redirect()->route('referrals.view', $referral->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create referral: ' . $e->getMessage());
        }
    }

    // private function guessAttachmentType($mimeType)
    // {
    //     if (str_contains($mimeType, 'image')) {
    //         return AttachmentType::IMAGING->value;
    //     } elseif (str_contains($mimeType, 'pdf')) {
    //         return AttachmentType::REPORT->value;
    //     } else {
    //         return AttachmentType::OTHER->value;
    //     }
    // }
  private function guessAttachmentType($file)
{
    // Check if it's a file object
    if (is_string($file)) {
        // If it's already a string (mime type), return appropriate type
        return $this->getAttachmentTypeFromMime($file);
    }
    
    // It's a file object
    $mimeType = $file->getMimeType();
    $extension = strtolower($file->getClientOriginalExtension());
    
    return $this->getAttachmentTypeFromMime($mimeType, $extension);
}

/**
 * Get attachment type from mime type and extension
 */
private function getAttachmentTypeFromMime($mimeType, $extension = null)
{
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
    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            $this->attachments = array_values($this->attachments);
        }
    }

    public function render()
    {
        return view('livewire.referral.create-referral');
    }
}
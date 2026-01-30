<?php
namespace App\Enums;

enum AttachmentType: string
{
    case LAB = 'lab';
    case IMAGING = 'imaging';
    case REPORT = 'report';
    case OTHER = 'other';
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'medicine_id',
        'batch_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // In WalkinOrderItem.php model
public function order()
{
    return $this->belongsTo(WalkinOrder::class, 'order_id'); // Specify the foreign key
}

    public function medicine()
    {
        return $this->belongsTo(PharmacyItem::class, 'medicine_id');
    }

    public function batch()
    {
        return $this->belongsTo(PharmacyBatch::class, 'batch_id');
    }
}

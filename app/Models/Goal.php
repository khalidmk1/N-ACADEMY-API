<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'souscategory_id',
        'goals',
    ];

    
    /**
     * Get the souscategory that owns the Goal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function souscategory(): BelongsTo
    {
        return $this->belongsTo(SousCategory::class);
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['document_number', 'document_date'];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFormattedDocumentDateAttribute(): ?string
    {
        if (is_null($this->document_date)) {
            return null;
        }
        return \Carbon\Carbon::parse($this->document_date)->translatedFormat('d F Y');
    }
}

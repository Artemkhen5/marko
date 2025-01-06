<?php

namespace App\Domain\Note;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $fillable = ['title', 'content'];

    public function getHtmlContentAttribute(): string
    {
        $converter = new CommonMarkConverter();
        $htmlContent = $converter->convert($this->content)->getContent();
        return nl2br($htmlContent);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

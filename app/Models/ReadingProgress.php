<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ReadingProgress extends Model { protected $fillable=['user_id','book_id','page_number','updated_at']; public $timestamps=false; protected function casts(): array { return ['updated_at'=>'datetime']; } public function user(): BelongsTo { return $this->belongsTo(User::class); } public function book(): BelongsTo { return $this->belongsTo(Book::class); } }
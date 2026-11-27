<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Book extends Model {
 protected $fillable=['book_code','category_id','author_id','publisher_id','author_name','publisher_name','shelf_id','title','slug','isbn','description','published_year','cover_path','ebook_path','ebook_mime','is_featured'];
 protected $casts=['is_featured'=>'boolean','published_year'=>'integer'];
 protected static function booted(): void { static::creating(function($book){ if(empty($book->book_code)) $book->book_code='RB-'.strtoupper(Str::random(8)); }); }
 public function category(): BelongsTo { return $this->belongsTo(Category::class); }
 public function author(): BelongsTo { return $this->belongsTo(Author::class); }
 public function publisher(): BelongsTo { return $this->belongsTo(Publisher::class); }
 public function shelf(): BelongsTo { return $this->belongsTo(Shelf::class); }
 public function copies(): HasMany { return $this->hasMany(BookCopy::class); }
 public function loans(): HasMany { return $this->hasMany(Loan::class); }
 public function readingLists(): HasMany { return $this->hasMany(ReadingList::class); }
 public function availableCopies(): HasMany { return $this->copies()->where('status','available'); }
 public function getAvailableCountAttribute(): int { if(array_key_exists('available_count',$this->attributes)) return (int)$this->attributes['available_count']; if($this->relationLoaded('copies')) return $this->copies->where('status','available')->count(); return $this->availableCopies()->count(); }
}

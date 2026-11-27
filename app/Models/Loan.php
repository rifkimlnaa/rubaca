<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Loan extends Model {
 protected $fillable=['user_id','book_id','book_copy_id','status','requested_at','borrowed_at','due_at','returned_at','fine_amount','fine_verified_at','fine_verified_by','renewal_requested_at','renewals','staff_note'];
 protected function casts(): array { return ['requested_at'=>'datetime','borrowed_at'=>'datetime','due_at'=>'datetime','returned_at'=>'datetime','fine_verified_at'=>'datetime','renewal_requested_at'=>'datetime','fine_amount'=>'decimal:2']; }
 public function user(): BelongsTo { return $this->belongsTo(User::class); }
 public function book(): BelongsTo { return $this->belongsTo(Book::class); }
 public function copy(): BelongsTo { return $this->belongsTo(BookCopy::class,'book_copy_id'); }
 public function isOverdue(): bool { return $this->status==='borrowed' && $this->due_at && $this->due_at->isPast(); }
}
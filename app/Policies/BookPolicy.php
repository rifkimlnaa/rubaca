<?php
namespace App\Policies;
use App\Models\Book;
use App\Models\User;
class BookPolicy {
 public function view(?User $user, Book $book): bool { return true; }
 public function manage(User $user): bool { return in_array($user->role,['admin','staff'],true); }
 public function readEbook(User $user, Book $book): bool { return $user->is_active && filled($book->ebook_path); }
}
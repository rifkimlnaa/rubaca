<?php
namespace App\Policies;
use App\Models\Loan;
use App\Models\User;
class LoanPolicy {
 public function view(User $user, Loan $loan): bool { return $user->isAdmin() || $user->isStaff() || $loan->user_id === $user->id; }
 public function process(User $user): bool { return $user->isAdmin() || $user->isStaff(); }
 public function request(User $user): bool { return $user->isMember() && $user->is_active; }
}
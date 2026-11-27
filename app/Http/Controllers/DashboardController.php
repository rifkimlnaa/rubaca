<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use App\Models\LibrarySetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isBackoffice = $user->isAdmin() || $user->isStaff();

        if ($user->isAdmin()) {
            $stats = [
                'Total judul buku' => Book::count(),
                'Eksemplar tersedia' => BookCopy::where('status', 'available')->count(),
                'Anggota aktif' => User::where('role', 'member')->where('is_active', true)->count(),
                'Pinjaman berjalan' => Loan::where('status', 'borrowed')->count(),
            ];
        } elseif ($user->isStaff()) {
            $stats = [
                'Menunggu persetujuan' => Loan::where('status', 'requested')->count(),
                'Sedang dipinjam' => Loan::where('status', 'borrowed')->count(),
                'Terlambat' => Loan::where('status', 'borrowed')->where('due_at', '<', now())->count(),
                'Menunggu perpanjangan' => Loan::whereNotNull('renewal_requested_at')->count(),
            ];
        } else {
            $stats = [
                'Pinjaman aktif' => $user->loans()->whereIn('status', ['requested', 'borrowed'])->count(),
                'Terlambat' => $user->loans()->where('status', 'borrowed')->where('due_at', '<', now())->count(),
                'Daftar bacaan' => $user->readingLists()->count(),
                'E-book tersedia' => Book::whereNotNull('ebook_path')->count(),
            ];
        }

        $loans = ($user->isMember() ? $user->loans() : Loan::query())
            ->with(['book', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $settings = $user->isAdmin() ? [
            'loan_days' => LibrarySetting::get('loan_days', 14),
            'renewal_days' => LibrarySetting::get('renewal_days', 7),
            'fine_per_day' => LibrarySetting::get('fine_per_day', 1000),
            'max_active_loans' => LibrarySetting::get('max_active_loans', 3),
        ] : [];

        $notifications = $user->notifications()->latest()->take(8)->get();

        // Data pendukung dashboard operasional (admin dan petugas).
        $monthlyLoans = collect();
        $categoryStats = collect();
        $dashboardCounts = [];

        if ($isBackoffice) {
            $monthlyLoans = collect(range(5, 0))->map(function (int $monthsAgo) {
                $month = now()->subMonths($monthsAgo);

                return [
                    'label' => $month->translatedFormat('M'),
                    'count' => Loan::whereBetween('requested_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth(),
                    ])->count(),
                ];
            });

            $categoryStats = Category::withCount('books')
                ->orderByDesc('books_count')
                ->limit(5)
                ->get();

            $dashboardCounts = [
                'pending' => Loan::where('status', 'requested')->count(),
                'overdue' => Loan::where('status', 'borrowed')->where('due_at', '<', now())->count(),
                'renewals' => Loan::whereNotNull('renewal_requested_at')->count(),
                'notifications_unread' => $user->unreadNotifications()->count(),
            ];
        }

        return view('dashboard.index', compact(
            'stats',
            'loans',
            'settings',
            'notifications',
            'isBackoffice',
            'monthlyLoans',
            'categoryStats',
            'dashboardCounts',
        ));
    }
}

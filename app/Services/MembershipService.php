<?php

namespace App\Services;

use App\Models\User;
use App\Models\Membership;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MembershipService
{
    /**
     * Menambah jumlah belanja user dan evaluasi tingkatan.
     */
    public function addSpending(User $user, int $amount): void
    {
        $this->checkOrCreateYearlyMembership($user);

        $userMembership = UserMembership::where('user_id', $user->id)
            ->where('year', now()->year)
            ->first();

        $userMembership->increment('yearly_spending', $amount);

        $this->evaluate($user);
    }

    /**
     * Evaluasi apakah user layak naik membership.
     */
    public function evaluate(User $user): void
    {
        $userMembership = UserMembership::where('user_id', $user->id)
            ->where('year', now()->year)
            ->first();

        $eligible = Membership::orderByDesc('min_annual_spending')
            ->where('min_annual_spending', '<=', $userMembership->yearly_spending)
            ->first();

        if ($eligible && $userMembership->membership_id !== $eligible->id) {
            $userMembership->update(['membership_id' => $eligible->id]);
        }
    }

    /**
     * Buat entry baru jika tahun berubah.
     */
    public function checkOrCreateYearlyMembership(User $user): void
    {
        $currentYear = now()->year;

        $exists = UserMembership::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->exists();

        if (!$exists) {
            $classic = Membership::where('name', 'Classic')->first(); // default
            UserMembership::create([
                'user_id' => $user->id,
                'membership_id' => $classic?->id,
                'year' => $currentYear,
                'yearly_spending' => 0,
            ]);
        }
    }

    public function getCurrentMembership(User $user): ?Membership
{
    return UserMembership::with('membership')
        ->where('user_id', $user->id)
        ->where('year', now()->year)
        ->first()?->membership;
}


    public function getUserDiscount(User $user, string $treatmentCategory): int
    {
        $membership = UserMembership::with('membership')
            ->where('user_id', $user->id)
            ->where('year', now()->year)
            ->first()?->membership;

        if (!$membership || $membership->discount_percent === 0) return 0;

    
        // Normalisasi string
        $scope = Str::lower(trim($membership->applies_to));
        $category = Str::lower(trim($treatmentCategory));

        if ($scope === 'all' || $scope === $category) {
            return $membership->discount_percent;
        }

        return 0;
    }
}

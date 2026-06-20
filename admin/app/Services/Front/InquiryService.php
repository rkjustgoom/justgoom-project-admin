<?php

namespace App\Services\Front;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Collection;

class InquiryService
{
    public function listForUser(User $user): Collection
    {
        return $user->inquiries()
            ->latest()
            ->get();
    }

    public function statsForUser(User $user): array
    {
        $inquiries = $user->inquiries()->get(['status']);

        return [
            'total' => $inquiries->count(),
            'new' => $inquiries->where('status', Inquiry::STATUS_NEW)->count(),
            'replied' => $inquiries->where('status', Inquiry::STATUS_REPLIED)->count(),
        ];
    }

    public function belongsToUser(Inquiry $inquiry, User $user): bool
    {
        return (int) $inquiry->user_id === (int) $user->id;
    }
}

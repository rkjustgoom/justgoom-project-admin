<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function getAll()
    {
        return User::with(['category', 'subCategory'])
            ->latest()
            ->paginate(10);
    }

    public function store(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? 1;
        $data['profile'] = $this->uploadProfile($data['profile'] ?? null);

        if (empty($data['referral_code'])) {
            $data['referral_code'] = $this->uniqueReferralCode();
        }

        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['status'] = $data['status'] ?? 1;

        if (isset($data['profile']) && $data['profile'] instanceof UploadedFile) {
            $this->deleteProfile($user->profile);
            $data['profile'] = $this->uploadProfile($data['profile']);
        } else {
            unset($data['profile']);
        }

        if (empty($data['referral_code'])) {
            $data['referral_code'] = $user->referral_code ?: $this->uniqueReferralCode();
        }

        $user->update($data);

        return $user;
    }

    public function delete(User $user): void
    {
        $this->deleteProfile($user->profile);
        $user->delete();
    }

    private function uniqueReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    private function uploadProfile($file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        $destination = public_path('uploads/user-profiles');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/user-profiles/'.$filename;
    }

    private function deleteProfile(?string $profile): void
    {
        if (! $profile) {
            return;
        }

        $path = public_path($profile);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}

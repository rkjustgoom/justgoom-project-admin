<?php

namespace App\Services\Front;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceService
{
    public function listForUser(User $user): Collection
    {
        return $user->services()
            ->latest()
            ->get();
    }

    public function listForPublicProfile(User $user): Collection
    {
        return $user->services()
            ->latest()
            ->get();
    }

    public function statsForUser(User $user): array
    {
        $total = $user->services()->count();

        return [
            'total' => $total,
            'active' => $total,
            'featured' => 0,
        ];
    }

    public function store(User $user, array $data): Service
    {
        return $user->services()->create([
            'product_name' => $data['product_name'],
            'product_desc' => $data['product_desc'] ?? null,
            'product_image' => $this->uploadImage($data['product_image'] ?? null),
        ]);
    }

    public function update(User $user, Service $service, array $data): Service
    {
        abort_unless($this->belongsToUser($service, $user), 404);

        $payload = [
            'product_name' => $data['product_name'],
            'product_desc' => $data['product_desc'] ?? null,
        ];

        if (($data['product_image'] ?? null) instanceof UploadedFile) {
            $this->deleteImage($service->product_image);
            $payload['product_image'] = $this->uploadImage($data['product_image']);
        }

        $service->update($payload);

        return $service->fresh();
    }

    public function delete(User $user, Service $service): void
    {
        abort_unless($this->belongsToUser($service, $user), 404);

        DB::transaction(function () use ($service) {
            $this->deleteImage($service->product_image);
            $service->delete();
        });
    }

    public function belongsToUser(Service $service, User $user): bool
    {
        return (int) $service->user_id === (int) $user->id;
    }

    private function uploadImage(mixed $file): ?string
    {
        if (! $file instanceof UploadedFile) {
            return null;
        }

        $destination = public_path('uploads/services');
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time().'-'.Str::random(12).'.'.$file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/services/'.$filename;
    }

    private function deleteImage(?string $image): void
    {
        if (! $image) {
            return;
        }

        $path = public_path($image);
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}

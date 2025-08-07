<?php

namespace App\Services;

use App\Repositories\interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;

class ProfileService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile($id, array $data)
    {
        try {
            $user = $this->userRepository->update($id, $data);
            if (isset($data['bio']) || isset($data['address'])) {
                $user->userDetails()->updateOrCreate(
                    ['user_id' => $id],
                    ['bio' => $data['bio'], 'address' => $data['address']]
                );
            }
            Log::info('Profile updated', ['user_id' => $id]);
            ActivityLog::create([
                'user_id' => $id,
                'action' => 'update_profile',
                'description' => "Updated profile for user: {$user->email}",
            ]);
            return $user;
        } catch (\Exception $e) {
            Log::error('Profile update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
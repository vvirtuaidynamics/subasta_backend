<?php

namespace App\Http\Api\Client;

use App\Http\Api\Base\BaseRepository;
use App\Http\Api\User\UserRepository;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class ClientRepository extends BaseRepository
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->makeModel();
        $this->userRepository = new UserRepository();

    }

    public function model(): string
    {
        return Client::class;
    }

    public function create(array $data): Model
    {
        $this->unsetClauses();

        $user = $this->userRepository->create([
            'username' => $data['username'],
            'name' => $data['name'],
            'surname' => $data['surname'] ?? '',
            'email' => $data['email'] ?? '',
            'password' => $data['password'],
            'avatar' => $data['avatar'] ?? '',
        ]);
        if (isset($user)) {
            $client = $this->model->create([
                'user_id' => $user->id,
                'address' => $data['address'],
                'phone' => $data['phone'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
                'company_name' => $data['company_name'],
                'industry' => $data['industry'],
                'notes' => $data['notes'],
                'about_me' => $data['about_me'],
                'photo' => $data['photo'],
            ]);
            return $client;
        }
    }


}

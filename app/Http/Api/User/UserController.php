<?php

namespace App\Http\Api\User;


use App\Helpers\ApiResponseHelper;
use App\Http\Api\Base\BaseController;
use App\Http\Api\User\Requests\StoreUserRequest;
use App\Http\Api\User\Requests\UserStoreRequest;
use App\Http\Api\User\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    private UserRepository $repository;

    public function  __constructor()
    {
        $this->repository = new UserRepository();
    }

    public function index()
    {
        $data = $this->repository->all();
        return $this->successResponse(UserResource::collection($data));

    }

    public function show($id)
    {
        //
        $user = $this->repository->getById($id);

        return ApiResponseHelper::sendRespose(new UserResource($user));

    }

    public function store(UserStoreRequest $request)
    {
        $data=[
            'username' => $request->name,
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $request->password,
            'active' => false,
            'avatar' => $request->avatar,
        ];
        DB::beginTransaction();
        try {
            $user = $this->repository->create($data);
            DB::commit();
            return $this->successResponse(new UserResource($user), 'User created success!', 201 );
        }catch (\Exception $ex){
            DB::rollBack();
            return  $this->errorResponse('Failed created user!', 500, $ex);
        }
    }



    public function update(UserUpdateRequest $request, $id)
    {
        $data=[
            'username' => $request->name,
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $request->password,
            'active' => false,
            'avatar' => $request->avatar,
        ];
        DB::beginTransaction();
        try {
            $user = $this->repository->updateById($id, $data);
            DB::commit();
            return $this->successResponse(new UserResource($user), 'User created success!', 201 );
        }catch (\Exception $ex){
            DB::rollBack();
            return  $this->errorResponse('Failed created user!', 500, $ex);
        }
    }

    public function destroy($id)
    {
        $this->repository->deleteById($id);
        return $this->successResponse(null,'User deleted success!');
    }
}

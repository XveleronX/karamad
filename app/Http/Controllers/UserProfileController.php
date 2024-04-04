<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class UserProfileController extends Controller
{
    public function index(): UserProfileResource|JsonResponse
    {
        $id = auth()->id();

        if( $id !== null){
            $user = User::find($id)->first();

            $image = User::find($id)->getMedia();

            $avatar_id = $image[0]->getUrl();

            $user_data = array(
                'user_name' => $user->name ,
                'avatar_url' => $avatar_id
            );

            return new UserProfileResource($user_data);
        }
        return response()->json(null);
    }
}

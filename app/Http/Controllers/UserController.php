<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $userData = $request->validated();

        if (isset($userData['name'])) {
            $user->update([
                'name' => $userData['name']
            ]);
        }

        if (isset($userData['avatar'])) {
            // $imageName = time() . '.' . $userData['avatar']->extension();
            $imageName = $userData['avatar']->store('profile', 'public');
            $userData['avatar'] = $imageName;

            if ($user->profile->avatar !== 'avatar-3814049_1280.png') {
                Storage::delete('profile/' . $user->profile->avatar);
            }
        }

        $user->profile()->update(collect($userData)->only(['avatar'])->all());

        $user->profile->refresh();

        return response()->json([
            'user' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent(Response::HTTP_OK);
    }
}
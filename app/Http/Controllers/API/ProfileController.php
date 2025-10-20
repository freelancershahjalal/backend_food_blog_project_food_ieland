<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        
        // Get all validated data EXCEPT the image file itself.
        $dataToUpdate = $request->safe()->except('profile_image');

        // Check if a new file is present in the request.
        if ($request->hasFile('profile_image')) {
            $user->addMediaFromRequest('profile_image')->toMediaCollection('avatars');
        }

        // Update the user model with the text data.
        $user->update($dataToUpdate);

        // After updating, reload the user from the database and also load the media relationship.
        $updatedUser = $user->fresh()->load('media');

        // Update the user data in the Pinia store on the frontend.
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $updatedUser // Return the user with the media loaded
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // Optional: Also delete the user's profile image from storage
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return response()->json(['message' => 'Account successfully deleted.']);
    }
}

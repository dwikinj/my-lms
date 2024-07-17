<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function Index()  {
        return view('frontend.index');
    }

    public function UserProfile()  {

        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('frontend.dashboard.edit_profile', ['profileData' => $profileData]);
    }//end method

    public function UserProfileUpdate(Request $request) {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'photo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = 'User profile update failed. ';
               
                $notification = [
                    'message' => $errorMessage,
                    'alert-type' => 'error'
                ];
        
                return redirect()->back()->withErrors($validator)->withInput()->with($notification);
            }
        

        $fillable = ['name', 'username', 'email', 'phone', 'address'];
        $user->fill($request->only($fillable));

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $fileName);

            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path('upload/user_images/' . $user->photo))) {
                unlink(public_path('upload/user_images/' . $user->photo));
            }

            $user->photo = $fileName;
        }

        $user->save();

        $notification = [
            'message' => 'User profile updated successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function UserLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function UserChangePassword(Request $request) {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        
        return view('frontend.dashboard.change_password', ['profileData' => $profileData]);
    }
}

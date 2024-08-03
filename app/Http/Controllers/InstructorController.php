<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
{
    public function InstructorDashboard() {
        return view('instructor.index');
    }

    public function InstructorLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('instructor.login');
    }

    public function InstructorLogin(Request $request)
    {
        return view('instructor.instructor_login');
    } //endmethod

    public function InstructorProfile(Request $request)
    {
        $id = Auth::user()->id;

        $profileData = User::find($id);
        return view('instructor.instructor_profile_view', ['profileData' => $profileData]);
    } //endmethod

    //update profile method
    public function InstructorProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|nullable|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = [];
    
            foreach ($errors->all() as $message) {
                $errorMessages[] = $message;
            }
    
            $notification = [
                'message' => 'Instructor profile update failed: ' . implode(', ', $errorMessages),
                'alert-type' => 'error'
            ];
    
            return redirect()->back()->withErrors($validator)->with($notification)->withInput();
        }
    

        $fillable = ['name', 'username', 'email', 'phone', 'address'];
        $user->fill($request->only($fillable));

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/instructor_images'), $fileName);

            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path('upload/instructor_images/' . $user->photo))) {
                unlink(public_path('upload/instructor_images/' . $user->photo));
            }

            $user->photo = $fileName;
        }

        $user->save();

        $notification = [
            'message' => 'Instructor profile updated successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    } //endmethod

    public function InstructorChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('instructor.instructor_change_password', ['profileData' => $profileData]);
    } //endmethod

    public function InstructorPasswordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);


        $id = Auth::user()->id;
        $user = User::find($id);

        if (!Hash::check($request->old_password, $user->password)) {

            $notification = [
                'message' => 'Old password does not match!',
                'alert-type' => 'error'
            ];

            return back()->with($notification);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        $notification = [
            'message' => 'Password updated successfully',
            'alert-type' => 'success'
        ];

        return back()->with($notification);
    } //endmethod

}

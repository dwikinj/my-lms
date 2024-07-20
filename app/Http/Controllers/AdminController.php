<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function AdminLogin(Request $request)
    {
        return view('admin.admin_login');
    } //endmethod

    public function AdminProfile(Request $request)
    {
        $id = Auth::user()->id;

        $profileData = User::find($id);
        return view('admin.admin_profile_view', ['profileData' => $profileData]);
    } //endmethod

    //update profile method
    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fillable = ['name', 'username', 'email', 'phone', 'address'];
        $user->fill($request->only($fillable));

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $fileName);

            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path('upload/admin_images/' . $user->photo))) {
                unlink(public_path('upload/admin_images/' . $user->photo));
            }

            $user->photo = $fileName;
        }

        $user->save();

        $notification = [
            'message' => 'Admin profile updated successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    } //endmethod

    public function AdminChangePassword()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', ['profileData' => $profileData]);
    } //endmethod

    public function AdminPasswordUpdate(Request $request)
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

    public function BecomeInstructor()
    {
        return view('frontend.instructor.register_instructor');
    } //endmethod

    public function InstructorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Instructor Register Failed',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification)->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_INSTRUCTOR,
            'status' => User::STATUS_INACTIVE,
        ]);

        $notification = [
            'message' => 'Instructor Registered Succesfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('instructor.login')->with($notification);
    } //endmethod

    public function AllInstructor()
    {
        $allinstructor = User::where('role', User::ROLE_INSTRUCTOR)->latest()->get();
        return view('admin.backend.instructor.all_instructor', compact('allinstructor'));
    } //endmethod

    public function UpdateUserStatus(Request $request)
    {
        $userId = $request->input('user_id');
        $isChecked = $request->input('is_checked',0);

        $user = User::find($userId);
        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json(['message'=>'User Status Updated Succesfully']);
    }

}

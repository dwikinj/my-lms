<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function AdminChangePassword()  {
        $id = Auth::user()->id;  
        $profileData = User::find($id);
        return view('admin.admin_profile_view', ['profileData' => $profileData]);
    }//endmethod

}

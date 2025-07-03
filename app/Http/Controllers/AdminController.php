<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\Question;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('price');
        $totalStudents = User::where('role', 'user')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $recentOrders = Order::with('payment')->latest()->take(6)->get();
        $totalCourses = Course::count();
        $totalCategories = Category::count();
        $totalSubCategories = SubCategory::count();
        $totalReviews = Review::count();
        $totalQuestions = Question::count();
        $totalBlogPosts = BlogPost::count();
        $totalCoupons = Coupon::where('coupon_status', 1)->count();
        $totalWishlists = Wishlist::count();

        $currentYear = date('Y');
        $orders = Order::whereYear('created_at', $currentYear)
            ->selectRaw('DATE_FORMAT(created_at, "%b") as month, count(*) as total_orders')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->get();

        $orderMonths = $orders->pluck('month');
        $orderCounts = $orders->pluck('total_orders');

        return view('admin.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalStudents',
            'totalInstructors',
            'recentOrders',
            'totalCourses',
            'totalCategories',
            'totalSubCategories',
            'totalReviews',
            'totalQuestions',
            'totalBlogPosts',
            'totalCoupons',
            'totalWishlists',
            'orderMonths',
            'orderCounts'
        ));
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
        $isChecked = $request->input('is_checked', 0);

        $user = User::find($userId);
        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json(['message' => 'User Status Updated Succesfully']);
    }

    public function AllCourses()
    {
        $courses = Course::with(['instructor', 'category'])->get();
        return view('admin.backend.course.all_course', compact('courses'));
    } //end method

    public function updateCourseStatus(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'is_checked' => 'required|boolean',
        ]);

        $courseId = $request->input('course_id');
        $isChecked = $request->input('is_checked');

        try {
            $course = Course::findOrFail($courseId);
            $course->status = $isChecked;
            $course->save();

            return response()->json(['message' => 'Course status updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update course status'], 500);
        }
    } //end method

    public function AdminCourseDetails(string $id)
    {
        $course = Course::with(['category', 'subCategory', 'instructor'])->find($id);
        return view('admin.backend.course.course_details', compact('course'));
    } //end method

    //////////Admin User All Method//////////////
    public function AllAdmin()
    {
        $allAdmin = User::where('role', 'admin')->with('roles')->latest()->get();
        return view('admin.backend.pages.admin.all_admin', compact('allAdmin'));
    } //end method

    public function AddAdmin()
    {
        $roles = Role::all();
        return view('admin.backend.pages.admin.add_admin', compact('roles'));
    } //end method

    public function StoreAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'roles' => 'required|exists:roles,name',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = [
            'message' => 'New Admin User Created Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.admin')->with($notification);
    } //end method

    public function EditAdmin(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin', compact('user', 'roles'));
    } //end method

    public function UpdateAdmin(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Update validasi untuk password (opsional)
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username,' . $request->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
            'roles' => 'required|exists:roles,name',
            'password' => 'sometimes|nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update data dasar user
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Simpan perubahan user

        // Gunakan syncRoles() untuk update role
        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        $notification = [
            'message' => 'Admin User Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.admin')->with($notification);
    }

    public function DeleteAdmin(string $id)
    {
        if (Auth::user()->id == $id) {
            $notification = [
                'message' => 'You cannot delete your own account!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
        
        $user = User::findOrFail($id);
        
        if ($user->hasRole('Super Admin')) { 
            $notification = [
                'message' => 'Super Admin cannot be deleted!',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    
        $user->delete();
    
        $notification = [
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        ];
    
        return redirect()->back()->with($notification);
    } //end method




}

<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function AllPermision()
    {
        $permissions = Permission::all();
        return view('admin.backend.pages.permission.all_permission', compact('permissions'));
    } //end method

    public function AddPermision()
    {
        return view('admin.backend.pages.permission.add_permission');
    } //end method

    public function StorePermision(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions',
            'group_name' => 'required',
        ]);

        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);
    } //end method

    public function EditPermision($id)
    {
        $permission = Permission::find($id);
        return view('admin.backend.pages.permission.edit_permission', compact('permission'));
    } //end method

    public function UpdatePermision(Request $request)
    {
        $permission_id = $request->id;

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission_id,
            'group_name' => 'required',
        ]);

        Permission::findOrFail($permission_id)->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.permission')->with($notification);
    } //end method

    public function DeletePermision($id)
    {
        Permission::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Permission Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } //end method

    public function ImportPermision()
    {
        return view('admin.backend.pages.permission.import_permission');
    } //end method

    public function ExportPermision()
    {
        return Excel::download(new PermissionExport, 'permissions.xlsx');
    } //end method

    public function ImportXlsxPermision(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls',
        ]);

        $notification = [];

        try {
            $import = new PermissionImport();
            Excel::import($import, $request->file('import_file'));

            // Cek apakah ada kegagalan yang dilewati
            if (count($import->failures()) > 0) {
                $errorMessages = [];
                $notification = [
                    'message' => 'Somethign wrong with xlsx',
                    'alert-type' => 'warning'
                ];
            } else {
                // Jika tidak ada failures, berarti sukses total
                $notification = [
                    'message' => 'Permission Imported Successfully',
                    'alert-type' => 'success'
                ];
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $notification = [
                'message' => 'Import Failed',
                'alert-type' => 'error'
            ];
            return redirect()->back()->withInput()->with($notification);
        } catch (\Exception $e) {
            $errorMessage = 'Somethign wrong when import data';
            Log::error('Import Permission Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());

            $notification = [
                'message' => $errorMessage,
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('all.permission')->with($notification);
    } //end method

    //// Roles ////
    public function AllRoles()
    {
        $roles = Role::all();
        return view('admin.backend.pages.role.all_roles', compact('roles'));
    } //end method

    public function AddRoles()
    {
        return view('admin.backend.pages.role.add_roles');
    } //end method

    public function StoreRoles(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3|unique:roles,name',
            ]);

            Role::create([
                'name' => $request->name,
            ]);

            $notification = array(
                'message' => 'Role Created Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.roles')->with($notification);
        } catch (ValidationException $e) {
            $errorMessages = [];
            foreach ($e->errors() as $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $errorMessages[] = $error;
                }
            }
            $errorMessageString = implode($errorMessages); // Gabungkan jadi satu string dengan line break

            $notification = array(
                'message' => 'Validation Failed: ' . $errorMessageString,
                'alert-type' => 'error'
            );


            return redirect()->back()->withInput()->with($notification);
        } catch (\Exception $e) {
            Log::error('Error storing role: ' . $e->getMessage()); // Opsional: log errornya

            $notification = array(
                'message' => 'An unexpected error occurred while creating the role. Please try again. Details: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->withInput()->with($notification);
        }
    } //end method

    public function EditRoles($id)
    {
        $role = Role::find($id);
        return view('admin.backend.pages.role.edit_role', compact('role'));
    } //end method

    public function UpdateRoles(Request $request)
    {
        $role_id = $request->id;

        try {
            $request->validate([
                'name' => 'required|min:3|unique:roles,name,' . $role_id,
                // tambahkan validasi lain jika perlu
            ]);

            $role = Role::findOrFail($role_id);
            $role->update([
                'name' => $request->name,
            ]);

            $notification = array(
                'message' => 'Role Updated Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->route('all.roles')->with($notification);
        } catch (ValidationException $e) {
            // Mengambil semua pesan error validasi
            $errorMessages = [];
            foreach ($e->errors() as $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $errorMessages[] = $error;
                }
            }
            $errorMessageString = implode($errorMessages);

            $notification = array(
                'message' => 'Validation Failed:' . $errorMessageString,
                'alert-type' => 'error'
            );

            // Redirect kembali ke halaman edit dengan input lama dan notifikasi error
            return redirect()->back()->withInput()->with($notification);
        } catch (\Exception $e) {
            // Menangkap error lain yang mungkin terjadi (misal database error)
            Log::error('Error updating role: ' . $e->getMessage()); // Opsional: log errornya

            $notification = array(
                'message' => 'An unexpected error occurred. Please try again. Details: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->withInput()->with($notification);
        }
    } //end method

    public function DeleteRoles($id)
    {
        Role::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Role Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } //end method


    /////Add Role Permission//////

    // Add Role Permission
    public function AddRolesPermission()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroup();
        return view('admin.backend.pages.rolesetup.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
    } //end method

    public function StoreRolesPermission(Request $request)
    {
        // 1. Validasi tetap sama, sudah bagus.
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ], [
            'role_id.required' => 'Please select a role.',
            'permission.required' => 'Please select at least one permission.',
        ]);

        // 2. Dapatkan instance Role dari database.
        $role = Role::findOrFail($request->role_id);
        $permissions = Permission::whereIn('id', $request->permission)->get();


        // 3. Gunakan syncPermissions. Ini akan menggantikan seluruh perulangan foreach Anda.
        // Metode ini akan men-sinkronkan permission yang ada di database dengan
        // array permission yang datang dari request.
        $role->syncPermissions($permissions);

        // 4. Notifikasi dan redirect tetap sama.
        $notification = array(
            'message' => 'Permissions Assigned to Role Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles.permission')->with($notification);
    } //end method

    public function AllRolesPermission()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.backend.pages.rolesetup.all_roles_permission', compact('roles'));
    } //end method

    public function EditRolesPermission($id)
    {
        $selected_role = Role::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroup();
        $rolePermissions = $selected_role->permissions->pluck('id')->toArray();

        return view('admin.backend.pages.rolesetup.edit_roles_permission', compact('roles', 'selected_role', 'permissions', 'permission_groups', 'rolePermissions'));
    } //end method

    public function UpdateRolesPermission(Request $request, $id)
    {
        // 1. Validasi bisa disederhanakan. 'nullable' bagus jika role boleh tidak punya permission.
        $request->validate([
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        // 2. Dapatkan instance Role yang sedang diedit.
        $role = Role::findOrFail($id);

        // 3. Gunakan syncPermissions. Metode ini akan menghapus permission yang tidak dicentang
        $permissions = Permission::whereIn('id', $request->permission)->get();

        // dan menambahkan yang baru dicentang secara otomatis.
        // Jika $request->permission kosong/null, semua permission akan dihapus dari role ini.
        $role->syncPermissions($permissions);

        // 4. Notifikasi dan redirect tetap sama.
        $notification = array(
            'message' => 'Role Permissions Updated Successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('all.roles.permission')->with($notification);
    }

    public function DeleteRolesPermission($id)
    {
        try {
            $role = Role::findOrFail($id);

            // Hapus semua permission yang terkait dengan role ini
            $role->syncPermissions([]);

            // Hapus role itu sendiri
            $role->delete();

            $notification = array(
                'message' => 'Role and its Permissions Deleted Successfully!',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            Log::error('Error deleting role and permissions: ' . $e->getMessage());

            $notification = array(
                'message' => 'An error occurred while deleting the role and its permissions. Please try again.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    } //end method


}

<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;

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

        $notification = []; // Inisialisasi array notifikasi

        try {
            $import = new PermissionImport();
            Excel::import($import, $request->file('import_file'));

            // Cek apakah ada kegagalan yang dilewati
            if (count($import->failures()) > 0) {
                $errorMessages = [];
                foreach ($import->failures() as $failure) {
                    $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . ' (Nilai: ' . implode(', ', $failure->values()) . ')';
                }
                $notification = [
                    'message' => 'Sebagian data berhasil diimpor. Namun, beberapa baris gagal:<br>' . implode('<br>', $errorMessages),
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
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . ' (Nilai: ' . implode(', ', $failure->values()) . ')';
            }
            $notification = [
                'message' => 'Import Gagal Total. Terdapat error validasi:<br>' . implode('<br>', $errorMessages),
                'alert-type' => 'error'
            ];
            return redirect()->back()->withInput()->with($notification);
        } catch (\Exception $e) {
            $errorMessage = 'Terjadi kesalahan saat mengimpor data.';

            if ($e instanceof QueryException) {
                $errorCode = $e->errorInfo[1] ?? null;
                if ($errorCode == 1062) {
                    $errorMessage = 'Import Gagal. Terdeteksi data duplikat yang mencoba dimasukkan ke database.';
                } else {
                    $errorMessage = 'Import Gagal karena kesalahan database.';
                }
            } else {
                $errorMessage = 'Import Gagal: ' . $e->getMessage();
            }
            Log::error('Import Permission Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());

            $notification = [
                'message' => $errorMessage,
                'alert-type' => 'error'
            ];
        }

        return redirect()->route('all.permission')->with($notification);
    } //end method


}

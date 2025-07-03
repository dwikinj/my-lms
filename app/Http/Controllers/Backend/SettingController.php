<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function SmtpSetting()
    {
        $smtp = SmtpSetting::find(1);
        return view('admin.backend.setting.smtp_update', compact('smtp'));
    } //end method

    public function SmtpUpdate(Request $request)
    {
        $smtp_id = $request->id;

        SmtpSetting::findOrFail($smtp_id)->update([
            'mailer' => $request->mailer,
            'host' => $request->host,
            'port' => $request->port,
            'username' => $request->username,
            'password' => $request->password,
            'encryption' => $request->encryption,
            'from_address' => $request->from_address,
        ]);

        $notification = array(
            'message' => 'SMTP Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } //end method

    ///Site Setting///
    public function SiteSetting()
    {
        $setting = SiteSetting::first();
        if (!$setting) {
            $setting = SiteSetting::firstOrCreate([], [
                'phone'     => '+6281234567890',
                'email'     => 'info@example.com',
                'address'   => 'Jl. Contoh No.123, Jakarta, Indonesia',
                'facebook'  => 'https://facebook.com/example',
                'twitter'   => 'https://twitter.com/example',
                'copyright' => '© My Lms' . date('Y'),
            ]);
        }
        return view('admin.backend.setting.site_update', compact('setting'));
    } //end method

    public function UpdateSiteSetting(Request $request)
    {
        // 1. Validasi Request
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:site_settings,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'copyright' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data setting yang akan diupdate
        $setting = SiteSetting::findOrFail($request->id);
        $oldLogo = $setting->logo;
        $newLogoFilename = null; // Untuk dikirim kembali ke client jika ada perubahan

        // 2. Proses Update Data Teks
        $setting->phone = $request->phone;
        $setting->email = $request->email;
        $setting->address = $request->address;
        $setting->facebook = $request->facebook;
        $setting->twitter = $request->twitter;
        $setting->copyright = $request->copyright;

        // 3. Handle Image Upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $save_path = public_path('upload/admin_images/');

            if (!File::isDirectory($save_path)) {
                File::makeDirectory($save_path, 0755, true, true);
            }

            $file->move($save_path, $filename);
            $setting->logo = $filename;
            $newLogoFilename = $filename; // Simpan nama file baru

            // Hapus logo lama jika ada
            if ($oldLogo && File::exists($save_path . $oldLogo) && $oldLogo !== 'no_image.jpg') {
                File::delete($save_path . $oldLogo);
            }
        }

        if ($setting->save()) {
            $newLogoUrl = null;
            if ($newLogoFilename) {
                $newLogoUrl = asset('upload/admin_images/' . $newLogoFilename);
            }
            return response()->json([
                'success' => true,
                'message' => 'Site Setting Updated Successfully!',
                'new_logo_url' => $newLogoUrl // Kirim URL logo baru jika ada perubahan
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update site settings. Please try again.'
            ], 500); // Internal server error
        }
    }

    ///end site setting//

}

<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'notification_emails' => 'required|string',
        ]);

        // Update or create the admin setting
        AdminSetting::updateOrCreate(
            ['id' => 1], // Assuming a single settings record
            ['notification_emails' => $validated['notification_emails']]
        );

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }
}

<?php


namespace App\Http\Controllers;

use App\Models\JoinUs;
use App\Models\AdminSetting;  // Add AdminSetting model
use Illuminate\Http\Request;
use App\Notifications\JoinUsNotification;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class JoinUsController extends Controller
{



    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(JoinUs::latest()->get())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
    return '
        <div class="dropdown">
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
          <ul class="dropdown-menu">
            <li><a href="#" class="dropdown-item" onclick="deleteJoinUs(' . $row->id . ')">Delete</a></li>
          </ul>
        </div>
    ';
})

                ->rawColumns(['action'])
                ->make(true);
        }
        $settings = AdminSetting::where('user_id', Auth::user()->id)->first();
        $existingEmails = array_filter(explode(',', $settings->notification_emails ?? ''));
        // dd($existingEmails);
        return view('joinus.index', compact('settings', 'existingEmails'));
    }
 


    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required|string|in:pharmacy,laboratory',
            'first_name' => ['required', 'regex:/^[A-Za-z]+$/'],
            'last_name' => ['required', 'regex:/^[A-Za-z]+$/'],
            'email' => 'required|email:rfc,dns',
            'phone_number' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'message' => 'nullable|string',
        ], [
            'type.required' => 'The type field is required.',
            'type.in' => 'Only pharmacy and laboratory are allowed in type.',
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name must only contain letters.',
            'last_name.required' => 'Last name is required.',
            'last_name.regex' => 'Last name must only contain letters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'The phone number must be a valid 10-digit number starting with 6 to 9.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Save the form submission
            $join = JoinUs::create($request->all());

            // 🔄 Defer email sending to after the response
            $this->sendJoinUsEmailsAfterResponse($join);

            // ✅ Respond immediately
            return response()->json([
                'status' => true,
                'message' => 'Request submitted successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('JoinUs Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    protected function sendJoinUsEmailsAfterResponse($join)
    {
        // Run AFTER response is sent to client
        app()->terminating(function () use ($join) {
            try {
                $adminSetting = AdminSetting::first();
                $adminEmails = explode(',', $adminSetting->notification_emails);

                foreach ($adminEmails as $email) {
                    $email = trim($email);
                    if (!empty($email)) {
                        Notification::route('mail', $email)
                            ->notify(new JoinUsNotification($join));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Email sending failed (JoinUs): ' . $e->getMessage());
            }
        });
    }





    public function updateEmails(Request $request)
    {
        $validated = $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        $newEmails = $validated['emails'];

        // Get existing record
        $setting = AdminSetting::where('user_id', Auth::user()->id)->first();

        if ($setting) {

            // Save back
            $setting->notification_emails = implode(',', $newEmails);
            $setting->save();
        } else {
            // No existing record, insert directly
            AdminSetting::create([
                'user_id' => Auth::user()->id,
                'notification_emails' => implode(',', $newEmails),
            ]);
        }

        return response()->json(['message' => 'Emails updated .']);
    }




 public function destroy($id)
{
    $join = JoinUs::findOrFail($id);
    $join->delete();

    if (request()->ajax()) {
        return response()->json(['success' => 'Join Us request deleted successfully']);
    }

    return redirect()->route('joinus.index')
        ->with('success', 'Join Us request deleted successfully');
}





    public function showSettings()
    {
        $adminSetting = AdminSetting::find(1); // or use ->first()
        return view('your-view-name', compact('adminSetting'));
    }
}

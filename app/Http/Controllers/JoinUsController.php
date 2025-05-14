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
    // Method to handle the "Join Us" request form submission
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
            $data = $request->all();
            $join = JoinUs::create($data);

            // Fetch admin emails from the admin_settings table
            $adminSetting = AdminSetting::first();  // Assumes there's only one record with the email settings
            $adminEmails = explode(',', $adminSetting->notification_emails);  // Assuming emails are stored as comma-separated values

            // Send notifications to all the admin emails
            foreach ($adminEmails as $email) {
                Notification::route('mail', trim($email))->notify(new JoinUsNotification($join));
            }

            // Notify the user who submitted the form
            // Notification::route('mail', $join->email)->notify(new JoinUsNotification($join));

            return response()->json([
                'status' => true,
                'message' => 'Request submitted successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('JoinUs Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
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
                            <li>
                              <form action="' . route('joinus.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button class="dropdown-item " type="submit">Delete</button>
                              </form>
                            </li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $settings = AdminSetting::where('user_id', Auth::user()->id)->first();
        $existingEmails = array_filter(explode(',', $settings->notification_emails ?? ''));
        // dd($existingEmails);
        return view('joinus.index', compact('settings', 'existingEmails'));
    }

    public function destroy($id)
    {
        $join = JoinUs::findOrFail($id);
        $join->delete();

        return redirect()->route('joinus.index')
            ->with('success', 'Join Us request deleted successfully');
    }





    public function showSettings()
    {
        $adminSetting = AdminSetting::find(1); // or use ->first()
        return view('your-view-name', compact('adminSetting'));
    }
}

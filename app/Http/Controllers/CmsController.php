<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\TermsAndCondition;
use App\Models\ReturnPolicy;
use App\Models\PrivacyPolicy;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class CmsController extends Controller
{
    // About Us
    public function aboutIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = AboutUs::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('cms.about-us.edit', $row->id);
                    $showUrl = route('cms.about-us.show', $row->id);
                    $deleteUrl = route('cms.about-us.destroy', $row->id);

                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="' . $showUrl . '">View</a></li>
            <li><a class="dropdown-item" href="' . $editUrl . '">Edit</a></li>
            <li><button class="dropdown-item btn-delete" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button></li>
        </ul>
    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cms.about-us.index');
    }

    public function aboutCreate()
    {
        return view('cms.about-us.create');
    }

    public function aboutStore(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        AboutUs::create([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.about-us.index')->with('success', 'About Us added successfully.');
    }

    public function aboutShow(AboutUs $aboutUs)
    {
        return view('cms.about-us.show', compact('aboutUs'));
    }

    public function aboutEdit(AboutUs $aboutUs)
    {
        return view('cms.about-us.edit', compact('aboutUs'));
    }

    public function aboutUpdate(Request $request, AboutUs $aboutUs)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $aboutUs->update([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.about-us.index')->with('success', 'About Us updated successfully.');
    }

    public function aboutDestroy(AboutUs $aboutUs)
    {
        try {
            $aboutUs->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete.']);
        }
    }



    // FAQs
    public function faqsIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($row) {
                    return $row->question ?? '-';
                })
                ->addColumn('description', function ($row) {
                    return $row->description ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('cms.faqs.edit', $row->id);
                    $showUrl = route('cms.faqs.show', $row->id);
                    $deleteUrl = route('cms.faqs.destroy', $row->id);

                    return '
                <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="' . $showUrl . '">View</a></li>
                        <li><a class="dropdown-item" href="' . $editUrl . '">Edit</a></li>
                        <li><button class="dropdown-item btn-delete" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button></li>
                    </ul>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cms.faqs.index');
    }


    public function faqsCreate()
    {
        return view('cms.faqs.create');
    }

    public function faqsStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'description' => 'required',
        ]);

        Faq::create([
            'question' => $request->question,
            'description' => $request->description,
        ]);

        return redirect()->route('cms.faqs.index')->with('success', 'FAQ added successfully.');
    }

    public function faqsShow(Faq $faq)
    {
        return view('cms.faqs.show', compact('faq'));
    }

    public function faqsEdit(Faq $faq)
    {
        return view('cms.faqs.edit', compact('faq'));
    }

    public function faqsUpdate(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'description' => 'required',
        ]);

        $faq->update([
            'question' => $request->question,
            'description' => $request->description,
        ]);

        return redirect()->route('cms.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function faqsDestroy(Faq $faq)
    {
        try {
            $faq->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete.']);
        }
    }



    // Return Policies
    public function returnPoliciesIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = ReturnPolicy::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('cms.return-policies.edit', $row->id);
                    $showUrl = route('cms.return-policies.show', $row->id);
                    $deleteUrl = route('cms.return-policies.destroy', $row->id);

                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="' . $showUrl . '">View</a></li>
            <li><a class="dropdown-item" href="' . $editUrl . '">Edit</a></li>
            <li><button class="dropdown-item btn-delete" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button></li>
        </ul>
    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cms.return-policies.index');
    }

    public function returnPoliciesCreate()
    {
        return view('cms.return-policies.create');
    }

    public function returnPoliciesStore(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        ReturnPolicy::create([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.return-policies.index')->with('success', 'Return Policy added successfully.');
    }

    public function returnPoliciesShow(ReturnPolicy $returnPolicy)
    {
        return view('cms.return-policies.show', compact('returnPolicy'));
    }

    public function returnPoliciesEdit(ReturnPolicy $returnPolicy)
    {
        return view('cms.return-policies.edit', compact('returnPolicy'));
    }

    public function returnPoliciesUpdate(Request $request, ReturnPolicy $returnPolicy)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $returnPolicy->update([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.return-policies.index')->with('success', 'Return Policy updated successfully.');
    }

    public function returnPoliciesDestroy(ReturnPolicy $returnPolicy)
    {
        try {
            $returnPolicy->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete.']);
        }
    }


    // Privacy Policies
    public function privacyPoliciesIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = PrivacyPolicy::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('cms.privacy-policies.edit', $row->id);
                    $showUrl = route('cms.privacy-policies.show', $row->id);
                    $deleteUrl = route('cms.privacy-policies.destroy', $row->id);

                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="' . $showUrl . '">View</a></li>
            <li><a class="dropdown-item" href="' . $editUrl . '">Edit</a></li>
            <li><button class="dropdown-item btn-delete" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button></li>
        </ul>
    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cms.privacy-policies.index');
    }

    public function privacyPoliciesCreate()
    {
        return view('cms.privacy-policies.create');
    }

    public function privacyPoliciesStore(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        PrivacyPolicy::create([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.privacy-policies.index')->with('success', 'Privacy Policy added successfully.');
    }

    public function privacyPoliciesShow(PrivacyPolicy $privacyPolicy)
    {
        return view('cms.privacy-policies.show', compact('privacyPolicy'));
    }

    public function privacyPoliciesEdit(PrivacyPolicy $privacyPolicy)
    {
        return view('cms.privacy-policies.edit', compact('privacyPolicy'));
    }

    public function privacyPoliciesUpdate(Request $request, PrivacyPolicy $privacyPolicy)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $privacyPolicy->update([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.privacy-policies.index')->with('success', 'Privacy Policy updated successfully.');
    }

    public function privacyPoliciesDestroy(PrivacyPolicy $privacyPolicy)
    {
        try {
            $privacyPolicy->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete.']);
        }
    }


    // Terms and Conditions
    public function termsIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = TermsAndCondition::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('cms.terms-and-conditions.edit', $row->id);
                    $showUrl = route('cms.terms-and-conditions.show', $row->id);
                    // $deleteUrl = route('cms.terms-and-conditions.destroy', $row->id);

                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="' . $showUrl . '">View</a></li>
            <li><a class="dropdown-item" href="' . $editUrl . '">Edit</a></li>
            </ul>
            </div>';
            // <li><button class="dropdown-item btn-delete" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button></li>
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cms.terms-and-conditions.index');
    }

    public function termsCreate()
    {
        return view('cms.terms-and-conditions.create');
    }

    public function termsStore(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        TermsAndCondition::create([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.terms-and-conditions.index')->with('success', 'Terms & Conditions added successfully.');
    }

    public function termsShow(TermsAndCondition $termsAndCondition)
    {
        return view('cms.terms-and-conditions.show', compact('termsAndCondition'));
        return view('webpage.terms', compact('termsAndCondition'));
    }

    public function termsEdit(TermsAndCondition $termsAndCondition)
    {
        return view('cms.terms-and-conditions.edit', compact('termsAndCondition'));
    }
    

    public function termsUpdate(Request $request, TermsAndCondition $termsAndCondition)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $termsAndCondition->update([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.terms-and-conditions.index')->with('success', 'Terms & Conditions updated successfully.');
    }

    public function termsDestroy(TermsAndCondition $termsAndCondition)
    {
        try {
            $termsAndCondition->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete.']);
        }
    }






    public function contactIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = ContactUs::latest()->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('cms.contact-us.edit', $row->id);
                    $showUrl = route('cms.contact-us.show', $row->id);
                    $deleteUrl = route('cms.contact-us.destroy', $row->id);
                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="' . $showUrl . '">View</a></li>
            <li><a class="dropdown-item" href="' . $editUrl . '">Edit</a></li>
            <li><button class="dropdown-item btn-delete" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button></li>
        </ul>
    </div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cms.contact-us.index');
    }
    public function contactCreate()
    {
        return view('cms.contact-us.create');
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);
        ContactUs::create([
            'description' => $request->description,
        ]);

        return redirect()->route('cms.contact-us.index')->with('success', 'Contact added successfully.');
    }


    public function contactShow(ContactUs $contactUs)
    {
        return view('cms.contact-us.show', compact('contactUs'));
    }

    public function contactEdit(ContactUs $contactUs)
    {
        return view('cms.contact-us.edit', compact('contactUs'));
    }

    public function contactUpdate(Request $request, ContactUs $contactUs)
    {
        $contactUs->update($request->only(['description']));

        return redirect()->route('cms.contact-us.index')->with('success', 'Contact updated successfully.');
    }

    public function contactDestroy(ContactUs $contactUs)
    {
        try {
            $contactUs->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete.']);
        }
    }
    public function aboutUs()
    {
        $about = AboutUs::latest()->first();
        return response()->json([
            'status' => true,
            'data' => $about,
        ]);
    }

    public function contactUs()
    {
        $contact = ContactUs::latest()->first();
        return response()->json([
            'status' => true,
            'data' => $contact,
        ]);
    }

    public function faqs()
    {
        $faqs = Faq::orderBy('id', 'desc')->get();
        return response()->json([
            'status' => true,
            'data' => $faqs,
        ]);
    }

public function terms()
{
    $terms = TermsAndCondition::latest()->first();

    $description = '<div style="text-align:justify;">' . $terms->description . '</div>';

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $terms->id,
            'description' => $description,
            'created_at' => $terms->created_at,
            'updated_at' => $terms->updated_at,
        ]
    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}


public function returnPolicy()
{
    $returnPolicy = ReturnPolicy::latest()->first();

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $returnPolicy->id,
            'description' => '<div style="text-align:justify;">' . $returnPolicy->description . '</div>',
            'created_at' => $returnPolicy->created_at,
            'updated_at' => $returnPolicy->updated_at,
        ],
    ]);
}



   public function privacyPolicy()
{
    $privacyPolicy = PrivacyPolicy::latest()->first();

    return response()->json([
        'status' => true,
        'data' => [
            'id' => $privacyPolicy->id,
            'description' => '<div style="text-align:justify;">' . $privacyPolicy->description . '</div>',
            'created_at' => $privacyPolicy->created_at,
            'updated_at' => $privacyPolicy->updated_at,
        ],
    ]);
}









public function termsweb()
{
    $termsAndCondition = TermsAndCondition::latest()->first(); // or where('status', 1)->first()

    return view('webpage.terms', compact('termsAndCondition'));
}


public function privacyPolicyweb()
{
    $privacyPolicy = PrivacyPolicy::latest()->first(); // or where('status', 1)->first()

    return view('webpage.privacy-policy', compact('privacyPolicy'));
}

public function contactweb()
{
    $contactUs = ContactUs::latest()->first(); // or where('status', 1)->first()

    return view('webpage.contact', compact('contactUs'));
}
public function aboutweb()
{
    $aboutUs = AboutUs::latest()->first(); // or where('status', 1)->first()

    return view('webpage.about', compact('aboutUs'));
}
// public function homeweb()
// {
//     $home = AboutUs::latest()->first(); // or where('status', 1)->first()

//     return view('webpage.home', compact('home'));
// }
}

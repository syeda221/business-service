<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Switch the active company in the session.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $companyId = $request->input('company_id');
        
        // Ensure the company belongs to the authenticated user (or if admin, let them switch to any)
        $company = Company::where('user_id', Auth::id())->where('id', $companyId)->firstOrFail();

        session(['active_company_id' => $company->id]);

        return back()->with('success', "Switched to company: {$company->name}");
    }

    /**
     * Create a new company and set it as active.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,NULL,id,user_id,' . Auth::id(),
        ]);

        $company = Company::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
        ]);

        session(['active_company_id' => $company->id]);

        return back()->with('success', "Company '{$company->name}' created and set as active.");
    }
}

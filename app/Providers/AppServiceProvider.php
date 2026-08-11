<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $companies = \App\Models\Company::where('user_id', $user->id)->get();
                
                // Auto-create default company if none exists
                if ($companies->isEmpty()) {
                    $defaultCompany = \App\Models\Company::create([
                        'user_id' => $user->id,
                        'name' => 'Default Company'
                    ]);
                    $companies = collect([$defaultCompany]);
                }
                
                $activeCompanyId = session('active_company_id');
                $activeCompany = null;
                if ($activeCompanyId) {
                    $activeCompany = $companies->firstWhere('id', $activeCompanyId);
                }
                
                if (!$activeCompany) {
                    $activeCompany = $companies->first();
                    session(['active_company_id' => $activeCompany->id]);
                }
                
                $view->with([
                    'companies' => $companies,
                    'activeCompany' => $activeCompany
                ]);
            }
        });
    }
}

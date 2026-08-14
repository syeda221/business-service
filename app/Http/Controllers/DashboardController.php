<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ServiceProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $userId = Auth::id();
        $companyId = session('active_company_id');

        if (!$companyId && Auth::check()) {
            $company = Company::where('user_id', $userId)->first();
            if ($company) {
                $companyId = $company->id;
                session(['active_company_id' => $companyId]);
            }
        }

        // Fetch marketplace-retail progress for active company
        $marketplaceProgress = ServiceProgress::where('user_id', $userId)
            ->where('service_key', 'marketplace-retail')
            ->when($companyId, function ($query) use ($companyId) {
                return $query->where('company_id', $companyId);
            })
            ->first();

        $marketplacePayload = $marketplaceProgress->payload ?? [];
        
        $orders = $marketplacePayload['orders'] ?? [];
        $products = $marketplacePayload['products'] ?? [];

        // Real dynamic revenue & customers calculation
        $totalRevenue = 0;
        $uniqueCustomers = [];
        $recentTransactions = [];

        if (!empty($orders) && is_array($orders)) {
            foreach ($orders as $order) {
                $amount = (float) ($order['order_amount'] ?? 0);
                if (isset($order['order_status']) && strtolower($order['order_status']) === 'succeeded') {
                    $totalRevenue += $amount;
                }
                if (!empty($order['customer_name'])) {
                    $uniqueCustomers[$order['customer_name']] = true;
                }

                $recentTransactions[] = [
                    'id' => $order['order_id'] ?? 'N/A',
                    'customer' => $order['customer_name'] ?? 'Customer',
                    'email' => $order['customer_email'] ?? ($order['notes'] ?? '—'),
                    'product' => $order['product_sku'] ?? 'Product',
                    'amount' => '$' . number_format($amount, 2),
                    'status' => $order['order_status'] ?? 'Pending',
                    'date' => !empty($order['order_date']) ? date('M d, Y', strtotime($order['order_date'])) : date('M d, Y'),
                ];
            }
        }

        $customerCount = count($uniqueCustomers);
        $avgRevenue = $customerCount > 0 ? ($totalRevenue / $customerCount) : 0;

        // Real dynamic top products
        $topProducts = [];
        if (!empty($products) && is_array($products)) {
            foreach ($products as $prod) {
                $productName = $prod['product_name'] ?? ($prod['sku'] ?? 'Product');
                $price = (float) ($prod['target_selling_price'] ?? 0);
                $qty = (int) ($prod['inventory_quantity'] ?? 0);
                $topProducts[] = [
                    'name' => $productName,
                    'sales' => $qty,
                    'revenue' => '$' . number_format($price * $qty, 2),
                ];
            }
        }

        $stats = [
            [
                'title' => 'Total ARR',
                'value' => '$' . number_format($totalRevenue, 2),
                'change' => $totalRevenue > 0 ? '+100%' : '0%',
                'trend' => $totalRevenue > 0 ? 'up' : 'neutral',
                'description' => 'real-time revenue',
                'icon' => 'currency-dollar'
            ],
            [
                'title' => 'Active Customers',
                'value' => number_format($customerCount),
                'change' => $customerCount > 0 ? '+' . $customerCount : '0',
                'trend' => $customerCount > 0 ? 'up' : 'neutral',
                'description' => 'total recorded customers',
                'icon' => 'users'
            ],
            [
                'title' => 'Avg. Revenue Per Account',
                'value' => '$' . number_format($avgRevenue, 2),
                'change' => $avgRevenue > 0 ? '+100%' : '0%',
                'trend' => $avgRevenue > 0 ? 'up' : 'neutral',
                'description' => 'based on orders',
                'icon' => 'trending-up'
            ],
            [
                'title' => 'Churn Rate',
                'value' => '0.00%',
                'change' => '0%',
                'trend' => 'down',
                'description' => 'target < 2.5%',
                'icon' => 'user-minus'
            ]
        ];

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'topProducts'));
    }
}

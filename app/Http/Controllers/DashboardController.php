<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            [
                'title' => 'Total ARR',
                'value' => '$142,380.00',
                'change' => '+14.2%',
                'trend' => 'up',
                'description' => 'vs last quarter',
                'icon' => 'currency-dollar'
            ],
            [
                'title' => 'Active Customers',
                'value' => '1,842',
                'change' => '+8.4%',
                'trend' => 'up',
                'description' => 'vs last month',
                'icon' => 'users'
            ],
            [
                'title' => 'Avg. Revenue Per Account',
                'value' => '$77.30',
                'change' => '+2.3%',
                'trend' => 'up',
                'description' => 'vs last week',
                'icon' => 'trending-up'
            ],
            [
                'title' => 'Churn Rate',
                'value' => '1.92%',
                'change' => '-0.4%',
                'trend' => 'down',
                'description' => 'target < 2.5%',
                'icon' => 'user-minus'
            ]
        ];

        $recentTransactions = [
            [
                'id' => 'TX-1092',
                'customer' => 'Olivia Ryans',
                'email' => 'olivia.ryans@example.com',
                'product' => 'Enterprise Plan (Annual)',
                'amount' => '$2,400.00',
                'status' => 'Succeeded',
                'date' => 'Aug 10, 2026'
            ],
            [
                'id' => 'TX-1091',
                'customer' => 'Marcus Vance',
                'email' => 'marcus.v@example.com',
                'product' => 'Startup Plan (Monthly)',
                'amount' => '$99.00',
                'status' => 'Succeeded',
                'date' => 'Aug 09, 2026'
            ],
            [
                'id' => 'TX-1090',
                'customer' => 'Aria Chen',
                'email' => 'aria.c@example.com',
                'product' => 'Custom API Addon',
                'amount' => '$450.00',
                'status' => 'Pending',
                'date' => 'Aug 09, 2026'
            ],
            [
                'id' => 'TX-1089',
                'customer' => 'Devon Lane',
                'email' => 'devon.lane@example.com',
                'product' => 'Growth Plan (Monthly)',
                'amount' => '$199.00',
                'status' => 'Failed',
                'date' => 'Aug 08, 2026'
            ],
            [
                'id' => 'TX-1088',
                'customer' => 'Sarah Jenkins',
                'email' => 'sarah.j@example.com',
                'product' => 'Startup Plan (Annual)',
                'amount' => '$990.00',
                'status' => 'Succeeded',
                'date' => 'Aug 08, 2026'
            ],
        ];

        $topProducts = [
            ['name' => 'Enterprise Plan', 'sales' => 142, 'revenue' => '$340,800.00'],
            ['name' => 'Growth Plan', 'sales' => 512, 'revenue' => '$101,888.00'],
            ['name' => 'Startup Plan', 'sales' => 1120, 'revenue' => '$110,880.00'],
        ];

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'topProducts'));
    }
}

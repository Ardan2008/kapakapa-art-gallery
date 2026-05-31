<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerCountryController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'today');

        $query = VisitorLog::query()
            ->whereNotNull('country_code')
            ->where('country_code', '!=', 'un');

        // Filter berdasarkan period
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case '7days':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
        }

        $countries = $query
            ->selectRaw('country_name, country_code, COUNT(*) as count')
            ->groupBy('country_name', 'country_code')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'period'    => $period,
            'countries' => $countries,
            'total'     => $countries->sum('count'),
        ]);
    }
}
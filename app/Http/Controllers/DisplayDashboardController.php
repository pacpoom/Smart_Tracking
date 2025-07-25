<?php

namespace App\Http\Controllers;

use App\Models\ContainerOrderPlan;
use App\Models\ContainerStock;
use App\Models\YardLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DisplayDashboardController extends Controller
{
    public function index()
    {
        // 1. Plan Status of the Month
        $statusCounts = ContainerOrderPlan::whereMonth('created_at', Carbon::now()->month)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Remaining Free Time List (Top 5 nearing expiration)
        $expiringContainers = ContainerOrderPlan::where('status', 2) // Received
            ->with(['container', 'containerStock.yardLocation'])
            ->get()
            ->sortBy('remaining_free_time')
            ->take(5);

        // 3. Location Occupancy Data for Chart
        $totalActiveLocations = YardLocation::where('is_active', true)->count();
        $occupiedLocationsCount = ContainerStock::distinct('yard_location_id')->count('yard_location_id');
        $availableLocationsCount = $totalActiveLocations - $occupiedLocationsCount;

        // 4. Container Activity of the Month for Bar Chart
        $activityCounts = \App\Models\ContainerTransaction::whereMonth('created_at', Carbon::now()->month)
            ->select('activity_type', DB::raw('count(*) as total'))
            ->groupBy('activity_type')
            ->pluck('total', 'activity_type');

        return view('display-dashboard', [
            'pendingCount' => $statusCounts->get(1, 0),
            'receivedCount' => $statusCounts->get(2, 0),
            'shippedOutCount' => $statusCounts->get(3, 0),
            'expiringContainers' => $expiringContainers,
            'availableLocationsCount' => $availableLocationsCount,
            'occupiedLocationsCount' => $occupiedLocationsCount,
            'activityLabels' => $activityCounts->keys(),
            'activityData' => $activityCounts->values(),
        ]);
    }
}

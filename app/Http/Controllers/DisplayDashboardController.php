<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerOrderPlan;
use App\Models\ContainerPullingPlan;
use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use App\Models\YardLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\YardCategory;

class DisplayDashboardController extends Controller
{
    // public function index()
    // {
    //     // 1. Plan Status of the Month
    //     $statusCounts = ContainerOrderPlan::whereMonth('created_at', Carbon::now()->month)
    //         ->select('status', DB::raw('count(*) as total'))
    //         ->groupBy('status')
    //         ->pluck('total', 'status');

    //     // 2. Remaining Free Time List (Top 5 nearing expiration)
    //     $expiringContainers = ContainerOrderPlan::where('status', 2) // Received
    //         ->with(['container', 'containerStock.yardLocation'])
    //         ->get()
    //         ->sortBy('remaining_free_time')
    //         ->take(5);

    //     // 3. Location Occupancy Data for Chart
    //     $totalActiveLocations = YardLocation::where('is_active', true)->count();
    //     $occupiedLocationsCount = ContainerStock::distinct('yard_location_id')->count('yard_location_id');
    //     $availableLocationsCount = $totalActiveLocations - $occupiedLocationsCount;

    //     // 4. Container Activity of the Month for Bar Chart
    //     $activityCounts = ContainerTransaction::whereMonth('created_at', Carbon::now()->month)
    //         ->select('activity_type', DB::raw('count(*) as total'))
    //         ->groupBy('activity_type')
    //         ->pluck('total', 'activity_type');
            
    //     // 5. Today's Pulling Plan Data
    //     $pullingTodayPlans = ContainerPullingPlan::whereDate('pulling_date', Carbon::today())
    //         ->with(['containerOrderPlan.container', 'containerOrderPlan.containerStock.yardLocation'])
    //         ->orderBy('pulling_order', 'asc')
    //         ->get();

    //     // 6. Total Containers in Master
    //     $totalContainers = Container::count();

    //     // 7. Expired Containers Count
    //     $expiredCount = ContainerOrderPlan::where('status', 2) // Received
    //         ->get()
    //         ->filter(function ($plan) {
    //             return $plan->expiration_date && $plan->expiration_date->isPast();
    //         })
    //         ->count();

    //     return view('display-dashboard', [
    //         'pendingCount' => $statusCounts->get(1, 0),
    //         'receivedCount' => $statusCounts->get(2, 0),
    //         'shippedOutCount' => $statusCounts->get(3, 0),
    //         'expiringContainers' => $expiringContainers,
    //         'availableLocationsCount' => $availableLocationsCount,
    //         'occupiedLocationsCount' => $occupiedLocationsCount,
    //         'activityLabels' => $activityCounts->keys(),
    //         'activityData' => $activityCounts->values(),
    //         'pullingTodayCount' => $pullingTodayPlans->count(), // Update count from the new query
    //         'totalContainers' => $totalContainers,
    //         'expiredCount' => $expiredCount,
    //         'pullingTodayPlans' => $pullingTodayPlans, // Pass the new collection
    //     ]);
    // }

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
        $activityCounts = ContainerTransaction::whereMonth('created_at', Carbon::now()->month)
            ->select('activity_type', DB::raw('count(*) as total'))
            ->groupBy('activity_type')
            ->pluck('total', 'activity_type');
            
        // 5. Today's Pulling Plan Data
        $pullingTodayPlans = ContainerPullingPlan::whereDate('pulling_date', Carbon::today())
            ->with(['containerOrderPlan.container', 'containerOrderPlan.containerStock.yardLocation'])
            ->orderBy('pulling_order', 'asc')
            ->get();

        // 6. Total Containers in Master
        $totalContainers = ContainerStock::count();

        // 7. Expired Containers Count
        $expiredCount = ContainerOrderPlan::where('status', 2) // Received
            ->get()
            ->filter(function ($plan) {
                return $plan->expiration_date && $plan->expiration_date->isPast();
            })
            ->count();

        // --- เพิ่ม Logic ใหม่ ---
        // 8. Available Locations by Type
        $locationTypes = YardCategory::where('type', 'location_type')
            ->withCount(['yardLocations' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $occupiedCountsByType = YardLocation::whereIn('id', ContainerStock::pluck('yard_location_id')->unique())
            ->where('is_active', true)
            ->select('location_type_id', DB::raw('count(*) as total'))
            ->groupBy('location_type_id')
            ->pluck('total', 'location_type_id');

        $availableLocationsByType = $locationTypes->map(function ($type) use ($occupiedCountsByType) {
            $occupied = $occupiedCountsByType->get($type->id, 0);
            return (object)[
                'name' => $type->name,
                'available' => $type->yard_locations_count - $occupied,
            ];
        });
        // --- สิ้นสุดส่วนที่เพิ่ม ---

        return view('display-dashboard', [
            'pendingCount' => $statusCounts->get(1, 0),
            'receivedCount' => $statusCounts->get(2, 0),
            'shippedOutCount' => $statusCounts->get(3, 0),
            'expiringContainers' => $expiringContainers,
            'availableLocationsCount' => $availableLocationsCount,
            'occupiedLocationsCount' => $occupiedLocationsCount,
            'activityLabels' => $activityCounts->keys(),
            'activityData' => $activityCounts->values(),
            'pullingTodayCount' => $pullingTodayPlans->count(),
            'totalContainers' => $totalContainers,
            'expiredCount' => $expiredCount,
            'pullingTodayPlans' => $pullingTodayPlans,
            'availableLocationsByType' => $availableLocationsByType, // ส่งข้อมูลใหม่ไปที่ View
        ]);
    }
}

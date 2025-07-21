<?php

namespace App\Http\Controllers;

use App\Models\PartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. นับจำนวน Part Request ในแต่ละสถานะ
        $statusCounts = PartRequest::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingCount = $statusCounts->get('pending', 0);
        $approvedCount = $statusCounts->get('approved', 0);
        $rejectedCount = $statusCounts->get('rejected', 0);
        $deliveryCount = $statusCounts->get('delivery', 0);

        // 2. ดึงข้อมูล 5 รายการเบิกล่าสุด
        $recentRequests = PartRequest::with(['user', 'part'])->latest()->take(5)->get();

        // 3. ส่งข้อมูลทั้งหมดไปที่ View
        return view('dashboard', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'deliveryCount',
            'recentRequests'
        ));
    }
}

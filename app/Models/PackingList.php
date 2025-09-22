<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Container;
use App\Models\Material; // อย่าลืมนำเข้า Model Material
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackingList extends Model
{
    use HasFactory;

    protected $table = 'packing_list';

    public function scopeFilter($query, Request $request)
    {
        // ใช้ Eager Loading เพื่อลดจำนวน Query และแก้ปัญหา N+1
        $query->with(['container', 'material']);

        // Delivery Date Range
        if ($request->filled('delivery_date_from')) {
            $query->where('delivery_date', '>=', $request->delivery_date_from);
        }
        if ($request->filled('delivery_date_to')) {
            $query->where('delivery_date', '<=', $request->delivery_date_to);
        }

        // Search text fields
        if ($request->filled('delivery_order')) {
            $query->where('delivery_order', 'like', '%' . $request->delivery_order . '%');
        }
        if ($request->filled('case_number')) {
            $query->where('case_number', 'like', '%' . $request->case_number . '%');
        }
        if ($request->filled('box_id')) {
            $query->where('box_id', 'like', '%' . $request->box_id . '%');
        }

        // Search through relationships
        if ($request->filled('container_no')) {
            $query->whereHas('container', function ($q) use ($request) {
                $q->where('container_no', 'like', '%' . $request->container_no . '%');
            });
        }
        if ($request->filled('material_no')) {
            $query->whereHas('material', function ($q) use ($request) {
                $q->where('material_number', 'like', '%' . $request->material_no . '%');
            });
        }

        return $query->orderBy('id', 'desc');
    }


    /**
     * New static method to get report data based on the provided SQL query.
     * แปลง Raw SQL ที่ซับซ้อนมาเป็น Query Builder
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getPlanReportData()
    {
        // Subquery (T2): สร้างตารางย่อยเพื่อรวม quantity ของ packing_list
        $packingSummary = DB::table('packing_list')
            ->select('material_id', 'container_id', DB::raw('SUM(quantity) as Qty'))
            ->groupBy('material_id', 'container_id');

        // Main Query: เริ่มต้นจาก container_order_plans (T1)
        return DB::table('container_order_plans as plans')
            ->where('plans.status', 2)
            ->select(
                'plans.plan_no',
                'containers.container_no',
                'containers.agent',
                'materials.material_number',
                'pfep.model',
                'pfep.part_type',
                'pfep.uloc',
                'pfep.pull_type',
                'packing_summary.Qty',
                'materials.unit'
            )
            ->leftJoinSub($packingSummary, 'packing_summary', function ($join) {
                $join->on('plans.container_id', '=', 'packing_summary.container_id');
            })
            ->leftJoin('material as materials', 'packing_summary.material_id', '=', 'materials.id')
            ->leftJoin('containers', 'plans.container_id', '=', 'containers.id')
            ->leftJoin('pfep', function ($join) {
                $join->on('materials.id', '=', 'pfep.material_id')
                     ->where('pfep.is_primary', 1);
            });
    }


    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

}

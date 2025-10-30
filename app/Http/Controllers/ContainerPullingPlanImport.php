<?php

namespace App\Imports;

use App\Models\Container;
use App\Models\ContainerOrderPlan;
use App\Models\ContainerPullingPlan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContainerPullingPlanImport implements ToModel, WithHeadingRow, WithValidation
{
    private $authUserId;

    public function __construct($authUserId)
    {
        $this->authUserId = $authUserId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Tìm Container bằng 'container_no'
        $container = Container::where('container_no', $row['container_no'])->first();

        if (!$container) {
            Log::warning('Container Pulling Plan Import: Container not found ' . $row['container_no']);
            return null;
        }

        // 2. Tìm ContainerOrderPlan mới nhất đang trong kho (status != 3)
        $orderPlan = ContainerOrderPlan::where('container_id', $container->id)
            ->whereHas('containerStock', fn($q) => $q->where('status', '!=', 3))
            ->latest('id')
            ->first();

        if (!$orderPlan) {
            Log::warning('Container Pulling Plan Import: Active stock plan not found for container ' . $row['container_no']);
            return null;
        }

        // 3. Chuyển đổi ngày tháng
        try {
            $pullingDate = Carbon::createFromFormat('Ymd', $row['pulling_date'])->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Container Pulling Plan Import: Invalid date format for ' . $row['container_no'] . '. Expected yyyyMMdd.');
            return null;
        }

        // 4. Tìm hoặc tạo mới Pulling Plan
        // Chúng ta sẽ tìm bằng order_plan_id VÀ pulling_date
        $pullingPlan = ContainerPullingPlan::firstOrNew(
            [
                'container_order_plan_id' => $orderPlan->id,
                'pulling_date' => $pullingDate
            ]
        );

        // Cập nhật hoặc thiết lập dữ liệu
        $pullingPlan->plan_type = $row['plan_typeallpull']; // Cần kiểm tra lại heading
        $pullingPlan->pulling_order = $row['pulling_order'];
        $pullingPlan->user_id = $this->authUserId;
        $pullingPlan->status = 1; // 1 = Planned

        // Nếu là bản ghi mới, tạo số plan_no
        if (!$pullingPlan->exists) {
            $pullingPlan->pulling_plan_no = ContainerPullingPlan::generatePullingPlanNumber();
        }

        $pullingPlan->save();

        return $pullingPlan;
    }

    public function rules(): array
    {
        return [
            'container_no' => 'required|string',
            'plan_typeallpull' => 'required|in:All,Pull',
            'pulling_order' => 'required|integer|min:1',
            'pulling_dateyyyymmdd' => 'required|numeric|digits:8',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'plan_typeallpull.in' => 'Plan Type must be either All or Pull.',
            'pulling_dateyyyymmdd.digits' => 'Pulling Date must be in yyyyMMdd format.',
        ];
    }
    
    /**
     * Map heading names to lowercase keys
     * @return array
     */
    public function headingRow(): int
    {
        return 1;
    }
}

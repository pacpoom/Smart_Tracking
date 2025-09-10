<?php

namespace App\Imports;

use App\Models\Container;
use App\Models\ContainerOrderPlan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class ContainerOrderPlanImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $container = Container::firstOrCreate(
        ['container_no' => $row['container_no'],'size' => 40 , 'agent' => $row['agent']],
        );

        // Check for existing record with the same container_id and house_bl
        $existingPlan = ContainerOrderPlan::where('container_id', $container->id)
            ->where('house_bl', $row['house_bl'])
            ->first();

        // If a duplicate is found, skip this row
        if ($existingPlan) {
            return null;
        }

        return new ContainerOrderPlan([
            'plan_no'      => ContainerOrderPlan::generatePlanNumber(),
            'container_id' => $container->id,
            'model'        => $row['model'],
            'type'         => $row['type'],
            'house_bl'     => $row['house_bl'],
            'eta_date'     => $this->transformDate($row['eta_date']),
            'free_time'    => $row['free_time'],
            'checkin_date' => $this->transformDate($row['checkin_date']),
            'depot'        => $row['depot'] ?? null,
            'status'       => 1, // Default to Pending
        ]);
    }

    public function rules(): array
    {
        return [
            // 2. แก้ไข: เปลี่ยนจาก 'exists' เป็น 'string'
            //    เพื่อให้สามารถรับค่า container_no ใหม่ๆ ได้
            'container_no' => 'required|string',
            'house_bl' => 'required|string',
            'eta_date' => 'nullable|date_format:Ymd',
            'checkin_date' => 'nullable|date_format:Ymd',
        ];
    }

    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Carbon::createFromFormat('Ymd', $value);
        } catch (\Exception $e) {
            return null;
        }
    }
}

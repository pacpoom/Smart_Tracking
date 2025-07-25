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
        // 1. ค้นหา Container จาก container_no
        //    ถ้าไม่เจอ จะสร้าง Container ใหม่ให้โดยอัตโนมัติ
        $container = Container::firstOrCreate(
            ['container_no' => $row['container_no']]
        );

        return new ContainerOrderPlan([
            'plan_no'      => ContainerOrderPlan::generatePlanNumber(),
            'container_id' => $container->id,
            'model'        => $row['model'],
            'type'         => $row['type'],
            'house_bl'     => $row['house_bl'],
            'eta_date'     => $this->transformDate($row['eta_date']),
            'free_time'    => $row['free_time'],
            'checkin_date' => $this->transformDate($row['checkin_date']),
            'status'       => 1, // Default to Pending
        ]);
    }

    public function rules(): array
    {
        return [
            // 2. แก้ไข: เปลี่ยนจาก 'exists' เป็น 'string'
            //    เพื่อให้สามารถรับค่า container_no ใหม่ๆ ได้
            'container_no' => 'required|string',
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

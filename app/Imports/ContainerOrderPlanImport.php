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
        $container = Container::where('container_no', $row['container_no'])->first();

        if (!$container) {
            return null;
        }

        return new ContainerOrderPlan([
            'container_id' => $container->id,
            'model'        => $row['model'],
            'type'         => $row['type'],
            'house_bl'     => $row['house_bl'],
            'eta_date'     => $this->transformDate($row['eta_date']),
            'free_time'    => $row['free_time'],
            'checkin_date' => $this->transformDate($row['checkin_date']),
            'is_active'    => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'container_no' => 'required|exists:containers,container_no',
            // แก้ไข: เพิ่ม 'digits:8' เพื่อความแม่นยำ
            'eta_date' => 'nullable|digits:8|date_format:Ymd',
            'checkin_date' => 'nullable|digits:8|date_format:Ymd',
        ];
    }

    /**
     * Transform a date value from Ymd format.
     *
     * @param  mixed $value
     * @return \Carbon\Carbon|null
     */
    private function transformDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // แปลงค่าจากรูปแบบ Ymd โดยตรง
        try {
            return Carbon::createFromFormat('Ymd', $value);
        } catch (\Exception $e) {
            return null;
        }
    }
}

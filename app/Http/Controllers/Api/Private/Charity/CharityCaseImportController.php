<?php

namespace App\Http\Controllers\Api\Private\Charity;

use App\Http\Controllers\Controller;
use App\Models\CharityCase\CharityCase;
use App\Models\Parameter\ParameterValue;
use App\Services\Upload\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;


class CharityCaseImportController extends Controller implements HasMiddleware
{    protected $uploadService;


    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public static function middleware(): array
    {
        return [
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
        $file = $request->file('file');

        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $socialStatusId = [
            'متزوجه' => 2,
            'مطلقه' => 4,
            'ارمله' => 1,
            'اعزب' => 3,
            'ارمل' => 4
        ];

        $houseType = [
            'إيجار' => 1,
            'ملك' => 0
        ];

        $dontationPriority = [
            'ح' => 23,
            'م' => 24,
            'س' => 25
        ];

        foreach (array_slice($rows, 2) as $index => $row) {

            $case = CharityCase::where('national_id', $row[19])->first();

            if($case != null){
                continue;
            }

            $area = ParameterValue::where('parameter_value', $row[11])->first();

            if ($area == null) {
                $area = null;
            }else{
                $area = $area->id;
            }

            // if($index == 111){
            //     dd($row[16]);
            //     dd($socialStatusId[$row[16]]);
            // }
            $data = [
                'name' => $row[20] == '/' || $row[20] == '' ? "": $row[20], // اسم الحالة
                'national_id' => $row[19]??'', // الرقم القومي للحالة
                'pair_name' => $row[18]??'', // اسم الزوج/الزوجة
                'pair_national_id' => $row[17]??'', // الرقم القومي للزوج
                'phone' => $row[14], // رقم الهاتف
                'number_of_children' => $row[15]??0, // عدد أفراد الأسرة
                'social_status_id' => $socialStatusId[$row[16]]?? null, // الحالة الاجتماعية
                'gender' => 1, // Default, or map if needed
                'housing_type' => $houseType[$row[12]] ?? 0, // نوع السكن
                'address' => $row[11], // منطقة السكن
                'area_id' => $area, // Custom method to map area name
                'note' => '', // Add if needed
                'donation_priority_id' => $dontationPriority[$row[13]] ?? null, // Default or map
                'date_of_birth' => null, // Add if available
                'charity_id' => 1
            ];



            CharityCase::create($data);


        }
        DB::commit();
        return response()->json([
            'message' => __('messages.success.created')
        ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

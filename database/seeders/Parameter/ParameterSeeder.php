<?php

namespace Database\Seeders\Parameter;

use App\Models\Parameter\Parameter;
use App\Models\Parameter\ParameterValue;
use App\Traits\MultiDatabaseArray;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParameterSeeder extends Seeder
{
    /**
     * List of parameter names and values to seed.
     * @var array
     */
    protected $parameters = [
        'socialStatus' => [
            ['parameter_value' => 'ارمل/ة', 'parameter_order' => 1, 'parameter_id' => 1, 'description' => ''],
            ['parameter_value' => 'متزوج/ة', 'parameter_order' => 1, 'parameter_id' => 1, 'description' => ''],
            ['parameter_value' => 'أعزب', 'parameter_order' => 1, 'parameter_id' => 1, 'description' => ''],
            ['parameter_value' => 'مطلق/ة', 'parameter_order' => 1, 'parameter_id' => 1, 'description' => ''],
        ],
        'area' => [
            ['parameter_value' => 'منطقة السلامونى', 'parameter_order' => 2, 'parameter_id' => 2, 'description' => ''],
            ['parameter_value' => 'شارع السلام', 'parameter_order' => 2, 'parameter_id' => 2, 'description' => ''],
            [
                'parameter_value' => 'المدرسه الإعداديه',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'عزبه العسكرى',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'عزبة العمدة',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'شارع المشروع',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'شارع السوق',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'الجمعيه الشرعيه',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'شارع الأنايه',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'شارع المجزر',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'الكوبرى الحديد',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'منطقه الرحبه',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'الجامع الكبير',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'شارع البحر',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'منطقه مسجد سالم الزكى',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'المساكن القديمه',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'جوجر',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ],
            [
                'parameter_value' => 'منشاءه البدوى',
                'parameter_order' => 2,
                'parameter_id' => 2,
                'description' => '',
            ]
        ],
        'donationPriority' => [
            ['parameter_value' => 'اكثر احتياجا', 'parameter_order' => 3, 'parameter_id' => 3, 'description' => 'ا'],
            ['parameter_value' => 'متوسط', 'parameter_order' => 3, 'parameter_id' => 3, 'description' => 'ب'],
            ['parameter_value' => 'مساعدات موسمية', 'parameter_order' => 3, 'parameter_id' => 3, 'description' => 'ج'],
            ['parameter_value' => 'اقل احتياجا', 'parameter_order' => 3, 'parameter_id' => 3, 'description' => 'د'],
        ],
        'educationLevel' => [
            ['parameter_value' => 'ابتدائى', 'parameter_order' => 4, 'parameter_id' => 4, 'description' => ''],
            ['parameter_value' => 'اعداداى', 'parameter_order' => 4, 'parameter_id' => 4, 'description' => ''],
            ['parameter_value' => 'ثانوى', 'parameter_order' => 4, 'parameter_id' => 4, 'description' => ''],
            ['parameter_value' => 'جامعى', 'parameter_order' => 4, 'parameter_id' => 4, 'description' => ''],
        ],
        'donationType' => [
            ['parameter_value' => 'مساعدات مدارس', 'parameter_order' => 5, 'parameter_id' => 5, 'description' => ''],
        ],
    ];

    public function run(): void
    {
        foreach ($this->parameters as $parameterName => $values) {
            // Create the parameter
            $parameter = Parameter::create([
                'parameter_name' => $parameterName,
                'parameter_order' => $values[0]['parameter_order'], // Take order from the first value
            ]);

            // Insert parameter values
            foreach ($values as $value) {
                ParameterValue::create([
                    'parameter_id' => $parameter->id,
                    'parameter_value' => $value['parameter_value'],
                    'parameter_order' => $value['parameter_order'],
                    'description' => $value['description'] ?? '',
                    'color' => $value['color'] ?? '',
                ]);
            }
        }
    }
}

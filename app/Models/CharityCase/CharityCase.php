<?php

namespace App\Models\CharityCase;

use App\Enums\Charity\CharityCaseGender;
use App\Enums\Charity\CharityCaseSocialStatus;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Lang; // Import Lang facade

class CharityCase extends Model
{
    use CreatedUpdatedBy, LogsActivity;

    protected $table = 'charity_cases';

    //protected static $logAttributes = ['title', 'content']; // Fields to track
    protected static $logName = 'charityCase'; // Custom log name

    public static $logMainColumn = 'name';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('charityCase')
            ->setDescriptionForEvent(function (string $eventName) {
                $user = auth()->user();
                $userName = $user ? $user->name : 'مستخدم غير معروف';

                return Lang::get("logs.$eventName", [
                    'name' => $this->name,
                    'nationalId' => $this->national_id,
                    'user' => $userName
                ]);
            });
    }
    protected $fillable = [
        'national_id',
        'name',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'social_status',
        'note'
    ];

    protected function casts(): array
    {
        return [
            'gender' => CharityCaseGender::class,
            'social_status' => CharityCaseSocialStatus::class
        ];
    }

    public function documents()
    {
        return $this->hasMany(CharityCaseDocument::class);
    }
}

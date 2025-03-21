<?php

namespace App\Models\Donation;

use App\Enums\Donation\DonationType;
use App\Models\Charity\Charity;
use App\Models\CharityCase\CharityCase;
use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Lang; // Import Lang facade


class Donation extends Model
{
    use CreatedUpdatedBy, LogsActivity;
    protected $fillable = [
        'amount',
        'date',
        'type',
        'note',
        'charity_case_id',
        'charity_id'
    ];

    protected static $logName = 'donation'; // Custom log name

    public static $logMainColumn = 'number';


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->number = time() . mt_rand(1000, 9999);
        });
    }

    protected function casts(): array
    {
        return [
            'type' => DonationType::class,
        ];
    }

    public function charity()
    {
        return $this->belongsTo(Charity::class);
    }

    public function CharityCase()
    {
        return $this->belongsTo(CharityCase::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('donation')
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

}

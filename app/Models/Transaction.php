<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use app\Models\Wallet;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = Uuid::uuid4();
        });
    }

    public function payer()
    {
        return $this->belongsTo(Wallet::class, 'payer_wallet_id');
    }

    public function payee()
    {
        return $this->belongsTo(Wallet::class, 'payee_wallet_id');
    }
}

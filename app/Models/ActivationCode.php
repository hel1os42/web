<?php

namespace App\Models;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Hashids\Hashids;

/**
 * Class ActivationCode
 * @package App
 *
 * @property string code
 * @property string user_id
 * @property string offer_id
 * @property string redemption_id
 */
class ActivationCode extends Model
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('database.default');

        $this->table      = 'activation_codes';
        $this->primaryKey = 'code';
        $this->timestamps = ["created_at"];
    }

    /** @return string */
    public function getCode(): string
    {
        return $this->code;
    }


    /** @return BelongsTo */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** @return BelongsTo */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    /** @return BelongsTo */
    public function redemption(): BelongsTo
    {
        return $this->belongsTo(Redemption::class, 'redemption_id', 'id');
    }

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function (ActivationCode $model) {
//            //$model->code = $model->generateCode();
//
//
//            $hashids = new Hashids('NAU', 3, 'abcdefghijklmnopqrstuvwxyz');
//            $model->code = $hashids->encode($model->id);
//        });
//    }

    /**
     * Generate pretty, readable, unique, random alpha-numeric string.
     *
     * @return string
     */
    public function generateCode()
    {
        $hashids = new Hashids('NAU', 3, 'abcdefghijklmnopqrstuvwxyz');
        return $hashids->encode(123);
//        $length = rand(3, 5);
//        $code = '';
//
//        while (($len = strlen($code)) < $length) {
//            $size = $length - $len;
//
//            $bytes = random_bytes($size);
//
//            $code .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
//        }

        //return $this->find($code) ? $this->generateCode() : $code;
    }
}

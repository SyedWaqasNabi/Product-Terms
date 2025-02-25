<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TradeItemOffer
 *
 * @package App\Models
 * @property int $id
 * @property string $queue
 * @property string $payload
 * @property int $attempts
 * @property int|null $reserved_at
 * @property int $available_at
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs whereAvailableAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs whereQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Jobs whereReservedAt($value)
 * @mixin \Eloquent
 */
class Jobs extends Model
{
    /**
     * The attributes that are mass blocked.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs';
}

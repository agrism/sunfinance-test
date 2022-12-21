<?php

namespace App\Models;

use App\Enums\NotificationChannelEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $client_id
 * @property NotificationChannelEnum $channel
 * @property string $content
 * @property ?Carbon $completed_at
 */
class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    protected $fillable = [
        'client_id',
        'channel',
        'content',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

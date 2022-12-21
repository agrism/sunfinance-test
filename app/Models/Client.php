<?php

namespace App\Models;

use App\Casts\ClientCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone_number
 * @property string $created_at
 * @property string $updated_at
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'status' => ClientCast::class,
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
    ];
}

<?php

namespace App\Models;

use App\Traits\UUIDAble;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\JwtToken
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $token_title
 * @property mixed|null $restrictions
 * @property mixed|null $permissions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $expires_at
 * @property string|null $last_used_at
 * @property string|null $refreshed_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereRefreshedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereRestrictions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereTokenTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JwtToken whereUuid($value)
 * @mixin \Eloquent
 */
class JwtToken extends Model
{
    use HasFactory;
    use UUIDAble;

    protected $table = 'jwt_tokens';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'user_id',
        'token_title',
        'token',
        'restrictions',
        'permissions',
        'last_used_at',
        'expires_at',
        'refreshed_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}

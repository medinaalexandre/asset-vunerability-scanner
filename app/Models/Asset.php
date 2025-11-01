<?php

namespace App\Models;

use Database\Factories\AssetFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $device_type
 * @property string $location
 * @property string $status
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static AssetFactory factory($count = null, $state = [])
 * @method static Builder<static>|Asset newModelQuery()
 * @method static Builder<static>|Asset newQuery()
 * @method static Builder<static>|Asset query()
 * @method static Builder<static>|Asset whereCreatedAt($value)
 * @method static Builder<static>|Asset whereDescription($value)
 * @method static Builder<static>|Asset whereDeviceType($value)
 * @method static Builder<static>|Asset whereId($value)
 * @method static Builder<static>|Asset whereLocation($value)
 * @method static Builder<static>|Asset whereName($value)
 * @method static Builder<static>|Asset whereStatus($value)
 * @method static Builder<static>|Asset whereUpdatedAt($value)
 * @method static Builder<static>|Asset whereUserId($value)
 */
class Asset extends Model
{
    /** @use HasFactory<AssetFactory> */
    use HasFactory;
    use SoftDeletes;

     protected $fillable = [
         'name',
         'description',
         'device_type',
         'location',
         'status',
         'user_id',
    ];

     public function vulnerabilities(): BelongsToMany
     {
         return $this->belongsToMany(Vulnerability::class);
     }
}

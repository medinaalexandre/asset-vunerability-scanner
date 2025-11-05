<?php

namespace App\Models;

use App\Enums\AssetCriticalityLevelEnum;
use Database\Factories\AssetFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property AssetCriticalityLevelEnum $criticality_level
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Vulnerability> $vulnerabilities
 * @property-read int|null $vulnerabilities_count
 * @method static AssetFactory factory($count = null, $state = [])
 * @method static Builder<static>|Asset newModelQuery()
 * @method static Builder<static>|Asset newQuery()
 * @method static Builder<static>|Asset onlyTrashed()
 * @method static Builder<static>|Asset query()
 * @method static Builder<static>|Asset whereCreatedAt($value)
 * @method static Builder<static>|Asset whereCriticalityLevel($value)
 * @method static Builder<static>|Asset whereDeletedAt($value)
 * @method static Builder<static>|Asset whereDescription($value)
 * @method static Builder<static>|Asset whereDeviceType($value)
 * @method static Builder<static>|Asset whereId($value)
 * @method static Builder<static>|Asset whereLocation($value)
 * @method static Builder<static>|Asset whereName($value)
 * @method static Builder<static>|Asset whereStatus($value)
 * @method static Builder<static>|Asset whereUpdatedAt($value)
 * @method static Builder<static>|Asset whereUserId($value)
 * @method static Builder<static>|Asset withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Asset withoutTrashed()
 */
class Asset extends Model
{
    /** @use HasFactory<AssetFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'criticality_level' => AssetCriticalityLevelEnum::class,
    ];

    protected $fillable = [
        'name',
        'description',
        'device_type',
        'location',
        'status',
        'user_id',
        'criticality_level',
    ];

     public function vulnerabilities(): BelongsToMany
     {
         return $this->belongsToMany(Vulnerability::class);
     }
}

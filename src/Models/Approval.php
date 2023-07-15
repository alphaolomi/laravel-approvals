<?php

namespace Alphaolomi\LaravelApprovals\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

/**
 * Class Approval
 *
 *
 * @property string $approval_id
 * @property string $approvable_type
 * @property string $approvable_id
 * @property string $type
 * @property string $comment
 * @property string $prev_approval_id
 * @property string $approved_by
 * @property string $approved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $approvable
 * @property-read User $approvedBy
 * @property-read Approval $prevApproval
 * @property-read Collection|Approval[] $subSequentApprovals
 */
class Approval extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approvals';

    protected $guarded = [];

    protected static function booted()
    {
        // when creating
        static::creating(function ($model) {
            // generate approval_id
            $model->approval_id = Str::ulid();
        });
    }

    /**
     * Get the owning approvable model.
     */
    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that approved the model.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the previous approval.
     */
    public function prevApproval(): BelongsTo
    {
        return $this->belongsTo(Approval::class, 'prev_approval_id');
    }

    /**
     * Get the subsequent approvals.
     */
    public function subSequentApprovals(): HasMany
    {
        return $this->hasMany(Approval::class, 'prev_approval_id')->orderBy('created_at', 'asc');
    }
}

<?php

declare(strict_types=1);

namespace Kanvas\Social\Follows\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kanvas\Social\Models\BaseModel;
use Kanvas\Users\Models\Users;

/**
 *  class UsersFollows
 *  @property int $id
 *  @property int $users_id
 *  @property int $entity_id
 *  @property int $companies_id
 *  @property int $companies_branches_id
 *  @property int $entity_namespace
*/
class UsersFollows extends BaseModel
{
    protected $guarded = [];
    protected $table = 'users_follows';

    /**
     * user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'users_id', 'id');
    }

    /**
     * toArray
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['entity'] = $this->entity;

        return $array;
    }

    /**
     * entity
     */
    public function getEntityAttribute(): mixed
    {
        return $this->entity_namespace::find($this->entity_id);
    }
}

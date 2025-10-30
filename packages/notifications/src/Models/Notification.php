<?php

namespace WebShop\Notifications\Models;

/**
 * @property string $uuid
 * @property int $type
 * @property int $action_id
 * @property array $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification first($columns = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Notification extends \Eloquent
{
    public const string TABLE_NAME = 'notification';
    protected $table = self::TABLE_NAME;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['uuid'];
    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'type' => 'int',
            'action_id' => 'int',
            'payload' => 'json',
        ];
    }
}

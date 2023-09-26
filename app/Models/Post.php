<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * @property Uuid $uuid
 * @property string $title
 * @property string $content
 * @property Uuid $author_uuid
 * @property User $author
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Post extends Model
{
    use HasUuids;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author_uuid',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_uuid');
    }
}

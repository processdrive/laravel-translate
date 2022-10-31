<?php

namespace ProcessDrive\LaravelCloudTranslation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translations extends Model
{
	use SoftDeletes;

    public $translatable = ['text'];

    public $guarded = ['id'];

    public $table = 'language_lines';

    public $fillable = [
        'group',
        'key',
        'text',
        'translated'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                    => 'integer',
        'group'                 => 'string',
        'key'                   => 'string',
        'text'                  => 'array',
        'translated'            => 'integer'
    ];

}

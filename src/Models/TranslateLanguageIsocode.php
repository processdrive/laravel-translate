<?php

namespace ProcessDrive\LaravelCloudTranslation\Models;

use Illuminate\Database\Eloquent\Model;

class TranslateLanguageIsocode extends Model
{

    public $guarded = ['id'];

    public $table = 'translate_language_isocode';

    public $fillable = [
        'iso_code',
        'name',
        'used',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'iso_code'              => 'string',
        'name'                  => 'string',
        'used'                  => 'integer',
    ];
}

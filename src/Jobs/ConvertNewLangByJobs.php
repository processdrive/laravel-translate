<?php

namespace ProcessDrive\LaravelCloudTranslation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProcessDrive\LaravelCloudTranslation\CloudTranslate;
use ProcessDrive\LaravelCloudTranslation\Models\Translations;
use DB;
use Cache;

class ConvertNewLangByJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    public $tries = 3;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request_data = $this->data;
        $data =  Translations::all()->toArray();
        foreach ($data as $value) {
            $value['text'][$request_data['to_lang']] = CloudTranslate::translate($value['text'][$request_data['from_lang']], $request_data['from_lang'], $request_data['to_lang']);
            Translations::whereId($value['id'])->update(['text' => $value['text']]);
        }
        Cache::flush();
        DB::table('translate_language_isocode')->where('iso_code',$request_data['to_lang'])->where('used','=',0)->update(['used' => 1]);
    }
}

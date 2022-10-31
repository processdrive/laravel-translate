<?php

namespace ProcessDrive\LaravelCloudTranslation;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ProcessDrive\LaravelCloudTranslation\Models\Translations;
use DB;
use Cache;

class TranslationStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trans:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'My trans db command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $trans = $this->generateTrans();
        return;
    }
    
    /**
     * Read the lang folder value and stored in database
     */

    public function generateTrans()
    {
        $scan = scandir(resource_path('lang'));
        foreach($scan as $key => $folder) {
            if (is_dir(resource_path("lang/$folder")) && $key > 1) {
                DB::table('translate_language_isocode')->where('iso_code',$folder)->where('used','=',0)->update(['used' => 1]);
                $files = collect(File::files(resource_path('lang/'.$folder)));
                $trans = $files->reduce(function($trans, $file) use ($folder) {
                    $filename = pathinfo($file)['filename'];                    
                    $translations = require($file);
                    if (!array_key_exists($folder, $trans)) {
                        $trans[$folder] = [];
                    }
                    foreach($translations as $key =>  $translation) {
                        $data = Translations::where('group', $filename)->where('key',$key)->first();      
                        if(@$data) {
                            $set_data = is_array($data->text) ? $data->text : json_decode($data->text, true);
                            $set_data[$folder] = $translation;
                            Translations::whereId($data->id)->update(['text' => json_encode($set_data)]);
                        }
                        else {
                            Translations::create([
                                'group' => $filename,
                                'key' => $key,
                                'text' => json_encode([$folder => $translation]),
                                'translated' => 1
                            ]);
                        } 
                    }
                    $filelist = resource_path("lang/$folder").'/'.$filename.'.json';    
                    $this->info("Write trans to: $filelist");
                    return $trans;
                }, []);
            }
        }
        Cache::flush();
    }
}
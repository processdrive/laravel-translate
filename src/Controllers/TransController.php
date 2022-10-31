<?php

namespace ProcessDrive\LaravelCloudTranslation\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ProcessDrive\LaravelCloudTranslation\CloudTranslate;
use ProcessDrive\LaravelCloudTranslation\Models\Translations;
use ProcessDrive\LaravelCloudTranslation\Models\TranslateLanguageIsocode;
use DB;
use DataTables;
use ProcessDrive\LaravelCloudTranslation\Jobs\ConvertNewLangByJobs;
use Cache;

class TransController extends Controller
{
   public function index ()
   {
        Cache::flush();
        $data = ['language' => TranslateLanguageIsocode::where('used', 1)->get()->pluck('name', 'iso_code')->toArray(), 'new_lang' => TranslateLanguageIsocode::where('used', 0)->get()->pluck('name', 'iso_code')->toArray()];
        return view('LaravelCloudTranslation::translation')->with($data);
   }

   public function store(Request $request)
   {
        $request_data  = $request->all();
        $locale        = translateLanguageIsocode::where('used', 1)->get()->pluck('iso_code')->toArray();
        $text          = [];
        if ($request_data['text']) {
            foreach ($locale as $lang) {
                $text[$lang] = CloudTranslate::translate($request_data['text'], $request_data['lang'], $lang);
            }
        }
        Cache::flush();
        return Translations::create([
            'group' => $request_data['group'],
            'key' => $request_data['key'],
            'text' => $text,
            'translated' => 1
        ])->id;
   }

   public function update(Request $request)
   {
        $request_data  = $request->all();
        $text          = Translations::whereId($request->get('edit_id'))->first()->text;
        $text          = is_array($text) ? $text : json_decode($text, true);
        $text[$request_data['lang']] = $request_data['text'];
        $data['text']  = json_encode($text);
        $data['group'] = $request_data['group'];
        $data['key']   = $request_data['key'];
        Cache::flush();
        return Translations::whereId($request->get('edit_id'))->update($data);
   }

   public function destory(Request $request)
   {
        $model = Translations::find($request->get('id'));
        return $model->delete();
   }

   public function getTranslation(Request $request)
   {
       if ($request->ajax()) {
           Cache::flush();
           $data =  Translations::all();
           $language = $request->get('lang');
           return Datatables::of($data)
               ->addIndexColumn()
               ->addColumn('text', function($row) use ($language){
                    return is_array($row->text) ? $row->text[$language] : json_decode($row->text, true)[$language];
                })
               ->addColumn('action', function($row){
                   $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" data-attr="'.$row->id.'"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                 <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-attr="'.$row->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                 <a href="javascript:void(0)" class="update btn btn-primary btn-sm" data-attr="'.$row->id.'" style="display: none;"><i class="fa fa-save"></i></a>
                                 <a href="javascript:void(0)" class="cancel btn btn-danger btn-sm" style="display: none;"><i class="fa fa-close"></i></a>';
                   return $actionBtn;
               })
               ->rawColumns(['action'])
               ->make(true);
       }
   }

   public function storeNewLanguage(Request $request)
   {
        Cache::flush();
        ConvertNewLangByJobs::dispatch($request->all())->delay(now()->addSeconds(1));
        return true;
   }

}

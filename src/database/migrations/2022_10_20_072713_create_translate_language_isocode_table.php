<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslateLanguageIsocodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translate_language_isocode', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('iso_code');
            $table->string('name');
            $table->integer('used')->nullable()->default(0);
            $table->timestamps();
        });
        $this->seed();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translate_language_isocode');
    }

    public function seed()
    {
        $language = file(__DIR__."/seed/language-codes_csv.csv");
        $lang = array();
        foreach ($language as $key => $value) {
            $temp = str_getcsv($value);
            array_push($lang, array('iso_code' => $temp[0], 'name' => $temp[1]));
        }
        DB::table('translate_language_isocode')->insert($lang);
    }
}

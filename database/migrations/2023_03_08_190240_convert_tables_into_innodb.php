<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertTablesIntoInnodb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* $tables = [
             'users',
             'products',
         ];*/

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {

            DB::statement('ALTER TABLE ' . $table->Tables_in_peru . ' ENGINE = InnoDB');

        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table->Tables_in_peru . ' ENGINE = MyISAM');
        }
    }
}

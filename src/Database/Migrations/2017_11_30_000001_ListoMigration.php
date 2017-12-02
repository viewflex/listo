<?php

use Illuminate\Database\Migrations\Migration;
use Viewflex\Listo\Database\Schema\ListoSchema;

/**
 * Creates the database table for the Viewflex/Listo package.
 */
class ListoMigration extends Migration
{
    protected $listing_tables = [
        'listo_items'               =>  'listo_items'
    ];
    
    /**
     * Run the migrations.
     */
    public function up()
    {
        ListoSchema::create($this->listing_tables);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        ListoSchema::drop($this->listing_tables);
    }
}

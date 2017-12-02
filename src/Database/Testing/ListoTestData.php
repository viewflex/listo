<?php

namespace Viewflex\Listo\Database\Testing;

use Viewflex\Listo\Database\Schema\ListoSchema;

class ListoTestData
{
    /**
     * Create the test database and tables.
     *
     * @param array $tables
     * @return void
     */
    public static function create($tables)
    {
        // Migrate
        ListoSchema::create($tables);

        // Seed:
        ListoTestSeeder::seed($tables);
    }

    /**
     * Drop the test database and tables.
     *
     * @param array $tables
     * @return void
     */
    public static function drop($tables)
    {
        // Drop the tables that we created.
        ListoSchema::drop($tables);
        
    }
    
}

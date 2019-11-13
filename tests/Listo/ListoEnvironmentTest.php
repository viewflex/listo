<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Viewflex\Listo\Database\Testing\ListoTestData;
use Viewflex\Listo\Publish\Demo\Items\Item;

class ListoEnvironmentTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     */
    public function data_source_can_be_created_and_queried()
    {
        ListoTestData::create(['listo_items' => 'listo_items']);
        
        $items = Item::all();
        $this->assertEquals(10, count($items));
    }
    

}

<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Viewflex\Listo\Database\Testing\ListoTestData;
use Viewflex\Listo\Publishers\HasFluentConfiguration;
use Viewflex\Listo\Publishers\HasPublisher;

class ListoUnitTest extends TestCase
{
    use HasFluentConfiguration;
    use HasPublisher;
    use DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();

        $this->createPublisherWithDefaults();
        include('ConfiguresItems.php');
        ListoTestData::create(['listo_items' => 'listo_items']);
    }

    public function test_mapColumn()
    {
        $this->setColumnMap([
            'name'          => 'full_name',
            'dob'           => 'date_of_birth'
        ]);
        $this->assertEquals('full_name', $this->getQuery()->mapColumn('name'));
        $this->assertEquals('name', $this->getQuery()->rmapColumn('full_name'));
    }

    public function test_getQueryInputs()
    {
        $this->setInputs([
            'id'            => '5',
            'active'        => '1',
            'name'          => 'Naot Teva Sandals',
            'category'      => 'Footwear',
            'subcategory'   => 'Unisex ', // trailing space should be trimmed
            'keyword'       => ' ', // empty string should be filtered
            'sort'          => 'name',
            'view'          => 'list',
            'limit'         => '',
            'start'         => '',
            'action'        => '',
            'items'         => '',
            'options'       => '',
            'page'          => '2'
        ]);

        $this->assertEquals(8, count($this->getRequest()->getQueryInputs()));
        $this->assertEquals('Unisex', $this->getRequest()->getQueryInputs()['subcategory']);
        $this->assertFalse(array_key_exists('keyword', $this->getRequest()->getQueryInputs()));
    }
    
}

<?php


namespace Deg\Table;

include(__DIR__.'/../config.php');

/* Unit testing HTMLTable */
class HTMLTableTest extends \PHPUnit_framework_testcase
{
    private $table;
    public function setup(){
        $di = new \Anax\DI\CDIFactoryDefault();
	   $tbl = [
        		[
        			'col-1'	=>	'test11',
        			'col-2' =>	'test12',
        			'col-3' =>	'test13',
//    			'col-4' =>	'test14'
        		],
        		[
        			'col-1' =>	'test21',
        			'col-2' =>	'test22',
        			'col-3'	=>	'test23',
        			'col-4' =>	'test24'
        		],
        		[
        			'col-1' =>	'test31',
        			'col-2' =>	'test32',
        			'col-3'	=>	'test33',
        			'col-4' =>	'test34'
        		],
        	];
        $this->table = new HTMLTable($tbl);
        $this->table->setDI($di);
    }

    /**
    * Test
    * 
    */
    public function testConstructWith1dKeyArray()
    {
        $this->setExpectedException('Deg\Table\TableException');
        $this->table = new HTMLTable(array('t' => 't', 'e' => 'e', 's' => 's', 't' => 't'));
    }

    public function testConstructWith1dArray()
    {
        $this->setExpectedException('Deg\Table\TableException');
        $this->table = new HTMLTable(array('t', 'e', 's', 't'));
    }

    /**
     * Test
     *
     */
    public function testSetClass(){
        $this->table->setClass('table');
        $this->setExpectedException('Deg\Table\TableException');
        $this->table->setClass(3);
    }

    /**
     * Test
     * 
     */
    public function testCombineCols()
    {
        $this->table->combineCols(array('col-3', 'col-4'), 'cols');
        $this->setExpectedException('Deg\Table\TableException');
        $this->table->combineCols(array('col-3', 'col-4'), 'columns');
    }

    /**
     * Test
     * 
     */
    public function testSeparateNonUnifiedHeaders()
    {
        $this->setExpectedException('Deg\Table\TableException');
        $this->table->setSeparateHeaders('name', 'col-1');
    }

    /**
     * Test
     * 
     */
    public function testSeparateHeadersNonColVal()
    {
        $this->table->setUnifiedHeaders('name');
        $this->setExpectedException('Deg\Table\TableException');
        $this->table->setSeparateHeaders('name', 'fakeCol');
    }

    /**
     * Test
     *
     */
    public function testClickableHeadersNullValue()
    {
        $this->setExpectedException('Deg\Table\TableException');

        $this->table->setUnifiedHeaders('name');
        $this->table->setSeparateHeaders('name', 'col-1');
        $this->table->clickableHeaders('test/url', 'col-5');
    }

    /**
     * Test
     *
     */
    public function testClickableHeadersTrueValue()
    {
        $this->table->setUnifiedHeaders('name');
        $this->table->setSeparateHeaders('name', 'col-1');
        $this->assertTrue($this->table->clickableHeaders('test/url', 'col-1'));
    }

    /**
     * Test
     *
     */
    public function testGetHTML()
    {
        $html =$this->table->getHTML();
        $this->assertTrue(is_string($html));
    }

    /**
     * Test
     *
     */
    public function testGetHTMLSeparate()
    {
        $this->table->setUnifiedHeaders('name');
        $this->table->setSeparateHeaders('name', 'col-1');
        $this->assertTrue(is_string($this->table->getHTML()));
    }

    /**
     * Test
     *
     */
    public function testGetHTMLClickable()
    {
        $this->table->clickableHeaders('test/url');
        $this->assertTrue(is_string($this->table->getHTML()));
    }
}
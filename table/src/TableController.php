<?php

namespace Deg\Table;

/**
 * Anax base class for table control.
 *
 */
class TableController
{
    use \Anax\DI\TInjectionaware;

    private $table;

    public function setDI($di)
    {$this->di =$di;}
    /**
     * Index action.
     *
     */
    public function indexAction()
    {

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
    	$this->table =new \Deg\Table\HTMLTable($this->di, $tbl);
        $this->table->setClass('table');
//        $table->setUnifiedHeaders('col-1: ', 'col-1');
//        $table->setSeparateHeaders('col-1: ', 'col-1');
//        $table->clickableHeaders('table', 'col-1');
//        $this->table->combineCols(array('col-3', 'col-4'), 'cols-34');

    	$this->di->theme->setTitle('Table test');
    	$this->di->views->add('table/table', [
            'title'     => "Table testing",
            'subtitle'  => "Basic table",
            'code'      => '<code>$table = new HTMLTable($di, $tableVals[])<br />
                            $table->setClass($class)</code>',
    		'table' =>    $this->table->getHTML(),
    		]
    	);
        $this->test1Action();
        $this->test2Action();
	}

	public function test2Action()
    {
        $this->table->setUnifiedHeaders('Testheader');
        $this->table->setSeparateHeaders('col-1: ', 'col-1');
        $this->table->clickableHeaders('table', 'col-1');

        $this->di->views->add('table/table', [
            'subtitle'  => 'Header manip',
            'code'      =>  '<code>$table->setUnifiedheaders($headerText)<br />
                            $table->setSeparateHeaders($headerText, $value)<br />
                            $table->clickableHeaders($url, $value)</code>',
            'table'     => $this->table->getHTML(),
            ]);
    }

    public function test1Action()
    {
        $this->table->combineCols(array('col-3', 'col-4'), 'cols-34');

        $this->di->views->add('table/table', [
            'subtitle'  =>  'Combining columns',
            'code'      =>  '<code>$table->combineCols($columns[], $newHeader])</code>',
            'table'     =>  $this->table->getHTML(),
            ]);
    }
}
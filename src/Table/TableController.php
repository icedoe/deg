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
    	$this->table =new \Deg\Table\HTMLTable($tbl);
        $this->table->setDI($this->di);
        $this->table->setClass('table');
//        $table->setUnifiedHeaders('col-1: ', 'col-1');
//        $table->setSeparateHeaders('col-1: ', 'col-1');
//        $table->clickableHeaders('table', 'col-1');
//        $this->table->combineCols(array('col-3', 'col-4'), 'cols-34');

    	$this->di->theme->setTitle('Table test');
    	$this->di->views->add('table/table', [
            'title'     => "Table testing",
            'subtitle'  => "Basic table",
            'code'      => '<p>Create new table:<br/>
                            <code>$table = new HTMLTable(DI $di, array $tableVals[])</code></p>
                            <p>Set class for style:<br/>
                            <code>$table->setClass(string $class)</code></p>',
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
            'code'      => '<p>Set one header for all columns:<br/>
                            <code>$table->setUnifiedheaders(string $headerText)</code></p>
                            <p>One header per row:</br>
                            <code>$table->setSeparateHeaders(string $headerText, string $value)</code></p>
                            <p>Make headers clickable:</br>
                            <code>$table->clickableHeaders(string $url, string $value)</code></p>',
            'table'     => $this->table->getHTML(),
            ]);
    }

    public function test1Action()
    {
        $this->table->combineCols(array('col-3', 'col-4'), 'cols-34');

        $this->di->views->add('table/table', [
            'subtitle'  =>  'Combining columns',
            'code'      => '<p>Combined column values will be wrapped i p-tags:</br>
                            <code>$table->combineCols(string[] $columns, string $newHeader])</code></p>',
            'table'     =>  $this->table->getHTML(),
            ]);
    }
}
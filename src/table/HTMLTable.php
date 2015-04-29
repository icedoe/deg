<?php
namespace Deg\Table;

/*****************************************************
 ** Class for printing and manipulating HTML-tables **
 **													**
 ** Author: Martin Degerman							**
 *****************************************************/


class HTMLtable
{
	use \Anax\DI\TInjectionAware;

	private $headers;
	private $tableVals;
	private $colCount;
	private $unifiedHeaders;
	private $separateHeaders;
	private $nonDisrupt;
	private $colspan;
	private $errMsg;
	private $class;


/*****************************************************
 ** Function __construct							**
 **													**
 ** @params DI-object								**
 ** @params 2D-array or object-array				**
 **													**
 ** @return null 									**
 **													**
 *****************************************************/
	public function __construct($di, $table)
	{
		$table =$this->chkInputArray($table);

		$this->setDI($di);
		$this->table =$table;
	}
/*****************************************************
 ** Function getHTML 								**
 **													**
 ** @return HTML-tables								**
 **													**
 *****************************************************/
	public function getHTML()
	{
		if(isset($this->errMsg[0])){
			$this->unifiedHeaders =false;
			$this->separateHeader =false;
			$this->headers =array('Index', 'Message');
			$this->tableVals =[];
			foreach($this->errMsg as $key => $msg){
				$this->tableVals[$key] = $msg;
			}
		}

		$this->colspan = $this->unifiedHeaders ? "colspan ='".$this->colCount."'" : '';
		$this->class =$this->class ? $this->class : '';
		$each = $this->separateHeaders ? true : false;

		$html ="";

		if(!$each){
			$html .="<table $this->class>";			
			$html .=$this->htmlHeaders($this->headers);
			$html .=$this->htmlRows($this->tableVals);
			$html .="</table>";
		}else{
			foreach($this->headers as $key => $head) {
				$html .="<table $this->class>";
				$html .=$this->htmlHeaders([$head]);
				$html .=$this->htmlRows([$this->tableVals[$key]]);
				$html .="</table>";
			}
		}

		return $html;
	}

/*****************************************************
 ** Function setClass								**
 **													**
 ** @params string class name						**
 *****************************************************/
	public function setClass($class)
	{
		if(!is_string($class)){
			$this->msg("Class must be of type string. Input: ".gettype($class));
			return false;
		}
		$this->class ="class='".$class."'";
	}

/*Helpers for getHTML*/

	private function htmlHeaders($headers)
	{
		$html ="<tr>";
		foreach($headers as $head){
			$html.="<th $this->colspan style='text-align: left'>$head</th>";
		}
		$html .="</tr>";
		return $html;
	}

	private function htmlRows($rows)
	{
		
		$html ='';
		foreach($rows as $row){
			print_r($row);
			$html .="<tr>";
			foreach ($row as $key => $value) {
			//	$val =in_array($key, $this->headers) ? $value : false;
				$html .="<td>".$value."</td>";
			}
			$html .="</tr>";
		}
		return $html;
	}

/*****************************************************
 ** Function combineCols							**
 **													**
 ** @params string array -cols to combine			**
 ** @params string name of combined column			**
 **													**
 *****************************************************/
	public function combineCols($cols, $name)
	{
		if(array($cols)){
			$init=false;
			foreach($cols as $col){
				if(!in_array($col, $this->headers)){
					$this->msg("Trying to combine non-existing col. Input: ".$col);
				}else{
					foreach($this->headers as $key =>$val){
						if($col == $val){
							$tmp =$key;
						}
					}
					if(!$init){
						$this->headers[$tmp] =$name;
						foreach($this->tableVals as $key => $vals){
							$this->tableVals[$key][$name] = isset($vals[$col]) ? "<p>".$vals[$col]."</p>" : '';
							unset($this->tableVals[$key][$col]);
						}

						$init=true;
					}else{
						unset($this->headers[$tmp]);
						foreach($this->tableVals as $key => $vals){
							$this->tableVals[$key][$name] .=isset($vals[$col]) ? "<p>".$vals[$col]."</p>" : '';
							unset($this->tableVals[$key][$col]);
						}
					}
				}
			}
		}
	}

	/*****************************************************
	 ** Function clickableHeaders						**
	 **													**
	 ** @params string contoller url					**
	 ** @params string column to append value			**
	 **													**
	 *****************************************************/
	public function clickableHeaders($url, $column=null)
	{
		if($this->unifiedHeaders){
			if($this->separateHeaders){
				foreach($this->tableVals as $key => $val){
					if(!isset($val[$column])){
						$this->msg("Trying to make null-value clickable. Missing value: $column in tableVals[$key]");
						return false;
					}
					$this->headers[$key] = "<a href='".$this->di->url->create($url.'/'.$val[$column])."'>".$this->headers[$key]."</a>";
				}
				return true;
			}
		}

		foreach($this->headers as $head){
			$column =$column ? $column : $head;
			$head ="<a href='".$this->di->url->create($url.'/'.$column)."'>$head</a>";
		}
	}
/* Single header */
	public function setUnifiedHeaders($name)
	{
		$this->headers =[$name];
		
		$this->unifiedHeaders =true;
	}

/* Header for each row */
	public function setSeparateHeaders($name, $column)
	{
		if(!$this->unifiedHeaders){
			$this->msg("Separate headers must be unified");
			return false;
		}
		$this->headers=[];
		foreach($this->tableVals as $key => $val){
			if(!in_array($column, array_keys($val))){
				$this->msg("Unified separate header in tableVals must be column value. Input: $column");
				return false;
			}
			$this->headers[] =$name.$this->tableVals[$key][$column];

		}
		$this->separateHeaders =true;
	}

/* Print errors to table, rather than die */
	public function setNonDisrupt($val =true)
	{
		$this->nonDisrupt =$val;
	}

	private function chkInputArray($array)
	{
		if(!is_array($array)){
			$this->msg('Input must be array. Input: '.gettype($array).'.');
		}elseif(is_array($array[0])){
			foreach($array as $sub){
				if(!is_array($sub) && !is_object($sub)){
					$this->msg('Valid row-matter is array or object. Input: '.gettype($sub));
				} else {
					$this->setVals($sub);
				}
			}
		}
	}

	private function setVals($array)
	{
		$this->colCount =$this->colCount ? $this->colCount : 0;
		
		$keys =array_keys($array);
		if($this->colCount < count($keys)){
			$sub =array_slice($keys, $this->colCount);
			foreach($sub as $key){
				$this->headers[] =$key;
				$this->colCount++;
			}
		}
		$this->tableVals[] =$array;
	}

/* semi fake error handler */
	private function msg($msg)
	{
		if($this->nonDisrupt){
			if(!$this->errMsg){
				$this->errMsg =[];
			}
			$this->errMsg[] =$msg;
		}else{
			die($msg);
		}
	}
}
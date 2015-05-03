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

//	private $di;
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
 *****************************************************/
	public function __construct($table)
	{
		$table =$this->chkInputArray($table);

		$this->table =$table;
	}
/*****************************************************
 ** Function getHTML 								**
 **													**
 ** @return HTML-table(s)							**
 **													**
 *****************************************************/
	public function getHTML()
	{
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
 ** Function setClass if styling's a thing			**
 **													**
 ** @params string class name						**
 *****************************************************/
	public function setClass($class)
	{
//		try{
			if(!is_string($class)){
				throw new TableException("Class must be of type string. Input: ".gettype($class));
			}
			$this->class ="class='".$class."'";
//		}
//		catch(TableException $e){
//			$this->msg($e);
//		}
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
 ** Function combineCols wraps specified column 	**
 ** values in p-tags								**
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
						throw new TableException("Trying to combine non-existing col. Input: ".$col);
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
	 ** Function clickableHeaders. $column mandatory if **
	 ** headers are separated.							**
	 **													**
	 ** @params string contoller url					**
	 ** @params string column to append value			**
	 **													**
	 ** return mock-bool or void						**
	 *****************************************************/
	public function clickableHeaders($url, $column=null)
	{
	
			if($this->unifiedHeaders){
				if($this->separateHeaders){
					foreach($this->tableVals as $key => $val){
						if(!isset($val[$column])){
							throw new TableException("Trying to make null-value clickable. Missing value: $column in tableVals[$key]");
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
	/*****************************************
	** function setUnifiedHeaders sets a 	**
	** single header for all columns 		**
	**										**
	** @param string name of new header 	**
	******************************************/
	public function setUnifiedHeaders($name)
	{
		$this->headers =[$name];
		
		$this->unifiedHeaders =true;
	}

	/*************************************************************
	**  function setSeparateHeaders sets 						**
	** header to [arbitrary name].[value of specified column]	**
	**															**
	** @param string name of unified header 					**
	** @param string column whose value is to be appended		**
	**															**
	** return bool on fail; else void 					 		**
	*************************************************************/
	public function setSeparateHeaders($name, $column)
	{
	
			if(!$this->unifiedHeaders){
				throw new TableException("Separate headers must be unified");
			}
			$this->headers=[];
			foreach($this->tableVals as $key => $val){
				if(!in_array($column, array_keys($val))){
					throw new TableException("Unified separate header in tableVals must be column value. Input: $column");
				}
				$this->headers[] =$name.$this->tableVals[$key][$column];

			}
			$this->separateHeaders =true;
	
	}



	private function chkInputArray($array)
	{
	
			if(!is_array($array) || !isset($array[0])){
				throw new TableException('Input must be indexed array. Input: '.gettype($array).'.');
			}else{
				foreach($array as $sub){
					if(!is_array($sub) && !is_object($sub)){
						throw new TableException('Valid row-matter is array or object. Input: '.gettype($sub));
					} else {
						$this->setVals($sub);
					}
				}
			}
	
	}
	/*Validate and set input array*/
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
}
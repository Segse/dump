<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * *
 * tested in Browser:								 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Firefox 22.0										 *
 * Google Chrome Version 28.0.1500.72 m				 *
 * Internet Explorer 10 Version: 10.0.9200.16635	 *
 * Safari 5.1.7(7534.57.2)							 *
 * Opera Version 12.16 Build 1860					 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 * handles var dump
 * 
 * @version 0.2 beta
 * 
 * @todo varname test cases
 * @todo lambda test cases
 * @todo closure test cases
 * @todo improve global local var check and test cases
 * @todo add private properties to object dump and test cases
 * @todo add private methods to object dump and test cases
 * @todo add method body to object dump and test cases
 * @todo class abstract, final implement interface, trait, namespace check and test cases
 * @todo pimp resource dump and more and different test cases for resource type
 * @todo test cases for var name
 * @todo varname composed of variables check and display correct and theirs values
 * @todo dump process optimize
 * @todo xml dump and test cases
 * @todo static class test cases and props
 * @todo multiple wert zu weisung ermoeglchen auch bis zu local
 *
 * @author Mirko Krotzek <mirko.krotzek@googlemail.com>
 * $package: debug $
 * $subpackage: dump $
 * $method: class $
 */
class Dump
{

	/**
	 * hold name of dump function
	 *
	 * @var String $funcName
	 */
	private $funcName = 'dump';

	/**
	 * if true html entities will be encoded
	 *
	 * @var boolean $encodeHtmlEntities
	 */
	private $encodeHtmlEntities = TRUE;

	/**
	 * true, if minimum one string was found in value to dump
	 *
	 * @var boolean $strWasEncoded;
	 */
	private $strWasEncoded = FALSE;

	/**
	 * true, if input is only a value and no variable
	 *
	 * @var boolean $onlyValNoVar;
	 */
	private $onlyValNoVar = FALSE;

	/**
	 * default indention interval step
	 * 
	 * @var integer $indent
	 */
	//	private $indent = 4;

	/**
	 * hold html format for output
	 *
	 * @var Array $html
	 */
	private $html = array(
		'varName' => array(
			'type' => array(
				'classConst' => 'Class CONSTANT',
				'staticProp' => 'static property',
				'prop' => 'property',
				'local' => '(global/local) variable',
				'globalConst' => 'global CONSTANT',
			),
		),
		'val' => array(
			'null' => array(
				'type' => 'NULL',
				'color' => '#3465a4',
				'val' => array(
					'null' => 'NULL'
				),
			),
			'bool' => array(
				'type' => 'boolean',
				'color' => '#75507b',
				'val' => array(
					'true' => 'TRUE',
					'false' => 'FALSE',
				),
			),
			'int' => array(
				'type' => 'integer',
				'color' => '#4e9a06',
			),
			'float' => array(
				'type' => 'float',
				'color' => '#f57900',
			),
			'str' => array(
				'type' => 'String',
				'color' => '#cc0000',
			),
			'arr' => array(
				'type' => 'Array',
				'key' => array(
					'empty' => '',
					'index' => 'indexed',
					'assoc' => 'associative',
				),
				'val' => array(
					'empty' => 'empty',
				),
			),
			'obj' => array(
				'type' => 'Object',
			),
			'res' => array(
				'type' => 'Resource',
			),
		),
	);

	/**
	 * class constructor
	 * 
	 * @param NULL
	 * @return NULL
	 */
	public function __construct()
	{
		
	}

	/**
	 * set function name
	 * 
	 * @param String $funcName
	 * @return boolean TRUE on success | FALSE on failure
	 */
	public function setFuncName($funcName)
	{
		if(($this->funcName = $funcName) === $this->funcName){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	/**
	 * set encodeHtmlEntities
	 * 
	 * @param boolean $encodeHtmlEntities
	 * @return boolean TRUE on success | FALSE on failure
	 */
	public function setEncodeHtmlEntities($encodeHtmlEntities)
	{
		if(($this->encodeHtmlEntities = $encodeHtmlEntities) === $this->encodeHtmlEntities){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	/**
	 * dump variable controller	
	 *
	 * @param mixed $var
	 * @return boolean TRUE on success | FALSE on failure
	 */
	public function varDump($var)
	{
		$this->onlyValNoVar = FALSE;
		$this->strWasEncoded = FALSE;

		if(($varName = $this->getVarName()) !== FALSE){
			if(!isset($varName['varName'])){
				$this->onlyValNoVar = TRUE;
			}
			if(($val = $this->analyseVal($var)) !== FALSE){
				if($val['type'] === 'str'){
					if($this->encodeHtmlEntities){
						$var = $this->encodeHtmlEntitiesIn($var);
						$this->strWasEncoded = TRUE;
					}
				}
				if($this->output($this->buildOutput($this->buildDump($varName, $val, $var)))){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
			else{
				return FALSE;
			}
		}
		else{
			echo 'cant find varname';
			return FALSE;
		}
	}

	private function getInnerArrDump($var)
	{
		if(($val = $this->analyseVal($var)) !== FALSE){
			if($val['type'] === 'str'){
				if($this->encodeHtmlEntities){
					$var = $this->encodeHtmlEntitiesIn($var);
					$this->strWasEncoded = TRUE;
				}
			}
			if($val['type'] === 'arr' OR $val['type'] === 'obj'){
				return $this->buildCompositeDataTypeDump($val, $var);
			}
			else{
				return $this->buildPrimitiveDataTypeDump($val, $var);
			}
		}
		else{
			return FALSE;
		}
	}

	/**
	 * return array hold variable name and type
	 * 
	 * @param NULL
	 * @return mixed Array on success | FALSE on failure
	 */
	private function getVarName()
	{
		if(($trace = $this->getCallerTrace()) !== FALSE){
			if(($varName = $this->analyseVarName($trace)) !== FALSE){
				return $varName;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}

	/**
	 * get caller trace
	 * 
	 * @param NULL
	 * @return mixed Array on success | FALSE on failure
	 */
	private function getCallerTrace()
	{
		$callerTraceFound = FALSE;
		foreach($trace = debug_backtrace() as $key => $value){
			if(preg_match('/' . $this->funcName . '\(/', $value['args'][0])){
				$callerTraceFound = TRUE;
				$callerTrace = $value;
				break;
			}
		}

		if($callerTraceFound){
			return $callerTrace;
		}
		else{
			return FALSE;
		}
	}

	/**
	 * get variable name type
	 * 
	 * @param Array $trace
	 * @return mixed Array on success | FALSE on failure
	 */
	private function analyseVarName($trace)
	{

		$delim = '/';
		$varNameSymbol = '[a-zA-Z0-9_]';

		$varType = array(
			'classConst' => array(
				'regExp' => $delim . '(?<=' . $this->funcName . '\()(?:' . '\$?' . $varNameSymbol . '+::' . $varNameSymbol . '+' . ')' . $delim,
				'type' => 'classConst',
			),
			'staticProp' => array(
				'regExp' => $delim . '(?<=' . $this->funcName . '\()(?:' . '\$?' . $varNameSymbol . '+::\$' . $varNameSymbol . '+' . ')' . $delim,
				'type' => 'staticProp',
			),
			'prop' => array(
				'regExp' => $delim . '(?<=' . $this->funcName . '\()(?:' . '\$' . $varNameSymbol . '+->\$?' . $varNameSymbol . '+' . ')' . $delim,
				'type' => 'prop',
			),
			'local' => array(
				'regExp' => $delim . '(?<=' . $this->funcName . '\()(?:' . '\$' . $varNameSymbol . '+' . ')' . $delim,
				'type' => 'local',
			),
			'globalConst' => array(
				'regExp' => $delim . '(?<=' . $this->funcName . '\()(?:' . '(?<=\()' . $varNameSymbol . '+' . ')' . $delim,
				'type' => 'globalConst',
			),
			'val' => array(
				'regExp' => $delim . '(?<=' . $this->funcName . '\()(?:' . '.*' . ')' . $delim,
				'type' => 'val',
			),
		);

		$subject = file($trace['file'])[$trace['line'] - 1];
		if(preg_match($varType['classConst']['regExp'], $subject, $match)){
			return array(
				'type' => $varType['classConst']['type'],
				'varName' => $match[0]
			);
		}
		elseif(preg_match($varType['staticProp']['regExp'], $subject, $match)){
			return array(
				'type' => $varType['staticProp']['type'],
				'varName' => $match[0]
			);
		}
		elseif(preg_match($varType['prop']['regExp'], $subject, $match)){
			return array(
				'type' => $varType['prop']['type'],
				'varName' => $match[0]
			);
		}
		elseif(preg_match($varType['globalConst']['regExp'], $subject, $match)){
			if(array_key_exists($match[0], (array)get_defined_constants(TRUE)['user'])){
				return array(
					'type' => $varType['globalConst']['type'],
					'varName' => $match[0]
				);
			}
			else{
				/*
				 * float is only represented by numbers
				 */
				return array(
					'type' => $varType['val']['type'],
				);
			}
		}
		else if(preg_match($varType['local']['regExp'], $subject, $match)){
			return array(
				'type' => $varType['local']['type'],
				'varName' => $match[0]
			);
		}
		else if(preg_match($varType['val']['regExp'], $subject, $match)){
			/*
			 * only for string value without variable
			 */
			return array(
				'type' => $varType['val']['type'],
			);
		}
		else{
			return FALSE;
		}
	}

	/**
	 * analyse value type and content
	 * 
	 * @param mixed $val variable value
	 * @return mixed Array on success | FALSE on failure
	 */
	private function analyseVal($val)
	{
		if(is_null($val)){
			return array(
				'type' => 'null',
				'val' => 'null',
			);
		}
		elseif(is_bool($val)){
			return array(
				'type' => 'bool',
				'val' => ($val ? 'true' : 'false'),
			);
		}
		elseif(is_int($val)){
			return array(
				'type' => 'int',
			);
		}
		elseif(is_float($val)){
			return array(
				'type' => 'float',
			);
		}
		elseif(is_string($val)){
			return array(
				'type' => 'str',
			);
		}
		elseif(is_array($val)){
			return array(
				'type' => 'arr',
				'key' => (empty($val) ? 'empty' : ($this->isArrAssoc($val) ? 'assoc' : 'index')),
			);
		}
		elseif(is_object($val)){
			return array(
				'type' => 'obj',
			);
		}
		elseif(is_resource($val)){
			return array(
				'type' => 'res',
			);
		}
		else{
			return FALSE;
		}
	}

	/**
	 * check if array is associative
	 * 
	 * @param Array $arr
	 * @return boolean TRUE on success | FALSE on failure
	 */
	private function isArrAssoc($arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * encode html entity
	 * 
	 * @param String $String
	 * @return String
	 */
	private function encodeHtmlEntitiesIn($String)
	{
		return htmlentities($String);
	}

	/**
	 * build html dump string
	 * 
	 * @param Array $varName
	 * @param Array $val
	 * @param mixed $varVal
	 * @return String
	 */
	private function buildDump($varName, $val, $varVal)
	{
		$dump = $this->createTag('div');
		$dump = $this->addAttr($dump, array(
			'class' => 'dump'));
		$pre = $this->createTag('pre');

		$divVarName = $this->createTag('div');
		$divVarName = $this->addAttr($divVarName, array(
			'class' => array(
				'varName',
				' floatLeft'
		)));
		if(isset($varName['varName'])){
			$varType = $this->createTag('small');
			$varType = $this->appendContent($varType, $this->html['varName']['type'][$varName['type']]);

			$varNameStr = $this->createTag('span');
			$varNameStr = $this->appendContent($varNameStr, $varName['varName']);

			$divVarName = $this->appendContent($divVarName, $varType . '&nbsp;' . $varNameStr);
		}
		$divVarName = $this->appendContent($divVarName, '&nbsp;=&nbsp;');

		$divVal = $this->createTag('div');
		$divVal = $this->addAttr($divVal, array(
			'class' => array(
				'val',
				'floatLeft'
		)));
		if($val['type'] === 'arr' || $val['type'] === 'obj' || $val['type'] === 'res'){
			$table = $this->buildCompositeDataTypeDump($val, $varVal);
			$divVal = $this->appendContent($divVal, $table);
		}
		else{
			$divVal = $this->appendContent($divVal, $this->buildPrimitiveDataTypeDump($val, $varVal));
		}

		$clearfix = $this->createTag('div');
		$clearfix = $this->addAttr($clearfix, array(
			'class' => 'clearfix'));

		$pre = $this->appendContent($pre, $divVarName . $divVal . ';' . $clearfix);
		return $this->appendContent($dump, $pre);
	}

	/**
	 * build value dump for primitive data types
	 * 
	 * @param Array $val
	 * @param mixed $varVal
	 * @return String
	 */
	private function buildPrimitiveDataTypeDump($val, $varVal)
	{
		$valType = $this->createTag('small');
		$valType = $this->appendContent($valType, $this->html['val'][$val['type']]['type']);

		$valStr = $this->createTag('span');
		$valStr = $this->addAttr($valStr, array(
			'class' => $val['type']));
		if(isset($this->html['val'][$val['type']]['val'])){
			$valStr = $this->appendContent($valStr, $this->html['val'][$val['type']]['val'][$val['val']]);
		}
		else{
			$valStr = $this->appendContent($valStr, $varVal);
		}

		$str = $valType . '&nbsp;{' . $valStr . '}';

		if($val['type'] === 'str'){
			$strLen = $this->createTag('i');
			$strLen = $this->appendContent($strLen, '(length = ' . strlen($varVal) . ')');
			$str.= '&nbsp;' . $strLen;
		}

		return $str;
	}

	/**
	 * build value dump for array
	 * 
	 * @param Array $val
	 * @param mixed $varVal
	 * @return String
	 */
	private function buildCompositeDataTypeDump($val, $varVal)
	{
		$table = $this->createTag('table');
		$table = $this->addAttr($table, array(
			//			'border' => '1',
			'cellspacing' => '2',
			'cellpadding' => '3',
			'class' => ($val['type'] === 'arr' ? 'arr' : 'obj')
		));
		$thead = $this->createTag('thead');
		$thead = $this->addAttr($thead, array(
			'class' => ($val['type'] === 'arr' ? 'arrHead' : 'objHead')));
		$tr = $this->createTag('tr');
		$th = $this->createTag('th');
		$colspan = 2;
		$th = $this->addAttr($th, array(
			'colspan' => $colspan));

		if($val['type'] === 'arr'){
			$arrType = $this->createTag('small');
			$arrType = $this->appendContent($arrType, $this->html['val'][$val['type']]['key'][$val['key']]);

			$valType = $this->createTag('b');
			$valType = $this->appendContent($valType, $this->html['val'][$val['type']]['type']);

			$arrSize = $this->createTag('i');
			$arrSize = $this->appendContent($arrSize, '(size = ' . count($varVal) . ')');

			$th = $this->appendContent($th, $arrType . '&nbsp;' . $valType . '&nbsp;' . $arrSize);
		}
		elseif($val['type'] === 'obj'){
			$arrType = $this->createTag('b');
			$arrType = $this->appendContent($arrType, $this->html['val'][$val['type']]['type']);
			$span = $this->createTag('span');
			$span = $this->addAttr($span, array(
				'class' => 'objName'));

			$span = $this->appendContent($span, '(' . get_class($varVal));
			if(($parent = class_parents($varVal))){
				foreach($parent as $key => $value){
					$small = $this->createTag('small');
					$small = $this->appendContent($small, 'extends');
					$i = $this->createTag('i');
					$i = $this->appendContent($i, $small . ' ' . $value);
					$span = $this->appendContent($span, ' ' . $i);
				}
			}
			$span = $this->appendContent($span, ')');
			$th = $this->appendContent($th, $arrType . $span);
		}
		$tr = $this->appendContent($tr, $th);
		$thead = $this->appendContent($thead, $tr);

		$tbody = $this->createTag('tbody');
		if($val['type'] === 'arr'){
			if($val['key'] === 'empty'){
				$tr = $this->createTag('tr');
				$td = $this->createTag('td');
				$td = $this->addAttr($td, array(
					'class' => array(
						'arrVal',
						'empty'
				)));
				$td = $this->addAttr($td, array(
					'colspan' => $colspan));
				$i = $this->createTag('i');
				$i = $this->addAttr($i, array(
					'class' => 'empty'));
				$i = $this->appendContent($i, $this->html['val'][$val['type']]['val'][$val['key']]);
				$td = $this->appendContent($td, '{' . $i . '}');
				$tr = $this->appendContent($tr, $td);
				$tbody = $this->appendContent($tbody, $tr);
			}
			else{
				foreach($varVal as $key => $value){
					$tr = $this->createTag('tr');
					$tdKey = $this->createTag('td');
					$tdKey = $this->addAttr($tdKey, array(
						'class' => 'key'));
					$tdKey = $this->appendContent($tdKey, $key);

					$tdVal = $this->createTag('td');
					$tdVal = $this->addAttr($tdVal, array(
						'class' => 'arrVal'));
					$valDump = $this->getInnerArrDump($value);
					$tdVal = $this->appendContent($tdVal, $valDump);

					$tr = $this->appendContent($tr, $tdKey . $tdVal);
					$tbody = $this->appendContent($tbody, $tr);
				}
			}
		}
		elseif($val['type'] === 'obj'){
			foreach(get_object_vars($varVal) as $key => $value){
				$tr = $this->createTag('tr');
				$tdKey = $this->createTag('td');
				$tdKey = $this->addAttr($tdKey, array(
					'class' => 'prop'));
				$i = $this->createTag('i');
				$i = $this->appendContent($i, 'public');
				$tdKey = $this->appendContent($tdKey, $i . ' ' . $key);

				$tdVal = $this->createTag('td');
				$tdVal = $this->addAttr($tdVal, array(
					'class' => 'propVal'));
				$valDump = $this->getInnerArrDump($value);
				$tdVal = $this->appendContent($tdVal, $valDump);

				$tr = $this->appendContent($tr, $tdKey . $tdVal);
				$tbody = $this->appendContent($tbody, $tr);
			}

			foreach(get_class_methods($varVal) as $key => $value){
				$tr = $this->createTag('tr');
				$tdKey = $this->createTag('td');
				$tdKey = $this->addAttr($tdKey, array(
					'class' => 'prop'));
				$i = $this->createTag('i');
				$i = $this->appendContent($i, 'public');
				$tdKey = $this->appendContent($tdKey, $i . ' ' . $value);

				$tdVal = $this->createTag('td');
				$tdVal = $this->addAttr($tdVal, array(
					'class' => 'propVal'));
				$tdVal = $this->appendContent($tdVal, '{<b>method</b>}');
				$tr = $this->appendContent($tr, $tdKey . $tdVal);
				$tbody = $this->appendContent($tbody, $tr);
			}
			//			foreach(get_class_vars(get_class($varVal)) as $key => $value){
			////				echo $html = $key . ' => ' . $value . '<br>';
			//			}
		}
		elseif($val['type'] === 'res'){
			$b = $this->createTag('b');
			$b = $this->appendContent($b, $this->html['val'][$val['type']]['type']);
			return $b . '(' . $varVal . ', ' . get_resource_type($varVal) . ')';
		}

		$table = $this->appendContent($table, $thead . $tbody);
		$table = $this->addAttr($table, array(
			'class' => 'floatLeft'));
		$span = $this->createTag('span');
		$span = $this->addAttr($span, array(
			'class' => 'floatLeft'));
		$span = $this->appendContent($span, '{');
		return $span . $table . '}';
	}

	/**
	 * create html tag
	 * 
	 * @param String $tag
	 * @return String
	 */
	private function createTag($tag)
	{
		return '<' . $tag . '></' . $tag . '>';
	}

	/**
	 * add attribute to html tag
	 * 
	 * @param String $tag
	 * @param Array $attr
	 * @return String
	 */
	private function addAttr($tag, $attr)
	{
		$char = '[a-zA-Z ="0-9_-]+';
		$str = '';
		if(is_array($attr)){
			foreach($attr as $key => $value){
				if(preg_match('§^<' . $char . $key . '="' . $char . '>§', $tag)){
					$tag = preg_replace('§' . $key . '="§', $key . '="' . $value . ' ', $tag, 1);
				}
				else{
					$str.= ' ' . $key . '="';
					if(is_array($value)){
						foreach($value as $k => $v){
							if(is_numeric($k)){
								$str.= $v . ' ';
							}
							else{
								$str.= $k . ': ' . $v . '; ';
							}
						}
					}
					else{
						$str.= $value;
					}
					$str.= '"';
				}
			}
		}
		return preg_replace('§>§', $str . '>', $tag, 1);
	}

	/**
	 * add content to html tag behind existing content
	 * 
	 * @param String $tag
	 * @param String $content
	 * @return String
	 */
	private function appendContent($tag, $content)
	{
		return preg_replace('§</(?=[a-zA-Z]+>$)§', $content . '</', $tag);
	}

	/**
	 * add content to html tag in front of existing content
	 * @param String $tag
	 * @param String $content
	 * @return String
	 */
	private function prependContent($tag, $content)
	{
		preg_match('§^<[a-zA-Z ="0-9_-]+>§', $tag, $match);
		return preg_replace('§^' . $match[0] . '§', $match[0] . $content, $tag);
	}

	/**
	 * build html output string
	 * 
	 * @param String $dump
	 * @return String
	 */
	private function buildOutput($dump)
	{
		$section = $this->createTag('section');
		$info = $this->createTag('div');
		$info = $this->addAttr($info, array(
			'class' => 'info'));
		$pre = $this->createTag('pre');

		if($this->strWasEncoded){
			$strEncoded = $this->createTag('span');
			$strEncoded = $this->appendContent($strEncoded, 'All applicable characters were converted to HTML entities: \'<>\' => \'' . $this->encodeHtmlEntitiesIn('&lt;&gt;') . '\'!');
			$pre = $this->appendContent($pre, $strEncoded);
			$pre = $this->appendContent($pre, '<br>');
		}
		if($this->onlyValNoVar){
			$noVar = $this->createTag('span');
			$noVar = $this->appendContent($noVar, 'Input is only a VALUE and no variable!');
			$pre = $this->appendContent($pre, $noVar);
			$pre = $this->appendContent($pre, '<br>');
		}

		$info = $this->appendContent($info, $pre);
		return $this->appendContent($section, $this->setCSS() . $info . $dump . $this->setJS());
	}

	private function setCSS()
	{
		return <<<CSS
<style>
	.null{
		color: #3465a4;
	}
	.bool{
		color: #75507b;
	}
	.int{
		color: #4e9a06;
	}
	.float{
		color: #f57900;
	}
	.str{
		color: #cc0000;
	}
	.floatLeft{
		float: left;
	}
	.clearfix{
		clear: both;
	}
	.arr{
		background-color: #BCA90F;
	}
	.arrHead{
		background-color: #DCD23F;
		cursor: pointer;
	}
	.key{
		background-color: #fce94f;
		cursor: pointer;
	}
	.arrVal{
		background-color: #FFFF8F;
	}
	.obj{
		background-color: #1A4E80;
	}
	.objHead{
		background-color: #598DBF;
		cursor: pointer;
	}
	.prop{
		background-color: #99CDFF;
		cursor: pointer;
	}
	.propVal{
		background-color: #D9FFFF;
	}
	thead:hover{
		cursor: pointer;
	}	
	thead i{
		font-weight: normal;
	}
	.objName{
		font-weight: normal;
	}
	td.empty{
		text-align: center;
	}
	i.empty{
		color: #888a85;
	}
	.info span{
		background-color: #e9b96e;
	}
</style>
CSS;
	}

	private function setJS()
	{
		return <<<JS
<script type="text/javascript" src="newjavascript.js">

</script>
<script type="text/javascript">
	window.onDomReady = initReady;
	window.onDomReady(onReady);
	function initReady(fn){
		if(document.addEventListener){
			document.addEventListener('DOMContentLoaded', fn, false);
		}
		else{
			document.onreadystatechange = function(){
				readyState(fn)
			}
		}
	}
	function readyState(func){
		if(document.readyState == 'interactive' || document.readyState == 'complete'){
			func();
		}
	}
	function onReady()
	{
		addEventListener();
	}
	var titleCollapse = 'Click to collapse!';
	var titleExpand = 'Click to expand!';
	function addEventListener(){
		for(var index in document.getElementsByTagName('thead')){
			if(isNumeric(index)){
				document.getElementsByTagName('thead')[index].addEventListener('click', toggleTbody);
				document.getElementsByTagName('thead')[index].title = titleCollapse;
			}
		}
		for(var index in document.getElementsByClassName('key')){
			if(isNumeric(index)){
				document.getElementsByClassName('key')[index].addEventListener('click', toggleTd);
				document.getElementsByClassName('key')[index].title = titleCollapse;
			}
		}
		for(var index in document.getElementsByClassName('prop')){
			if(isNumeric(index)){
				document.getElementsByClassName('prop')[index].addEventListener('click', toggleTd);
				document.getElementsByClassName('prop')[index].title = titleCollapse;
			}
		}
	}
	function isNumeric(val){
		if(isNaN(parseFloat(val))){
			return false;
		}
		else{
			return true;
		}
	}
	function toggleTbody(elm){
		elm = elm.target;
		while(elm.tagName !== 'THEAD'){
			elm = elm.parentElement;
		}

		var val = elm.parentElement.children[1];
		if(val.style.display === 'none'){
			val.style.display = 'table-row-group';
			elm.style.fontStyle = 'normal';
			elm.title = titleCollapse;
		}
		else{
			val.style.display = 'none';
			elm.style.fontStyle = 'italic';
			elm.title = titleExpand;
		}
	}
	function toggleTd(elm){
		console.log(elm);
		elm = elm.target;
		while(elm.tagName !== 'TD'){
			elm = elm.parentElement;
		}

		var val = elm.parentElement.lastChild;
		if(val.style.display === 'none'){
			val.style.display = 'table-cell';
			elm.style.fontStyle = 'normal';
			elm.title = titleCollapse;
		}
		else{
			val.style.display = 'none';
			elm.style.fontStyle = 'italic';
			elm.title = titleExpand;
		}
	}	
</script>
JS;
	}

	/**
	 * output dump string
	 *
	 * @param String $htmlOutput
	 * @return boolean TRUE
	 */
	private function output($htmlOutput)
	{
		echo $htmlOutput;
		return TRUE;
	}

}

/**
 * dump variable
 * 
 * @param mixed $var variable to dump
 * @param boolean $encodeHtmlEntities [opitonal] TRUE by default, define, if html entities will be encoded
 * @return boolean TRUE on success | FALSE on failure
 */
function dump($var, $encodeHtmlEntities = TRUE)
{
	$Dump = new Dump();
	$Dump->setFuncName('dump');
	$Dump->setEncodeHtmlEntities($encodeHtmlEntities);
	$re = $Dump->varDump($var);
	unset($Dump);
	return $re;
}

?>
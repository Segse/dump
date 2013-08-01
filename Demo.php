<?php
   



/*
 * test cases for dump.php
 * 
 * @version 0.3 beta
 * @date 2013.07.30
 * 
 * @author Mirko Krotzek <mirko.krotzek@googlemail.com>
 * @package debug
 * @subpackage dump
 * @method
 */

/*
 * apache config, define ini_set
 */
//ini_set('display_errors', 0);
ini_set('display_errors', 1);
ini_set('max_execution_time', 10);
//ini_set('error_reporting', E_ALL);
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING & ~E_STRICT);

/*
 * Dump class
 */
require_once 'Dump.php';

/*
 * test cases
 */
demoNull();
demoBool();
demoInt();
demoFloat();
demoStr();
demoArr();
demoObj();
demoRes();
demoConst();
demoVarName();
demoLambdaFunc();
demoClosure();

/**
 * output seperator
 * 
 * @example seperator('solid');
 * @example seperator('dashed');
 * @example seperator('dotted');
 * @example seperator('br');
 * 
 * @param String $attribute
 * @return NULL
 */
function seperator($attribute)
{
	if($attribute === 'solid'){
		echo '__________________________________________________';
	}
	elseif($attribute === 'dashed'){
		echo '--------------------------------------------------';
	}
	elseif($attribute === 'dotted'){
		echo '..................................................';
	}
	elseif($attribute === 'br'){
		echo '<br>';
	}
	else{
		echo 'Invalid attribute!';
	}
}

/**
 * output type to test
 * 
 * @param String $type
 * @return NULL
 */
function test($type)
{
	seperator('br');
	seperator('solid');
	seperator('br');
	seperator('dashed');
	seperator('br');
	echo 'Test type ' . $type . ':';
	seperator('br');
	seperator('dashed');
}

/**
 * output the code and the var_dump()
 * 
 * @param String $String
 * @return NULL
 */
function demoVarDump($String)
{
	seperator('br');
	seperator('dotted');
	seperator('br');
	seperator('br');
	echo buildDemoDump($String);
	eval($String);
	seperator('dotted');
}

/**
 * output the code and the dump
 * 
 * @param String $String
 * @return NULL
 */
function demoDump($String)
{
	seperator('br');
	seperator('dashed');
	seperator('br');
	seperator('br');
	echo buildDemoDump($String);
	eval($String);
}

/**
 * build demo dump
 * 
 * @param String $String
 * @return String
 */
function buildDemoDump($String)
{
	$pattern = '/(?<!\t)\t/';
	if(preg_match($pattern, $String)){
		$String = preg_replace($pattern, "<br>", $String);
	}
	if(preg_match('/<|>/', $String)){
		$String = htmlentities($String);
	}
	$pattern = '/&lt;br&gt;/';
	if(preg_match($pattern, $String)){
		$String = preg_replace($pattern, '<br>', $String);
	}
	return '<pre>' . $String . '</pre>';
}

/**
 * handle NULL demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoNull()
{
	test('NULL');

	demoVarDump('var_dump(NULL);');

	demoDump('dump(NULL);');
	demoDump('$var;	dump($var);');
	demoDump('$var = 1;	$var = NULL;	dump($var);');
}

/**
 * handle boolean demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoBool()
{
	test('boolean');

	demoVarDump('var_dump(TRUE);');

	demoDump('dump(TRUE);');
	demoDump('dump(FALSE);');
	demoDump('dump($bool = TRUE);');
	demoDump('dump($bool = FALSE);');
}

/**
 * handle integer demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoInt()
{
	test('integer');

	demoVarDump('var_dump(12);');

	demoDump('dump(12);');
	demoDump('dump($int = 123);');
}

/**
 * handle float demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoFloat()
{
	test('float');

	demoVarDump('var_dump(12.34);');

	demoDump('dump(12.34);');
	demoDump('dump($float = 123.456);');
}

/**
 * handle String demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoStr()
{
	test('String');

	demoVarDump('var_dump(\'asdf\');');

	demoDump('dump(\'\');');
	demoDump('dump(\' \');');
	demoDump('dump(\'asdf\');');
	demoDump('dump(\'123\');');
	demoDump('dump(\'asdf123\');');
	demoDump('dump(\'123asdf\');');
	demoDump('dump(\'a1 !\');');
	demoDump('dump(\'\\\'a1 !"\');');
	demoDump('dump(\'\\\'a1 !\\\'\');');
	demoDump('dump($str = \'str\');');
	demoDump('dump(\'<h1>HALLO DUMP!</h1>\', TRUE);');
	demoDump('dump(\'<h1>HALLO DUMP!</h1>\', FALSE);');
}

/**
 * handle Array demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoArr()
{
	test('Array');

	demoVarDump('var_dump(array());');
	demoDump('dump(array());');
	seperator('dashed');

	demoVarDump('var_dump($arr = array(1,2,3));');
	demoDump('dump($arr = array(1,2,3));');
	seperator('dashed');

	demoVarDump('var_dump($arr = array(1,2=>2,3));');
	demoDump('dump($arr = array(1,2=>2,3));');
	seperator('dashed');

	demoVarDump('var_dump($arr = array(1,"2"=>2,3));');
	demoDump('dump($arr = array(1,"2"=>2,3));');
	seperator('dashed');

	demoVarDump('var_dump($arr = array(1,"asdf"=>2,3));');
	demoDump('dump($arr = array(1,"asdf"=>2,3));');
	seperator('dashed');

	demoVarDump('class arrTest{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	$arr = array(		"null" => NULL,		"bool" => TRUE,		"bool" => FALSE,		"int" => 1,		1,		"float" => 2.3,		"str" => "asdf",		0 => array(),		"" => "key is empty string!",		array(			1,			2,			array(				1,				array(					1,					2,					3,					array(						1,						array(							1,							2,							3,						),						2,						3,					),				),				new arrTest(),				2,				3,			),			3,			array(				1,				2,				3,				array(					1,					2,					3,				),			),		),		new arrTest(),		[1, 2, 3, [1, 2, 3],],	);	var_dump($arr);');
	demoDump('class arrTest2{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	$arr = array(		"null" => NULL,		"bool" => TRUE,		"bool" => FALSE,		"int" => 1,		1,		"float" => 2.3,		"str" => "asdf",		0 => array(),		"" => "key is empty string!",		array(			1,			2,			array(				1,				array(					1,					2,					3,					array(						1,						array(							1,							2,							3,						),						2,						3,					),				),				new arrTest2(),				2,				3,			),			3,			array(				1,				2,				3,				array(					1,					2,					3,				),			),		),		new arrTest2(),		[1, 2, 3, [1, 2, 3],],	);	dump($arr);');
}

/**
 * handle Object demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoObj()
{
	test('Object');

	demoVarDump('class testObj{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	var_dump(new testObj());');
	demoDump('class testObj2{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	dump(new testObj2());');
	seperator('dashed');

	demoVarDump('class testObj3{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjB3 extends testObj3{		public $pubProp = 4;		public $pubProp2 = 5;		private $privateProp2 = 6;		public function __construct(){					}		public function setPubProp($var){			$this->pubProp = $var + 1;		}		private function getPubProp(){			return $this->pubProp;		}	}	var_dump(new testObjB3());');
	demoDump('class testObj4{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjB4 extends testObj4{		public $pubProp = 4;		public $pubProp2 = 5;		private $privateProp2 = 6;		public function __construct(){					}		public function setPubProp($var){			$this->pubProp = $var + 1;		}		private function getPubProp(){			return $this->pubProp;		}	}	dump(new testObjB4());');
	seperator('dashed');

	demoVarDump('class testObj5{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjB5 extends testObj5{		public $pubProp = 4;		public $pubProp2 = 5;		private $privateProp2 = 6;		public function __construct(){					}		public function setPubProp($var){			$this->pubProp = $var + 1;		}		private function getPubProp(){			return $this->pubProp;		}	}	$var = new testObjB5();	$var->setPubProp(7);	var_dump($var);');
	demoDump('class testObj6{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjB6 extends testObj6{		public $pubProp = 4;		public $pubProp2 = 5;		private $privateProp2 = 6;		public function __construct(){					}		public function setPubProp($var){			$this->pubProp = $var + 1;		}		private function getPubProp(){			return $this->pubProp;		}	}	$var = new testObjB6();	$var->setPubProp(7);	dump($var);');
	seperator('dashed');

	demoVarDump('class testObj7{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjB7 extends testObj7{		public $pubProp = 4;		public $pubProp2 = 5;		private $privateProp2 = 6;		public function __construct(){					}		public function setPubProp($var){			$this->pubProp = $var + 1;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjC7 extends testObjB7{			}	var_dump(new testObjC7());');
	demoDump('class testObj8{		public $pubProp = 1;		public $pubPropArr = array(1, 2, 3);		private $privateProp = 2;		public function __construct(){			$this->pubProp = 3;		}		public function setPubProp($var){			$this->pubProp = $var;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjB8 extends testObj8{		public $pubProp = 4;		public $pubProp2 = 5;		private $privateProp2 = 6;		public function __construct(){					}		public function setPubProp($var){			$this->pubProp = $var + 1;		}		private function getPubProp(){			return $this->pubProp;		}	}	class testObjC8 extends testObjB8{			}	dump(new testObjC8());');
}

/**
 * handle Resource demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoRes()
{
	test('resource');

	demoVarDump('$db_link = @mysql_connect("localhost", "root", "admin");	if(!is_resource($db_link)){		echo("Can\\"t connect : " . mysql_error());	}	var_dump($db_link);');
	demoDump('$db_link = @mysql_connect("localhost", "root", "admin");	if(!is_resource($db_link)){		echo("Can\\"t connect : " . mysql_error());	}	dump($db_link);');
	seperator('dashed');

	demoVarDump('$filename = "Dump.php";	$handle = fopen($filename, "r");	var_dump($handle);');
	demoDump('$filename = "Dump.php";	$handle = fopen($filename, "r");	dump($handle);');
}

/**
 * handle CONSTANT demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoConst()
{
	test('constant');

	demoVarDump('define("CONST1", 123);	var_dump(CONST1);');
	demoDump('define("CONST2", 12345);	dump(CONST2);');
	seperator('dashed');

	demoVarDump('class constA{		const CONSTANT = 1;	}	class constB extends constA{		const CONSTANT = "constant value";			}	$class = new constB();	var_dump(constB::CONSTANT);');
	demoDump('class constA2{		const CONSTANT = 1;	}	class constB2 extends constA2{		const CONSTANT = "constant value";			}	$class = new constB2();	dump(constB2::CONSTANT);');
	seperator('dashed');

	demoVarDump('class constA3{		const CONSTANT = 1;	}	class constB3 extends constA3{		const CONSTANT = "constant value";			}	$class = new constB3();	var_dump(constA3::CONSTANT);');
	demoDump('class constA4{		const CONSTANT = 1;	}	class constB4 extends constA4{		const CONSTANT = "constant value";			}	$class = new constB4();	dump(constA4::CONSTANT);');
	seperator('dashed');

	demoVarDump('class constA5{		const CONSTANT = 1;	}	class constB5 extends constA5{		const CONSTANT = "constant value";		function showConstant(){			var_dump(self::CONSTANT);		}	}	$class = new constB5();	$class->showConstant();');
	demoDump('class constA6{		const CONSTANT = 1;	}	class constB6 extends constA6{		const CONSTANT = "constant value";		function showConstant(){			dump(self::CONSTANT);		}	}	$class = new constB6();	$class->showConstant();');
	seperator('dashed');

	demoVarDump('class constA7{		const CONSTANT = 1;	}	class constB7 extends constA7{		const CONSTANT = "constant value";		function showConstant(){			var_dump(parent::CONSTANT);		}	}	$class = new constB7();	$class->showConstant();');
	demoDump('class constA8{		const CONSTANT = 1;	}	class constB8 extends constA8{		const CONSTANT = "constant value";		function showConstant(){			dump(parent::CONSTANT);		}	}	$class = new constB8();	$class->showConstant();');
	seperator('dashed');

	demoVarDump('class constA9{		const CONSTANT = 1;	}	class constB9 extends constA9{		const CONSTANT = "constant value";		}	$class = new constB9();	$classname = "constB9"; var_dump($classname::CONSTANT);');
	demoDump('class constA10{		const CONSTANT = 1;	}	class constB10 extends constA10{		const CONSTANT = "constant value";	}	$class = new constB10();	$classname = "constB10";	dump($classname::CONSTANT);');
	seperator('dashed');
}

/**
 * handle variable name demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoVarName()
{
	test('variable name');

	seperator('br');
	seperator('dotted');
	seperator('br');
	seperator('br');
	echo buildDemoDump('DOES NOT EXIST!');
	seperator('dotted');

	demoDump('dump($obj->$var);');
	demoDump('class testVarName{public $asdf = 123;private $asdf2 = 123;}$obj = new testVarName();$var = "asdf";dump($obj->$var);');
	/*
	 * better test case und ausgabe von function return
	 */
	demoDump('function test2($var, $var2 = 1){return $var+1;}$var = "test2";dump($var("2"));');
	demoDump('dump($obj->$var);');


	/*
	 * global local cases
	 */
	demoDump('function test12()	{		$asdf = 1;		dump($asdf);	}	test12();');
	demoDump('function test123()	{		global $asdf;		$asdf = 2;		dump($asdf);	}	test123();');
	demoDump('$GLOBALS["asdf"] = 3;	dump($GLOBALS["asdf"]);');
	demoDump('$GLOBALS["asdf"] = 4;	global $asdf;	dump($asdf);');
	demoDump('$GLOBALS["asdf"] = 5;	function test1234()	{		global $asdf;		dump($asdf);	}	test1234();');
	demoDump('dump($GLOBALS[1]["asdf"][2] = 6);');
	demoDump('dump($arr[1]["asdf"][2] = 7);');
	demoDump('global $arr;	dump($arr[1]["asdf"][2] = 7);');
	demoDump('final class arrTest23	{		public static function func()		{			return TRUE;		}	}	$arr = array(array(0, "arrTest23"), 1, 5, "func");	dump($arr[0][1]::$arr[$arr[2] = 3]());');
	demoDump('final class arrTest24	{		public static $prop = 4;	}	$arr = array(array(0, "arrTest24"), 1, 5, "prop");	dump($arr[0][1]::${$arr[$arr[2] = 3]});');

	$var = 12;
//	$arr[0][1]->$arr[$arr[2]];
	exit;
//
//
//class test{
//
//	public function mu(){
//		global $var;
//		$test = 4;
//		$var = 5;
//		return $GLOBALS['aa'] + asdf + $GLOBALS['var'] + $test + $var;
//	}
//
//}
//
//$o = new test();
//var_dump($o->mu());
//var_dump((array)get_defined_vars());
//
//
//
//
//foreach((array)get_defined_vars()['GLOBALS']as $key => $value){
//	var_dump($key . '=>' . $value);
//	if($key === 'var' AND $value === 5){
//		var_dump($value);
//	}
//}
//
//exit;
	//$GLOBAsLS['asdfa']["asd"][12] = 1;
//dump($GLOBAsLS['asdfa']["asd"][12]);
//var_dump($GLOBALS);
//var_dump($GLOBALS['asdfa']);
//var_dump($GLOBALS['asdfa']["asd"]);
//var_dump($GLOBALS['asdfa']["asd"][12]);
//var_dump(eval('echo $GLOBALS["asdfa"]["asd"][12];'));
//exit;
}

/**
 * handle lambda function demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoLambdaFunc()
{
	test('lambda function');

//	demoVarDump('$db_link = @mysql_connect("localhost", "root", "admin");if(!is_resource($db_link)){echo("Can\\"t connect : " . mysql_error());}var_dump($db_link);');
//	demoVarDump('$filename = "Dump.php";$handle = fopen($filename, "r");var_dump($handle);');
//	var_dump(preg_replace_callback('~-([a-z])~', function ($match){
//				return strtoupper($match[1]);
//			}, 'hello-world'));
// outputs helloWorld
//	$greet = function($name){
//			printf("Hello %s\r\n", $name);
//		};
//
//	$greet('World');
//	$greet('PHP');
//	function asdf($var){
//		return $var * $var;
//	}
//
//	$var = 2;
//	var_dump($re = asdf(function($var){
//			var_dump($var);
//			return $var * $var;
//		}));
//	var_dump($re);
////	exit;
//	var_dump($func);
//	$int = 5;
//	$re = $func($int);
//	var_dump($func($int));
//	var_dump($re);
	demoVarDump('$func = function($var){return $var * $var;};var_dump($func);$int = 5;$re = $func($int);var_dump($func($int));var_dump($re);');

	demoDump('$func = function($var){return $var * $var;};dump($func);$int = 5;$re = $func($int);dump($func($int));dump($re);');

//	demoDump('$db_link = @mysql_connect("localhost", "root", "admin");if(!is_resource($db_link)){echo("Can\\"t connect : " . mysql_error());}dump($db_link);');
//	demoDump('$filename = "Dump.php";$handle = fopen($filename, "r");dump($handle);');
}

/**
 * handle lambda function demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoClosure()
{
	test('closure');

//	demoVarDump('$db_link = @mysql_connect("localhost", "root", "admin");if(!is_resource($db_link)){echo("Can\\"t connect : " . mysql_error());}var_dump($db_link);');
//	demoVarDump('$filename = "Dump.php";$handle = fopen($filename, "r");var_dump($handle);');

	demoVarDump('$func = function($var){return $var * $var;};var_dump($func);$int = 5;$re = $func($int);var_dump($func($int));var_dump($re);');

	demoDump('$func = function($var){return $var * $var;};dump($func);$int = 5;$re = $func($int);dump($func($int));dump($re);');

//	demoDump('$db_link = @mysql_connect("localhost", "root", "admin");if(!is_resource($db_link)){echo("Can\\"t connect : " . mysql_error());}dump($db_link);');
//	demoDump('$filename = "Dump.php";$handle = fopen($filename, "r");dump($handle);');
}

//	demoDump('
//class MyClass
//{
//    const CONSTANT = "constant value";
//
//    function showConstant() {
//        dump(self::CONSTANT);
//    }
//}
//
//dump(MyClass::CONSTANT);
//
//$classname = "MyClass";
//dump($classname::CONSTANT); 
//
//$class = new MyClass();
//$class->showCONSTANT();
//
//dump($class::CONSTANT);');
//	class parentClass
//	{
//
//		public $popsVar = 8989989;
//
//		const popsVar2 = 8989989;
//
//	}
//
//	class MyClass extends parentClass
//	{
//
//		public static $asdf = 1234;
//
//		public $asdf23 = 1333234;
//
//		const CONSTANT = 'constant value';
//
//		function showConstant()
//		{
//
//			dump(self::CONSTANT);
//			dump($this->CONSTANT);
//			dump(self::popsVar2);
//			dump(parent::popsVar2);
//		}
//
//	}
//
//	dump(MyClass::$asdf);
//	dump(MyClass::CONSTANT);
//	$classname = "MyClass";
//	dump($classname::CONSTANT);
//	dump($classname::$asdf);
////
//	$class = new MyClass();
//	$class->showCONSTANT();
//	dump($class->asdf23);
//	$varas = 'asdf';
//	dump($varas);
//	define('ASDF_12sd', 123455);
//	dump(ASDF_12sd);
//	dump(123);
//	dump($class->popsVar);
//	dump(MyClass::popsVar2);
//	dump(parentClass::popsVar2);
//
//	dump($class::CONSTANT);
//	define('ASDF_123_ASDf', 123);
//	$this->foo_foo = "knight";
//	self::$bar_123 = array(1, 2, 3);
//	parent::$bar_1232 = array(1, 2, 3);
//	$baz = 12345;
//	varName2($val);
//	varName($this->foo_foo);
//	varName(self::$bar_123);
//	varName(parent::$bar_1232);
//	varName(ASDF_123_ASDf);
//	varName($baz);
//	
//	
$xml = '

<? xml version = "1.0" encoding = "UTF-8" ?>
<mediamarkt>
	<hardware>1</hardware>
	<tariff>2</tariff>
	<option>3</option>
</mediamarkt>';
//$parser = xml_parser_create();
//var_dump($parser);
//var_dump(xml_parse($parser, $xml));
//dump($parser);
////$obj = simplexml_load_string($xml);
//$obj = simplexml_load_file('../mediamarkt.xml');
////var_dump($obj);
//$doc = new DOMDocument();
//$doc->appendChild(new DOMNode::)
//var_dump($doc);
//$doc->loadXML($xml);
//var_dump($doc);
//var_dump($doc->saveXML());
//xml = (new DOMParser()).parseFromString(xml, "text/xml")
//dump(xml);
//
//$trace = array_pop(debug_backtrace());
//$vLine = file($trace['file']);
//$fLine = $vLine[$trace['line'] - 1];
//var_dump($fLine);
//$delim = '/';
//$staticExp = '\w+::\$\w+';
//$objExp = '\$\w+->\w+';
//$localExp = '\$\w+';
//$constantExp = '(?<=\()\w+(?=,|\))';
//
//preg_match($delim . $staticExp . '|' . $objExp . '|' . $localExp . '|' . $constantExp . $delim, $fLine, $match);
//var_dump($match);
//echo '<br>';
//		define('ASDF_123_ASDf', 123);
//		$this->foo_foo = "knight";
//		self::$bar_123 = array(1, 2, 3);
//		parent::$bar_1232 = array(1, 2, 3);
//		$baz = 12345;
//		varName($var);
//		varName($this->foo_foo);
//		varName(self::$bar_123);
//		varName(parent::$bar_1232);
//		varName(ASDF_123_ASDf);
//		varName($baz);
?>
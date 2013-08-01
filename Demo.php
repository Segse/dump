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

	demoDump('dump(123);');
	demoDump('$asdf = 123;	dump("{$asdf}");');

	demoDump('define("ASDF", 12345);	dump(ASDF);');

	demoDump('$GLOBALS["var"][1][\'wer\'] = 2;	dump($GLOBALS["var"][1]["wer"]);');
	demoDump('$var = 123;	function testGlobal()	{		global $var;		dump($var = 345);	}	testGlobal();');

	demoDump('function localTest()	{		dump($var = 4);	}	localTest();');

	demoDump('function localTest2()	{		return "func";	}	dump(localTest2());');
	demoDump('function localTest3()	{		return "func";	}	$var = array(0, "localTest3");	dump($var[1]());');

	demoDump('$arr = array(0 => 1, array(0, 2 => "test4"));	function test3()	{		return 2;	}	function test4()	{		return 123;	}	dump($arr[$arr[0]][test3()]());');

	demoDump('class testClass	{		public $prop = 3;	}	$obj = new testClass();	dump($obj->prop);');
	demoDump('class testClass2	{		public $prop = 3;	}	$obj = new testClass2();	$var = array(0, "prop");	dump($obj->{$var[1]});');
	demoDump('class testClass3	{		public function method()		{			return 4;		}		public function method2($var1, $var2)		{			return $var1 + $var2;		}	}	$var = "method";	$obj = new testClass3();	dump($obj->method2($obj->{$var}(), 6));');

	demoDump('class testClass5	{		const test = 123;	}	dump(testClass5::test);');
	demoDump('class testClass6	{		const test = 123;	}	$obj = new testClass6();	dump($obj::test);');
	demoDump('class testClass7	{		const _testConst = 6;		public static $prop = 3;		public static $prop2 = array(			0 => 1,			1 => array(				0 => 1,				1 => "test"			)		);		public static function method()		{			dump(self::{self::$prop2[1][1]}());		}		public static function test()		{			return 10;		}	}	testClass7::method();');
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

?>
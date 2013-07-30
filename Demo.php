<?php

/**
 * test cases for dump.php
 * 
 * 
 * 
 * @todo 
 * info overkill bekaempfen durch
 * voreinstellung und pro dump check boxes for hide
 * test cases fuer var name
 * 
 * alle hard codet dump( durch variable ersetzten
 * behandlung fuer classen objecte  
 * parent attr check
 * 
 * varname der aus vars besteht deren val anzeigen
 * 
 * local global var check
 * 
 *
 *  
 * @author Mirko Krotzek <mirko.krotzek@googlemail.com>
 * $package: debug $
 * $subpackage: dump $
 * $method: $
 */
/*
 * define ini set
 */
//ini_set('display_errors', 0);
ini_set('display_errors', 1);

ini_set('max_execution_time', 10);

//; Common Values:
//;   E_ALL & ~E_NOTICE  (Show all errors, except for notices and coding standards warnings.)
//;   E_ALL & ~E_NOTICE | E_STRICT  (Show all errors, except for notices)
//;   E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR  (Show only errors)
//;   E_ALL | E_STRICT  (Show all errors, warnings and notices including coding standards.)
//; Default Value: E_ALL & ~E_NOTICE
//; Development Value: E_ALL | E_STRICT
//; Production Value: E_ALL & ~E_DEPRECATED
//; http://php.net/error-reporting
//error_reporting = E_ALL | E_STRICT
//ini_set('error_reporting', E_ALL);
//ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

/*
 * Dump class
 */
require_once 'Dump.php';

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
function seperator($attribute) {
    if ($attribute === 'solid') {
        echo '__________________________________________________';
    } elseif ($attribute === 'dashed') {
        echo '--------------------------------------------------';
    } elseif ($attribute === 'dotted') {
        echo '..................................................';
    } elseif ($attribute === 'br') {
        echo '<br>';
    } else {
        echo 'Invalid attribute!';
    }
}

/**
 * output type to test
 * 
 * @param String $type
 * @return NULL
 */
function test($type) {
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
function demoVarDump($String) {
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
function demoDump($String) {
    seperator('br');
    seperator('dashed');
    seperator('br');
    seperator('br');
    echo buildDemoDump($String);
    eval($String);
}

/**
 * output the code and the dump
 * 
 * @param String $String
 * @return NULL
 */
function demoClassDump($String) {
    
}

/**
 * build demo dump
 * 
 * @param String $String
 * @return String
 */
function buildDemoDump($String) {
    $pattern = '/; |;/';
    if (preg_match($pattern, $String)) {
        $String = preg_replace($pattern, ';<br>', $String);
    }
    if (preg_match('/<|>/', $String)) {
        $String = htmlentities($String);
    }
    $pattern = '/&lt;br&gt;/';
    if (preg_match($pattern, $String)) {
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
function demoNull() {
    test('NULL');

    demoVarDump('var_dump(NULL);');

    demoDump('dump(NULL);');
    demoDump('$var; dump($var);');
    demoDump('$var = 1; $var = NULL; dump($var);');
}

/**
 * handle boolean demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoBool() {
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
function demoInt() {
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
function demoFloat() {
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
function demoStr() {
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
 * handle CONSTANT demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoConst() {
    test('constant');

    demoVarDump('define(\'CONST1\', 123); var_dump(CONST1);');

    demoDump('dump(TRUE);');
    demoDump('define(\'CONST2\', 12345); dump(CONST2);');

    seperator('br');
    seperator('dashed');
    seperator('br');
    seperator('br');
    echo '<pre>	class parentClass
	{

		const popsVar2 = 8989989;

	}

	class MyClass extends parentClass
	{

		const CONSTANT = \'constant value\';

		function showConstant()
		{
			define(\'ASDF\',123);
			dump(self::CONSTANT);
			dump(self::popsVar2);
			dump(parent::popsVar2);
			dump(ASDF);
		}

	}
	
	$class = new MyClass();
	$class->showCONSTANT();</pre>';

    class parentClass {

        const popsVar2 = 8989989;

    }

    class MyClass extends parentClass {

        const CONSTANT = 'constant value';

        function showConstant() {
            define('ASDF', 123);
            dump(self::CONSTANT);
            dump(self::popsVar2);
            dump(parent::popsVar2);
            dump(ASDF);
        }

    }

    $class = new MyClass();
    $class->showConstant();

    demoDump('dump(MyClass::CONSTANT);');
    demoDump('$classname = "MyClass"; dump($classname::CONSTANT);');
    demoDump('dump(MyClass::popsVar2);');
    demoDump('dump(parentClass::popsVar2);');
    demoDump('dump(ASDF);');
}

/**
 * handle variable name demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoVarName() {
    test('variable name');

    seperator('br');
    seperator('dotted');
    seperator('br');
    seperator('br');
    echo buildDemoDump('DOES NOT EXIST!');
    seperator('dotted');

    class test {

        public $asdf = 123;

    }

    $obj = new test();
    $obj2 = new test();
    $var2 = 'asdf';
    $var = 'asdf';
    demoDump('dump($obj->$var);');
    exit;

    function test2($var, $var2 = 1) {
        return $var;
    }

    test2($var = 'asdf');
    demoDump('dump($obj->$var);');
    test2($var = 'asdf', 1);
    demoDump('dump($obj->$var);');
}

/**
 * handle Array demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoArr() {
    test('Array');

    demoVarDump('var_dump(array());');
    demoDump('dump(array());');

    /*
     * @todo obj in arr
     * color und info overflash on/off per flag
     * global local var check
     * better test cases
     */
    demoDump('dump($arr = array(TRUE, 123, 123.456,	\'asdf\',array(12 => 1,	\'ASdf\' => \'asdf123\',\'bool\' => FALSE,	array(	1,	\'a\',),1,	\'a\',	array(	),),1,\'a\',array(	1,	\'a\',	\'dfas\' => array(),),));');
    demoDump('dump($arr = array());');
    demoDump('dump([1, \'2\', [3, \'4\', [5, \'6\'], 7], 8, 9, [10, 11], 12, 13]);');
    demoDump('dump($arr = [1, \'2\', [3, \'4\', [5, \'6\'], 7], 8, 9, [10, 11], 12, 13]);');
    demoDump('dump($arr = array(14, \'15\', [16.12, \'asd\' => \'17\', array(18, \'19\'), 20], 21, 22, [23, 24], 25, 26));');

    /*
     * @todo keine array key typisirung nur index assoc wegen strunz dummer implement
     */
//$arr = array(
//	1 => 'a',
//	'1' => 'b',
//);
//var_dump($arr);
}

/**
 * handle Object demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoObj() {
    test('Object');

    class testObj {

        public $int = 123;
        public $int2 = 123;
        private $int23 = 123;
        private $int223 = 123;

        public function __construct() {
            
        }

        public function getBlub() {
            return 'blub';
        }

        private function setBlub() {
            return 1;
        }

    }

//	class test2Obj {
    class test2Obj extends testObj {

        public $int = 123;
        public $int2 = 123;
        private $int23 = 123;

        public function __construct() {
            
        }

        public function getBlub() {
            return 'blub';
        }

        public function setBlub() {
            $this->int = 'blub';
        }

        private function setBlub2() {
            return 1;
        }

    }

    demoVarDump('var_dump(new testObj());');
    demoVarDump('$var = new test2Obj(); $var->setBlub(); var_dump($var);');
//	demoVarDump('print_r($var = new testObj());');
//	demoVarDump('var_dump($var2 = new testObj());');
//	demoVarDump('var_dump($var3 = new test2Obj());');
//	demoVarDump('var_dump($var4 = new test2Obj());');
//	demoDump('$var = new test2Obj(); $var->setBlub(); dump($var);');
//	var_dump();
}

/**
 * handle resource demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoRes() {
    test('resource');

    demoVarDump('$db_link = @mysql_connect(\'localhost\', \'root\', \'admin\');if(!is_resource($db_link)){echo(\'Can\\\'t connect : \' . mysql_error());}var_dump($db_link);');
    demoVarDump('$filename = \'Dump.php\';$handle = fopen($filename, \'r\');var_dump($handle);');

    demoDump('$db_link = @mysql_connect(\'localhost\', \'root\', \'admin\');if(!is_resource($db_link)){echo(\'Can\\\'t connect : \' . mysql_error());}dump($db_link);');
    demoDump('$filename = \'Dump.php\';$handle = fopen($filename, \'r\');dump($handle);');
}

/**
 * handle lambda function demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoLambdaFunc() {
    test('resource');

//	demoVarDump('$db_link = @mysql_connect(\'localhost\', \'root\', \'admin\');if(!is_resource($db_link)){echo(\'Can\\\'t connect : \' . mysql_error());}var_dump($db_link);');
//	demoVarDump('$filename = \'Dump.php\';$handle = fopen($filename, \'r\');var_dump($handle);');
    var_dump(preg_replace_callback('~-([a-z])~', function ($match) {
                        return strtoupper($match[1]);
                    }, 'hello-world'));

// outputs helloWorld
//	$greet = function($name){
//			printf("Hello %s\r\n", $name);
//		};
//
//	$greet('World');
//	$greet('PHP');

    function asdf($var) {
        return $var * $var;
    }

    $var = 2;
    var_dump($re = asdf(function($var) {
                var_dump($var);
                return $var * $var;
            }));
    var_dump($re);
    exit;
    var_dump($func);
    $int = 5;
    $re = $func($int);
    var_dump($func($int));
    var_dump($re);
    demoVarDump('$func = function($var){return $var * $var;};var_dump($func);$int = 5;$re = $func($int);var_dump($func($int));var_dump($re);');

    demoDump('$func = function($var){return $var * $var;};dump($func);$int = 5;$re = $func($int);dump($func($int));dump($re);');

//	demoDump('$db_link = @mysql_connect(\'localhost\', \'root\', \'admin\');if(!is_resource($db_link)){echo(\'Can\\\'t connect : \' . mysql_error());}dump($db_link);');
//	demoDump('$filename = \'Dump.php\';$handle = fopen($filename, \'r\');dump($handle);');
}

/**
 * handle lambda function demo dump
 * 
 * @param NULL
 * @return NULL
 */
function demoClosure() {
    test('resource');

//	demoVarDump('$db_link = @mysql_connect(\'localhost\', \'root\', \'admin\');if(!is_resource($db_link)){echo(\'Can\\\'t connect : \' . mysql_error());}var_dump($db_link);');
//	demoVarDump('$filename = \'Dump.php\';$handle = fopen($filename, \'r\');var_dump($handle);');

    demoVarDump('$func = function($var){return $var * $var;};var_dump($func);$int = 5;$re = $func($int);var_dump($func($int));var_dump($re);');

    demoDump('$func = function($var){return $var * $var;};dump($func);$int = 5;$re = $func($int);dump($func($int));dump($re);');

//	demoDump('$db_link = @mysql_connect(\'localhost\', \'root\', \'admin\');if(!is_resource($db_link)){echo(\'Can\\\'t connect : \' . mysql_error());}dump($db_link);');
//	demoDump('$filename = \'Dump.php\';$handle = fopen($filename, \'r\');dump($handle);');
}

################################################################################################
//demoNull();
//demoBool();
//demoInt();
//demoFloat();
//demoStr();
//demoConst();
//demoVarName();
//demoArr();
//demoObj();
//demoRes();
demoLambdaFunc();
demoClosure();
################################################################################################
//	demoDump('
//class MyClass
//{
//    const CONSTANT = \'constant value\';
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
//log('--------------------------------------------------');
//log('Test Object: ');
//
//var obj = {
//};
//dump(obj);
//
//obj = {
//a: 1,
// b: '2',
// c: function() {
//return arr[0] + 3;
//},
// d: {
//e: 4,
// f: 5
//},
// g: 6
//};
//dump(obj);
//
//obj = new Object();
//dump(obj);
//
//obj = new Object(7, '8');
//dump(obj);
//
//obj = new Object(
//h = 9,
// i = '10',
// j = function() {
//return arr[0] + 11;
//}
//);
//dump(obj);
//
//obj = new Object(
//k = 12,
// l = '13',
// m = {
//n: 14,
// o: 15
//},
// p = function() {
//return arr[0] + 16;
//},
// q = new Object(
//r = 17,
// s = '18',
// t = {
//u: 19,
// v: '20',
// w: function() {
//return obj.h + 21;
//},
// x: {
//y: 22,
// z: function() {
//return obj[h] + 23;
//},
// aa: {
//ab: 24
//}
//}
//},
// new Object(25),
// {
//26: 27,
// 28: new Object(29)
//}
//)
//);
//dump(obj);
//
////################################################################################################
//
//log('--------------------------------------------------');
//log('Test new Object: ');
//
//dump(new Boolean());
//
//dump(new Boolean(TRUE));
//
//dump(new Number());
//
//dump(new Number(123));
//
//dump(new Number(123.456));
//
////dump(new Integer());
////dump(new float());
//
//dump(new String());
//
//dump(new String('asdf'));
//
//dump(new Array());
//
//dump(new Array(1, 2, 3));
//
//dump([]);
//
//dump([1, 2, 3]);
//
//dump(new Object());
//
//dump(new Object(1, 2, 'b'));
//
//dump(new Object(a = 1, b = 2, c = function() {
//return 4;
//}));
//
//dump({
//});
//
//function test(tex) {
//return tex + 3;
//}
////test = function(tex) {
////	return tex + 3;
////}
//dump({
//a: 1,
// b: 2,
// c: test(),
// d: function() {
//return 5;
//}
//});
//
//dump({
//a: 1,
// b: 2,
// c: function(tex) {
//return tex + 3;
//},
// d: function() {
//return 5;
//}
//});
//
////################################################################################################
//
//log('--------------------------------------------------');
//log('Test XML: ');
//
//var xml = '<?xml version="1.0" encoding="UTF-8"
//<mediamarkt><hardware>1</hardware></mediamarkt>';
//xml = (new DOMParser()).parseFromString(xml, "text/xml")
//dump(xml);
//
////################################################################################################
//
//log('--------------------------------------------------');
//log('Test function: ');
//
//var func = function(test) {
//return 1;
//};
//dump(func);
//
//dump(func());
//
//dump(func(3));
//
//function foo(test) {
//return 2;
//}
//dump(foo);
//
//dump(foo());
//
//dump(foo(2));
//
//log('--------------------------------------------------');
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
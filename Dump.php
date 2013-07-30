<?php

/**
 * handles var dump
 * 
 * @author Mirko Krotzek <mirko.krotzek@googlemail.com>
 * $package: debug $
 * $subpackage: dump $
 * $method: class $
 */
class Dump {

    /**
     * if true html entities will be encoded
     *
     * @var boolean $encodeHtmlEntities
     */
    private $encodeHtmlEntities = TRUE;

    /**
     * default indention interval step
     * 
     * @var integer $indent
     */
    private $indent = 4;

    /**
     * true, if minimum one string was found in value to dump
     *
     * @var boolean $strInVal;
     */
    private $strInVal;

    /**
     * hold html format for output
     *
     * @var Array $html
     */
    private $html = array(
        'type' => array(
            'open' => '<small>',
            'close' => '</small>',
        ),
        'val' => array(
            'open' => array(
                'begin' => '<font color="',
                'end' => '">',
            ),
            'close' => '</font>',
            'ldelim' => '{',
            'rdelim' => '}',
        ),
        'null' => array(
            'type' => 'NULL',
            'color' => '#3465a4',
        ),
        'bool' => array(
            'type' => 'boolean',
            'color' => '#75507b',
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
            'len' => 'length'
        ),
        'arr' => array(
            'type' => 'Array',
            'color' => '#888a85',
            'size' => 'size',
            'arr' => array(
                'index' => 'indexed',
                'assoc' => 'associative',
            ),
        ),
        'obj' => array(
            'type' => 'Object',
        ),
    );

    /**
     * class constructor
     * 
     * @param NULL
     * @return NULL
     */
    public function __constructor() {
        
    }

    /**
     * dump value of variable
     * 
     * @param mixed $val value to dump
     * @param boolean $encodeHtmlEntities define if html entities will be encoded
     * @return boolean TRUE on success | FALSE on failure
     */
    public function varDump($val, $encodeHtmlEntities = TRUE) {
        $this->encodeHtmlEntities = $encodeHtmlEntities;
        $this->strInVal = FALSE;

        if (($htmlOutput = $this->getDump($val)) !== FALSE) {
            return $this->output($this->getVarName() . ' = ' . $htmlOutput . ';');
        } else {
            return FALSE;
        }
    }

    /**
     * get variable name
     *  
     * @param NULL
     * @return mixed String on success | FALSE on failure
     */
    private function getVarName() {
        $eval = FALSE;
        foreach ($trace = debug_backtrace() as $key => $value) {
            if ($value['function'] === 'dump') {
                if (preg_match('/eval()/', $value['file'])) {
                    $trace = $trace[$key + 2];
                    $eval = TRUE;
                    break;
                }
                $trace = $value;
                break;
            }
        }
        return $this->getVarNameType($trace);
    }

    /**
     * get variable name type
     *  
     * @param Array $trace
     * @return mixed String on success | FALSE on failure
     */
    private function getVarNameType($trace) {
        $delim = '/';
        $varNameSymbol = '[a-zA-Z0-9_]';

        $classConst = '\$?' . $varNameSymbol . '+::' . $varNameSymbol . '+';
        $staticAttr = '\$?' . $varNameSymbol . '+::\$' . $varNameSymbol . '+';

        $attr = '\$' . $varNameSymbol . '+->\$?' . $varNameSymbol . '+';
        $local = '\$' . $varNameSymbol . '+';

        $globalConst = '(?<=\()' . $varNameSymbol . '+';

//		$strVal = '(?<=\()(((?<!\\)".*(?<!\\)"|\".*\")|((?<!\\)\'.*(?<!\\)\'|\\\'.*\\\'))(?=,|\))';
        $strVal = '.*';

        $subject = file($trace['file'])[$trace['line'] - 1];
        if (preg_match($this->buildRegExp($delim, $eval, $classConst), $subject, $match)) {
            return $this->html['type']['open'] . 'class constant ' . $this->html['type']['close'] . $match[0];
        } elseif (preg_match($this->buildRegExp($delim, $eval, $staticAttr), $subject, $match)) {
            return $this->html['type']['open'] . 'static attribute ' . $this->html['type']['close'] . $match[0];
        } elseif (preg_match($this->buildRegExp($delim, $eval, $attr), $subject, $match)) {
//			$delimiter = '->';
//			/*
//			 * @todo multiple verkettete variablen varnamen
//			 */
//			if(count($arrExplode = explode($delimiter, $match[0]))){
//				foreach($arrExplode as $key => $value){
//					for($line = $trace['line'] - 1; $line >= 0; $line--){
//						/*
//						 * @todo
//						 * multiple wert zu weisung ermoeglchen auch bis zu local
//						 */
//						if(preg_match('/(?<=\\' . $value . ' = )[^;,\)]+/', file($trace['file'])[$line], $matchName)){
//							if(preg_match('/new/', $matchName[0])){
//								$matchName[0] .=')';
//							}
//							/*
//							 * @todo start end string delim check "ljkl" 'df' 'asdf" "sdf'
//							 */
//							$arrExplode[$key] = trim($matchName[0], '\'"');
//							break;
//						}
//					}
//				}
//				var_dump(implode($delimiter, $arrExplode));
//				var_dump($trace);
//				$trace = array(
//					'file' => $trace['file'],
//					'line' => $line + 1,
//				);
//				var_dump($trace);
//				if(($htmlOutput = $this->getDump(implode($delimiter, $arrExplode))) !== FALSE){
//					var_dump($htmlOutput);
////					exit;
////					$this->output(implode('->', $arrExplode) . ' = ' . $match[0] . ';');
//					return implode('->', $arrExplode) . ' = ' . $match[0];
//					return $this->output($this->getVarNameType(implode($delimiter, $arrExplode)) . ' = ' . $value . ';');
//				}
//								if(($htmlOutput = $this->getDump($value)) !== FALSE){
//									$this->output($this->getVarName() . ' = ' . $htmlOutput . ';');
//								}
//				}
//			}
            return $this->html['type']['open'] . 'attribute ' . $this->html['type']['close'] . $match[0];
        } elseif (preg_match($this->buildRegExp($delim, $eval, $globalConst), $subject, $match)) {
            if (array_key_exists($match[0], (array) get_defined_constants(TRUE)['user'])) {
                return $this->html['type']['open'] . 'global constant ' . $this->html['type']['close'] . $match[0];
            } else {
                /*
                 * float is only represented by numbers
                 */
                return '<font style="background-color:#e9b96e;">Input is only a VALUE and no variable!</font><br>';
            }
        } else if (preg_match($this->buildRegExp($delim, $eval, $local), $subject, $match)) {
            return $this->html['type']['open'] . '(global/local) variable ' . $this->html['type']['close'] . $match[0];
        } else if (preg_match($this->buildRegExp($delim, $eval, $strVal), $subject, $match)) {
            /*
             * only for string value without variable
             */
            return '<font style="background-color:#e9b96e;">Input is only a VALUE and no variable!</font><br>';
        } else {
            return FALSE;
        }
    }

    /**
     * build regulare expression
     * 
     * @param String $delim delimiter
     * @param boolean $eval true, if dump by eval
     * @param String $regExp regulare Expression
     * @return String regulare Expression
     */
    private function buildRegExp($delim, $eval, $regExp) {
        return $delim . '(?<=dump\()(?:' . $regExp . ')' . $delim;
//		return $delim . '(?<=dump\()(?:' . $regExp . ')(?=,|\))' . $delim;
//		return $delim . ($eval ? '(?<=dump\()' : '') . '(?:' . $regExp . ')' . $delim;
    }

    private function getDump($val, $indent = 0) {
        if (is_null($val)) {
            return $this->buildDump('null', 'NULL');
        } elseif (is_bool($val)) {
            return $this->buildDump('bool', ($val ? 'TRUE' : 'FALSE'));
        } elseif (is_int($val)) {
            return $this->buildDump('int', $val);
        } elseif (is_float($val)) {
            return $this->buildDump('float', $val);
        } elseif (is_string($val)) {
            if ($this->encodeHtmlEntities) {
                if (($encodedVal = $this->encodeHtmlEntitiesIn($val)) !== $val) {
                    $this->strInVal = TRUE;
                    $val = $encodedVal;
                }
            }
            return $this->buildDump('str', $val) . ' <i>(length = ' . strlen($val) . ')</i>';
        } elseif (is_array($val)) {
            $html = $this->html['type']['open'] . ($this->isArrAssoc($val) ? (empty($val) ? '' : $this->html['arr']['arr']['assoc'] . ' ') : $this->html['arr']['arr']['index'] . ' ') . $this->html['type']['close'] . '<b>' . $this->html['arr']['type'] . '</b> <i>(size = ' . count($val) . ')</i>';
            if (empty($val)) {
                return $html .= '<br>' . $this->getIndent($indent + 5) . $this->html['val']['ldelim'] . '<i>' . $this->html['val']['open']['begin'] . $this->html['arr']['color'] . $this->html['val']['open']['end']
                        . 'empty' . $this->html['val']['close'] . '</i>' . $this->html['val']['rdelim'];
            } else {
                $firstLoop = TRUE;
                foreach ($val as $key => $value) {
                    $html .= ($firstLoop ? ' ' . $this->html['val']['ldelim'] . '<br>' . $this->getIndent($indent + $this->indent) : $this->getIndent($indent + $this->indent))
                            . $this->getDump($key) . ' => ' . $this->getDump($value, $this->getArrIndent($indent, $key, $value)) . '<br>';
                    $firstLoop = FALSE;
                }
            }
            return $html . $this->getIndent($indent) . $this->html['val']['rdelim'];
        } elseif (is_object($val)) {
            var_dump($val);
            return;
//			$html = '<b>' . $this->html['obj']['type'] . '</b>(' . get_class($val) . ') ' . $this->html['val']['ldelim'] . '<br>';
//			foreach(get_class_methods($val) as $key => $value){
//				$html.=$key . ' => ' . $value . '<br>';
//			}
////			foreach(get_class_vars(get_class($val)) as $key => $value){
////				$html.=$key . ' => ' . $value . '<br>';
////			}
//			foreach(get_object_vars($val) as $key => $value){
//				$html.=$key . ' => ' . $value . '<br>';
//			}
//			$html.=get_parent_class($val) . '<br>';
////			$html.=get_resource_type($val) . '<br>';
//			return $html;
////			return $this->buildDump('obj', $val);
//			return 123;
        } elseif (is_resource($val)) {
            var_dump($val);
            return;
        } else {
            echo '!!!unknown variable type!!!';
            return FALSE;
        }
    }

    /**
     * check if array is associative
     * 
     * @param Array $arr
     * @return boolean TRUE on success | FALSE on failure
     */
    private function isArrAssoc($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * return number of indentions
     * 
     * @param integer $indent
     * @param Strng $key
     * @param mixed $value
     * @return integer
     */
    private function getArrIndent($indent, $key, $value) {
        $indent+= $this->indent;
        if (is_array($value) AND empty($value)) {
            if (is_int($key)) {
                $indent+=strlen($this->html['int']['type']);
            } else {
                $indent+=strlen($this->html['str']['type']);
            }
            $indent += 2 + strlen($key) + 1;
            if (is_string($key)) {
                $indent += 2 + strlen($this->html['str']['len']) + 3 + strlen(strlen($key)) + 1;
            }
            $indent+= 1;

            /*
             * for optic
             */
            $indent+= - 2;
        }
        return $indent;
    }

    /**
     * return indention
     * 
     * @param integer $indent
     * @return String
     */
    private function getIndent($indent) {
        $String = '';
        for ($i = 0; $i < $indent; $i++) {
            $String .= ' ';
        }
        return $String;
    }

    /**
     * build html dump string
     * 
     * @param String $type
     * @param mixed $val
     * @return String
     */
    private function buildDump($type, $val) {
        return $this->html['type']['open'] . $this->html[$type]['type'] . $this->html['type']['close'] . ' '
                . $this->html['val']['ldelim'] . $this->html['val']['open']['begin'] . $this->html[$type]['color'] . $this->html['val']['open']['end']
                . $val . $this->html['val']['close'] . $this->html['val']['rdelim'];
    }

    /**
     * encode html entity
     * 
     * @param String $String
     * @return String
     */
    private function encodeHtmlEntitiesIn($String) {
        return htmlentities($String);
    }

    /**
     * output dump
     * 
     * @param String $htmlOutput
     * @return boolean TRUE;
     */
    private function output($htmlOutput) {
        if ($this->encodeHtmlEntities === TRUE AND $this->strInVal === TRUE) {
            $htmlOutput = '<font style="background-color:#e9b96e;">All applicable characters were converted to HTML entities! \'<>\' => \'' . $this->encodeHtmlEntitiesIn('&lt;&gt;') . '\'</font><br>' . $htmlOutput;
        }
        echo '<pre>' . $htmlOutput . '</pre>';
        return TRUE;
    }

}

/**
 * dump value of variable
 * 
 * @param mixed $val value to dump
 * @param boolean $encodeHtmlEntities define if html entities will be encoded
 * @return boolean TRUE on success | FALSE on failure
 */
function dump($val, $encodeHtmlEntities = TRUE) {
    $Dump = new Dump();
    return $Dump->varDump($val, $encodeHtmlEntities);
}

?>
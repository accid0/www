<?php
namespace packages\view\builder;

use packages\view\exception\BuilderChainException;

use packages\view\plugins\DumpObserver;

use packages\view\expression\chain\VarChainPluginExpression;

use packages\view\expression\chain\FunctionChainPluginExpression;

use packages\view\expression\chain\ArrayChainPluginExpression;

use packages\view\expression\chain\ChainPluginExpression;

use packages\models\factory\AbstractFactory;

use packages\models\observer\DepthObserver;

use packages\view\expression\TemplateExpression;

use packages\view\expression\BinaryOperatorExpression;

use packages\view\expression\HtmlExpression;

use packages\view\expression\PluginExpression;

use packages\view\parser\ParserObserver;

use packages\view\expression\Expression;

use packages\models\visitorer\Visitorer;

use packages\models\observer\Observer;

use ArrayObject,ReflectionClass,Exception;

class Builder extends Visitorer {
  /**
   * 
   * Enter description here ...
   * @var mixed $dumpResult
   * @todo 
   * - здесь находится дамп значения узла
   */
  private $dumpResult;
  /**
   * @return ArrayObject
   * @param string $tmpl
   */
  private function regexGlobal( $tmpl){
     $result = new ArrayObject(array(), 
       ArrayObject::STD_PROP_LIST);
     $tocens = array();
     $endTag = array();
     preg_match_all("@(?:
			[^{]++ (?: \{\s)?
		|
    		\{([a-zA-Z0-9_\\\\]++  \s*(?:->|\[)\s*   ( [^{}]++ | \{ (?2) \} ) *) \}
		|
    		\{([a-zA-Z0-9_\\\\]++) ( (?2)*) \}
		|
			\{\/((?3))\}
		)
		@xs", $tmpl, $tocens, PREG_SET_ORDER);
     foreach ($tocens as $toc) {
       if ( !is_null($toc[5]) && $toc[5] != ''){
         $endTag []= $toc[5];
       }
     }
     for ($i = 0; $i < count($tocens); $i++) {
       $func = array();
       if ( !is_null($tocens[$i][3]) && $tocens[$i][3] != ''){
         if ( in_array( $tocens[$i][3], $endTag)){
           $func[1] = $tocens[$i][3];
           if ( !is_null($tocens[$i][4]) && $tocens[$i][4] != '')
             $func[2] = $tocens[$i][4];
           $body = '';
           $depth =0;
           while ( ++$i < count($tocens) && 
            ( $tocens[$i][5] != $func[1] || $depth != 0)){
             $body .=  $tocens[$i][0];
             if ( $tocens[$i][5] == $func[1] )
               $depth++;
             elseif (  $tocens[$i][5] == $func[1] )
               $depth--;
           }
           if ( $body != '')
             $func[4] = $body;
           $func [0]= "{". $func[1] . $func[2]. "}" . $body . "{/" . 
              $func[1] . "}";
         }
         else{
           $func[7] = $tocens[$i][3];
           if ( !is_null($tocens[$i][4]) && $tocens[$i][4] != '')
             $func[8] = $tocens[$i][4];
           $func [0]= $tocens[$i][0];
         }
         $result []= $func;
       }
       elseif ( !is_null($tocens[$i][1]) && $tocens[$i][1] != ''){
         $func [6]= $tocens[$i][1];
         $func [0]= $tocens[$i][0];
         $result []= $func;
       }
       elseif ( is_null($tocens[$i][5])){
         $func [0]= $tocens[$i][0];
         $result []= $func;
       }
     }
     return $result;
  }
  /**
   * 
   * Enter description here ...
   * @return Builder
   */
  function init(){
    $this->dumpResult = NULL;
    return $this;
  }
  /**
   * @return mixed Возвращает дамп узла
   * Enter description here ...
   */
  function getDumpResult(){
     return $this->dumpResult;
  }
  /**
   * Enter description here ...
   * @param mixed $dr Сохраняет дамп узла
   */
  function  setDumpResult( $dr ){
	  $this->dumpResult = $dr;
  }
  /**
   * @return void
   * Enter description here ...
   */
  function __construct(){
    $this->init();
  }
    /**
     * @return void
     * Enter description here ...
     * @param PluginExpression $exn
     * @todo plugin
     */
	function visitPluginExpression(PluginExpression $exn) {
	  $result = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
      $obr = AbstractFactory::getInstance('packages\\view\\factory\\FactoryPluginObserver')
        ->getObject($exn->getDumpResult('name'));
      $exn->attach($obr);
	  $sv = $exn->getDumpResult('params');
	  if ( $sv !== NULL){
	    preg_match_all("@
	  			(?:
                  (\{[a-zA-Z0-9_\\\\]++(?R)*\}) \s++(?:as)\s++ ([a-zA-Z0-9_]++(?:\s*=>\s*[a-zA-Z0-9_]++)?)
                |
                  ([a-zA-Z0-9_]++) \s*(?:=)\s* ((?R))
                |
                  ([a-zA-Z0-9_]++) \s*(?:=)\s* ([a-zA-Z0-9_]++)
                |
                  ([a-zA-Z0-9_]++) \s*(?:=)\s* ((?1))
                |
                  (?:[a-zA-Z0-9_]++|(?1)|\(\s*(?R)\s*\)) \s*(?:==|>=|<=|>|<|\|\||\&\&)\s* (?:(?R)|[a-zA-Z0-9_]++|(?1))
                |
                	\(\s*(?R)\s*\)
                |
	    		  ( (?:[a-zA-Z0-9_\[\],;.\->#\\\\\/\.] | \( (?:[^()]|(?9))* \))++ )
	    		|
                  \s++
                )
		@sx", $sv, $result, PREG_SET_ORDER);
        //print_r($result);
        //echo "<br/>";
    	foreach ($result as $r) {
    	  $r[0] = preg_replace("/(^\s++|\s++$)/xs", '', $r[0]);
    	  if (!is_null($r[0]) && $r[0] != ''){
    	    if (!is_null($r[1]) && $r[1] != ''){
    	      $po = new BinaryOperatorExpression($r[0],$exn);
    	      $exn->addVar('__as$'.$po->getId(), $po);
    	    }
    	    elseif (!is_null($r[3]) && $r[3] != ''){
    	      $po = new BinaryOperatorExpression($r[0],$exn);
    	      $exn->addVar('__equal$'.$po->getId(), $po);
    	    }
    	    elseif (!is_null($r[5]) && $r[5] != ''){
    	      $po = new BinaryOperatorExpression($r[0],$exn);
    	      $exn->addVar('__equal$'.$po->getId(), $po);
    	    }
    	    elseif (!is_null($r[7]) && $r[7] != ''){
    	      $po = new BinaryOperatorExpression($r[0],$exn);
    	      $exn->addVar('__equal$'.$po->getId(), $po);
    	    }
    	    elseif (count($r) == 1){
    	      $po = new BinaryOperatorExpression($r[0],$exn);
    	      $exn->addVar('__binary_operator$'.$po->getId(), $po);
    	    }
    	    elseif (!is_null($r[9]) && $r[9] != ''){
    	      $po = new TemplateExpression($r[9],$exn);
    	      $exn->addVar('__var_operator$'.$po->getId(), $po);
    	    }
    	    $po->visit($this);
    	    $po->setDumpResult('result' , $this->dumpResult);
    	  }
    	}
	  }
	  $result = $exn->execute();
	  if (! ( $result instanceof  TemplateExpression )) $this->dumpResult = 
	    $result;
	  else{
	    $exn->addStorage($result);
	    $result = '';
	    foreach ($exn->storages() as $stg){
    	  $stg->visit($this);
    	  $result .= $this->dumpResult;
    	}
    	$this->dumpResult = $result;
	  }
	}
	/**
	 * @return void
	 * Enter description here ...
	 * @param TemplateExpression $exn
	 * @todo template
	 */
	function visitTemplateExpression(TemplateExpression $exn) { 	  
	  $this->initPlugin($exn->expression(), $exn);
	}
	/**
	 * @return void
	 * Enter description here ...
	 * @param HtmlExpression $exn
	 * @todo html
	 */
	function visitHtmlExpression(HtmlExpression $exn){
	  $this->dumpResult = $exn->expression();
	}
	/**
	 * @return void
	 * Enter description here ...
	 * @param BinaryOperatorExpression $exn
	 * @todo binary operator
	 */
	function visitBinaryOperatorExpression(BinaryOperatorExpression $exn){
	  $tmpl = $exn->expression();
	  $r = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
      //            (?:([a-zA-Z0-9_]++) | (\{(?:[^{}]++|(?2))*\}) | \(((?R))\)) \s*(==|>=|<=|>|<|\|\||\&\&)\s* (?:((?R)|(?1)|(?2)) | \(((?R))\))
      //          |
	  preg_match("@
	  			(?:
                  ([a-zA-Z0-9_\.\/\\\\]++) \s*(\s++as\s++|==|>=|<=|=>|>|<|\|\||\&\&|=|,)\s* ((?R))
                |
                	((?1)) \s*((?2))\s* ((?1))
                |
                	((?1)) \s*((?2))\s* (\{(?:[^{}]++|(?9))*\})
                |
                	((?1)) \s*((?2))\s* \(\s*((?R))\s*\)
                |
                	((?9)) \s*((?2))\s* ((?R))
                |
                	((?9)) \s*((?2))\s* ((?1))
                |
                	((?9)) \s*((?2))\s* ((?9))
                |
                	((?9)) \s*((?2))\s* \(((?R))\)
                |
                	\(\s*((?R))\s*\) \s*((?2))\s* ((?R))
                |
                	\(\s*((?R))\s*\) \s*((?2))\s* ((?1))
                |
                	\(\s*((?R))\s*\) \s*((?2))\s* ((?9))
                |
                	\(\s*((?R))\s*\) \s*((?2))\s* \(\s*((?R))\s*\)
                )
		@sx", $tmpl, $r);
    	$r[0] = preg_replace("/(^\s++|\s++$)/xs", '', $r[0]);
    	if (!is_null($r[0]) && $r[0] != ''){
    	    if (!is_null($r[1]) && $r[1] != '') {
    	      $lo = new HtmlExpression($r[1],$exn);
    	      $ro = new BinaryOperatorExpression($r[3],$exn);
    	      $exn->addVar($lo, $r[2], $ro);
    	    }
    	    elseif (!is_null($r[4]) && $r[4] != ''){
    	      $lo = new HtmlExpression($r[4],$exn);
    	      $ro = new HtmlExpression($r[6],$exn);
    	      $exn->addVar($lo, $r[5], $ro);
    	    }
    	    elseif (!is_null($r[7]) && $r[7] != ''){
    	      $lo = new HtmlExpression($r[7],$exn);
    	      $ro = $this->initPlugin($r[9],$exn);
    	      $exn->addVar($lo, $r[8], $ro);
    	    }
    	    elseif (!is_null($r[10]) && $r[10] != ''){
    	      $lo = new HtmlExpression($r[10],$exn);
    	      $ro = new BinaryOperatorExpression($r[12],$exn);
    	      $exn->addVar($lo, $r[11], $ro);
    	    }
    	    elseif (!is_null($r[13]) && $r[13] != ''){
    	      $lo = $this->initPlugin($r[13],$exn);
    	      $ro = new BinaryOperatorExpression($r[15],$exn);
    	      $exn->addVar($lo, $r[14], $ro);
    	    }
    	    elseif (!is_null($r[16]) && $r[16] != ''){
    	      $lo = $this->initPlugin($r[16],$exn);
    	      $ro = new HtmlExpression($r[18],$exn);
    	      $exn->addVar($lo, $r[17], $ro);
    	    }
    	    elseif (!is_null($r[19]) && $r[19] != ''){
    	      $lo = $this->initPlugin($r[19],$exn);
    	      $ro = $this->initPlugin($r[21],$exn);
    	      $exn->addVar($lo, $r[20], $ro);
    	    }
    	    elseif (!is_null($r[22]) && $r[22] != ''){
    	      $lo = $this->initPlugin($r[22],$exn);
    	      $ro = new BinaryOperatorExpression($r[24],$exn);
    	      $exn->addVar($lo, $r[23], $ro);
    	    }
    	    elseif (!is_null($r[25]) && $r[25] != ''){
    	      $lo = new BinaryOperatorExpression($r[25],$exn);
    	      $ro = new BinaryOperatorExpression($r[27],$exn);
    	      $exn->addVar($lo, $r[26], $ro);
    	    }
    	    elseif (!is_null($r[28]) && $r[28] != ''){
    	      $lo = new BinaryOperatorExpression($r[28],$exn);
    	      $ro = new HtmlExpression($r[30],$exn);
    	      $exn->addVar($lo, $r[29], $ro);
    	    }
    	    elseif (!is_null($r[31]) && $r[31] != ''){
    	      $lo = new BinaryOperatorExpression($r[31],$exn);
    	      $ro = $this->initPlugin($r[33],$exn);
    	      $exn->addVar($lo, $r[32], $ro);
    	    }
    	    elseif (!is_null($r[34]) && $r[34] != ''){
    	      $lo = new BinaryOperatorExpression($r[34],$exn);
    	      $ro = new BinaryOperatorExpression($r[36],$exn);
    	      $exn->addVar($lo, $r[35], $ro);
    	    }
    	    $lo->visit($this);
    	    $lr = $this->dumpResult;
    	    $ro->visit($this);
    	    $rr = $this->dumpResult;
        	switch($exn->getOperator()){
        	    case 'as':
        	      $this->dumpResult = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
        	      $this->dumpResult ['array']= $lr;
        	      if ($ro instanceof BinaryOperatorExpression){
        	        $this->dumpResult ['key']= $rr['key'];
        	        $this->dumpResult ['value']= $rr['value'];
        	      }
        	      else $this->dumpResult ['value']= $rr;
        	      break;
        	    case '==':
        	      $this->dumpResult = ($lr == $rr)?true:false;
        	      break;
        	    case '>=':
        	      $this->dumpResult = ($lr >= $rr)?true:false;
        	      break;
        	    case '<=':
        	      $this->dumpResult = ($lr <= $rr)?true:false;
        	      break;
        	    case '=>':
        	      $this->dumpResult = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
        	      $this->dumpResult ['key']= $lr;
        	      $this->dumpResult ['value']= $rr;
        	      break;
        	    case '>':
        	      $this->dumpResult = ($lr > $rr)?true:false;
        	      break;
        	    case '<':
        	      $this->dumpResult = ($lr < $rr)?true:false;
        	      break;
        	    case '||':
        	      $this->dumpResult = ($lr || $rr)?true:false;
        	      break;
        	    case '&&':
        	      $this->dumpResult = ($lr && $rr)?true:false;
        	      break;
        	    case '=':
        	      $this->dumpResult = $rr;
        	      break;
        	    case ',':
        	      $this->dumpResult = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
        	      $this->dumpResult []= $lr;
        	      if ($ro instanceof BinaryOperatorExpression){
        	        foreach ($rr as $v){
        	          $this->dumpResult []= $v;
        	        }
        	      }
        	      else $this->dumpResult []= $rr;
        	      break;
        	  }
    	  }
	}
	/**
	 * 
	 * Enter description here ...
	 * @param string $tmpl
	 * @param Expression $exn
	 * @return PluginExpression or void
	 * @todo init plugin, html, template
	 */
	function initPlugin( $tmpl, Expression $exn){
	  $result = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
	  $str = '';
	  $result = $this->regexGlobal($tmpl);
	  foreach ($result as $s){
	    $e = NULL;
		if ( !is_null($s[1]) && $s[1] != '' ) {
		  $e = new PluginExpression($s[0],$exn);
		  $e->setDumpResult('name' , $s[1]);
		  if ( !is_null($s[2]) && $s[2] != '' )
		    $e->setDumpResult('params' , $s[2]);
		  if ( !is_null($s[4]) && $s[4] != '' )
		    $e->setDumpResult('body' , $s[4]);
		}
		elseif ( !is_null($s[6]) && $s[6] != '' ){
		  $e = new ChainPluginExpression($s[6],$exn);
		}
		elseif ( !is_null($s[7]) && $s[7] != '' ){
		  $e = new PluginExpression($s[0],$exn);
		  $e->setDumpResult('name' , $s[7]);
		  if ( !is_null($s[8]) && $s[8] != '' )
		    $e->setDumpResult('params' , $s[8]);
		}
		else {
		  $e = new HtmlExpression($s[0],$exn);
		}
		if ($exn instanceof BinaryOperatorExpression) return $e;
		$e->visit($this);
		$str .= $this->dumpResult;
		$exn->addStorage($e);
      }
      $this->dumpResult = $str;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param ChainPluginExpression $exn
	 * @return void
	 * @todo chain plugin
	 */
	function visitChainPluginExpression(ChainPluginExpression $exn){
	  $result = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
	  preg_match("@(?:
	  			((\) ((?:[^)(]|(?2))*) \()([a-zA-Z0-9_\s\\\\]++)) >- ((?4))(?!.*>-)
	  		|
	  			((\] ((?:[^\[\]]|(?7))*) \[)((?4))) >- ((?4))(?!.*>-)
	  		|
	  			((?4)) >- ((?4))(?!.*>-)
	  		|
	  			((\) ((?:[^)(]|(?14))*) \()((?4))) >- ((?R))(?!.*>-)
	  		|
	  			((\] ((?:[^\[\]]|(?19))*) \[)((?4))) >- ((?R))(?!.*>-)
	  		|
	  			((?4)) >- ((?R))(?!.*>-)
	  		|
	  			(\] ((?:[^\[\]]|(?25))*) \[)((?4))(?!.*>-)
	  )@xs", strrev($exn->expression()), $result);
	  $result[0] = preg_replace("/(^\s++|\s++$)/xs", '', $result[0]);
      if (!is_null($result[0]) && $result[0] != ''){
        if (!is_null($result[1]) && $result[1] != '') {
          $r = new FunctionChainPluginExpression(strrev($result[1]),$exn);
          $r->setDumpResult('name' , strrev($result[4]));
          $r->setDumpResult('params' , strrev($result[3]));
          $l = $this->initPlugin("{".strrev($result[5])."}",$exn);
        }
        elseif (!is_null($result[6]) && $result[6] != ''){
          $r = new ArrayChainPluginExpression(strrev($result[6]),$exn);
          $r->setDumpResult('name' , strrev($result[9]));
          $r->setDumpResult('params' , strrev($result[8]));
          $l = $this->initPlugin("{".strrev($result[10])."}",$exn);
        }
        elseif (!is_null($result[11]) && $result[11] != ''){
          $r = new VarChainPluginExpression(strrev($result[11]),$exn);
          $l = $this->initPlugin("{".strrev($result[12])."}",$exn);
        }
        elseif (!is_null($result[13]) && $result[13] != ''){
          $r = new FunctionChainPluginExpression(strrev($result[13]),$exn);
          $r->setDumpResult('name' , strrev($result[16]));
          $r->setDumpResult('params' , strrev($result[15]));
          $l = new ChainPluginExpression(strrev($result[17]),$exn);
        }
        elseif (!is_null($result[18]) && $result[18] != ''){
          $r = new ArrayChainPluginExpression(strrev($result[18]),$exn);
          $r->setDumpResult('name' , strrev($result[21]));
          $r->setDumpResult('params' , strrev($result[20]));
          $l = new ChainPluginExpression(strrev($result[22]),$exn);
        }
        elseif (!is_null($result[23]) && $result[23] != ''){
          $r = new VarChainPluginExpression(strrev($result[23]),$exn);
          $l = new ChainPluginExpression(strrev($result[24]),$exn);
        }
        elseif (!is_null($result[25]) && $result[25] != ''){
          $r = new ArrayChainPluginExpression(strrev($result[25]),$exn);
          $r->setDumpResult('name' , '');
          $r->setDumpResult('params' , strrev($result[26]));
          $l = $this->initPlugin("{".strrev($result[27])."}",$exn);
        }
        $r->visit($this);
        $rr = $this->dumpResult;
        $l->visit($this);
        $ll = $this->dumpResult;
        $exn->addVar($l, '->', $r);
        if ( $r instanceof VarChainPluginExpression  ){
          try{
            $class = new ReflectionClass($ll);
          }
          catch (Exception $e){
            throw new BuilderChainException("Невозможно получить 
            	класс для результата плагина : " . $l->expression());
          }
          if ( !(isset($ll -> $rr)) ) throw new 
            BuilderChainException("Невозможно получить 
            свойство [$rr]  объекта [" . $class->getName() . "] <br/>\n");
           $this->dumpResult = $ll -> $rr;
        }
        elseif ( $r instanceof  FunctionChainPluginExpression ){
          try{
            $class = new ReflectionClass($ll);
          }
          catch (Exception $e){
            throw new BuilderChainException("Невозможно получить 
            	класс для результата плагина : " . $l->expression());
          }
          if ( !($class->hasMethod($rr["name"])) ) throw new 
            BuilderChainException("Невозможно получить 
            метод объекта " . $rr["name"]);
           $method = $class->getMethod($rr["name"]);
           $this->dumpResult = $method->invokeArgs($ll,$rr["args"]);
        }
        elseif ( $r instanceof ArrayChainPluginExpression ){
          $arr = $rr["array"];
          if ( $arr != '')
            $arr = $ll->$arr;
          else 
            $arr = $ll;
          $key = $rr["key"];
          $this->dumpResult = $arr[$key];
        }
      }
	}
	/**
	 * 
	 * Enter description here ...
	 * @param FunctionChainPluginExpression $exn
	 * @return void
	 * @todo function chain
	 */
	function visitFunctionChainPluginExpression(FunctionChainPluginExpression $exn){
	  $sv = $exn->getDumpResult('params');
	  if (preg_match("@[^,]+,[^,]+@", $sv)){
	    $v = new BinaryOperatorExpression($sv,$exn);
	    $exn->addStorage($v);
	    $v->visit($this);
	    $ps = $this->dumpResult;
	  }
	  else {
	    $v = new TemplateExpression($sv,$exn);
	    $exn->addStorage($v);
	    $v->visit($this);
	    $ps = new ArrayObject(array($this->dumpResult), ArrayObject::STD_PROP_LIST);
	  }
	  $this->dumpResult = new ArrayObject(array(
	    "name" =>  $exn->getDumpResult("name"),
	    "args" => $ps
	  ), ArrayObject::STD_PROP_LIST);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param ArrayChainPluginExpression $exn
	 * @return void
	 * @todo array chain
	 */
	function visitArrayChainPluginExpression(ArrayChainPluginExpression $exn){
	  $sv = $exn->getDumpResult('params');
	  $v = new TemplateExpression($sv,$exn);
	  $exn->addStorage($v);
	  $v->visit($this);
	  $this->dumpResult = new ArrayObject(array(
	    "key" => $this->dumpResult,
	    "array" => $exn->getDumpResult('name')
	  ), ArrayObject::STD_PROP_LIST);
	}
	/**
	 * 
	 * Enter description here ...
	 * @param VarChainPluginExpression $exn
	 * @todo var chain
	 */
	function visitVarChainPluginExpression(VarChainPluginExpression $exn){
	  $this->dumpResult = $exn->expression();
	}
}
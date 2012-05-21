<?php

namespace packages\view\builder;

use packages\view\expression\chain\VarChainPluginExpression;

use packages\view\expression\chain\ArrayChainPluginExpression;

use packages\view\expression\chain\FunctionChainPluginExpression;

use packages\view\expression\chain\ChainPluginExpression;

use packages\view\expression\Expression;

use packages\view\expression\TemplateExpression;

use packages\view\expression\BinaryOperatorExpression;

use packages\view\expression\PluginExpression;

use packages\view\expression\HtmlExpression;

use packages\models\visitorer\Visitorer;

class TreeBuilder extends Visitorer{
  private static $num_tab = 30;
  private function print_node(Expression $exn , $str){
    $n = $exn->getDepth()*self::$num_tab;
    $s = "";
    for($i=0;$i<$n;$i++){
      $s .= "&nbsp";
    }
    if (strlen($exn->expression())>2*self::$num_tab) 
      print " <hr>" . $s . "$str:<br/>" . $s . substr($exn->expression(), 0, self::$num_tab) .
    	" <strong>...</strong> " . substr($exn->expression(), strlen($exn->expression())-self::$num_tab-1) . "<br/>\n";  
    else    
      print " <hr>" . $s . "$str:<br/>" . $s . $exn->expression() . "<br/>\n";
  }
  function visitHtmlExpression(HtmlExpression $exn){
    $this->print_node($exn,"HtmlExpression\$".$exn->getId());
  }
  function visitTemplateExpression(TemplateExpression $exn){
    
    $this->print_node($exn,"TemplateExpression\$".$exn->getId());
    foreach ($exn->storages() as $stg){
      $stg->visit($this);
    }
  }
  function visitPluginExpression(PluginExpression $exn){
    
    $this->print_node($exn,"PluginExpression\$".$exn->getId());
    foreach ($exn->getVars() as $v){
      $v->visit($this);
    }
  }
  function visitBinaryOperatorExpression(BinaryOperatorExpression $exn){
    
    $this->print_node($exn,"BinaryOperatorExpression\$".$exn->getId());
    $exn->getL_Var()->visit($this);
    $exn->getR_Var()->visit($this);
  }
  function visitChainPluginExpression(ChainPluginExpression $exn){
    $this->print_node($exn,"ChainPluginExpression\$".$exn->getId());
    $exn->getL_Var()->visit($this);
    $exn->getR_Var()->visit($this);
  }
  function visitFunctionChainPluginExpression(FunctionChainPluginExpression $exn){
	$this->print_node($exn,"FunctionChainPluginExpression\$".$exn->getId());
    foreach ($exn->storages() as $stg){
      $stg->visit($this);
    }
  }
  function visitArrayChainPluginExpression(ArrayChainPluginExpression $exn){
	$this->print_node($exn,"FunctionChainPluginExpression\$".$exn->getId());
  }
  function visitVarChainPluginExpression(VarChainPluginExpression $exn){
	$this->print_node($exn,"FunctionChainPluginExpression\$".$exn->getId());
  }
}
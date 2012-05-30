<?php
namespace modules\datatable;
use packages\models\db\xPDOCollection;

use packages\view\expression\HtmlExpression;

use packages\view\expression\Expression;
use packages\view\plugins\PluginObserver;
use packages\view\exception\PluginObserverException;
use packages\models\observer\Observer;
use xPDOObject, xPDOCriteria;
class DatatableObserver extends PluginObserver {
    /**
    * (non-PHPdoc)
    * @see packages\view\plugins.PluginObserver::install()
    */
    protected function install(){
    }
	/**
	 * @param Expression $subject
	 */
	protected function doExecute(Expression $subject){
    }
	/**
   	* (non-PHPdoc)
   	* @see packages\view\plugins.PluginObserver::init()
	*/
	function init(Observer $obs){
        $db = $this->getDbPersistenceFactory();
    	$r = $this->getPlugin('request');
    	/*
    	 * Script:    DataTables server-side script for PHP and MySQL
    	 * Copyright: 2010 - Allan Jardine
    	 * License:   GPL v2 or BSD (3-point)
    	 */
	
    	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    	 * Easy set variables
    	 */
    	
    	/* Array of database columns which should be read and sent back to DataTables. Use a space where
    	 * you want to insert a non-database field (for example a counter or static image)
    	 */
    	$aColumns = explode( ',', $r->sColumns );
    	
    	/* DB table to use */
    	$sTable = $r->table;
    	$query = $db->newQuery( $sTable);
    	
    	/* Indexed column (used for fast and accurate table cardinality) */
    	$sIndexColumn = $db->newObject( $sTable);
    	$sIndexColumn = $sIndexColumn->getPk();
    	//$query->select( "$sTable.$sIndexColumn, $r->sColumns");
    	/* 
    	 * Paging
    	 */
    	$sLimit = "";
    	if ( isset( $r->iDisplayStart) && $r->iDisplayStart != '-1' )
    	{
    	  $query->limit(  $r->iDisplayLength, $r->iDisplayStart);
    	}
    	
    	
    	/*
    	 * Ordering
    	 */
    	if ( isset( $r->iSortCol_0 ) )
    	{
    	  for ( $i=0 ; $i<intval( $r->iSortingCols) ; $i++ )
    	  {
    		 $cn = 'iSortCol_' . $i;
    		 $cn = intval($r->$cn);
    		 $rn = 'bSortable_' . $cn;
    		 $dn = 'sSortDir_' . $i;
    		 if ( $r->$rn == "true" )
    		 {
    		   $query->sortby( $aColumns[ $cn ],  $r->$dn );
    		 }
    	   }
    	 }
    	
    	
    	/* 
    	 * Filtering
    	 * NOTE this does not match the built-in DataTables filtering which does it
    	 * word by word on any field. It's possible to do here, but concerned about efficiency
    	 * on very large tables, and MySQL's regex functionality is very limited
    	 */
    	if ( $r->sSearch != "" )
    	{
    	  $sWhere = array();
    	  $sWhere [0]= array();
    	  for ( $i=0 ; $i<count($aColumns) ; $i++ )
    	  {
    	    $sWhere [0]['OR:' . $aColumns[$i] . ':LIKE']=  '%' . $r->sSearch . '%';
    	   }
    	
    	/* Individual column filtering */
           for ( $i=0 ; $i<count($aColumns) ; $i++ )
           {
        	  $sn = 'bSearchable_'.$i;
        	  $cn = 'sSearch_'.$i;
        	  if ( $r->$sn == "true" && $r->$cn != '' )
        	  {
        	    $sWhere ['AND:' . $aColumns[$i] . ':LIKE']= '%' . $r->$cn . '%';
        	  }
        	}
        	$query->where( $sWhere);
    	}
    	
        $query->groupby("$sTable.$sIndexColumn");
    	$query->bindGraph( $r->graph);
    	$data = $db->getCollection( $sTable, $query, $r->graph);
    	$iFilteredTotal = $data->count();
    	$query = $db->newQuery($sTable);
    	$iTotal = $db->getCount( $sTable, $query);
    	/*
    	 * Output
    	 */
      	$output = array(
      		"sEcho" => intval($r->sEcho),
      		"iTotalRecords" => $iTotal,
      		"iTotalDisplayRecords" => $iFilteredTotal,
      	    "sColumns" => $r->sColumns,
      		"aaData" => array()
      	);
    	$output['aaData'] = $data->columnsToArray( $aColumns);
    	$this->setResult( $this->toHtml( json_encode( $output )));
    }
	
	
	function __construct(){
	  parent::__construct();
	}
}
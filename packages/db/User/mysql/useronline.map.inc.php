<?php
$xpdo_meta_map['Useronline']= array (
  'package' => 'User',
  'version' => '1.1',
  'table' => 'useronline',
  'fields' => 
  array (
    'ip' => '0',
    'userId' => NULL,
    'expire' => 0,
    'uname' => NULL,
    'invisible' => NULL,
  ),
  'fieldMeta' => 
  array (
    'ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
    'userId' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'expire' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'uname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'invisible' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'kpro_useronline_userId_Idx' => 
    array (
      'alias' => 'kpro_useronline_userId_Idx',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'userId' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'fk_kpro_useronline_kpro_user2' => 
    array (
      'alias' => 'fk_kpro_useronline_kpro_user2',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'uname' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'User',
      'local' => 'userId',
      'foreign' => 'userId',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);

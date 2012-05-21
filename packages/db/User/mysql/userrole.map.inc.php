<?php
$xpdo_meta_map['Userrole']= array (
  'package' => 'User',
  'version' => '1.1',
  'table' => 'userrole',
  'fields' => 
  array (
    'userId' => NULL,
    'ugroup' => NULL,
  ),
  'fieldMeta' => 
  array (
    'userId' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
    'ugroup' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
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
        'ugroup' => 
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
    'UserGroup' => 
    array (
      'class' => 'Usergroup',
      'local' => 'ugroup',
      'foreign' => 'ugroup',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
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

<?php
$xpdo_meta_map['Usergroup']= array (
  'package' => 'User',
  'version' => '1.1',
  'table' => 'usergroup',
  'fields' => 
  array (
    'ugroup' => NULL,
    'groupname_single' => NULL,
    'groupname' => NULL,
    'groupmaxpn' => 50,
    'groupmaxsig' => 100,
    'maxpnlength' => 1000,
    'maxcommlength' => 500,
    'maxpicdownload' => 5,
    'avatar_size' => 15360,
    'avatar_width' => 120,
    'avatar_height' => 100,
    'default_avatar' => NULL,
    'set_default_avatar' => 0,
    'maxlength_post' => 50000,
    'max_attachments' => 5,
    'deductions' => 0,
  ),
  'fieldMeta' => 
  array (
    'ugroup' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
      'generated' => 'native',
    ),
    'groupname_single' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '75',
      'phptype' => 'string',
      'null' => false,
    ),
    'groupname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'groupmaxpn' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 50,
    ),
    'groupmaxsig' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 100,
    ),
    'maxpnlength' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1000,
    ),
    'maxcommlength' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 500,
    ),
    'maxpicdownload' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 5,
    ),
    'avatar_size' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 15360,
    ),
    'avatar_width' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 120,
    ),
    'avatar_height' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 100,
    ),
    'default_avatar' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'set_default_avatar' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'maxlength_post' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 50000,
    ),
    'max_attachments' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 5,
    ),
    'deductions' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '6',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
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
        'ugroup' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'groupid' => 
    array (
      'alias' => 'groupid',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'ugroup' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'UserRole' => 
    array (
      'class' => 'Userrole',
      'local' => 'ugroup',
      'foreign' => 'ugroup',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

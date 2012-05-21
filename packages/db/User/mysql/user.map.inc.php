<?php
$xpdo_meta_map['User']= array (
  'package' => 'User',
  'version' => '1.1',
  'table' => 'user',
  'fields' => 
  array (
    'userId' => NULL,
    'name' => NULL,
    'uname' => NULL,
    'email' => NULL,
    'url' => NULL,
    'user_avatar_pref' => NULL,
    'user_regdate' => 0,
    'user_sig' => NULL,
    'user_from' => NULL,
    'user_interests' => NULL,
    'user_birthday' => NULL,
    'pass' => NULL,
    'passtemp' => NULL,
    'salt' => NULL,
    'posts' => 0,
    'theme' => NULL,
    'last_login' => 0,
    'user_viewemail' => 'yes',
    'user_canpn' => 'yes',
    'invisible' => 'no',
    'pntomail' => 'yes',
    'status' => 1,
    'user_lastonline' => NULL,
    'user_lastonline_temp' => NULL,
    'user_posts' => 0,
    'user_lastpost' => NULL,
    'recieve_newsletter' => 1,
    'usedefault_avatar' => 1,
    'user_avatar' => NULL,
    'country' => 'DE',
    'group_id_misc' => NULL,
    'person' => 'private',
    'phone' => NULL,
    'phone_mobile' => NULL,
    'fax' => NULL,
    'lastname' => NULL,
    'zip' => NULL,
    'street' => NULL,
    'title' => NULL,
    'show_public' => 1,
    'company' => NULL,
    'ustid' => NULL,
    'fsk18' => 0,
    'user_icq' => NULL,
    'user_aim' => NULL,
    'user_skype' => NULL,
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
      'generated' => 'native',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
    ),
    'uname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '65',
      'phptype' => 'string',
      'null' => false,
    ),
    'url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '200',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_avatar_pref' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_regdate' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'unique',
    ),
    'user_sig' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_from' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '75',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_interests' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_birthday' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
    ),
    'pass' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '35',
      'phptype' => 'string',
      'null' => false,
    ),
    'passtemp' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '35',
      'phptype' => 'string',
      'null' => false,
    ),
    'salt' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '35',
      'phptype' => 'string',
      'null' => false,
    ),
    'posts' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'theme' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '75',
      'phptype' => 'string',
      'null' => false,
    ),
    'last_login' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'user_viewemail' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'yes\',\'no\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'yes',
    ),
    'user_canpn' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'yes\',\'no\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'yes',
    ),
    'invisible' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'yes\',\'no\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'no',
    ),
    'pntomail' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'yes\',\'no\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'yes',
    ),
    'status' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'user_lastonline' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'unique',
    ),
    'user_lastonline_temp' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'unique',
    ),
    'user_posts' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'user_lastpost' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'recieve_newsletter' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'usedefault_avatar' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '6',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'user_avatar' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'country' => 
    array (
      'dbtype' => 'char',
      'precision' => '2',
      'phptype' => 'string',
      'null' => false,
      'default' => 'DE',
    ),
    'group_id_misc' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
      'index' => 'unique',
    ),
    'person' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'private\',\'company\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'private',
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'phone_mobile' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'fax' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'lastname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'zip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'street' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
    ),
    'show_public' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'company' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'ustid' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'fsk18' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'user_icq' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_aim' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => false,
    ),
    'user_skype' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => false,
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
      ),
    ),
    'kpro_user_uname_Idx' => 
    array (
      'alias' => 'kpro_user_uname_Idx',
      'primary' => false,
      'unique' => true,
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
    'kpro_user_userId_Idx' => 
    array (
      'alias' => 'kpro_user_userId_Idx',
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
    'user_regdate' => 
    array (
      'alias' => 'user_regdate',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'user_regdate' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'group_id_misc' => 
    array (
      'alias' => 'group_id_misc',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'group_id_misc' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'user_lastonline' => 
    array (
      'alias' => 'user_lastonline',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'user_lastonline' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'user_lastonline_temp' => 
    array (
      'alias' => 'user_lastonline_temp',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'user_lastonline_temp' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'UserRole' => 
    array (
      'class' => 'Userrole',
      'local' => 'userId',
      'foreign' => 'userId',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'UserOnline' => 
    array (
      'class' => 'Useronline',
      'local' => 'userId',
      'foreign' => 'userId',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
);

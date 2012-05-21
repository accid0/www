<?php
$xpdo_meta_map['ShopCat']= array (
  'package' => 'Articles',
  'version' => '1.1',
  'table' => 'shop_cat',
  'fields' => 
  array (
    'catid' => NULL,
    'parent_id' => 0,
    'catname' => NULL,
    'catdesc' => NULL,
    'posi' => 1,
    'icon' => NULL,
    'startpage' => 0,
  ),
  'fieldMeta' => 
  array (
    'catid' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'pk',
      'generated' => 'native',
    ),
    'parent_id' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'catname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
      'index' => 'unique',
    ),
    'catdesc' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => false,
    ),
    'posi' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'icon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'startpage' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
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
        'catid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'kpro_shop_cat_catid_Idx' => 
    array (
      'alias' => 'kpro_shop_cat_catid_Idx',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'catid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'catname' => 
    array (
      'alias' => 'catname',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'catname' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'fk_kpro_shop_cat_kpro_shop_cat' => 
    array (
      'alias' => 'fk_kpro_shop_cat_kpro_shop_cat',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'parent_id' => 
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
    'Articles' => 
    array (
      'class' => 'ShopArticlesCat',
      'local' => 'catid',
      'foreign' => 'catid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

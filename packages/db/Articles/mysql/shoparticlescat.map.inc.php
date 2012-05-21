<?php
$xpdo_meta_map['ShopArticlesCat']= array (
  'package' => 'Articles',
  'version' => '1.1',
  'table' => 'shop_articles_cat',
  'fields' => 
  array (
    'catid' => NULL,
    'id' => NULL,
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
    'id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
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
        'catid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'id' => 
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
    'Article' => 
    array (
      'class' => 'ShopArticles',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Category' => 
    array (
      'class' => 'ShopCat',
      'local' => 'catid',
      'foreign' => 'catid',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);

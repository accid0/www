<?php
/**
*@name delete.php
*@packages models
*@subpackage 
*@author Andrew Scherbakov
*@version 1.0
*@copyright created 25.05.2012
*/
function deltree($folder){
        
        print "param[$folder]<br/>";
        if (is_dir($folder)){
          print "dir[$folder]<br/>";
                 $handle = opendir($folder);
                 while ($subfile = readdir($handle)){
                         if ($subfile == '.' or $subfile == '..') continue;
                         if (is_file($subfile)){
                           if ( !unlink("{$folder}/{$subfile}")) print 'motherfucker<br/>';
                           else print "deleted [{$folder}/{$subfile}]<br/>";
                         }
                         else deltree("{$folder}/{$subfile}");
                 }
                 closedir($handle);
                 rmdir ($folder);
         }
         else unlink($folder);
}
error_reporting(E_ALL);
deltree( __DIR__ . "/cache");
deltree( __DIR__ . "/modules/extend");
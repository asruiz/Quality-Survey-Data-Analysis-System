<?php
require 'path.php';
init_cobalt();
require 'subclasses/department_doc.php';
$obj_doc = new department_doc;
$obj_doc->auto_doc();
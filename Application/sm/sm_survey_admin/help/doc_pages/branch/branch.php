<?php
require 'path.php';
init_cobalt();
require 'subclasses/branch_doc.php';
$obj_doc = new branch_doc;
$obj_doc->auto_doc();
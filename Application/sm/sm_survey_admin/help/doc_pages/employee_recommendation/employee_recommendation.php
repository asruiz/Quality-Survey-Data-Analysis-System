<?php
require 'path.php';
init_cobalt();
require 'subclasses/employee_recommendation_doc.php';
$obj_doc = new employee_recommendation_doc;
$obj_doc->auto_doc();
<?php
require 'path.php';
init_cobalt();
require 'subclasses/question_details_doc.php';
$obj_doc = new question_details_doc;
$obj_doc->auto_doc();
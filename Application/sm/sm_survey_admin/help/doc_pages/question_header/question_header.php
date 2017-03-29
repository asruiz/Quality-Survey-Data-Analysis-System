<?php
require 'path.php';
init_cobalt();
require 'subclasses/question_header_doc.php';
$obj_doc = new question_header_doc;
$obj_doc->auto_doc();
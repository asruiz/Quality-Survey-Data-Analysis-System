<?php
require 'path.php';
init_cobalt();
require 'subclasses/survey_header_doc.php';
$obj_doc = new survey_header_doc;
$obj_doc->auto_doc();
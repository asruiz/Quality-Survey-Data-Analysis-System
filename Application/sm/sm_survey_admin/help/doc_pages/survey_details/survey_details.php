<?php
require 'path.php';
init_cobalt();
require 'subclasses/survey_details_doc.php';
$obj_doc = new survey_details_doc;
$obj_doc->auto_doc();
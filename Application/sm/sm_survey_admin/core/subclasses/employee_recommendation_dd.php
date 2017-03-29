<?php
class employee_recommendation_dd
{
    static $table_name = 'employee_recommendation';
    static $readable_name = 'Employee Recommendation';

    static function load_dictionary()
    {
        $fields = array(
                    'employee_recommendation_id' => array('value'=>'',
                                          'nullable'=>FALSE,
                                          'data_type'=>'integer',
                                          'length'=>20,
                                          'required'=>FALSE,
                                          'attribute'=>'primary key',
                                          'control_type'=>'none',
                                          'size'=>'60',
                                          'drop_down_has_blank'=>TRUE,
                                          'label'=>'Employee Recommendation ID',
                                          'extra'=>'',
                                          'companion'=>'',
                                          'in_listview'=>FALSE,
                                          'char_set_method'=>'generate_num_set',
                                          'char_set_allow_space'=>FALSE,
                                          'extra_chars_allowed'=>'-',
                                          'allow_html_tags'=>FALSE,
                                          'trim'=>'trim',
                                          'valid_set'=>array(),
                                          'date_elements'=>array('','',''),
                                          'date_default'=>'',
                                          'list_type'=>'',
                                          'list_settings'=>array(''),
                                          'rpt_in_report'=>TRUE,
                                          'rpt_column_format'=>'normal',
                                          'rpt_column_alignment'=>'center',
                                          'rpt_show_sum'=>TRUE),
                    'survey_header_id' => array('value'=>'',
                                          'nullable'=>FALSE,
                                          'data_type'=>'integer',
                                          'length'=>20,
                                          'required'=>TRUE,
                                          'attribute'=>'foreign key',
                                          'control_type'=>'drop-down list',
                                          'size'=>'60',
                                          'drop_down_has_blank'=>TRUE,
                                          'label'=>'Survey Header',
                                          'extra'=>'',
                                          'companion'=>'',
                                          'in_listview'=>TRUE,
                                          'char_set_method'=>'generate_num_set',
                                          'char_set_allow_space'=>FALSE,
                                          'extra_chars_allowed'=>'-',
                                          'allow_html_tags'=>FALSE,
                                          'trim'=>'trim',
                                          'valid_set'=>array(),
                                          'date_elements'=>array('','',''),
                                          'date_default'=>'',
                                          'list_type'=>'sql generated',
                                          'list_settings'=>array('query' => "SELECT survey_header.survey_header_id AS `Queried_survey_header_id`, survey_header.survey_number FROM survey_header ORDER BY `survey_number`",
                                                                     'list_value' => 'Queried_survey_header_id',
                                                                     'list_items' => array('survey_number'),
                                                                     'list_separators' => array()),
                                          'rpt_in_report'=>TRUE,
                                          'rpt_column_format'=>'normal',
                                          'rpt_column_alignment'=>'center',
                                          'rpt_show_sum'=>TRUE),
                    'employee_id' => array('value'=>'',
                                          'nullable'=>FALSE,
                                          'data_type'=>'integer',
                                          'length'=>20,
                                          'required'=>TRUE,
                                          'attribute'=>'foreign key',
                                          'control_type'=>'drop-down list',
                                          'size'=>'60',
                                          'drop_down_has_blank'=>TRUE,
                                          'label'=>'Employee',
                                          'extra'=>'',
                                          'companion'=>'',
                                          'in_listview'=>TRUE,
                                          'char_set_method'=>'generate_num_set',
                                          'char_set_allow_space'=>FALSE,
                                          'extra_chars_allowed'=>'-',
                                          'allow_html_tags'=>FALSE,
                                          'trim'=>'trim',
                                          'valid_set'=>array(),
                                          'date_elements'=>array('','',''),
                                          'date_default'=>'',
                                          'list_type'=>'sql generated',
                                          'list_settings'=>array('query' => "SELECT employee.employee_id AS `Queried_employee_id`, employee.first_name, employee.middle_name, employee.last_name FROM employee ORDER BY `first_name`, `middle_name`, `last_name`",
                                                                     'list_value' => 'Queried_employee_id',
                                                                     'list_items' => array('first_name', 'middle_name', 'last_name'),
                                                                     'list_separators' => array()),
                                          'rpt_in_report'=>TRUE,
                                          'rpt_column_format'=>'normal',
                                          'rpt_column_alignment'=>'center',
                                          'rpt_show_sum'=>TRUE)
                       );
        return $fields;
    }

    static function load_relationships()
    {
        $relations = array(array('type'=>'1-1',
                                 'table'=>'employee',
                                 'alias'=>'',
                                 'link_parent'=>'employee_id',
                                 'link_child'=>'employee_id',
                                 'link_subtext'=>array('first_name','middle_name','last_name'),
                                 'where_clause'=>''),
                           array('type'=>'1-1',
                                 'table'=>'survey_header',
                                 'alias'=>'',
                                 'link_parent'=>'survey_header_id',
                                 'link_child'=>'survey_header_id',
                                 'link_subtext'=>array('survey_number'),
                                 'where_clause'=>''),
                           array('type'=>'M-1',
                             'table'=>'survey_header',
                             'alias'=>'',
                             'link_parent'=>'survey_header_id',
                             'link_child'=>'survey_header_id',
                             'minimum'=>1,
                             'where_clause'=>''));

        return $relations;
    }

    static function load_subclass_info()
    {
        $subclasses = array('html_file'=>'employee_recommendation_html.php',
                            'html_class'=>'employee_recommendation_html',
                            'data_file'=>'employee_recommendation.php',
                            'data_class'=>'employee_recommendation');
        return $subclasses;
    }

}
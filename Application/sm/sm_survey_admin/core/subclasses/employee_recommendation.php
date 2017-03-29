<?php
require_once 'employee_recommendation_dd.php';
class employee_recommendation extends data_abstraction
{
    var $fields = array();


    function __construct()
    {
        $this->fields     = employee_recommendation_dd::load_dictionary();
        $this->relations  = employee_recommendation_dd::load_relationships();
        $this->subclasses = employee_recommendation_dd::load_subclass_info();
        $this->table_name = employee_recommendation_dd::$table_name;
        $this->tables     = employee_recommendation_dd::$table_name;
    }

    function add($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('INSERT');
            $this->set_fields('employee_recommendation_id, survey_header_id, employee_id');
            $this->set_values("?,?,?");

            $bind_params = array('iii',
                                 &$this->fields['employee_recommendation_id']['value'],
                                 &$this->fields['survey_header_id']['value'],
                                 &$this->fields['employee_id']['value']);

            $this->stmt_prepare($bind_params);
        }

        $this->stmt_execute();
        return $this;
    }

    function edit($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('UPDATE');
            $this->set_update("survey_header_id = ?, employee_id = ?");
            $this->set_where("employee_recommendation_id = ?");

            $bind_params = array('iii',
                                 &$this->fields['survey_header_id']['value'],
                                 &$this->fields['employee_id']['value'],
                                 &$this->fields['employee_recommendation_id']['value']);

            $this->stmt_prepare($bind_params);
        }
        $this->stmt_execute();

        return $this;
    }

    function delete($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("employee_recommendation_id = ?");

        $bind_params = array('i',
                             &$this->fields['employee_recommendation_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        return $this;
    }

    function delete_many($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("survey_header_id = ?");

        $bind_params = array('i',
                             &$this->fields['survey_header_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        return $this;
    }

    function select()
    {
        $this->set_query_type('SELECT');
        $this->exec_fetch('array');
        return $this;
    }

    function check_uniqueness($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('SELECT');
        $this->set_where("employee_recommendation_id = ?");

        $bind_params = array('i',
                             &$this->fields['employee_recommendation_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }

    function check_uniqueness_for_editing($param)
    {
        $this->set_parameters($param);


        $this->set_query_type('SELECT');
        $this->set_where("employee_recommendation_id = ? AND (employee_recommendation_id != ?)");

        $bind_params = array('ii',
                             &$this->fields['employee_recommendation_id']['value'],
                             &$this->fields['employee_recommendation_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }
}

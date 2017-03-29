<?php
require_once 'question_header_dd.php';
class question_header extends data_abstraction
{
    var $fields = array();


    function __construct()
    {
        $this->fields     = question_header_dd::load_dictionary();
        $this->relations  = question_header_dd::load_relationships();
        $this->subclasses = question_header_dd::load_subclass_info();
        $this->table_name = question_header_dd::$table_name;
        $this->tables     = question_header_dd::$table_name;
    }

    function add($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('INSERT');
            $this->set_fields('question_header_id, branch_id, question_header_description, question_type, is_active');
            $this->set_values("?,?,?,?,?");

            $bind_params = array('iisss',
                                 &$this->fields['question_header_id']['value'],
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['question_header_description']['value'],
                                 &$this->fields['question_type']['value'],
                                 &$this->fields['is_active']['value']);

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
            $this->set_update("branch_id = ?, question_header_description = ?, question_type = ?, is_active = ?");
            $this->set_where("question_header_id = ?");

            $bind_params = array('isssi',
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['question_header_description']['value'],
                                 &$this->fields['question_type']['value'],
                                 &$this->fields['is_active']['value'],
                                 &$this->fields['question_header_id']['value']);

            $this->stmt_prepare($bind_params);
        }
        $this->stmt_execute();

        return $this;
    }

    function delete($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("question_header_id = ?");

        $bind_params = array('i',
                             &$this->fields['question_header_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        return $this;
    }

    function delete_many($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("");

        $bind_params = array('',
                             );

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
        $this->set_where("question_header_id = ?");

        $bind_params = array('i',
                             &$this->fields['question_header_id']['value']);

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
        $this->set_where("question_header_id = ? AND (question_header_id != ?)");

        $bind_params = array('ii',
                             &$this->fields['question_header_id']['value'],
                             &$this->fields['question_header_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }
}

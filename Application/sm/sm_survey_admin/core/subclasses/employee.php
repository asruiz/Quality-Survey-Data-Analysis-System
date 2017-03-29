<?php
require_once 'employee_dd.php';
class employee extends data_abstraction
{
    var $fields = array();


    function __construct()
    {
        $this->fields     = employee_dd::load_dictionary();
        $this->relations  = employee_dd::load_relationships();
        $this->subclasses = employee_dd::load_subclass_info();
        $this->table_name = employee_dd::$table_name;
        $this->tables     = employee_dd::$table_name;
    }

    function add($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('INSERT');
            $this->set_fields('employee_id, branch_id, department_id, first_name, middle_name, last_name, gender, photo');
            $this->set_values("?,?,?,?,?,?,?,?");

            $bind_params = array('iiisssss',
                                 &$this->fields['employee_id']['value'],
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['department_id']['value'],
                                 &$this->fields['first_name']['value'],
                                 &$this->fields['middle_name']['value'],
                                 &$this->fields['last_name']['value'],
                                 &$this->fields['gender']['value'],
                                 &$this->fields['photo']['value']);

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
            $this->set_update("branch_id = ?, department_id = ?, first_name = ?, middle_name = ?, last_name = ?, gender = ?, photo = ?");
            $this->set_where("employee_id = ?");

            $bind_params = array('iisssssi',
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['department_id']['value'],
                                 &$this->fields['first_name']['value'],
                                 &$this->fields['middle_name']['value'],
                                 &$this->fields['last_name']['value'],
                                 &$this->fields['gender']['value'],
                                 &$this->fields['photo']['value'],
                                 &$this->fields['employee_id']['value']);

            $this->stmt_prepare($bind_params);
        }
        $this->stmt_execute();

        return $this;
    }

    function delete($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("employee_id = ?");

        $bind_params = array('i',
                             &$this->fields['employee_id']['value']);

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
        $this->set_where("employee_id = ?");

        $bind_params = array('i',
                             &$this->fields['employee_id']['value']);

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
        $this->set_where("employee_id = ? AND (employee_id != ?)");

        $bind_params = array('ii',
                             &$this->fields['employee_id']['value'],
                             &$this->fields['employee_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }
}

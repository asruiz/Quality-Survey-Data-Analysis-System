<?php
require_once 'branch_dd.php';
class branch extends data_abstraction
{
    var $fields = array();


    function __construct()
    {
        $this->fields     = branch_dd::load_dictionary();
        $this->relations  = branch_dd::load_relationships();
        $this->subclasses = branch_dd::load_subclass_info();
        $this->table_name = branch_dd::$table_name;
        $this->tables     = branch_dd::$table_name;
    }

    function add($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('INSERT');
            $this->set_fields('branch_id, branch_name, branch_address');
            $this->set_values("?,?,?");

            $bind_params = array('iss',
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['branch_name']['value'],
                                 &$this->fields['branch_address']['value']);

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
            $this->set_update("branch_name = ?, branch_address = ?");
            $this->set_where("branch_id = ?");

            $bind_params = array('ssi',
                                 &$this->fields['branch_name']['value'],
                                 &$this->fields['branch_address']['value'],
                                 &$this->fields['branch_id']['value']);

            $this->stmt_prepare($bind_params);
        }
        $this->stmt_execute();

        return $this;
    }

    function delete($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("branch_id = ?");

        $bind_params = array('i',
                             &$this->fields['branch_id']['value']);

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
        $this->set_where("branch_id = ?");

        $bind_params = array('i',
                             &$this->fields['branch_id']['value']);

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
        $this->set_where("branch_id = ? AND (branch_id != ?)");

        $bind_params = array('ii',
                             &$this->fields['branch_id']['value'],
                             &$this->fields['branch_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }
}

<?php
require_once 'survey_header_dd.php';
class survey_header extends data_abstraction
{
    var $fields = array();


    function __construct()
    {
        $this->fields     = survey_header_dd::load_dictionary();
        $this->relations  = survey_header_dd::load_relationships();
        $this->subclasses = survey_header_dd::load_subclass_info();
        $this->table_name = survey_header_dd::$table_name;
        $this->tables     = survey_header_dd::$table_name;
    }

    function add($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('INSERT');
            $this->set_fields('survey_header_id, branch_id, survey_number, room_number, date_submitted, guest_name, guest_age, guest_address, guest_check_in, guest_check_out, include_in_mailing_list');
            $this->set_values("?,?,?,?,?,?,?,?,?,?,?");

            $bind_params = array('iisssssssss',
                                 &$this->fields['survey_header_id']['value'],
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['survey_number']['value'],
                                 &$this->fields['room_number']['value'],
                                 &$this->fields['date_submitted']['value'],
                                 &$this->fields['guest_name']['value'],
                                 &$this->fields['guest_age']['value'],
                                 &$this->fields['guest_address']['value'],
                                 &$this->fields['guest_check_in']['value'],
                                 &$this->fields['guest_check_out']['value'],
                                 &$this->fields['include_in_mailing_list']['value']);

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
            $this->set_update("branch_id = ?, survey_number = ?, room_number = ?, date_submitted = ?, guest_name = ?, guest_age = ?, guest_address = ?, guest_check_in = ?, guest_check_out = ?, include_in_mailing_list = ?");
            $this->set_where("survey_header_id = ?");

            $bind_params = array('isssssssssi',
                                 &$this->fields['branch_id']['value'],
                                 &$this->fields['survey_number']['value'],
                                 &$this->fields['room_number']['value'],
                                 &$this->fields['date_submitted']['value'],
                                 &$this->fields['guest_name']['value'],
                                 &$this->fields['guest_age']['value'],
                                 &$this->fields['guest_address']['value'],
                                 &$this->fields['guest_check_in']['value'],
                                 &$this->fields['guest_check_out']['value'],
                                 &$this->fields['include_in_mailing_list']['value'],
                                 &$this->fields['survey_header_id']['value']);

            $this->stmt_prepare($bind_params);
        }
        $this->stmt_execute();

        return $this;
    }

    function delete($param)
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
        $this->set_where("survey_header_id = ?");

        $bind_params = array('i',
                             &$this->fields['survey_header_id']['value']);

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
        $this->set_where("survey_header_id = ? AND (survey_header_id != ?)");

        $bind_params = array('ii',
                             &$this->fields['survey_header_id']['value'],
                             &$this->fields['survey_header_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }
}

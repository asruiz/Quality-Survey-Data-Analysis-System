<?php
require_once 'Loan_dd.php';
class Loan extends data_abstraction
{
    var $fields = array();

    var $db_use='cgsclib_db';

    function Loan()
    {
        $this->fields     = Loan_dd::load_dictionary();
        $this->relations  = Loan_dd::load_relationships();
        $this->subclasses = Loan_dd::load_subclass_info();
        $this->table_name = Loan_dd::$table_name;
        $this->tables     = Loan_dd::$table_name;
    }

    function add($param)
    {
        $this->set_parameters($param);

        if($this->stmt_template=='')
        {
            $this->set_query_type('INSERT');
            $this->set_fields('id, member_id, accession_no, loan_datetime, due_date, return_datetime, extension_due_date, extension_count, remarks, loan_rule_id, is_lent, is_extended, is_returned');
            $this->set_values("?,?,?,?,?,?,?,?,?,?,?,?,?");

            $bind_params = array('issssssisiiii',
                                 &$this->fields['id']['value'],
                                 &$this->fields['member_id']['value'],
                                 &$this->fields['accession_no']['value'],
                                 &$this->fields['loan_datetime']['value'],
                                 &$this->fields['due_date']['value'],
                                 &$this->fields['return_datetime']['value'],
                                 &$this->fields['extension_due_date']['value'],
                                 &$this->fields['extension_count']['value'],
                                 &$this->fields['remarks']['value'],
                                 &$this->fields['loan_rule_id']['value'],                                 
                                 &$this->fields['is_lent']['value'],
                                 &$this->fields['is_extended']['value'],
                                 &$this->fields['is_returned']['value']);

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
            $this->set_update("member_id = ?, accession_no = ?, loan_datetime = ?, due_date = ?, return_datetime = ?, extension_due_date = ?, extension_count = ?, remarks = ?, loan_rule_id = ?, is_lent = ?, is_extended = ?, is_returned = ?");
            $this->set_where("id = ?");

            $bind_params = array('ssssssisiiiii',
                                 &$this->fields['member_id']['value'],
                                 &$this->fields['accession_no']['value'],
                                 &$this->fields['loan_datetime']['value'],
                                 &$this->fields['due_date']['value'],
                                 &$this->fields['return_datetime']['value'],  
                                 &$this->fields['extension_due_date']['value'],
                                 &$this->fields['extension_count']['value'],
                                 &$this->fields['remarks']['value'],
                                 &$this->fields['loan_rule_id']['value'],                                                                
                                 &$this->fields['is_lent']['value'],
                                 &$this->fields['is_extended']['value'],
                                 &$this->fields['is_returned']['value'],
                                 &$this->fields['id']['value']);

            $this->stmt_prepare($bind_params);
        }
        $this->stmt_execute();

        return $this;
    }

    function delete($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('DELETE');
        $this->set_where("id = ?");

        $bind_params = array('i',
                             &$this->fields['id']['value']);

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
        $this->set_where("id = ?");

        $bind_params = array('i',
                             &$this->fields['id']['value']);

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
        $this->set_where("id = ? AND (id != ?)");

        $bind_params = array('ii',
                             &$this->fields['id']['value'],
                             &$this->fields['id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_execute();
        $this->stmt_close();

        if($this->num_rows > 0) $this->is_unique = FALSE;
        else $this->is_unique = TRUE;

        return $this;
    }
    
    function select_current_loans($param)
    {        
        $this->set_parameters($param);
        $this->set_query_type('SELECT');
        $this->set_fields('CatalogItem.id,
                            CatalogItem.catalog_id,
                            CatalogItem.accession_no,
                            Catalog.title,
                            Catalog.synopsis,
                            Catalog.series_title,
                            Catalog.edition,
                            Catalog.collation, 
                            Catalog.language_id,
                            Catalog.isbn,
                            Catalog.classification,
                            Catalog.content_type_id,
                            Catalog.media_type_id,                                                         
                            Catalog.image_file, 
                            Catalog.notes, 
                            Catalog.physical_file,
                            Catalog.physical_description,
                            Catalog.link,
                            Catalog.image_file,
                            Publisher.`name`,
                            Catalog.publishing_place, 
                            Catalog.publishing_year, 
                            CatalogAuthor.author_name, 
                            Catalog.call_number,
                            CatalogItem.inventory_code,
                            CatalogItem.location,
                            CatalogItem.shelf_location_id,
                            CatalogItem.collection_type_id,
                            CollectionType.collection_type,
                            CatalogItem.item_status_id,
                            CatalogItem.order_number,
                            CatalogItem.order_date,
                            CatalogItem.receiving_date,
                            CatalogItem.item_source_id,
                            CatalogItem.invoice,
                            CatalogItem.invoice_date,
                            CatalogItem.price,
                            CatalogItem.price_currency_id,
                            Loan.member_id,
                            Loan.loan_datetime,
                            Loan.due_date');
        $this->set_table('Catalog
                            INNER JOIN CatalogItem ON Catalog.id = CatalogItem.catalog_id
                            LEFT JOIN CollectionType ON CatalogItem.collection_type_id = CollectionType.id
                            LEFT JOIN Loan ON Loan.accession_no = CatalogItem.accession_no
                            LEFT JOIN Publisher ON Catalog.publisher_id = Publisher.id 
                            LEFT JOIN 
                            (SELECT * FROM CatalogAuthor WHERE author_role_id = 1) CatalogAuthor ON Catalog.id = CatalogAuthor.catalog_id ');
        $this->set_where("Loan.member_id = ? AND 
                          NOT Loan.id IS NULL AND
                          Loan.is_lent = 1 AND 
                          Loan.is_returned = 0");
                          
        $bind_params = array('s',                             
                             &$this->fields['member_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_fetch();
        $this->stmt_close();                

        return $this;
    }
    
    function select_history_loans($param)
    {
        $this->set_parameters($param);
        $this->set_query_type('SELECT');
        $this->set_fields('CatalogItem.id,
                            CatalogItem.catalog_id,
                            CatalogItem.accession_no,
                            Catalog.title,
                            Catalog.synopsis,
                            Catalog.series_title,
                            Catalog.edition,
                            Catalog.collation, 
                            Catalog.language_id,
                            Catalog.isbn,
                            Catalog.classification,
                            Catalog.content_type_id,
                            Catalog.media_type_id,                                                         
                            Catalog.image_file, 
                            Catalog.notes, 
                            Catalog.physical_file,
                            Catalog.physical_description,
                            Catalog.link,
                            Catalog.image_file,
                            Publisher.`name`,
                            Catalog.publishing_place, 
                            Catalog.publishing_year, 
                            CatalogAuthor.author_name, 
                            Catalog.call_number,
                            CatalogItem.inventory_code,
                            CatalogItem.location,
                            CatalogItem.shelf_location_id,
                            CatalogItem.collection_type_id,
                            CollectionType.collection_type,
                            CatalogItem.item_status_id,
                            CatalogItem.order_number,
                            CatalogItem.order_date,
                            CatalogItem.receiving_date,
                            CatalogItem.item_source_id,
                            CatalogItem.invoice,
                            CatalogItem.invoice_date,
                            CatalogItem.price,
                            CatalogItem.price_currency_id,
                            Loan.member_id,
                            Loan.loan_datetime,
                            Loan.return_datetime');
        $this->set_table('Catalog
                            INNER JOIN CatalogItem ON Catalog.id = CatalogItem.catalog_id
                            LEFT JOIN CollectionType ON CatalogItem.collection_type_id = CollectionType.id
                            LEFT JOIN Loan ON Loan.accession_no = CatalogItem.accession_no
                            LEFT JOIN Publisher ON Catalog.publisher_id = Publisher.id 
                            LEFT JOIN 
                            (SELECT * FROM CatalogAuthor WHERE author_role_id = 1) CatalogAuthor ON Catalog.id = CatalogAuthor.catalog_id ');
        $this->set_where("Loan.member_id = ? AND 
                            NOT Loan.id IS NULL AND 
                            Loan.is_lent = 0 AND 
                            Loan.is_returned = 1");

        $bind_params = array('s',                             
                             &$this->fields['member_id']['value']);

        $this->stmt_prepare($bind_params);
        $this->stmt_fetch();
        $this->stmt_close();

        return $this;
    }

    function select_loan_summary()
    {
        $this->set_query_type('SELECT');
        $this->set_fields('a.lent_count,
                            b.extended_count, 
                            c.returned_count,
                            d.overdue_count,  
                            (a.lent_count + b.extended_count + c.returned_count) AS total');
        $this->set_table("(SELECT
                                COUNT(id) AS lent_count 
                            FROM
                                Loan
                            WHERE 
                                is_lent = 1) a,
                            (SELECT
                                COUNT(id) AS extended_count 
                            FROM
                                Loan
                            WHERE 
                                is_extended = 1) b,
                            (SELECT
                                COUNT(id) AS returned_count 
                            FROM
                                Loan
                            WHERE 
                                is_returned = 1) c, 
                            (SELECT 
                                COUNT(id) AS overdue_count
                            FROM 
                                Loan 
                            WHERE 
                                due_date < CURDATE()) d");
        $this->exec_fetch('array');
        return $this;
    }

    function select_loan_latest_transactions()
    {
        $start_date = date('Y-m-d', strtotime("last Monday"));
        $end_date = date('Y-m-d', strtotime("last Monday +6 day"));
        $current_date = date('Y-m-d');

        $this->set_query_type('SELECT');
        $this->set_fields('a.datetime, 
                            IFNULL(a.loan_count,0) AS loan_count,
                            IFNULL(b.overdue_count,0) AS overdue_count,
                            IFNULL(c.extended_count,0) AS extended_count,
                            IFNULL(d.returned_count,0) AS returned_count');
        $this->set_table("(SELECT
                                a.datetime,
                                IFNULL(b.loan_count, 0) AS loan_count 
                            FROM
                                (SELECT '$start_date' + INTERVAL a + b DAY datetime
                                    FROM
                                    (SELECT 0 a UNION SELECT 1 a UNION SELECT 2 UNION SELECT 3
                                        UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
                                        UNION SELECT 8 UNION SELECT 9 ) d,
                                    (SELECT 0 b UNION SELECT 10 UNION SELECT 20 
                                        UNION SELECT 30 UNION SELECT 40) m
                                    WHERE '$start_date' + INTERVAL a + b DAY  <=  '$end_date') a
                            LEFT JOIN 
                            (SELECT
                                    DATE(Loan.loan_datetime) AS datetime,
                                    COUNT(Loan.id) AS loan_count
                                FROM
                                    Loan
                                WHERE
                                    DATE(Loan.loan_datetime) >= '$start_date'
                                AND DATE(Loan.loan_datetime) <= '$end_date'
                                AND Loan.is_lent = 1
                                GROUP BY
                                    DATE(Loan.loan_datetime)) b ON a.datetime = b.datetime) a 
                            LEFT JOIN 
                                (SELECT 
                                    DATE(Loan.due_date) AS datetime, 
                                    COUNT(Loan.id) AS overdue_count 
                                FROM 
                                    Loan 
                                WHERE 
                                    DATE(Loan.due_date) < '$current_date' AND 
                                    Loan.is_lent = 1 AND 
                                    Loan.is_returned = 0
                                GROUP BY 
                                    DATE(Loan.loan_datetime)) b ON a.datetime = b.datetime 
                            LEFT JOIN 
                                (SELECT 
                                    DATE(Loan.extension_due_date) AS datetime, 
                                    COUNT(Loan.id) AS extended_count 
                                FROM 
                                    Loan 
                                WHERE 
                                    DATE(Loan.loan_datetime) >= '$start_date' AND 
                                    DATE(Loan.loan_datetime) <= '$end_date' AND 
                                    Loan.is_extended = 1
                                GROUP BY 
                                    DATE(Loan.loan_datetime)) c ON a.datetime = c.datetime
                            LEFT JOIN 
                                (SELECT 
                                    DATE(Loan.return_datetime) AS datetime, 
                                    COUNT(Loan.id) AS returned_count 
                                FROM 
                                    Loan 
                                WHERE 
                                    DATE(Loan.return_datetime) >= '$start_date' AND 
                                    DATE(Loan.return_datetime) <= '$end_date' AND 
                                    Loan.is_returned = 1
                                GROUP BY 
                                    DATE(Loan.loan_datetime)) d ON a.datetime = d.datetime");
        $this->set_group_by('DATE(a.datetime)');
        $this->set_order('DATE(a.datetime)');
        $this->exec_fetch('array');
        return $this;
    }

    function select_loan_latest_transactions_per_member($member_id='')
    {
        $start_date = date('Y-m-d', strtotime("last Monday"));
        $end_date = date('Y-m-d', strtotime("last Monday +10 day"));
        $current_date = date('Y-m-d');

        $this->set_query_type('SELECT');
        $this->set_fields('a.datetime, 
                            IFNULL(a.loan_count,0) AS loan_count,
                            IFNULL(b.overdue_count,0) AS overdue_count,
                            IFNULL(c.extended_count,0) AS extended_count,
                            IFNULL(d.returned_count,0) AS returned_count');
        $this->set_table("(SELECT
                                a.datetime,
                                IFNULL(b.loan_count, 0) AS loan_count 
                            FROM
                                (SELECT '$start_date' + INTERVAL a + b DAY datetime
                                    FROM
                                    (SELECT 0 a UNION SELECT 1 a UNION SELECT 2 UNION SELECT 3
                                        UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
                                        UNION SELECT 8 UNION SELECT 9 ) d,
                                    (SELECT 0 b UNION SELECT 10 UNION SELECT 20 
                                        UNION SELECT 30 UNION SELECT 40) m
                                    WHERE '$start_date' + INTERVAL a + b DAY  <=  '$end_date') a
                            LEFT JOIN 
                            (SELECT
                                    DATE(Loan.loan_datetime) AS datetime,
                                    COUNT(Loan.id) AS loan_count
                                FROM
                                    Loan
                                WHERE
                                    DATE(Loan.loan_datetime) >= '$start_date'
                                AND DATE(Loan.loan_datetime) <= '$end_date'
                                AND Loan.is_lent = 1
                                AND Loan.member_id = '$member_id'
                                GROUP BY
                                    DATE(Loan.loan_datetime)) b ON a.datetime = b.datetime) a 
                            LEFT JOIN 
                                (SELECT 
                                    DATE(Loan.due_date) AS datetime, 
                                    COUNT(Loan.id) AS overdue_count 
                                FROM 
                                    Loan 
                                WHERE 
                                    DATE(Loan.due_date) < '$current_date' 
                                    AND Loan.is_lent = 1 
                                    AND Loan.is_returned = 0
                                    AND Loan.member_id = '$member_id'
                                GROUP BY 
                                    DATE(Loan.loan_datetime)) b ON a.datetime = b.datetime 
                            LEFT JOIN 
                                (SELECT 
                                    DATE(Loan.extension_due_date) AS datetime, 
                                    COUNT(Loan.id) AS extended_count 
                                FROM 
                                    Loan 
                                WHERE 
                                    DATE(Loan.loan_datetime) >= '$start_date'  
                                    AND DATE(Loan.loan_datetime) <= '$end_date' 
                                    AND Loan.is_extended = 1
                                    AND Loan.member_id = '$member_id'
                                GROUP BY 
                                    DATE(Loan.loan_datetime)) c ON a.datetime = c.datetime
                            LEFT JOIN 
                                (SELECT 
                                    DATE(Loan.return_datetime) AS datetime, 
                                    COUNT(Loan.id) AS returned_count 
                                FROM 
                                    Loan 
                                WHERE 
                                    DATE(Loan.return_datetime) >= '$start_date' 
                                    AND DATE(Loan.return_datetime) <= '$end_date' 
                                    AND Loan.is_returned = 1
                                    AND Loan.member_id = '$member_id'
                                GROUP BY 
                                    DATE(Loan.loan_datetime)) d ON a.datetime = d.datetime");
        $this->set_group_by('DATE(a.datetime)');
        $this->set_order('DATE(a.datetime)');
        $this->exec_fetch('array');
        
        return $this;
    }

    function select_member_current_loan_count($param)
    {
        $this->set_query_type('SELECT');
        $this->set_fields('COUNT(*) AS loan_count');
        $this->set_where("member_id = '" . $param['member_id'] . "' AND 
                            Loan.is_lent = 1 AND 
                            Loan.is_returned = 0");                
        $this->exec_fetch('single');
        return $this;
    }
}

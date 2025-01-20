<?php
class Database
{
    // privat
    var $gs_DBName;
    var $gs_DBUser;
    var $gs_DBPass;
    var $query = "";
    var $stmt = "";
    var $conn = "";
    var $error = true;
    var $version = "";
    var $errorcode = ""; // error number
    var $errormessage = ""; // error number
    var $errorstring = ""; // komplete html error
    var $numcols;
    var $max_persistent;
    //var $fied;
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR')) $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED')) $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR')) $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED')) $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR')) $ipaddress = getenv('REMOTE_ADDR');
        else $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    //adjustment oci_connect
    function __construct($User = "", $Pass = "", $DB = "", $Server = 0)
    {
        $this->gs_DBUser = $User;
        $this->gs_DBPass = $Pass;
        $this->gs_DBName = $DB;

        if (!isset($GLOBALS[md5($this->gs_DBUser . $this->gs_DBPass . $this->gs_DBName) ]) || !$GLOBALS[md5($this->gs_DBUser . $this->gs_DBPass . $this->gs_DBName) ])
        {
            $GLOBALS[md5($this->gs_DBUser . $this->gs_DBPass . $this->gs_DBName) ] = @oci_pconnect($this->gs_DBUser, $this->gs_DBPass, $this->gs_DBName);
            @oci_set_client_info($GLOBALS[md5($this->gs_DBUser . $this->gs_DBPass . $this->gs_DBName) ], $this->get_client_ip() . '||' . $_SESSION["USER"] . '||' . $_SESSION['KODE_FORM_AKSES'] . '||' . date("Y/m/d H:i:s"));
        }
        $this->conn = $GLOBALS[md5($this->gs_DBUser . $this->gs_DBPass . $this->gs_DBName) ];

        if (!$this->conn)
        {
            $this->error($this->conn);
        }

        $this->version = @OCIServerVersion($this->conn);
    }

    /**Define by Name*/
    function definebyname($stmt, $field = "")
    {
        $this->field = $field;
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        $this->stmt = $stmt;
        $this->fied = strtoupper($field);
        @oci_define_by_name($this->stmt, $this->fied, $this->field);

        return $this->stmt;
    }

    /**Fetch bu Kampretz*/
    function fetch($stmt)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        $this->stmt = $stmt;
        @oci_fetch($this->stmt);
        return $this->error();
    }

    /** build error output
     *    type can be stmt|conn|global
     */
    function error($type = "")
    {
        if (!$type)
        {
            $type = $this->stmt;
        }
        if ($type == false) 
        {
            $type = null;
        }

        $error = @oci_error($type);

        if ($error)
        {
            $errorstring = "<br>\nOCIError: " . $error["code"] . " " . $error["message"] . " <br>\nAction: " . $this->query . "<br>\n";
            $this->errorstring = $errorstring;
            //trace(2,__LINE__,get_class($this), $errorstring);
            $this->errorcode = $error["code"];
            $this->errormessage = $error["message"];
            $this->error = false;
            return false;

        }
        else
        {
            $this->errorcode = false;
            $this->errormessage = false;
            $this->error = true;
            return true;
        }
    }

    /** parse a query and return a statement */
    function parse($query)
    {
        $this->query = $query;
        $stmt = oci_parse($this->conn, $query);
        $this->stmt = $stmt;
        $this->error();
        return $stmt;
    }

    /** executes a statement */
    function execute($stmt = "", $param = OCI_COMMIT_ON_SUCCESS)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        if ($stmt == false) {
            return $this->error();
        }
        oci_execute($stmt, $param);
        return $this->error();
    }

    function close() {
        @oci_close($this->conn);
    }

    /** Commit the outstanding transaction */
    function commit()
    {
        @oci_commit($this->conn);
        return $this->error();
    }

    /** Commit the outstanding transaction */
    function rollback()
    {
        @oci_rollback($this->conn);
        return $this->error();
    }

    /** returns array of assoc array's */
    function result($stmt = false, $from = false, $to = false)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        $result = array();
        if (!$from && !$to)
        {
            // while (@oci_fetch_row($stmt, $arr, OCI_ASSOC + OCI_RETURN_NULLS))
            while ($arr = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS))
            {
                $result[] = $arr;
            }
        }
        else
        {
            $counter = 0;
            // while (@oci_fetch_row($stmt, $arr, OCI_ASSOC + OCI_RETURN_NULLS))
            while ($arr = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS))
            {
                if ($counter >= $from && $counter <= $to)
                {
                    $result[] = $arr;
                }
                $counter++;
            }
        }
        @oci_free_statement($stmt);
        return $result;
    }

    /** return thge the next row based upon @ocifetchinto($stmt,$arr,OCI_ASSOC+OCI_RETURN_NULLS) */
    function nextrow($stmt = false, $param = false)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        if ($stmt == false) {
            return false;
        }
        if (!$param)
        {
            $param = OCI_ASSOC + OCI_RETURN_NULLS;
        }
        $res = oci_fetch_array($stmt, $param);
        return $res;
    }

    /** returns rownum of affected rows */

    function affected($stmt = false)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        return @oci_num_rows($stmt);
    }

    function numcols($stmt = false)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        return @oci_num_fields($stmt);
    }

    /** returns type of field */
    function fieldtype($field, $stmt = false)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        return @oci_field_type($stmt, $field);
    }

    /** returns type of field */
    function fieldsize($field, $stmt = false)
    {
        if (!$stmt)
        {
            $stmt = $this->stmt;
        }
        return @oci_field_size($stmt, $field);
    }

    /** returns single row array */
    function get_row_data($stmt)
    {
        $this->parse($stmt);
        $this->execute();
        $row = $this->nextrow();
        return (isset($row)) ? $row : null;
    }

    /** returns first row single field string value */
    function get_data($stmt)
    {
        $this->parse($stmt);
        //$this->stmt = $stmt;
        $this->execute();
        $row = $this->nextrow();
        if (isset($row) && !empty($row) && count($row) > 0)
        {
            $arr_key = @array_keys($row);
            if ($arr_key) $value = $row[$arr_key[0]];
        }
        return (isset($value)) ? $value : '';
    }
    /**  insert file to blob table - gw 12 Desember 2017*/
    public function insertBlob($sql, $action, $blobbindname, $blobdata, $otherbindvars = array())
    {
        $stmt = @oci_parse($this->conn, $sql);
        $dlob = @oci_new_descriptor($this->conn, OCI_D_LOB);
        @oci_bind_by_name($stmt, $blobbindname, $dlob, -1, OCI_B_BLOB);
        foreach ($otherbindvars as $bv)
        {
            // oci_bind_by_name(resource, bv_name, php_variable, length)
            @oci_bind_by_name($stmt, $bv[0], $bv[1], $bv[2]);
        }
        //@oci_set_action($this->conn, $action);
        @oci_execute($stmt, OCI_DEFAULT);
        if ($dlob->save($blobdata . date('H:i:s', time())))
        {
            @oci_commit($this->conn);
            return true;
        }
        else
        {
            @oci_rollback($DB->conn);
            return false;
        }
    }

    /**destructor */
     function __destruct() {
        /*if ($this->stmt)
        {
            @oci_free_statement($this->stmt);
        }*/
        @oci_close($this->conn);
    }
    /*function destruct()
    {
        @oci_free_statement($this->stmt);
        @oci_close($this->conn);

        return true;
    }*/
} //class Database

?>

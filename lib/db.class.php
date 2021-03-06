<?php

class DB {
    var $db_host;
    var $db_user;
    var $db_password;
    var $db_name;
    var $_conn;

    function __construct($config) {
        $this->db_host = $config['db_host'];
        $this->db_user = $config['db_user'];
        $this->db_password = $config['db_password'];
        $this->db_name = $config['db_name'];
    }

    function escape($s) {
        if (empty($this->_conn)) $this->connect();
        return $this->_conn->real_escape_string($s);
    }

    function quote($data) {
        if (empty($this->_conn)) $this->connect();
        if (is_null($data)) return "NULL";
        if (is_numeric($data)) return (string) $data;
        if (is_string($data)) return "'" . $this->escape($data) . "'";
        throw new DatabaseException("Cannot quote value of type " . gettype($data));
    }

    function query($sql) {
        if (empty($this->_conn)) $this->connect();
        $result = $this->_conn->query($sql);
        if (!$result) {
            throw new DatabaseException($this->_conn->error, $sql);
        }
        return $result;
    }

    function execute($sql) {
        $this->query($sql);
        return $this->_conn->affected_rows;
    }

    function select_row($sql) {
        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        $result->close();
        if (!$row) return null;
        return $row;
    }

    function select_rows($sql) {
        $result = $this->query($sql);
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->close();
        return $rows;
    }

    function select_map($sql) {
        $result = $this->query($sql);
        $map = array();
        while ($row = $result->fetch_row()) {
            $map[$row[0]] = $row[1];
        }
        $result->close();
        return $map;
    }

    function select_list($sql) {
        $result = $this->query($sql);
        $list = array();
        while ($row = $result->fetch_row()) {
            $list[] = $row[0];
        }
        $result->close();
        return $list;
    }

    function select_value($sql) {
        $result = $this->query($sql);
        $row = $result->fetch_row();
        $result->close();
        if (!$row) return null;
        return $row[0];
    }

    function insert($table, $values) {
        foreach ($values as $key => $value) {
            $clauses[] = "$key=" . $this->quote($value);
        }
        return $this->execute("INSERT INTO $table SET " . join(', ', $clauses));
    }
    
    function connect() {
        $this->_conn = @new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
        if (mysqli_connect_errno()) {
            throw new DatabaseException(mysqli_connect_error());
        }
        if (!$this->_conn->set_charset("utf8")) {
            throw new DatabaseException($this->_conn->error, 'mysqli::set_charset');
        }
    }
}

class DatabaseException extends Exception {
    var $sql;

    function __construct($message = null, $query = null) {
        parent::__construct($message);
        $this->sql = $query;
    }

    function getQuery() {
        return $this->sql;
    }
}

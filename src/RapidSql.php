<?php

namespace Rapid\Sql;
use Json\Bash\JsonBash;

class RapidSql
{

    private $connection;
    private $JsonBash;
    private $query;
    private $error_flag;

    // Generate Database Connection from constructor
    function __construct($servername="", $username="", $password="", $databasename=""){

        $connected_database  = $this->database_connect($servername,$username,$password,$databasename);
        // Check Database Connected Successfully
        if($connected_database!=true){
            die("Connection failed: " . $connected_database);
        }
        $this->JsonBash =  new JsonBash();
        $this->error_flag=false;
    }

    //Connect With Database
    function database_connect($servername="",$username="",$password="",$databasename=""){
        $this->connection = new \MySQLi($servername, $username, $password, $databasename);
        if ($this->connection->connect_error) {
            return $this->connection->connect_error;
        }else{
            return true;
        }
    }

    function execute_query($query="",$data=[]){
        try{
            if($query!=""){
                $execute = $this->connection->prepare($query);
                if(!empty($data)){
                    $execute->bind_param(str_repeat("s", count($data)),...$data);
                }
                // if (!$tqry) {
                //     throw new Exception($mysqli->error);
                // }
                $execute->execute();
                return $execute;
            }else{
                return [];
            }
        }catch (Exception | ErrorException | Error | ArgumentCountError | ArithmeticError | AssertionError | DivisionByZeroError | CompileError | ParseError | TypeError $e) { 
            $this->error_flag=true;
            return [];
        }
    }

    function getData($row="",$table="",$where="",$group="",$order="",$data=[],$exit=0){
        $this->query = 'SELECT';
        if($row!=""){
            $this->query .= $row;
        }
        if($table!=""){
            $this->query .= ' FROM ' .$table; 
        }
        if($where!=""){
            $this->query .= ' WHERE ' .$where;
        }
        if($group!=""){
            $this->query .= ' GROUP BY ' .$group;
        }
        if($order!=""){
            $this->query .= ' ORDER BY ' .$order;
        }
        if($exit!=0){
            return $this->query;
        }
        $result = $this->execute_query($this->query,$data);
        return $result->get_result()->fetch_all();
    }
    
    function getData_R($row="",$table="",$where="",$group="",$order="",$data=[],$exit=0){
        $result = $this->getData($row,$table,$where,$group,$order,$data,$exit);
        if($exit!=0){
            return $result;
        }
        if($this->error_flag){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong",$result);
        }
        return $this->JsonBash->jsonmanager(true,"Data Fetch Successfully",$result);
    }

    function getJoinData($row="",$table="",$join="",$where="",$group="",$order="",$data=[],$exit=0){
        $this->query = 'SELECT';
        if($row!=""){
            $this->query .= $row;
        }
        if($table!=""){
            $this->query .= ' FROM ' .$table; 
        }
        if($join!=""){
            $this->query .= ' '.$join.' '; 
        }
        if($where!=""){
            $this->query .= ' WHERE ' .$where;
        }
        if($group!=""){
            $this->query .= ' GROUP BY ' .$group;
        }
        if($order!=""){
            $this->query .= ' ORDER BY ' .$order;
        }
        if($exit!=0){
            return $this->query;
        }
        $result = $this->execute_query($this->query,$data);
        return $result->get_result()->fetch_all();
    }
    function getJoinData_R($row="",$table="",$join="",$where="",$group="",$order="",$data=[],$exit=0){
        $result = $this->getJoinData($row,$table,$join,$where,$group,$order,$data,$exit);
        if($exit!=0){
            return $result;
        }
        if($this->error_flag){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong",$result);
        }
        return $this->JsonBash->jsonmanager(true,"Data Fetch Successfully",$result);
    }

    function insertData($table="",$data=[],$exit=0){
        $this->query = ' INSERT INTO ';
        if($table!=""){
            $this->query .= $table." ";
        }
        if(array_keys($data) !== range(0, count($data) - 1)){
            $insert_data = [];
            $this->query .= "(";
            foreach($data as $key => $value) { 
                $this->query .= $key.","; 
                array_push($insert_data,$value);
            }
            $this->query = substr($this->query, 0, -1);
            $this->query .= ") VALUES (";
            $this->query .= str_repeat("?,", count($data));
            $this->query = substr($this->query, 0, -1);
            $this->query .= ")";
        }
        if($exit!=0){
            return $this->query;
        }
        $result = $this->execute_query($this->query,$insert_data);
        $result_array = array();
        foreach($result as $key => $res){
            $result_array[$key] = $result->$key;
        }
        if($result_array["affected_rows"] <= 0){
            return false;
        }
        return $result_array;
    }
    function insertData_R($table="",$data=[],$exit=0){
        $result = $this->insertData($table,$data,$exit);
        if($exit!=0){
            return $result;
        }
        if($this->error_flag){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong",$result);
        }
        if(!$result){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong Data is Not Saved",[]);
        }
        return $this->JsonBash->jsonmanager(true,"Data Inserted Sucessfull",$result);
    }

    function insertMultiData($table="",$column=[],$data=[],$exit=0){
        $this->query = ' INSERT INTO ';
        if($table!=""){
            $this->query .= $table." ";
        }
        if(!empty($column)){
            $this->query .= "(";
            foreach($column as $col_name){
                $this->query .= $col_name.",";
            }
            $this->query = substr($this->query, 0, -1);
            $this->query .= ")";
        }
        if(!empty($data)){
            $insert_data = array();
            $this->query .= " VALUES ";
            foreach($data as $row){
                $this->query .= "(";
                foreach($row as $single_val){
                    $this->query .= "?,";
                    array_push($insert_data,$single_val);
                }
                $this->query = substr($this->query, 0, -1);
                $this->query .= "),";
            }
            $this->query = substr($this->query, 0, -1);
        }
        if($exit!=0){
            return $this->query;
        }
        $result = $this->execute_query($this->query,$insert_data);
        $result_array = array();
        foreach($result as $key => $res){
            $result_array[$key] = $result->$key;
        }
        if($result_array["affected_rows"] <= 0){
            return false;
        }
        return $result_array;
    }
    function insertMultiData_R($table="",$column=[],$data=[],$exit=0){
        $result = $this->insertMultiData($table,$column,$data,$exit);
        if($exit!=0){
            return $result;
        }
        if($this->error_flag){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong",$result);
        }
        if(!$result){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong Data is Not Saved",[]);
        }
        return $this->JsonBash->jsonmanager(true,"Data Inserted Successfully",$result);
    }

    function updateData($table="",$data=[],$where="",$where_data=[],$exit=0){
        $this->query = ' UPDATE ';
        if($table!=""){
            $this->query .= $table." ";
        }
        if(array_keys($data) !== range(0, count($data) - 1)){
            $update_data = [];
            $this->query .= " SET ";
            foreach($data as $key => $value) { 
                $this->query .= $key."=?,"; 
                array_push($update_data,$value);
            }
            $this->query = substr($this->query, 0, -1);
        }
        if($where!=""){
            $this->query .= ' WHERE ' .$where;
            $update_data = array_merge($update_data,$where_data);
        }
        if($exit!=0){
            return $this->query;
        }
        $result = $this->execute_query($this->query,$update_data);
        $result_array = array();
        foreach($result as $key => $res){
            $result_array[$key] = $result->$key;
        }
        if($result_array["affected_rows"] <= 0){
            return false;
        }
        return $result_array;
    }
    function updateData_R($table="",$data=[],$where="",$where_data=[],$exit=0){
        $result = $this->updateData($table,$data,$where,$where_data,$exit);
        if($exit!=0){
            return $result;
        }
        if($this->error_flag){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong",$result);
        }
        if(!$result){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong Data is Not Updated",[]);
        }
        return $this->JsonBash->jsonmanager(true,"Data Update Successfully",$result);
    }

    function deleteData($table="",$where="",$data=[],$exit=0){
        $this->query = ' DELETE FROM ';
        if($table!=""){
            $this->query .= $table." ";
        }
        if($where!=""){
            $this->query .= ' WHERE ' .$where;
        }
        if($exit!=0){
            return $this->query;
        }
        $result = $this->execute_query($this->query,$data);
        $result_array = array();
        foreach($result as $key => $res){
            $result_array[$key] = $result->$key;
        }
        if($result_array["affected_rows"] <= 0){
            return false;
        }
        return $result_array;
    }
    function deleteData_R($table="",$where="",$data=[],$exit=0){
        $result = $this->deleteData($table,$where,$data,$exit);
        if($exit!=0){
            return $result;
        }
        if($this->error_flag){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong",$result);
        }
        if(!$result){
            return $this->JsonBash->jsonmanager(false,"Something is Wrong Data is Not Deleted",[]);
        }
        return $this->JsonBash->jsonmanager(true,"Data Deleted Sucessfully",$result);
    }

}
<?php
    class Database{
        protected $connect;
        protected $database;
        protected $table;
        protected $resultQuery;

        public function __construct($param){
            $link = @mysqli_connect($param['server'], $param['username'], $param['password']);
            if(!$link){
                die('Fail connect: ' . mysqli_error());
            }else{

                $this->connect = $link;
                $this->database = $param['database'];
                $this->table = $param['table'];
                $this->setDatabase($param['database']);
                $this->_query("SET NAMES 'utf8'");
                $this->_query("SET CHARACTER SET 'utf8'");
            }
        }

        //GET CONNECT
        public function getConnect(){
            return $this->connect;
        }

        //SET DATABASE
        public function setDatabase($database = null){
            if($database != null){
                $this->database = $database;
                mysqli_select_db($this->connect, $this->database);
            }
        }

        //DISCONNECT DATABASE
        public function __destruct(){
            mysqli_close($this->connect);
        }

        //INSERT (có 2 kiểu insert 1 và insert nhiều)
        public function insert($data, $type = 'single'){
            if($type == 'single'){
                $newQuery = $this->createInsertSQL($data);
                $query = "INSERT INTO `".$this->table."`(".$newQuery['cols'].") VALUES (".$newQuery['vals'].")"; 
                $this->_query($query);
            }else{
                foreach ($data as $key => $value) {
                    $newQuery = $this->createInsertSQL($value) ;
                    $query = "INSERT INTO `".$this->table."`(".$newQuery['cols'].") VALUES (".$newQuery['vals'].")"; 
                    $this->_query($query);
                }
            }
            return $this->lastID();
        }
        //LAST ID
        public function lastID(){
            return mysqli_insert_id($this->connect);
        }
        //QUERY
        public function _query($query){
            $this->resultQuery =  mysqli_query($this->connect,$query);
            return $this->resultQuery;
        }
        //CREATE INSERTSQL
        public function createInsertSQL($data){
            $newQuery = array();
            $cols = "";
            $vals = "";
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $cols .= ", `$key`";
                    $vals .= ", '$value'";
                }
            }
            $newQuery['cols'] = substr($cols, 2);
            $newQuery['vals'] = substr($vals, 2);
            return $newQuery;
        }     

        //UPDATE
        public function update($data, $where){
            $newSet = $this->createUPdateSQL($data);
            $newWhere = $this->createWhereUpdateSQL($where);
            $query = "UPDATE `".$this->table."` SET ".$newSet." WHERE ".$newWhere.""; 
            // die();
            $this->_query($query);
            return $this->affectedRow();
            
        }

        //affected_rows
        public function affectedRow(){
            return mysqli_affected_rows($this->connect);
        }

        //CREATE UPDATESQL
        public function createUPdateSQL($data){
            if(!empty($data)){
                $newQuery = "";
                foreach ($data as $key => $value) {
                    $newQuery .= ", `$key` = '$value'";
                }
            }
            return $newQuery = substr($newQuery, 2);
        }

        public function createWhereUpdateSQL($data){
            $newWhere = array();
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $newWhere[] = "`".$value[0]."` = '".$value[1]."'";
                    if(isset($value[2])){
                        $newWhere[] = $value[2];
                    }
                }
                $newWhere = implode(" ", $newWhere);
            }
            
            return $newWhere;
        }

        public function createWhereDeleteSQL($data){
            $newWhere = "";
            foreach ($data as $key => $value) {
                $newWhere .= ",'".$value."'";
            }
            $newWhere = substr($newWhere, 1);
            return $newWhere;
        }

        //DELETE
        public function delete($where){
            $newWhere = $this->createWhereDeleteSQL($where);
            $query = "DELETE FROM `".$this->table."` WHERE `id` IN (".$newWhere.")";       
            $this->_query($query);
            return $this->affectedRow();
        }

        //LIST RECORD
        public function listRecord($query){
            $result = array();
            if(!empty($query)){
                $resultQuery =$this->_query($query);
                if(mysqli_num_rows($resultQuery) > 0){
                    while($row = mysqli_fetch_assoc($resultQuery)){
                        $result[] = $row;
                    }
                mysqli_free_result($resultQuery);
            }
            
            }
            
            return $result;
        }

        //SINGLE RECORD
        public function singleRecord($query){
            $result = array();
            if(!empty($query)){
                $resultQuery = $this->_query($query);
                if(mysqli_num_rows($resultQuery) > 0){
                    $result = mysqli_fetch_assoc($resultQuery);
                }
                mysqli_free_result($resultQuery);
            }
            return $result;
        }

        //TOTAL ITEM
        public function totalItem($query){
            if(!empty($query)){
                $resultQuery = $this->_query($query);
                if(mysqli_num_rows($resultQuery) > 0){
                    $result = mysqli_fetch_assoc($resultQuery);
                }
                mysqli_free_result($resultQuery);
            }
            return $result['totalItems'];
        }

    }
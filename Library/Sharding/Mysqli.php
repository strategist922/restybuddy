<?php
/**
 * Mysqli simple db class
 * 
 * $sql = 'select * from sz_account limit 1';
 * $sqllarge = 'select * from sz_stream';
 * $db = Base::instance('Mysqlidb');
 * $result = $db->fetchRow($sql);
 * //use to query big data
 * $db->connect()->real_query($sqllarge);
 * $result2 = $db->connect()->use_result();
 * while($row = $result2->fetch_row()){
 *     var_dump($row);
 * }
 * echo "-------<br />";
 * var_dump($result);
 * 
 * if ($db->query($sqlproduct)) {
 *    while ($arrRow = mysqli_fetch_assoc($db->getQuery())) {
 *        var_dump($arrRow);
 *        echo "--------------<br />";
 *    }
 * }
 */
class Sharding_Mysqli {

    /**
     * db config
     * @var array
     */
    protected $_arrConfig = null;

    /**
     * auto free
     * @var bool
     */
    protected $_bolAutoFree = false;

    /**
     * use pconnect
     * @var bool
     */
    protected $_bolPconnect = false;

    /**
     * transtimes
     * @var integer
     */
    protected $_intTransTimes = 0;

    /**
     * query
     * @var string
     */
    protected $_strQueryStr = '';

    /**
     * auto ins id
     * @var integer
     */
    protected $_intLastInsID = null;

    /**
     * row number
     * @var integer
     */
    protected $_intNumRows = 0;

    /**
     * linkid
     * @var mysqli
     */
    protected $_objLinkID = null;

    /**
     * query object
     * @var object
     */
    protected $_objQueryID = null;

    /**
     * use master
     * @var bool
     */
    protected $_bolIsMaster = false;

    /**
     * is master
     * @var bool
     */
    protected $_bolMaster = false;

    /**
     * is connected
     * @var bool
     */
    protected $_bolConnected = false;

    
    static $_instance = null;

    public static function instance($group='mysql')
    {
	if(!isset(self::$_instance[$group])) {
		$arrConfig = C($group);	
    		self::$_instance[$group] =  new self($arrConfig);
	}
	return self::$_instance[$group];
    }

    /**
     * construct
     */
    private function __construct($arrConfig) {
        if (!extension_loaded('mysqli')) {
            throw new Exception('MYSQLI EXTENSTION NOT LOADED!');
        }
        if (!empty($arrConfig)) {
		$this->_arrConfig = $arrConfig;
        } else {
            throw new Exception('DB CONFIG SET ERROR!please set group.node.master/slave/near/backup');
        }
    }

    /**
     * get config
     * @param bool $bolMaster is master
     * @param int $intRetry  retry 
     * @return array  
     */
    private function _get_db_config($bolMaster, $intRetry = 0) {
        if ($bolMaster) {
            return $this->_arrConfig['master'];
        } else {
            //read from slaves
            switch ($intRetry) {
		case 0:
		     //slaves split by ,
                     if(!empty($this->_arrConfig['slave']['host']) && is_int(strpos($this->_arrConfig['slave']['host'],','))) {
			  $expand = explode(',',$this->_arrConfig['slave']['host']);
			  $this->_arrConfig['slave']['host'] = array_rand($expand);
		     }
		     return $this->_arrConfig['slave'];
                case 1:
                    return $this->_arrConfig['backup'];
                case 2:
                    return $this->_arrConfig['master'];
                default:
                    return $this->_arrConfig['master'];
            }
        }
    }
    

    /**
     * connect setting
     * 
     */
    public function connect() {
        if ($this->_objLinkID && $this->_bolIsMaster == false && $this->_bolMaster == true) {
            //if connected slave,but need to execute a insert/update then disconnect slave then connect to master
            $this->close();
        }

        if (!isset($this->_objLinkID)) {
            $connect_success = false;
            for ($i = 0; $i < 3; $i++) {
                $arrConfig = $this->_get_db_config($this->_bolMaster, $i);
                $this->_objLinkID = new mysqli($arrConfig['host'], $arrConfig['username'],
                                $arrConfig['password'], $arrConfig['database'], $arrConfig['port']);
                if (mysqli_connect_errno()) {
		    error_log("error".mysqli_connect_errno());
                    continue;
                } else {
                    $connect_success = true;
                    break;
                }
            }
            if (!$connect_success) {
                throw new Exception(mysqli_connect_error());
            }
            if (!$this->_objLinkID->set_charset('utf8')) {
                throw new Exception("Failed to set character set utf-8!");
            }
            $this->_bolConnected = true;
            $this->_bolIsMaster = $this->_bolMaster;
        }
        return $this->_objLinkID;
    }
    

    /**
     * query return result
     * @param string $str sql
     * @return array  
     */
    public function query($strSql) {
        $this->connect();
        $this->_strQueryStr = $strSql;
        $this->free();
        $this->_objQueryID = mysqli_query($this->_objLinkID, $this->_strQueryStr);
        if (!$this->_objQueryID) {
            throw new Exception(
                    "QUERY ERROR:" . $this->_objLinkID->error . " SQL:" . $strSql);
            return false;
        } else {
            $this->_intNumRows = mysqli_num_rows($this->_objQueryID);
            return true;
        }
    }

    /**
     * get query result
     *
     */
    public function fetch() {
        return mysqli_fetch_assoc($this->_objQueryID);
    }

    /**
     * return one row
     *
     * $db->fetchRow($strSql);
     * @param string $strSql SQL
     * @return array/empty array 
     */
    public function fetchRow($strSql) {
        if ($this->query($strSql)) {
            return $this->fetch();
        }
        return array();
    }

    /**
     * return column，examples:SELECT field FROM table WHERE id = 3;
     *
     * $db->fetchColumn($strSql);
     * @param string $strSql SQL
     * @return array/empty array 
     */
    public function fetchColumn($strSql) {
        $arrColumn = array();
        if ($this->query($strSql)) {
            while ($arrRow = $this->fetch()) {
                $arrRow = array_values($arrRow);
                $arrColumn[] = $arrRow[0];
            }
        }
        return $arrColumn;
    }

    /**
     *
     * fetch one,examples:SELECT field FROM table WHERE id = 12;
     *
     * $db->fetchOne($strSql);
     * @param string $strSql
     */
    public function fetchOne($strSql) {
        $arrRow = $this->fetchRow($strSql);
        if (!is_array($arrRow)) {
            return false;
        }
        $arrRow = array_values($arrRow);
        return $arrRow[0];
    }

    /**
     *
     * query，example:SELECT * FROM table WHERE id > 3;
     *
     * $db->fetchAll($strSql);
     * @param string $strSql
     */
    public function fetchAll($strSql) {
        $arrAll = array();
        if ($this->query($strSql)) {
            return mysqli_fetch_all($this->_objQueryID, MYSQLI_ASSOC);
        }
        return $arrAll;
    }

    /**
     * execute ,connect to master
     * @param string $str sql
     * @return int  execute numbers
     */
    public function execute($strSql) {
        //connect to master
        $this->_bolMaster = true;
        $this->connect();
        $this->_strQueryStr = $strSql;
        $this->free();
        //$startInterval = microtime(true);
        $result = mysqli_query($this->_objLinkID, $this->_strQueryStr);
        if (false === $result) {
            throw new Exception("QUERY ERROR:" . $this->_objLinkID->error . " SQL:" . $strSql);
            return false;
        } else {
            $this->_intNumRows = mysqli_affected_rows($this->_objLinkID);
            $this->_intLastInsID = mysqli_insert_id($this->_objLinkID);
            //$endInterval = microtime(true);
            //error_log(($endInterval - $startInterval) . " " . $strSql . "\n", 3 ,"/tmp/mccsql.log");    		
            return $this->_intNumRows;
        }
    }

    /**
     * insert into table
     * @param type $strTable
     * @param type $arrBind
     * @return type 
     */
    public function insert($strTable, $arrBind) {
        $fields = "(";
        $values = "(";
        foreach ($arrBind as $key => $value) {
            $fields .= "`" . $key . "`";
            $fields .= ",";
            $values .= ("'" . $this->escape($value) . "'");
            $values .= ",";
        }
        $fields = rtrim($fields, ",");
        $values = rtrim($values, ",");
        $fields .= ")";
        $values .= ")";
        $sql = "INSERT INTO {$strTable} {$fields} VALUES {$values}";
        return $this->execute($sql);
    }

    /**
     * do update
     * @param type $strTable table name
     * @param type $arrBind bind array
     * @param type $strWhere where
     * @return type 
     */
    public function update($strTable, $arrBind, $strWhere) {
        $sql = "UPDATE {$strTable} SET ";
        $setString = "";
        foreach ($arrBind as $key => $value) {
            $strSeg = "`" . $key . "`";
            $valSeg = ("'" . $this->escape($value) . "'");
            $strSeg .= (" = " . $valSeg);
            $strSeg .= ",";
            $setString .= $strSeg;
        }

        $setString = rtrim($setString, ',');
        $sql .= $setString;
        $sql .= (" WHERE " . $strWhere);
        return $this->execute($sql);
    }

    /**
     * begin transaction
     * @return null
     */
    public function beginTransaction() {
        $this->_bolMaster = true;
        $this->_objLinkID = $this->connect();
        if (!$this->_objLinkID) {
            return false;
        }
        $result = mysqli_query($this->_objLinkID, 'START TRANSACTION');
        return $result;
    }

    /**
     * commit
     * @return bool true/false
     */
    public function commit() {
        $result = mysqli_query($this->_objLinkID, 'COMMIT');
        return $result;
    }

    /**
     * rollback
     * @return bool 
     */
    public function rollBack() {
        $result = mysqli_query($this->_objLinkID, 'ROLLBACK');
        return $result;
    }

    /**
     * free last query result
     */
    public function free() {
        if (!empty($this->_objQueryID)) {
            mysqli_free_result($this->_objQueryID);
            $this->_objQueryID = null;
        }
    }

    /**
     * free resource
     */
    public function close() {
        if (!empty($this->_objQueryID)) {
            mysqli_free_result($this->_objQueryID);
        }
        if (!empty($this->_objLinkID)) {
            mysqli_close($this->_objLinkID);
        }
        unset($this->_objLinkID);
        unset($this->_objQueryID);
        $this->_bolConnected = false;
    }

    /**
     * destruct
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * get last query sql, help debug
     * @return string
     */
    public function getLastSql() {
        return $this->_strQueryStr;
    }

    public function getLastInsertId() {
        return $this->_intLastInsID;
    }

    public function escape($string) {
        $this->connect();
        return $this->_objLinkID->real_escape_string($string);
    }

    public function getNumRows() {
        return $this->_intNumRows;
    }

    public function getConnection($bolMaster) {
        if (!isset($bolMaster) || $bolMaster === null) {
            $bolMaster = $this->_bolMaster;
        }
        $this->_bolMaster = $bolMaster;
        $this->connect();
        return $this->_objLinkID;
    }

    public function getQuery() {
        return $this->_objQueryID;
    }
    
    
}


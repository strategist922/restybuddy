<?php
/**
 * query maker
 * use mysql se to query result
 * $search = new Search_Query();
 * $search->setField('')->setDatabase()->setQuery()->setIndex('main,main_delta')->setMatchMode()->setRankingMode()->setFieldWeight()->setFilter()->setSort()->setLimit();
 * $result = $search->fetchAll();
 *
 */
class Search_Query {

	protected $_sql = 'SELECT ';
	protected $_mysql = '';

	public function __construct($group = 'search')
	{
		$this->_mysql = Sharding_Mysqli::instance($group);
	}


	public function mysql()
	{
		return $this->_mysql;
	}

	/**
	 * set filed ,filter is ,
	 */
	public function setField($field='*')
	{
		$this->_sql .= $field." FROM ";
	}

	public function setDatabase($database)
	{
		$this->_sql .= $database . " WHERE ";
	}

	/**
	 * deal query word
	 */
	public function setQuery($query)
	{
		$query = $this->_mysql->escape($query);
		$this->_sql .= "query='$query;";
	}

	public function setIndex($index = '')
	{
		if(!empty($index)){
			$this->_sql .= "index=$index;";
		}
	}

	public function setIndexWeights($index = '')
	{
		if(!empty($index)){
			//idx_exact,2,idx_staaa,1;
			$this->_sql .= "indexweights=$index;";
		}
	}

	public function setMatches($count=1000)
	{
		$count =  (!is_int($count) || $count <0 ) ? $count = 1000 : $count;
		$this->_sql .= "maxmatches=$count;offset=0;limit=$count;";
		 
	}

	public function setMode($match)
	{
		$this->_sql .="mode=$match;";
	}

	public function setRanker($ranker)
	{
		$this->_sql .= "ranker=$ranker;";
	}

	public function setFieldWeight($fieldweight)
	{
		$this->_sql .= "weights=$fieldweight;";
	}


	public function setFilter($filter='')
	{
		if(!empty($filter)){
			$this->_sql .= "filter=$filter;";
		}
	}

	public function setRange($range='')
	{
		if(!empty($range))
		{

			$this->_sql .= "range=$range;";
		}
	}

	public function setSort($sort ='')
	{
		if(!empty($sort)) {
			$this->_sql .= "sort=$sort;";
		}

	}

	public function setExtend($str='')
	{
		if(!empty($str)) {
			$this->_sql .= rtrim($str,";").";";
		}

	}

	public function setLimit($limit = 1000)
	{
		$this->_sql .= "' LIMIT 0,$limit";
	}

	public function fetchAll()
	{
		//error_log($this->_sql."\n");
		return $this->_mysql->fetchAll($this->_sql);
	}

	public function fetchColumn()
	{
		//error_log($this->_sql."\n");
		return $this->_mysql->fetchColumn($this->_sql);
	}


	public function getQueryStr()
	{
		return $this->_sql;
	}


}

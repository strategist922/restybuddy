<?php

class Business_Comment{
	protected $_params = '';
	protected $_redis = '';

	public function __construct($params)
	{
		$this->_params = $params;
		$this->_params['lat'] = isset($this->_params['lat']) ? $this->_params['lat'] : '';
		$this->_params['long'] = isset($this->_params['long']) ? $this->_params['long'] : '';
		$this->_params['attache'] = isset($this->_params['attache']) ? $this->_params['attache'] : '';
		$this->_params['annotations'] = isset($this->_params['annotations']) ? $this->_params['annotations'] : '';
		$this->_redis = Sharding_Redis::instance();
	}

	private function isPost($value='')
	{
		$key = K('user.commentlastpost');
		if(empty($value)) {
			$lastpost = $this->_redis->get($key.':'.$this->_params['uid']);
			if($lastpost === $this->_params['comment'])
			{
				return true;
			}
			return false;
		}
		return Sharding_Redis::objectSet($key,$value,$this->_params['uid']);
	}

	public function createComment()
	{
		if(!$this->isPost()) {
			$this->_params['id'] = objid();
			//$key = K('stream.stream');
			$r = Sharding_Redis::objectSet(K('comment.comment'), $this->_params,$this->_params['id']);
			if($r) {
				//update stream
				$this->isPost($this->_params['comment']);
				Sharding_Redis::objectSet(K('stream.commentlist'), $this->_params['id'],$this->_params['rid']);
				//add to event
				$eventkey = K('event.comment');
				Sharding_Redis::objectSet($eventkey, $this->_params['id'].':'.$this->_params['rid']);
				return true;
			}
		}
		return false;
	}


	public function replyComment()
	{
			

	}



	public function destoryComment()
	{
		//$eventkey = K('event.stream');
		//$this->_redis->lRem($eventkey,$this->_params['id']);
		//del id in his keys
		Sharding_Redis::delInKeys($this->_params['id']);
		return true;
	}

	public function getComment()
	{
		$pageSize = 20;
		$start = isset($this->_params['s']) ? $this->_params['s'] : 0;
		$end = isset($this->_params['e']) ? $this->_params['e'] : $pageSize;
		$start = ($start === 0) ? 0 : (($end * $start - $end == 0) ? 0 : $end * $start - $end );
        $end = ($end === -1) ? -1 : (($start + $end) > 1 ? $start + $end - 1 : 0 );
        $key = K('stream.commentlist').':'.$this->_params['id'];
		$ids = $this->_redis->lRange($key,$start,$end);
		$keyprefix = K('comment.comment');
		$return = array();
		if(is_array($ids) && count($ids)) {
			foreach($ids as $one) {
				$getKey = $keyprefix.':'.$one;
				$return[$one] = $this->_redis->hGetAll($getKey);
			}
		}
		return $return;
	}
}
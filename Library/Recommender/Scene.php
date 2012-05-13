<?php
/**
* 场景推荐器
* 根据tag,user,area,place 4种不同的维度的场景来推荐用户的排序
* 
* 
* 调用方式
* $s = new Recommend_Scene();
* $data = $s->data();
*/

class Recommender_Scene {
	
	protected $_data;
	
	/**
	* 通过用户ID,城市ID及区域ID来推荐信息
	* 构造基于类型的用户场景的数据推荐
	* type 类型为  tag 基于TAG 的推荐,  user 基于用户相似的推荐,  area基于区域概率的推荐, place基于商家相似度的推荐
	*/
	public function __construct($appID,$cityID,$blockID,$type='tag')
	{
		$function = "getUserCityAreaSceneBy".ucwords($type);
		$this->$function;
	}

	/**
	* 通过tag方式来实现用户所在城市,地区所可能的场景设置
	* 返回是一个tag的集合
	*/
	private function getUserCityAreaSceneByTag($appID,$cityID,$blockID)
	{

		
		$this->_data['product'] = array();
		
		$this->_data['shoppingstreet'] = array();
		
		$this->_data['place'] = array();
		
		$this->_data['activity'] = array();
		


	}

	
	private function getUserCityAreaSceneByUser($appID,$cityID,$blockID)
        {

                
                $this->_data['product'] = array();
                
                $this->_data['shoppingstreet'] = array();
                
                $this->_data['place'] = array();
                
                $this->_data['activity'] = array();
                


        }




	private function getUserCityAreaSceneByArea($appID,$cityID,$blockID)
        {

                
                $this->_data['product'] = array();
                
                $this->_data['shoppingstreet'] = array();
                
                $this->_data['place'] = array();
                
                $this->_data['activity'] = array();
                


        }


	private function getUserCityAreaSceneByPlace($appID,$cityID,$blockID)
        {

                
                $this->_data['product'] = array();
                
                $this->_data['shoppingstreet'] = array();
                
                $this->_data['place'] = array();
                
                $this->_data['activity'] = array();
                


        }
	
	/**
	* 时段数据推荐
	* 
	*/
	private function periodRecommendByTag($appID,$cityID,$blockID)
	{
		//获取当前段
		$hour = Common_Time::nowHours();

		//根据时间段和cityID取得城市时间段的偏好

		//

		//取得当前的场景的关键字
		$redis = Sharding_Redis::instance();
		$key = Business_Userkey::userkey();
		$tags = ''; 

	}

	
	

	/**
	* 返回推荐的结果
	*/
	public function data()
	{
		return $this->_data;
	}	

}

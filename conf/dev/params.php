<?php
//return params set
return array(
		//hardware type
		'/hardware/id' => array(
				'type' => 'require',
		),
		
		
		
		'/stream/post' => array(
				'email' => 'email',
				'account' => 'require',
				'password' =>'require',
		),
		
		
		'/stream/get' => array(
				'email' => 'email',
				'account' => 'require',
				'password' =>'require',
		),
		
		
		
);
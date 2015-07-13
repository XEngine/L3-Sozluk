<?php 

 class XConfig extends Eloquent {
    	   public static $table = 'config';

    	   /**
    	    * setConfig
    	    *
    	    * @return boolean
    	    * @author timeout
    	    **/
    	   public static function setConfig($array)
    	   {
    	   		while ($config_valueTMP = current($array)) {

    	   		}
    	   }

    	    /**
    	    * setConfig
    	    *
    	    * @return boolean
    	    * @author timeout
    	    **/
    	   public static function getConfig($array,$debug = false)
    	   {
			
			$result = true;
			$debug = array();
			$cfgs = array();

			/*

			if ( Cache::has('config') )
			{
				$cfgs = Cache::get('config');
			}
			else
			{
				$cfgs = XConfig::all();
				Cache::forget('config'
				Cache::put('config',$cfgs,10);			
			}
			*/
			$cfgs = XConfig::all();
			while ($config_valueTMP = current($array)) {

			  	foreach ($cfgs as $cfg)
				{
				     if ($cfg->config_name  == key($array) )
				     {
				     	if ( $cfg->config_value != $config_valueTMP )
				     		{
				     			$result = false;
				     			if ( $debug )
				     			{
				     				array_push($debug, $cfg->config_name);
				     			}
				     		}
				     	
				     }			     
				}
			    next($array);
			}
			if ( $debug && $result == false)
			{
				return $debug;
			}
			else
			{
				return $result;
			}
			
			

		  }
    }
 ?>
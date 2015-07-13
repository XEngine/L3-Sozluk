<?php

/**
* 
*/
class Search_Controller extends Base_Controller
{
	
	public $restful = true;

	public function get_thread()
	{
		//Array ( [thread] => asdgasyhudjıkopağ )
    	$inputs = Input::all();
    	$key = $inputs['term'];
    	$basliklar = Thread::where('title','LIKE','%'.$key.'%')->order_by('title','asc')->take(10)->get(array('id','title'));
    	$titles = array();

    	foreach($basliklar as $title)
    	{
    		$titleX = array('label' => $title->title,'id' => $title->id);
    		array_push($titles, $titleX);
    	}
    	
    	return Response::json($titles);

    	
    	
    }






}
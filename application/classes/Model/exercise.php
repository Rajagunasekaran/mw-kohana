<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_exercise extends Model
{
	public function insertLibraryDetails($array)
	{
		$seg_results = DB::insert('unit_gendata', array('title', 'status_id', 'access_id', 'feat_img', 'feat_vid', 'type_id', 'musprim_id','equip_id','mech_id','level_id','sport_id','force_id','descbr','descfull'))
				->values(array($array['title'],$array['unit_status'],$array['unit_access'] ,$array['feat_img']  ,$array['feat_vid'], $array['unit_type'], $array['unit_muscle'],$array['unit_equip'],$array['unit_mech'],$array['unit_level'],$array['unit_sport'],$array['unit_force'],$array['unit_descbr'],$array['unit_descfull']))->execute();
		return $seg_results[0];
	}
	public function updateLibraryDetails($array,$unit_id)
	{
		DB::update('unit_gendata')->set(array(
			'title'			=> $array['title'],
			'status_id' 	=> $array['unit_status'],
			'access_id' 	=> $array['unit_access'],
			'feat_img'		=> $array['feat_img'],
			'feat_vid'		=> $array['feat_vid'],
			'type_id'		=> $array['unit_type'],
			'musprim_id'	=> $array['unit_muscle'],
			'equip_id' 		=> $array['unit_equip'],
			'mech_id'		=> $array['unit_mech'],
			'level_id' 		=> $array['unit_level'],
			'sport_id' 		=> $array['unit_sport'],
			'force_id'		=> $array['unit_force'],
			'descbr'		=> $array['unit_descbr'],
			'unit_descfull' => $array['unit_descfull']
		))
		->where('unit_id', '=', $unit_id)
		->execute();
	}
	
}
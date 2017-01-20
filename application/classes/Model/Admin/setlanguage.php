<?php

defined('SYSPATH') or die('No direct access allowed.');

class Model_Admin_Setlanguage extends Model
{
   /*public function getAttributeslanguage($authid, $siteid, $roleid,$limitCurrent = '', $offset = '')
   {
      $sql   = "select * from language_attributes";
	  if ($limitCurrent != '') {
		$sql .= " LIMIT " . $offset . " , " . $limitCurrent . "";
	  }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function filtersubscriber($attrname)
   {
      $where = '';
      if ($attrname != '') {
         $where[] = "where attribute_name like %'".$attrname."'%";
      }
      $siteid     = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '1');
      $sql   = "select * from language_attributes $where order by langattr_id asc";
	  $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }*/
 }
 ?>
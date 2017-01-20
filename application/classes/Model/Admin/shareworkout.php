<?php
defined('SYSPATH') OR die('No direct access allowed.');
class Model_Admin_Shareworkout extends Model
{
   public function insert($table, $array)
   {
      $results = DB::insert($table, array_keys($array))->values(array_values($array))->execute();
      return $results[0];
   }
   public function update($table, $data, $array)
   {
      $query = DB::update($table)->set($data)->where($array)->execute();
      return $query->execute();
   }
   public function delete($table, $array)
   {
      $results = DB::delete($table)->where($array)->execute();
      return $results[0];
   }
   public function getdata($table, $array)
   {
      $condition = '';
      $i         = 0;
      foreach ($array as $k => $v) {
         if ($i > 0) {
            $condition .= " and ";
         }
         $condition .= "$k = '$v'";
         $i++;
      }
      $sql   = "SELECT * FROM $table where " . $condition;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getuserdetails($arg)
   {
      $sql   = "SELECT * FROM users where id in($arg)";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function gettagdetails($arg)
   {
      $sql   = "SELECT * FROM tag where tag_id in($arg)";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getsitedetails($arg)
   {
      $sql   = "SELECT * FROM sites where id in($arg)";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getwkoutname($arg)
   {
      $sql   = "SELECT wkout_id,wkout_title FROM wkout_gendata where wkout_id in($arg)";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function filtersubscriber($id = '', $gender = '', $from = '', $to = '')
   {
      $where = '';
      if ($id != '') {
         $where[] = "id in ($id)";
      }
      if ($gender != '') {
         $gender = implode(',', $gender);
         if ($gender) {
            $where[] = "user_gender in ($gender)";
         }
      } else {
         $where[] = "user_gender in (1,2)";
      }
      if ($from != '' && $to != '') {
         $where[] = "floor(datediff (now(), user_dob)/365)>$from && floor(datediff (now(), user_dob)/365)<$to";
      }
      if ($where) {
         $where = " and " . implode(" and ", $where);
      }
      //$sql = "SELECT id,user_fname, user_lname, user_dob, user_gender, floor(datediff (now(), user_dob)/365) as age FROM users where banned=0 $where";
      $sql   = "SELECT id, concat(user_fname,' ',user_lname) as name FROM users where banned=0 $where";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
}
<?php
class Helper_Activityfeed
{
   public static function activity_index($value)
   {
      $user_id    = Auth::instance()->get_user();
      $icon_array = array(
         'workout folder' => 'fa-folder-open',
         'workout plan'   => 'fa-file',
         'assigned'       => 'fa-calendar',
         'tag'            => 'fa-tag',
         'image'          => 'fa-picture-o',
         'account'        => 'fa-user',
         'journal'        => 'fa-file',
			'workout journal'        => 'fa-file',
         'sample workout plan' => 'fa-file',
			'assigned workout plan' => 'fa-file'
      );
      $case       = $value['type'] . "-" . $value['action'];
      $type       = ($value["type"] == "assigned") ? "workout plan" : $value["type"];
      //echo $case."<br>";
      $string     = "";
		//echo case
      $string .= $value["id"].'#'.$case;
      $string .= '<p class="list-group-item feed-item">';
      if (isset($icon_array[$value['type']]) && $icon_array[$value['type']] != '')
         $string .= '<i class="fa fa-fw ' . $icon_array[$value['type']] . '"></i> ';
      $string .= '<span class="badge">' . Helper_Common::time_ago($value['created_date']) . '</span>';
      if ($user_id == $value["user"]) {
         $string .= '<a href="javascript:void(0);" onclick="showUserModel(\'' . $user_id . '\',0)">You</a> ';
      } else {
         $fetch_field  = 'concat(user_fname," ",user_lname) as name';
         $fetch_condtn = 'id=' . $value['user'];
         $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
         if ($result) {
            $result = $result[0];
            $string .= '<a href="javascript:void(0);" onclick="showUserModel(\'' . $value["user"] . '\',0)">' . $result["name"] . '</a> ';
         }
      }
      $string .= '<strong>' . $value['action'] . ' </strong>';
      //$string .= $value["type"].' ';
      $string .= $type . ' ';
      switch ($case) {
         case "image-uploaded":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "image-edited":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "image data-edited":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "image-modified":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "image-deleted":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "image-replaced":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "image-shared":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "account-modified password":
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "account-forgot password":
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "account-activated":
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "account-registered":
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "-logged-out":
            $string .= date("d M Y H:i:s a T", strtotime($value['created_date']));
            break;
         case "account-logged-out":
            $string .= date("d M Y H:i:s a T", strtotime($value['created_date']));
            break;
         case "account-logged-in":
            $string .= date("d M Y H:i:s a T", strtotime($value['created_date']));
            break;
         case "exercise record-edited":
            break;
         case "sample workout plan-copied":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $jstxt = trim($jsondata);
                  $string .= $jstxt;
               }
               //$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
            }
            break;
         case "sample workout plan-created":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               //$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $jstxt = trim($jsondata->text);
                  $string .= $jstxt;
                  if ($jstxt == "from workout plan") {
                     $fetch_field  = 'wkout_title';
                     $fetch_condtn = 'wkout_id=' . $jsondata->wkoutid;
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $string .= ' <a href="javascript:void(0);" onclick="viewwkout(\'' . $jsondata->wkoutid . '\')">"' . $result[0][$fetch_field] . '"</a>';
                     }
                  } else if ($jstxt == "from shared workout plan") {
                     $fetch_field  = 'wkout_title';
                     $fetch_condtn = 'wkout_share_id=' . $jsondata->wkoutid;
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
                     }
                  }
               }
            }
            break;
         case "sample workout plan-deleted":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               //$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
            }
            break;
			case "sample workout plan-cancelled":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               //$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
            }
            break;
         case "workout journal-created":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_log_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
            break;
         case "workout journal-cancelled":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_log_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "logged-workout plan":
            /*$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	
            $result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
            if(isset($result) && count($result)>0){
            $string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
            }
            */
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "workout journal-edited":
            /*$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	
            $result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
            if(isset($result) && count($result)>0){
            $string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
            }
            */
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "workout journal-opened":
            /*$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	
            $result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
            if(isset($result) && count($result)>0){
            $string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
            }
            */
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "workout journal-deleted":
            /*$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	
            $result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
            if(isset($result) && count($result)>0){
            $string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
            }
            */
            $string .= date("d M Y", strtotime($value['created_date']));
            break;
         case "journal-edited":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_log_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout folder-created":
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout folder-moved":
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout folder-edited":
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout folder-deleted":
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout folder-moved":
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-printed":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
			case "workout plan-logged":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-copied":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "exercise record-created":
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "exercise record-opened":
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "exercise record-edited":
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "exercise record-shared":
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "exercise record-modified":
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "assigned workout plan-deleted":
            $fetch_field     = 'wkout_title';
            $fetch_condtn    = 'wkout_id=' . $value['type_id'];
            $wkout_assign_id = $value['type_id'];
            $result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $string .= " from " . date("d M Y", strtotime($value['created_date']));
            }
            break;
         case "assigned workout plan-edited":
            $fetch_field     = 'wkout_title';
            $fetch_condtn    = 'wkout_id=' . $value['type_id'];
            $wkout_assign_id = $value['type_id'];
            $result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $string .= " from " . date("d M Y", strtotime($value['created_date']));
            }
            break;
         case "assigned workout plan-opened":
            $fetch_field     = 'wkout_title';
            $fetch_condtn    = 'wkout_id=' . $value['type_id'];
            $wkout_assign_id = $value['type_id'];
            $result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $string .= " to " . date("d M Y", strtotime($value['created_date']));
            }
            break;
         case "assigned workout plan-copied":
            $fetch_field     = 'wkout_title';
            $fetch_condtn    = 'wkout_id=' . $value['type_id'];
            $wkout_assign_id = $value['type_id'];
            $result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-assigned":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= ' <a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $string .= " to " . date("d M Y", strtotime($value['created_date']));
            }
            break;
         case "workout plan-edited":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-opened":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-deleted":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-created":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-moved":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $string .= ' to workout folder';
                  $subscriber   = array();
                  $str          = "";
                  $fetch_field  = 'folder_title';
                  $fetch_condtn = 'id=' . $jsondata["id"];
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
                  }
                  $string .= $str;
               }
            }
            break;
         case "shared workout plan-opened":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_share_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
			case "shared workout plan-cancelled":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_share_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
			case "shared workout plan-logged":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_share_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-shared":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $subscriber = array();
                  $str        = "";
                  if (count($jsondata) > 1) {
                     $i   = 0;
                     $str = "$str and <a href='javascript:void(0);' onclick='show_others(\"" . implode(",", $jsondata) . "\")'>" . count($jsondata) . " users</a>";
                  } else {
							$string .= ' to ';
                     foreach ($jsondata as $k => $v) {
                        $jsonuser = Model::instance('Model/admin/shareworkout')->getuserdetails($v);
                        $jsonuser = $jsonuser[0];
                        $str .= "<a href='javascript:void(0);' onclick='viewusers($v)'>";
                        $str .= $jsonuser["user_fname"] . ' ' . $jsonuser['user_lname'] . "</a>, ";
                     }
                     $str = substr($str, 0, -2);
                  }
                  $string .= $str;
               }
            }
            break;
         case "tag-created":
            $fetch_field  = 'tag_title';
            $fetch_condtn = 'tag_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "workout plan-tagged":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
               $string .= ' with ';
               $jsondata = json_decode($value['json_data']);
               //print_r($jsondata);
               if ($jsondata) {
                  $subscriber = array();
                  $str        = "";
                  foreach ($jsondata as $k => $v) {
                     //print_r($v); exit;
                     //$v = implode(',',$v);
                     $jsontag = Model::instance('Model/admin/shareworkout')->gettagdetails($v);
                     /*
                     $sql   = "SELECT * FROM tag where tag_id in($v)";
                     $query = DB::query(Database::SELECT, $sql);
                     $jsontag  = $query->execute()->as_array();
                     */
                     $jsontag = $jsontag[0];
                     $str .= '<a href="javascript:void(0);" >"';
                     $str .= $jsontag["tag_title"] . '"</a>, ';
                  }
                  $str = substr($str, 0, -2);
                  $string .= $str;
               }
            }
            break;
         case "tag-removed":
            $fetch_field  = 'tag_title';
            $fetch_condtn = 'tag_id=' . $value['type_id'];
            $tag_id       = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $fetch    = 'wkout_title';
                  $condtn   = 'wkout_id=' . $jsondata[0];
                  $wkout_id = $jsondata[0];
                  $results  = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch, $condtn);
                  $string .= " from workout plan <a href='javascript:void(0);' onclick='viewwkout(" . $jsondata[0] . ")'>";
                  $string .= '"' . $results[0]["wkout_title"] . '"';
                  $string .= "</a>";
               }
            }
            break;
         case "assigned-assigned":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "assigned-edited":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "assigned-copied":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case "assigned-logged":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $string .= '<a href="javascript:void(0);" onclick="viewwkout(\'' . $wkout_id . '\')">"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         default:
            break;
      }
      $string .= "</p>";
      return $string;
   }
}
?>
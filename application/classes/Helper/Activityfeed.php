<?php
class Helper_Activityfeed
{
   public static function activity_index($value, $isFront = false , $isPopup = false)
   {
      $user_id       = Auth::instance()->get_user();
      $icon_array    = array(
         'workout folder' => 'fa-folder-open',
         'my workout folder' => 'fa-folder-open',
         'sample workout folder' => 'fa-folder-open',
         'shared workout folder' => 'fa-folder-open',
         'workout plan' => 'fa-file',
         'exercise record' => 'fa-file',
         'my exercise records' => 'fa-file',
         'sample exercise records' => 'fa-file',
         'default exercise records' => 'fa-file',
         'shared exercise records' => 'fa-file',
         'my workout plans' => 'fa-file',
         'sample workout plans' => 'fa-file',
         'default workout plans' => 'fa-file',
         'shared workout plans' => 'fa-file',
         'device' => 'fa-desktop',
         'assigned' => 'fa-calendar',
         'tag' => 'fa-tag',
         'image' => 'fa-picture-o',
         'image data' => 'fa-picture-o',
         'account' => 'fa-user',
         'account confirmation' => 'fa-user',
         'user' => 'fa-user',
         'user profile' => 'fa-user',
         'preference setting' => 'fa-user',
         'journal' => 'fa-file',
         'workout journal' => 'fa-file',
         'sample workout plan' => 'fa-file',
         'shared workout plan' => 'fa-file',
         'assigned workout plan' => 'fa-file',
		   'default workout plan' => 'fa-file'
      );
      $case          = $value['type'] . "-" . $value['action'];
      $type          = ($value["type"] == "assigned" ) ? "workout plan" : $value["type"]; //|| $value["type"] == "assigned workout plan"
      $string        = "";
      $stringcontent = '';
      // echo $case . "------" . $value["user"];
		$stringcontent .= '<p class="list-group-item feed-item" data-case="'.$case.'" data-caseid="'.$value["id"].'">';
      if (isset($icon_array[$value['type']]) && $icon_array[$value['type']] != '')
         $stringcontent .= '<i class="fa fa-fw ' . $icon_array[$value['type']] . '"></i> ';
      $stringcontent .= '<span class="badge">' . Helper_Common::time_ago($value['created_date']) . '</span>';
	  if($isPopup){
		if ($user_id == $value["user"]) {
            $stringcontent .= '<a href="javascript:void(0);">You</a> ';
        }else{
			$fetch_field  = 'concat(user_fname," ",user_lname) as name';
			$fetch_condtn = 'id=' . $value['user'];
			$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
			if ($result) {
			   $result = $result[0];
			   $stringcontent .= '<a href="javascript:void(0);">' . $result["name"] . '</a> ';
			}
		}
      }elseif ($isFront) {
         $stringcontent .= '<a href="javascript:void(0);" style="text-decoration:none">You</a> ';
      } else {
         if ($user_id == $value["user"]) {
            $stringcontent .= '<a href="javascript:void(0);" onclick="showUserModel(\'' . $user_id . '\',1)">You</a> ';
         } else {
            $fetch_field  = 'concat(user_fname," ",user_lname) as name';
            $fetch_condtn = 'id=' . $value['user'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
            if ($result) {
               $result = $result[0];
               $stringcontent .= '<a href="javascript:void(0);" onclick="showUserModel(\'' . $value["user"] . '\',1)">' . $result["name"] . '</a> ';
            }
         }
      }
      $stringcontent .= '<strong>' . $value['action'] . ' </strong>';
      $stringcontent .= ($type != 'user profile' ? $type . ' ' : ' ');
      
      switch ($case) {
         case ($case == 'device-logged-in' || $case == 'device-logged-out'):
            $deviceInfo = json_decode($value['json_data']);
            if (is_object($deviceInfo) && !empty($deviceInfo)) {
               $stringcontent .= 'On ' . (isset($deviceInfo->device) && !empty($deviceInfo->device) ? ' Platform : <span class="activedatacol" ' . ($isFront != true ? 'onclick="opendeviceinfo(' . "'" . $value['id'] . "'" . ');"' : '') . '>' . $deviceInfo->device . '</span> ' : '') . (isset($deviceInfo->browser) && !empty($deviceInfo->browser) ? ' Browser : <span class="activedatacol" ' . ($isFront != true ? 'onclick="opendeviceinfo(' . "'" . $value['id'] . "'" . ');"' : '') . '>' . $deviceInfo->browser . '</span>' : '');
            }
            break;
         case ($case == "user profile-modified phone number" || $case == "user profile-modified age" || $case == "user profile-modified birthdate" || $case == "user profile-modified profile image" || $case == "user profile-modified gender" || $case == "user profile-modified weight" || $case == "user profile-modified height" || $case == 'preference setting-modified emailing time' || $case == 'preference setting-modified date format' || $case == 'preference setting-modified weight' || $case == 'preference setting-modified distance' || $case == 'preference setting-modified assignment reminder' || $case == 'preference setting-modified language' || $case == 'preference setting-modified timezone' || $case == 'preference setting-modified device integration'):
            if (strpos($stringcontent, 'preference setting') !== false) {
               $stringcontent = str_replace('preference setting', '', $stringcontent);
               if (strpos($stringcontent, 'modified emailing time') !== false)
                  $stringcontent = str_replace('modified emailing time', 'modified  </strong>preference setting <strong>emailing time </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified date format') !== false)
                  $stringcontent = str_replace('modified date format </strong>', 'modified  </strong>preference setting <strong>date format </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified weight') !== false)
                  $stringcontent = str_replace('modified weight </strong>', 'modified  </strong>preference setting <strong>weight </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified distance') !== false)
                  $stringcontent = str_replace('modified distance </strong>', 'modified  </strong>preference setting <strong>distance </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified assignment reminder') !== false)
                  $stringcontent = str_replace('modified assignment reminder </strong>', 'modified  </strong>preference setting <strong>assignment reminder </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified language') !== false)
                  $stringcontent = str_replace('modified language </strong>', 'modified  </strong>preference setting <strong>language </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified timezone') !== false)
                  $stringcontent = str_replace('modified timezone </strong>', 'modified  </strong>preference setting <strong>timezone </strong>', $stringcontent);
               else if (strpos($stringcontent, 'modified device integration') !== false)
                  $stringcontent = str_replace('modified device integration </strong>', 'modified  </strong>preference setting <strong>device integration </strong>', $stringcontent);
            }
            if (strpos($stringcontent, 'user profile') !== false) {
               $stringcontent = str_replace('preference setting', '', $stringcontent);
               if (strpos($stringcontent, 'user profile-modified phone number') !== false)
                  $stringcontent = str_replace('modified phone number </strong>', 'modified </strong>user profile <strong>phone number </strong>', $stringcontent);
               else if (strpos($stringcontent, 'user profile-modified birthdate') !== false)
                  $stringcontent = str_replace('modified birthdate </strong>', 'modified  </strong>user profile <strong>birthdate </strong>', $stringcontent);
               else if (strpos($stringcontent, 'user profile-modified gender') !== false)
                  $stringcontent = str_replace('modified gender </strong>', 'modified  </strong>user profile <strong>gender </strong>', $stringcontent);
               else if (strpos($stringcontent, 'user profile-modified height') !== false)
                  $stringcontent = str_replace('modified height </strong>', 'modified  </strong>user profile <strong>height </strong>', $stringcontent);
               else if (strpos($stringcontent, 'user profile-modified weight') !== false)
                  $stringcontent = str_replace('modified weight </strong>', 'modified  </strong>user profile <strong>weight </strong>', $stringcontent);
            }
            $stringcontent .= (!empty($value['json_data']) ? ' to <span class="activedatacol">' . str_replace('to ', '', json_decode($value['json_data'])) . ($value['context_date'] != '0000-00-00 00:00:00' ? Helper_Common::change_default_date_dob($value['context_date']) : '') . '</span>' : ($value['context_date'] != '0000-00-00 00:00:00' ? '<span class="activedatacol">' . Helper_Common::change_default_date_dob($value['context_date']) . '</span>' : ''));
            if (strpos($stringcontent, 'device integration') != false) {
               $stringcontent = str_replace('to ', '', $stringcontent);
            }
            break;
         case "user-Session is Refreshed":
            $stringcontent = str_replace('</strong>user', '</strong>', $stringcontent);
            break;
         case ($case == 'my workout folder-opened' || $case == 'sample workout folder-opened' || $case == 'shared workout folder-opened'):
            if (!empty($value['type_id'])) {
               $fetch_field  = 'folder_title';
               $fetch_condtn = 'id=' . $value['type_id'];
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
               if (isset($result) && count($result) > 0) {
                  $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
               }
            }
            break;
         case ($case == "my exercise records-exported" || $case == "sample exercise records-exported" || $case == "default exercise records-exported" || $case == "shared exercise records-exported" || $case == "my exercise records-exported selected" || $case == "sample exercise records-exported selected" || $case == "default exercise records-exported selected" || $case == "shared exercise records-exported selected" || $case == "my workout plans-exported" || $case == "sample workout plans-exported" || $case == "default workout plans-exported" || $case == "shared workout plans-exported"):
            $jsondata = json_decode($value['json_data']);
            if ($jsondata) {
               $stringcontent .= 'to <a href="javascript:void(0);">"' . $jsondata . '"</a>';
            }
            break;
         case "exercise record-modified1":
            break;
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="preview_erexrcise(' . $value['type_id'] . ',\'' . $result[0][$fetch_field] . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a> ';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $unistatusid  = implode(",", $jsondata);
                  $fetch_field  = 'status_title';
                  $fetch_condtn = 'status_id in (' . $unistatusid . ')';
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_status', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $ss = '';
                     foreach ($result as $i => $j) {
                        $ss .= '<a href="javascript:void(0);" >' . $j[$fetch_field] . "</a>, ";
                     }
                     $ss = substr($ss, 0, -2);
                     $stringcontent .= ' to  ' . $ss;
                  }
               }
            }
            break;
         case "exercise record-tagged":
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="preview_erexrcise(' . $value['type_id'] . ',\'' . $result[0][$fetch_field] . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a> ';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $tageids      = implode(",", $jsondata);
                  $fetch_field  = 'tag_title';
                  $fetch_condtn = 'tag_id in (' . $tageids . ')';
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $ss = '';
                     foreach ($result as $i => $j) {
                        $ss .= '<a href="javascript:void(0);" >' . $j[$fetch_field] . "</a>, ";
                     }
                     $ss = substr($ss, 0, -2);
                     $stringcontent .= ' with ' . $ss;
                  }
               }
            }
            break;
         case ($case == "image-created" || $case == "image-uploaded" || $case == "image-replaced" || $case == "image-shared" || $case == "image data-edited" || $case == "image data-modified" || $case == "image-edited" || $case == "image-modified" || $case == "image-opened" || $case == "image data-opened" || $case == "image-previewed" || $case == "image-copied" || $case == "image-exited" || $case == "image data-exited"):
            if ($case == "image data-edited" || $case == "image data-modified") {
               $stringcontent .= ' for ';
            } elseif ($case == "image data-opened") {
               $stringcontent .= ' of ';
            }
            $fetch_field  = 'img_title, img_url';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $imgurl = '';
               if (!empty($result[0]['img_url']) && file_exists($result[0]['img_url'])) {
                  $imgurl = URL::base(TRUE) . $result[0]["img_url"];
               }
               $stringcontent .= '<a class="common_thumb-img wrapword" ' . ($isFront != true ? 'onclick="common_popuptriggerImgPrevModal(this);"' : '') . '  data-itemtype="folder" data-itemurl="/' . $result[0]['img_url'] . '" data-itemname="AVRUPATURiZM" data-itemid="' . $value['type_id'] . '"  >' . $result[0]["img_title"] . '</a>';
            }
            if (!empty($value['json_data'])) {
               $jsonData = json_decode($value['json_data']);
               $stringcontent .= " " . $jsonData->text;
            }
            break;
         case "image-deleted":
            $jsondata = json_decode($value['json_data']);
            if ($jsondata) {
               $stringcontent .= '<a href="javascript:void(0);">"' . $jsondata->text . '"</a>';
            }
            break;
         case "image-modified1":
            break;
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
               $jsondata = json_decode($value['json_data']);
               if (isset($jsondata) && !empty($jsondata)) {
                  $unistatusid  = implode(",", $jsondata);
                  $fetch_field  = 'status_title';
                  $fetch_condtn = 'status_id in (' . $unistatusid . ')';
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_status', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $ss = '';
                     foreach ($result as $i => $j) {
                        $ss .= '<a href="javascript:void(0);" >' . $j[$fetch_field] . "</a>, ";
                     }
                     $ss = substr($ss, 0, -2);
                     $stringcontent .= ' to  ' . $ss;
                  }
               }
            }
            break;
         case "image-tagged":
            $fetch_field  = 'img_title';
            $fetch_condtn = 'img_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $tageids      = implode(",", $jsondata);
                  $fetch_field  = 'tag_title';
                  $fetch_condtn = 'tag_id in (' . $tageids . ')';
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $ss = '';
                     foreach ($result as $i => $j) {
                        $ss .= '<a href="javascript:void(0);" >' . $j[$fetch_field] . "</a>, ";
                     }
                     $ss = substr($ss, 0, -2);
                     $stringcontent .= ' with ' . $ss;
                  }
               }
            }
            break;
         case ($case == "tag-removed" || $case == "tag-deleted"):
            $jsondata = json_decode($value['json_data']);
            if ($jsondata && isset($jsondata->tag_id) && isset($jsondata->text)) {
               $tageids      = implode(",", $jsondata->tag_id);
               $fetch_field  = 'tag_title';
               $fetch_condtn = 'tag_id in (' . $tageids . ')';
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag', $fetch_field, $fetch_condtn);
               if (isset($result) && count($result) > 0) {
                  $ss = '';
                  foreach ($result as $i => $j) {
                     $ss .= '"<a href="javascript:void(0);" >' . $j[$fetch_field] . "</a>\", ";
                  }
                  if (count($result) > 1) {
                     if ($case == "tag-removed") {
                        $string = str_replace("removed tag", "removed tags", $string);
                     } else {
                        $string = str_replace("deleted tag", "deleted tags", $string);
                     }
                  }
                  $ss = substr($ss, 0, -2);
                  $stringcontent .= $ss;
                  $stringcontent .= " " . $jsondata->text;
                  if ($jsondata->text == "from image") {
                     $fetch_field  = 'img_title';
                     $fetch_condtn = 'img_id=' . $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
                     }
                  } elseif ($jsondata->text == "from exercise record") {
                     $fetch_field  = 'title';
                     $fetch_condtn = 'unit_id=' . $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
                     }
                  } elseif ($jsondata->text == "from workout plan") {
                     $fetch_field  = 'wkout_title';
                     $fetch_condtn = 'wkout_id=' . $value['type_id'];
                     $wkout_id     = $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
                     }
                     break;
                  } elseif ($jsondata->text == "from user") {
                     $fetch_field  = 'concat(user_fname," ",user_lname)';
                     $fetch_condtn = 'id=' . $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
                     }
                  }
               }
            }
            break;
         case "tag-created":
            $jsondata = json_decode($value['json_data']);
            if ($jsondata && isset($jsondata->tag_id) && isset($jsondata->text)) {
               $tageids      = implode(",", $jsondata->tag_id);
               $fetch_field  = 'tag_title';
               $fetch_condtn = 'tag_id in (' . $tageids . ')';
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag', $fetch_field, $fetch_condtn);
               if (isset($result) && count($result) > 0) {
                  $ss = '';
                  foreach ($result as $i => $j) {
                     $ss .= '<a href="javascript:void(0);" >' . $j[$fetch_field] . "</a>, ";
                  }
                  $ss = substr($ss, 0, -2);
                  $stringcontent .= " " . $jsondata->text;
                  if ($jsondata->text == "for workout plan") {
                     $fetch_field  = 'wkout_title';
                     $fetch_condtn = 'wkout_id=' . $value['type_id'];
                     $wkout_id     = $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
                        $stringcontent .= ' with "' . $ss . '"';
                     }
                  } elseif ($jsondata->text == "for user") {
                     $userid = $jsondata->user_id;
                     if ($userid) {
                        $subscriber = array();
                        $str        = "";
                        if (count($userid) > 1) {
                           $i   = 0;
                           $str = "$str <a href='javascript:void(0);' " . ($isFront != true ? "onclick='show_others(\"" . implode(",", $userid) . "\")'" : '') . ">" . count($userid) . " users</a>";
                        } else {
                           foreach ($userid as $k => $v) {
                              $jsonuser = Model::instance('Model/admin/shareworkout')->getuserdetails($v);
                              $jsonuser = $jsonuser[0];
                              $str .= " <a href='javascript:void(0);' " . ($isFront != true ? "onclick='viewusers($v)'" : '') . ">";
                              $str .= $jsonuser["user_fname"] . ' ' . $jsonuser['user_lname'] . "</a>, ";
                           }
                           $str = substr($str, 0, -2);
                        }
                        $stringcontent .= $str;
                     }
                     $stringcontent .= ' with "' . $ss . '"';
                  } elseif ($jsondata->text == "for image") {
                     $fetch_field  = 'img_title';
                     $fetch_condtn = 'img_id=' . $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('img', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
                        $stringcontent .= ' with "' . $ss . '"';
                     }
                  } elseif ($jsondata->text == "for exercise record") {
                     $fetch_field  = 'title';
                     $fetch_condtn = 'unit_id=' . $value['type_id'];
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a> ';
                        $stringcontent .= ' with "' . $ss . '"';
                     }
                  }
               }
            }
            break;
         case ($case == "account-modified password" || $case == "account-forgot password" || $case == "account-activated" || $case == "account-registered" || $case == "logged-workout plan" || $case == "workout journal-edited" || $case == "workout journal-deleted" || $case == 'account confirmation-resend'):
            $stringcontent .= (("account-modified password" || $case == "account-forgot password" || $case == "account-activated" || $case == "account-registered" || $case == 'account confirmation-resend') ? 'on ' : '') . Helper_Common::change_default_datetime($value['created_date']) . (!empty($value['json_data']) ? ' ' . json_decode($value['json_data']) : '');
            break;
         case ($case == "-logged-out" || $case == "account-logged-out" || $case == "account-logged-in"):
            $stringcontent .= 'on ' . Helper_Common::change_default_datetime($value['created_date']);
            break;
         case ($case == "sample workout plan-created" || $case == "sample workout plan-previewed"):
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);"  ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'previewsample' . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a> ';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata && isset($jsondata->text)) {
                  $jstxt = trim($jsondata->text);
                  $stringcontent .= $jstxt;
                  if ($jstxt == "from workout plan") {
                     $fetch_field  = 'wkout_title';
                     $fetch_condtn = 'wkout_id=' . $jsondata->wkoutid;
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $jsondata->wkoutid . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
                     }
                  } else if ($jstxt == "from shared workout plan") {
                     $fetch_field  = 'wkout_title';
                     $fetch_condtn = 'wkout_share_id=' . $jsondata->wkoutid;
                     $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
                     if (isset($result) && count($result) > 0) {
                        $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $jsondata->wkoutid . '\',\'' . 'shared' . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a>';
                     }
                  }
               }
            }
            break;
         case ($case == "sample workout plan-deleted" || $case == "sample workout plan-created" || $case == "sample workout plan-logged" || $case == "sample workout plan-cancelled" ||  $case == "sample workout plan-printed" || $case == "sample workout plan-edited"): 
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
				if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="getsampleworkoutpreview(\'' . $value['type_id'] . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a> ';
            }
            break;
         case ($case == "workout journal-created" || $case == "workout journal-cancelled" || $case == "workout journal-modified" || $case == "workout journal-opened" || $case == "journal-edited" || $case == "workout journal-previewed" || $case == "journal-Marked as Skipped"):
            $fetch_field  = 'wkout_title,assigned_date,status_id';
            $fetch_condtn = 'wkout_log_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               if ($result[0]['status_id'] != 4) {
                  $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'logged' . '\')"' : '') . '>"';
                  $stringcontent .= $result[0]['wkout_title'];
                  $stringcontent .= '"</a>';
               } else {
                  $stringcontent .= '<a href="javascript:void(0);" onclick="alert(\'This journal was removed\')" data-id="'.$value['type_id'].'">';
                  $stringcontent .= $result[0]['wkout_title'];
                  $stringcontent .= '"</a>';
               }
               if ($case != "workout journal-previewed" && $case != "journal-Marked as Skipped" && $case != "workout journal-opened") {
                  $stringcontent .= ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob((!empty($value['context_date']) && $value['context_date'] != '0000-00-00 00:00:00' ? $value['context_date'] : $result[0]['assigned_date'])) . '</span>';
               }elseif($case == "workout journal-opened" && !empty($value['json_data'])){
				   $stringcontent .= ' in <span class="activedatacol">' .json_decode($value['json_data']). '</span>';
			   }
            }
            break;
         case ($case == "journal-Marked as Completed"):
            $fetch_field  = 'wkout_title,assigned_date';
            $fetch_condtn = 'wkout_log_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'logged' . '\')"' : '') . '>"' . $result[0]['wkout_title'] . '"</a>';
               $stringcontent .= ' for <span class="activedatacol">' . Helper_Common::change_default_date_dob((!empty($value['context_date']) ? $value['context_date'] : $result[0]['assigned_date'])) . '</span>';
            }
            break;
         case ($case == "workout folder-created" || $case == "workout folder-opened" || $case == "workout folder-deleted"):
            if (!empty($value['type_id'])) {
               $fetch_field  = 'folder_title';
               $fetch_condtn = 'id=' . $value['type_id'];
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
               if (isset($result) && count($result) > 0) {
                  $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
				  $jsonData = json_decode($value['json_data']);
				  if (!empty($jsonData)) {
					 $fetch_condtn = 'id=' . $jsonData;
					 $resultnew       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', 'folder_title', $fetch_condtn);
					 if (isset($resultnew) && count($resultnew) > 0) {
						 $stringcontent .= ' in <a href="javascript:void(0);">"' . $resultnew[0]['folder_title'] . '"</a> Folder';
					 }
				 }else{
					 $stringcontent .= ' in <a href="javascript:void(0);" >"My Workout Plans"</a>';
				 }
               }
            } else
               $stringcontent .= '<a href="javascript:void(0);" >"My Workout Plans"</a>';
            break;
         case ($case == "workout folder-moved"):
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>' . ' to ' . '<a href="javascript:void(0);" >"' . json_decode($value['json_data']) . '"</a>';
            }
            break;
         case ($case == "workout folder-copied"):
            $fetch_field  = 'folder_title';
            $fetch_condtn = 'id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>' . ' as ' . '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         case ($case == "workout folder-modified"):
		    $fetch_field  = 'folder_title';
		    $fetch_condtn = 'id=' . $value['type_id'];
		    $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
			$jsonData = json_decode($value['json_data']);
		    if (isset($result) && count($result) > 0) {
				if (!empty($jsonData) && isset($jsonData->oldname) && !empty($jsonData->oldname)) {
					$stringcontent .= '<a href="javascript:void(0);" >"' . $jsonData->oldname . '"</a> to ';
				}
			    $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
			    $jsonData = json_decode($value['json_data']);
			    if (!empty($jsonData) && isset($jsonData->wkoutfolder) && !empty($jsonData->wkoutfolder)) {
					$fetch_condtn = 'id=' . $jsonData->wkoutfolder;
					$resultnew       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', 'folder_title', $fetch_condtn);
					if (isset($resultnew) && count($resultnew) > 0) {
						$stringcontent .= ' in <a href="javascript:void(0);">"' . $resultnew[0]['folder_title'] . '"</a> Folder';
					}
				}else{
					$stringcontent .= ' from <a href="javascript:void(0);" >"My workout plans folder"</a>';
				}
            }
            break;
         case ($case == "workout plan-previewed" || $case == "my workout plan-previewed" || $case == "sample workout plan-previewed" || $case == "workout plan-exported" || $case == "my workout plan-exported" || $case == "sample workout plan-exported" || $case == "shared workout plan-exported" || $case == "workout plan-exported selected" || $case == "sample workout plan-exported selected" || $case == "shared workout plan-exported selected" || $case == "default workout plan-exported selected" || $case == 'assigned workout plan-exported' || $case =='workout journal-exported'):
            $fetch_field  = 'wkout_title';
            $wkout_id     = $value['type_id'];
			$viewType	  = '';
			if($case == "sample workout plan-previewed" || $case == "sample workout plan-exported"){
				$fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'previewsample';
			}else if($case == "shared workout plan-exported"){
				$fetch_condtn = 'wkout_share_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'previewshared';
			}else if($case == 'assigned workout plan-exported'){
				$fetch_condtn = 'wkout_assign_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'assigned';
			}else if($case == 'workout journal-exported'){
				$fetch_condtn = 'wkout_log_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'logged';
			}else{
				$fetch_condtn = 'wkout_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
			}
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\',\'' . $viewType . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
            }
            if (!empty($value['json_data'])) {
               $jsonData = json_decode($value['json_data']);
               if (!empty($jsonData) && is_numeric($jsonData)) {
                  $fetch_field  = 'folder_title';
                  $fetch_condtn = 'id=' . $jsonData;
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' to <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
                  }
               } elseif (!empty($jsonData)) {
                  $stringcontent .= ' to <a href="javascript:void(0);">"' . $jsonData . '"</a>';
               }
            }
            break;
		 case ($case == "workout plan-copied" || $case == "my workout plan-copied" || $case == "sample workout plan-copied" || $case == "shared workout plan-copied" || $case == "assigned workout plan-copied" || $case == "default workout plan-copied" || $case == "workout journal-copied"):
            $fetch_field  = 'wkout_title';
            $wkout_id     = $value['type_id'];
			$viewType	  = '';
			if($case == "sample workout plan-copied"){
				$fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'previewsample';
			}else if($case == "shared workout plan-copied"){
				$fetch_condtn = 'wkout_share_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'previewshared';
			}else if($case == "workout journal-copied"){
				$fetch_condtn = 'wkout_log_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'logged';
			}else if($case == "assigned workout plan-copied"){
				$fetch_condtn = 'wkout_assign_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
				$viewType	  = 'assigned';
			}else{
				$fetch_condtn = 'wkout_id=' . $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
			}
            if (isset($result) && count($result) > 0) {
               $stringcontent .= 'from <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\',\'' . $viewType . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
            }
            if (!empty($value['json_data'])) {
               $jsonData = json_decode($value['json_data']);
               if (!empty($jsonData) && isset($jsonData->wkout)) {
				  $fetch_condtn = 'wkout_id=' . $jsonData->wkout;
				  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' as New Workout <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $jsonData->wkout . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a>';
					 if (!empty($jsonData) && isset($jsonData->wkoutfolder) && !empty($jsonData->wkoutfolder)) {
						 $fetch_condtn = 'id=' . $jsonData->wkoutfolder;
						 $resultnew       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', 'folder_title', $fetch_condtn);
						 if (isset($resultnew) && count($resultnew) > 0) {
							 $stringcontent .= ' in <a href="javascript:void(0);">"' . $resultnew[0]['folder_title'] . '"</a> Folder';
					     }
					 }
                  }
               }else if (!empty($jsonData) && (isset($jsonData->wkoutsample) || isset($jsonData->wkoutdefault))) {
				  $wkoutid = (isset($jsonData->wkoutdefault) ? $jsonData->wkoutdefault : $jsonData->wkoutsample);
				  $fetch_condtn = 'wkout_sample_id=' . $wkoutid;
				  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' as New '.(isset($jsonData->wkoutdefault) ? 'Default' : 'Sample').' Workout <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkoutid . '\',\''.'previewsample'.'\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a>';
                  }
               }elseif (!empty($jsonData) && isset($jsonData->wkoutlog)) {
				  $fetch_field  .= ',assigned_date';
				  $fetch_condtn = 'wkout_log_id=' . $jsonData->wkoutlog;
				  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' as New Journal entry <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $jsonData->wkoutlog . '\',\'logged\')"' : '') . '>"' . $result[0]['wkout_title'] . '"</a> on <a href="javascript:void(0);" >"' . Helper_Common::change_default_date_dob($result[0]['assigned_date']) . '"</a>';
                  }
               }elseif (!empty($jsonData) && isset($jsonData->wkoutassign)) {
				  $fetch_field  .= ',assigned_date';
				  $fetch_condtn = 'wkout_assign_id=' . $jsonData->wkoutassign;
				  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' as New Assignment <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $jsonData->wkoutassign . '\',\'assigned\')"' : '') . '>"' . $result[0]['wkout_title'] . '"</a> on <a href="javascript:void(0);" >"' . Helper_Common::change_default_date_dob($result[0]['assigned_date']) . '"</a>';
					 if (!empty($jsonData) && isset($jsonData->createdbyuser)) {
						$fetch_field  = 'concat(user_fname," ",user_lname) as name';
						$fetch_condtn = 'id=' . $jsonData->createdbyuser;
						$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
						if ($result) {
						   $result = $result[0];
						   $stringcontent .= ' from <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="showUserModel(\'' . $jsonData->createdbyuser . '\',1)"' : '') . '>' . $result["name"] . '</a> ';
						}
					 }
                  }
               }elseif (!empty($jsonData)) {
                  $stringcontent .= ' to <a href="javascript:void(0);">"' . $jsonData . '"</a>';
               }
            }
            break;
         case ($case == "exercise record-created" || $case == "exercise record-deleted" || $case == "exercise record-rated" || $case == "exercise record-opened" || $case == "exercise record-edited" || $case == "exercise record-shared" || $case == "exercise record-modified" || $case == "exercise record-previewed" || $case == "exercise record-copied" || $case == "exercise record-exited"):
            $fetch_field  = 'title';
            $fetch_condtn = 'unit_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('unit_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="preview_erexrcise(' . $value['type_id'] . ',\'' . $result[0][$fetch_field] . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a>';
            }
            if (!empty($value['json_data'])) {
               $jsonData = json_decode($value['json_data']);
               if (isset($jsonData->text))
                  $stringcontent .= " " . $jsonData->text;
               elseif (isset($jsonData[0]) && !empty($jsonData[0]) && is_numeric($jsonData[0])) {
                  $fetch_field  = 'concat(user_fname," ",user_lname) as name';
                  $fetch_condtn = 'id=' . $jsonData[0];
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
                  if ($result) {
                     $result = $result[0];
                     $stringcontent .= ' to <a href="javascript:void(0);" onclick="showUserModel(\'' . $jsonData[0] . '\',1)">' . $result["name"] . '</a> ';
                  }
               }
            }
            break;
         case ($case == "assigned workout plan-edited"): // || $case == 'assigned workout plan-created'
            $fetch_field     = 'wkout_title, assigned_date';
            $fetch_condtn    = 'wkout_assign_id=' . $value['type_id'];
            $wkout_assign_id = $value['type_id'];
            $result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
				if (isset($result) && count($result) > 0) {
               $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'assigned' . '\')"' : '') . ' >"' . $result[0]['wkout_title'] . '"</a>';
               $stringcontent .= ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob((!empty($value['context_date']) ? $value['context_date'] : $result[0]['assigned_date'])) . '</span>';
            }
            break;
			case ($case == 'assigned workout plan-created'):
				if (!empty($value['json_data'])) {
					$jsonData = json_decode($value['json_data']);
					$fetch_field     = 'wkout_title, assigned_date';
					$fetch_condtn    = 'wkout_assign_id=' . $jsonData->assigned;
					$result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
					if (isset($result) && count($result) > 0) {
						$stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'assigned' . '\')"' : '') . ' >"' . $result[0]['wkout_title'] . '"</a>';
						$stringcontent .= ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob((!empty($value['context_date']) ? $value['context_date'] : $result[0]['assigned_date'])) . '</span>';
						if (!empty($jsonData) && isset($jsonData->createdbyuser)) {
							$fetch_field  = 'concat(user_fname," ",user_lname) as name';
							$fetch_condtn = 'id=' . $jsonData->createdbyuser;
							$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
							if ($result) {
							   $result = $result[0];
							   $stringcontent .= ' for <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="showUserModel(\'' . $jsonData->createdbyuser . '\',1)"' : '') . '>' . $result["name"] . '</a> ';
							}
						}
					}
				}
            break;
         case ($case == "assigned workout plan-previewed" || $case == "assigned workout plan-modified" || $case == "assigned workout plan-opened" || $case == "assigned workout plan-rescheduled" || $case == "assigned workout plan-deleted"):
            $fetch_field     = 'wkout_title, assigned_date';
            $fetch_condtn    = 'wkout_assign_id=' . $value['type_id'];
            $wkout_assign_id = $value['type_id'];
            $result          = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'assigned' . '\')"' : '') . '>"' . $result[0]['wkout_title'] . '"</a>';
               $stringcontent .= ' from <span class="activedatacol">' . Helper_Common::change_default_date_dob((!empty($value['context_date']) && $value['context_date'] != '0000-00-00 00:00:00' ? $value['context_date'] : $result[0]['assigned_date'])) . '</span>';
               if ($case == "assigned workout plan-opened" && !empty($value['json_data']))
					$stringcontent .= ' in <a href="javascript:void(0);" >"' . json_decode($value['json_data']) . '"</a>';
               else if (($case == "assigned workout plan-rescheduled" || ($case == "assigned workout plan-copied" && $value['context_date'] != '0000-00-00 00:00:00')) && !empty($value['json_data'])) {
                  $stringcontent .= ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob(json_decode($value['json_data'])) . '</span>';
               } else if ($case == "assigned workout plan-copied" && $value['context_date'] == '0000-00-00 00:00:00') {
                  $stringcontent .= ' as <span class="activedatacol">' . json_decode($value['json_data']) . '</span>';
               }
            }
            break;
			case ($case == "assigned workout plan-assigned"):
				$fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_assign_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
				if (isset($result) && count($result) > 0) {
					//$stringcontent .= ' as <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
					$stringcontent .= 'as <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'assigned' . '\')"' : '') . ' >"' . $result[0][$fetch_field] . '"</a>';
				}
				$stringcontent .= ($value['context_date'] != '0000-00-00 00:00:00' ? ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob($value['context_date']) . '</span>' : '');
				break;
         case ($case == "workout plan-logged" ||  $case == "workout plan-assigned" ||  $case == "workout plan-exited"):
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
               $assignArray = json_decode($value['json_data']);
               if (!empty($value['json_data']) && (isset($assignArray->assigned) || isset($assignArray->logged))) {
                  $assignId     = (isset($assignArray->assigned) ? $assignArray->assigned : $assignArray->logged);
                  $fetch_field  = 'wkout_title';
                  $fetch_condtn = (isset($assignArray->assigned) ? 'wkout_assign_id=' . $assignId : 'wkout_log_id=' . $assignId);
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn((isset($assignArray->assigned) ? 'wkout_assign_gendata' : 'wkout_log_gendata'), $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' as <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
                  }
               } else if (!empty($value['json_data'])) {
                  $stringcontent .= ' as <a href="javascript:void(0);" >"' . $assignArray . '"</a>';
               }
               $stringcontent .= ($value['context_date'] != '0000-00-00 00:00:00' ? ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob($value['context_date']) . '</span>' : '');
            }
            break;
			case ($case == 'assigned workout plan-logged'):
				$fetch_field  = 'wkout_title';
				$fetch_condtn = 'wkout_assign_id=' . $value['type_id'];
				$wkout_id     = $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
				$stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\',\'assigned\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
				$assignArray = json_decode($value['json_data']);
				if (!empty($value['json_data']) && isset($assignArray->wkoutlog)) {
					$fetch_field  = 'wkout_title, assigned_date';
					$fetch_condtn = 'wkout_log_id=' . $assignArray->wkoutlog;
					$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
					if (isset($result) && count($result) > 0) {
						$stringcontent .= ' as  <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $assignArray->wkoutlog . '\',\'logged\')"' : '') . '>"' . $result[0]['wkout_title'] . '"</a>'.' to <span class="activedatacol">' . Helper_Common::change_default_date_dob($result[0]['assigned_date']) . '</span> '.(isset($assignArray->text) ? $assignArray->text : '');
					}
				}
				
			break;
			case ($case == "assigned workout plan-logged" || $case == 'assigned workout plan-exited' || $case == 'workout journal-exited'):
			if($case == 'assigned workout plan-exited'){
				$fetch_field  = 'wkout_title';
				$fetch_condtn = 'wkout_assign_id=' . $value['type_id'];
				$wkout_id     = $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_assign_gendata', $fetch_field, $fetch_condtn);
			}elseif($case == 'workout journal-exited'){
				$fetch_field  = 'wkout_title';
				$fetch_condtn = 'wkout_log_id=' . $value['type_id'];
				$wkout_id     = $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
			}else{
				$fetch_field  = 'wkout_title';
				$fetch_condtn = 'wkout_log_id=' . $value['type_id'];
				$wkout_id     = $value['type_id'];
				$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata', $fetch_field, $fetch_condtn);
			}
            if (isset($result) && count($result) > 0) {
				if($case == 'assigned workout plan-exited')
					$stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\',\'assigned\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
				elseif($case == 'workout journal-exited')
					$stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\',\'logged\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
			    else
					$stringcontent .= ' <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\',\'\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
               $assignArray = json_decode($value['json_data']);
               if (!empty($value['json_data']) && (isset($assignArray->assigned) || isset($assignArray->logged))) {
                  $assignId     = (isset($assignArray->assigned) ? $assignArray->assigned : $assignArray->logged);
                  $fetch_field  = 'wkout_title';
                  $fetch_condtn = (isset($assignArray->assigned) ? 'wkout_assign_id=' . $assignId : 'wkout_log_id=' . $assignId);
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn((isset($assignArray->assigned) ? 'wkout_assign_gendata' : 'wkout_log_gendata'), $fetch_field, $fetch_condtn);
						if (isset($result) && count($result) > 0) {
                     $stringcontent .= ' as  <a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $assignId . '\',\'assigned\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
                  }
               } else if (!empty($value['json_data'])) {
                  $stringcontent .= ' as <a href="javascript:void(0);" >"' . $assignArray . '"</a>';
               }
               $stringcontent .= ($value['context_date'] != '0000-00-00 00:00:00' ? ' to <span class="activedatacol">' . Helper_Common::change_default_date_dob($value['context_date']) . '</span>' : '');
            }
            break;
         case ($case == "workout plan-opened" || $case == "workout plan-reopened" || $case == "workout plan-deleted" || $case == "workout plan-created" || $case == "workout plan-edited" || $case == "workout plan-modified"):
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
               $json_data = json_decode($value['json_data']);
			   if(!empty($json_data)){
				   if(isset($json_data->wkoutfolder)){
					  if(!empty($json_data->wkoutfolder)){
						  $fetch_field  = 'folder_title';
						  $fetch_condtn = 'id=' . $json_data->wkoutfolder;
						  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
						  if (isset($result) && count($result) > 0 && $result[0][$fetch_field] != '') {
							 $stringcontent .= ' in <a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '" Folder</a>';
						  }
					  }else{
						  $stringcontent .= ' from <a href="javascript:void(0);" >"My workout plans folder"</a>';
					  }
				   }else{
					   $stringcontent .= ' in <a href="javascript:void(0);" >"' . $json_data . '"</a>';
				   }
			   }else {
                  if ($case != "workout plan-edited" && $case != "workout plan-modified" && $case != "workout plan-deleted")
                     $stringcontent .= ' in <a href="javascript:void(0);" >"My Workout Plans folder"</a>';
               }
            }
            break;
         case "workout plan-moved":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
               $jsondata = json_decode($value['json_data']);
               if (!empty($jsondata) && is_numeric($jsondata)) {
                  $stringcontent .= ' to workout folder';
                  $subscriber   = array();
                  $str          = "";
                  $fetch_field  = 'folder_title';
                  $fetch_condtn = 'id=' . $jsondata["id"];
                  $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
                  if (isset($result) && count($result) > 0) {
                     $stringcontent .= '<a href="javascript:void(0);" >"' . $result[0][$fetch_field] . '"</a>';
                  }
                  $stringcontent .= $str;
               } elseif (!empty($jsondata)) {
                  $stringcontent .= ' to <a href="javascript:void(0);" >"' . $jsondata . '"</a>';
               }
            }
            break;
         case ($case == "shared workout plan-opened" || $case == "shared workout plan-cancelled" || $case == "shared workout plan-logged" || $case == 'shared workout plan-previewed' || $case == "shared workout plan-Hide" || $case == "shared workout plan-removed"):
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_share_id=' . $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'previewshared' . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
			   if($case = "shared workout plan-Hide" || $case = "shared workout plan-removed"){
				   $fetch_field  = 'shared_by';
				   $fetch_condtn = 'wkout_share_id=' . $value['type_id'];
				   $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_seq', $fetch_field, $fetch_condtn);
				   if (isset($result) && count($result) > 0) {
					  $jsonuser = Model::instance('Model/admin/shareworkout')->getuserdetails($result[0][$fetch_field]);
					  if (isset($jsonuser) && count($jsonuser) > 0) {
						 $jsonuser = $jsonuser[0];
						 $stringcontent .= " from <a href='javascript:void(0);' " . ($isFront != true ? "onclick='viewusers(" . $result[0][$fetch_field] . ")'" : '') . ">";
						 $stringcontent .= $jsonuser["user_fname"] . ' ' . $jsonuser['user_lname'] . "</a> ";
					  }
				   }
			   }
            }
            break;
         case ($case == "workout plan-shared" || $case == "sample workout plan-shared"):
            $jsondata = json_decode($value['json_data']);
            if (isset($jsondata->from_wkout) && $jsondata->from_wkout == "myworkout") {
               $fetch_field  = 'wkout_title';
               $fetch_condtn = 'wkout_id=' . $value['type_id'];
               $wkout_id     = $value['type_id'];
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
            } else if (isset($jsondata->from_wkout) && $jsondata->from_wkout == "sample-workout") {
               $fetch_field  = 'wkout_title';
               $fetch_condtn = 'wkout_sample_id=' . $value['type_id'];
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata', $fetch_field, $fetch_condtn);
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $value['type_id'] . '\',\'' . 'previewsample' . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
            } else {
               $fetch_field  = 'wkout_title';
               $fetch_condtn = 'wkout_id=' . $value['type_id'];
               $wkout_id     = $value['type_id'];
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
            }
            if (isset($jsondata->sharedto)) {
               $fetch_field  = 'concat(user_fname," ",user_lname) as name';
               $fetch_condtn = 'id=' . $jsondata->sharedto;
               $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
               if ($result) {
                  $result = $result[0];
                  if (isset($jsondata->sharedto) && isset($user_id) && $user_id == $jsondata->sharedto) {
                     $stringcontent .= ' to <a href="javascript:void(0);" onclick="showUserModel(\'' . $jsondata->sharedto . '\',1)">You</a> ';
                  } else {
                     $stringcontent .= ' to <a href="javascript:void(0);" onclick="showUserModel(\'' . $jsondata->sharedto . '\',1)">' . $result["name"] . '</a> ';
                  }
               }
            }
            if (isset($jsondata->subscriber)) {
               $subscriber = array();
               $str        = "";
               if (count($jsondata->subscriber) > 1) {
                  $i   = 0;
                  $str = "$str and <a href='javascript:void(0);' " . ($isFront != true ? "onclick='show_others(\"" . implode(",", $jsondata->subscriber) . "\")'" : '') . ">" . count($jsondata->subscriber) . " users</a>";
               } else {
                  $stringcontent .= ' to ';
                  foreach ($jsondata->subscriber as $k => $v) {
                     $jsonuser = Model::instance('Model/admin/shareworkout')->getuserdetails($v);
                     $jsonuser = $jsonuser[0];
                     $str .= "<a href='javascript:void(0);' " . ($isFront != true ? "onclick='viewusers($v)'" : '') . ">";
                     $str .= $jsonuser["user_fname"] . ' ' . $jsonuser['user_lname'] . "</a>, ";
                  }
                  $str = substr($str, 0, -2);
               }
               $stringcontent .= $str;
            } else if (isset($jsondata) && count($jsondata) > 0 && is_array($jsondata)) {
               $subscriber = array();
               $str        = "";
               if (count($jsondata) > 1) {
                  $i   = 0;
                  $str = "$str and <a href='javascript:void(0);' " . ($isFront != true ? "onclick='show_others(\"" . implode(",", $jsondata) . "\")'" : '') . ">" . count($jsondata) . " users</a>";
               } else {
                  $stringcontent .= ' to ';
                  foreach ($jsondata as $k => $v) {
                     $jsonuser = Model::instance('Model/admin/shareworkout')->getuserdetails($v);
                     $jsonuser = $jsonuser[0];
                     $str .= "<a href='javascript:void(0);' " . ($isFront != true ? "onclick='viewusers($v)'" : '') . ">";
                     $str .= $jsonuser["user_fname"] . ' ' . $jsonuser['user_lname'] . "</a>, ";
                  }
                  $str = substr($str, 0, -2);
               }
               $stringcontent .= $str;
            }
            break;
         case "workout plan-tagged":
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
               $stringcontent .= ' with ';
               $jsondata = json_decode($value['json_data']);
               if ($jsondata) {
                  $subscriber = array();
                  $str        = "";
                  foreach ($jsondata as $k => $v) {
                     $jsontag = Model::instance('Model/admin/shareworkout')->gettagdetails($v);
                     $jsontag = $jsontag[0];
                     $str .= '<a href="javascript:void(0);" >"';
                     $str .= $jsontag["tag_title"] . '"</a>, ';
                  }
                  $str = substr($str, 0, -2);
                  $stringcontent .= $str;
               }
            }
            break;
         case ($case == "assigned-assigned" || $case == "assigned-edited" || $case == "assigned-copied" || $case == "assigned-logged"):
            $fetch_field  = 'wkout_title';
            $fetch_condtn = 'wkout_id=' . $value['type_id'];
            $wkout_id     = $value['type_id'];
            $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata', $fetch_field, $fetch_condtn);
            if (isset($result) && count($result) > 0) {
               $stringcontent .= '<a href="javascript:void(0);" ' . ($isFront != true ? 'onclick="viewwkout(\'' . $wkout_id . '\')"' : '') . '>"' . $result[0][$fetch_field] . '"</a>';
            }
            break;
         default:
            break;
      }
      $stringcontent .= "</p>";
      return $stringcontent;
      return $string;
   }
}
?>
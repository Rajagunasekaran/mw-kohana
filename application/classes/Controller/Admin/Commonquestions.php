<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Commonquestions extends Controller_Admin_Website
{
   public function _Construct()
   {
      parent::__construct($request, $response);
   }
   public function action_index()
   {
      $this->template->title = 'Manage Common Questions';
      $questionmodel         = ORM::factory('admin_commonquestions');
      $lim                   = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
      $dataall               = $questionmodel->getallQuestions();
      $cnt                   = count($dataall); //echo $cnt;
      $pagination            = pagination::factory(array(
         'total_items' => $cnt,
         'items_per_page' => $lim,
         //'auto_hide'         => TRUE,
         'first_page_in_url' => TRUE
      ));
      //echo $pagination->items_per_page; 
      if (isset($_REQUEST['page'])) {
         $page_number = $_REQUEST['page'];
      } else {
         $page_number = 1;
      }
      $offset = $lim * ($page_number - 1);
      // Pass controller and action names explicitly to $pagination object
      $pagination->route_params(array(
         'controller' => $this->request->controller(),
         'action' => $this->request->action()
      ));
      $getQuestions = $questionmodel->getallQuestions($pagination->items_per_page, $offset); //$wkoutf_val;
      $this->render();
      $this->template->css                 = array(
         'assets/plugins/tinytoggle/css/tiny-toggle.css'
      );
      $this->template->js_bottom           = array(
         'assets/plugins/tinytoggle/js/tiny-toggle.js'
      ); //, 'assets/js/pages/admin/setting.js');
      $this->template->content->pagination = $pagination;
      $this->template->content->lim        = $lim;
      $this->template->content->questions  = $getQuestions;
   }
   public function action_changequestionorder()
   {
      $datetime      = Helper_Common::get_default_datetime();
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         //echo "<pre>"; print_r($post); die;
         //$data = $post["data"][0];
			$data = $post["data"];
         $i    = 1;
         foreach ($data as $k => $v) {
            //$qid                = str_replace("question_", '', $v["id"]);
				$qid                = str_replace("row-", '', $v);
            $update["sequence"] = $i++;
            $questionmodel->updateQuestions($update, $qid);
         }
         $this->session->set('success', 'Your Question order was changed Successfully!!!');
         echo true;
         exit;
      }
   }
   public function action_changeorder()
   {
      $datetime      = Helper_Common::get_default_datetime();
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         echo "<pre>"; print_r($post);	//die;
         //$data = $post["data"][0];
			$data = $post["data"];
         $sqid = $post["id"];
         $i    = 1;
         foreach ($data as $k => $v) {
            //$oid                = str_replace("option", '', $v["id"]);
            $oid                = str_replace("option", '', $v);
				$oid                = explode("_", $oid);
            $oid                = $oid[0];
            $update["sequence"] = $i++;
            $questionmodel->updateOption($update, $oid);
         }
         //$this->session->set('success', 'Your Option order was changed Successfully!!!');
         echo true;
         exit;
      }
   }
   public function action_add_edit_questions()
   {
      $datetime      = Helper_Common::get_default_datetime();
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         if ($post["id"] != "") {
            $update["question"]     = $post["question"];
				$update["placeholder_text"]     = $post["placeholder_text"];
            $update["answer_field"] = $post["answer_field"];
            $update["isrequired"]   = $post["isreq"];
            $update["modified"]     = $datetime;
            $update["min_val"]      = $post["min_val"];
            $update["max_val"]      = $post["max_val"];
            $questionmodel->updateQuestions($update, $post["id"]);
            $sqid = $post["id"];
            $this->session->set('success', 'Your Question was updated Successfully!!!');
         } else {
            $insert["question"]     = $post["question"];
				$insert["placeholder_text"]     = $post["placeholder_text"];
            $insert["sequence"]     = $post["seq"];
            $insert["isrequired"]   = $post["isreq"];
            $insert["answer_field"] = $post["answer_field"];
            $insert["min_val"]      = $post["min_val"];
            $insert["max_val"]      = $post["max_val"];
            $insert["added"]        = $datetime;
            $insert["modified"]     = $datetime;
            $sqid                   = $questionmodel->insertQuestions($insert);
            $this->session->set('success', 'Your Question was added Successfully!!!');
         }
         echo $sqid;
      }
      exit;
   }
   public function action_questionupdates()
   {
      $datetime      = Helper_Common::get_default_datetime();
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         //print_r($post);
         if ($post["type"] == "status") {
            $update["status"] = $post["status"];
         } elseif ($post["type"] == "required") {
            $update["isrequired"] = $post["req"];
         }
         $questionmodel->updateQuestions($update, $post["id"]);
         echo true;
      }
      exit;
   }
   public function action_update_questionoptions()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         if ($post["id"] != "") {
            $update["option"] = $post["option"];
            $questionmodel->updateOption($update, $post["id"]);
            echo true;
            exit;
         }
      }
      echo false;
      exit;
   }
   public function action_removequestion()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         if ($post["id"] != "") {
            $sqid             = $post["id"];
            $update["status"] = 1;
            //$questionmodel->updateQuestions($update,$post["id"]);	
            //$questionmodel->updateQuestionOptions($update,$post["id"]);
            $q                = $questionmodel->getQuestion($post["id"]);
            $q                = $q[0];
            //print_r($opt);die;
            //$update["status"]	= 1;
            //echo $questionmodel->updateQuestionOptions($update,$post["oid"]);
            //echo $results; die;
            $questionmodel->updateQuestionSeq($q["sequence"], $this->current_site_id);
            $questionmodel->deleteQuestions($post["id"]);
            $questionmodel->deleteQuestionOptions($post["id"]);
            echo $sqid;
				$this->session->set('success', 'Your Question was removed Successfully!!!');
         }
      }
      exit;
   }
   public function action_removequestionoption()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         //print_r($post); die;
         if ($post["id"] != "" && $post["oid"] != "") {
            $opt = $questionmodel->getOption($post["oid"]);
            $opt = $opt[0];
            //print_r($opt);die;
            //$update["status"]	= 1;
            //echo $questionmodel->updateQuestionOptions($update,$post["oid"]);
            //echo $results; die;
            $questionmodel->updateQuestionOptionsSeq($opt["sequence"], $opt["sqid"]);
            $questionmodel->deleteOption($post["oid"]);
            //$this->session->set('success', 'Your Option was removed Successfully!!!');
            echo true;
            exit;
         }
      }
      echo false;
      exit;
   }
   public function action_getquestionoption()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post = $this->request->post();
         if ($post["id"] != "") {
            $sqid    = $post["id"];
            $options = $questionmodel->getQuestionOptions($post["id"]);
            echo ($options) ? json_encode($options) : false;
         }
      }
      exit;
   }
   public function action_getallquestionoption()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      $question      = $questionmodel->getQuestions();
      $str           = "";
      if ($question) {
         //sort($question);
         $qno = 1;
         foreach ($question as $k => $q) {
            $options = $questionmodel->getQuestionOptions($q["id"]);
            $str .= "<div class=\"panel-heading\">" . $qno++ . ". " . $q["question"] . "</div>";
            $str .= "<div class=\"panel-body\">
                  	<ul class=\"questionlist\">";
            if ($q["answer_field"] == 1) {
               $str .= "<li><!--div class=\"rightlabel\"-->
					<textarea tabindex='" . $q["id"] . "' type='text' class=\"form-control\" id='square-radio-" . $q["id"] . "' name='ans" . $q["id"] . "' value=''  style='width:100%'></textarea>
					<!--/div--></li>";
            } elseif ($q["answer_field"] == 5) {
               $str .= "<li><!--div class=\"rightlabel\"-->
					  Range : <label class='range-square-radio-" . $q["id"] . "'></label>
					<input tabindex='" . $q["id"] . "' type='hidden' class=\"form-control \" id='range-square-radio-" . $q["id"] . "' name='ans" . $q["id"] . "' value='' readonly style='width:100%'>
					<div id='slider-range-max-" . $q["id"] . "'></div>
					<!--/div--></li>";
               $avg = round(($q["min_val"] + $q["max_val"]) / 2);
               $str .= '
								<script>
								$( function() {
								  $( "#slider-range-max-' . $q["id"] . '" ).slider({
									 range: "max",
									 min: ' . $q["min_val"] . ',
									 max: ' . $q["max_val"] . ',
									 value: ' . $avg . ',
									 slide: function( event, ui ) {
										$( "#range-square-radio-' . $q["id"] . '" ).val( ui.value );
										$( ".range-square-radio-' . $q["id"] . '" ).html( ui.value );
									 }
								  });
								  $( "#range-square-radio-' . $q["id"] . '" ).val( $( "#slider-range-max-' . $q["id"] . '" ).slider( "value" ) );
								  $( ".range-square-radio-' . $q["id"] . '" ).html( $( "#slider-range-max-' . $q["id"] . '" ).slider( "value" ) );
								} );
								</script>
							';
            }
            if ($options) {
               if ($q["answer_field"] == 2) {
                  $str .= "<li><div class=\"rightlabel\">";
                  $str .= "<select class='mact' >";
                  foreach ($options as $op => $o) {
                     $str .= "<option value='" . $q["id"] . "_" . $o["id"] . "'>";
                     $str .= $o["option"];
                     $str .= "</option>";
                  }
                  $str .= "</select>";
                  $str .= "</div></li>";
               } elseif ($q["answer_field"] == 3) {
                  foreach ($options as $op => $o) {
                     $str .= "<li><div class=\"rightlabel\">
							<input tabindex='" . $q["id"] . "_" . $o["id"] . "' type='checkbox' id='square-radio-" . $q["id"] . "_" . $o["id"] . "'
							name='ans" . $q["id"] . "' value='" . $q["id"] . "_" . $o["id"] . "'>
							<label for=\"square-radio-1\">" . $o["option"] . "</label></div></li>";
                  }
               } elseif ($q["answer_field"] == 4) {
                  foreach ($options as $op => $o) {
                     $str .= "<li><div class=\"rightlabel\">
							<input tabindex='" . $q["id"] . "_" . $o["id"] . "' type='radio' id='square-radio-" . $q["id"] . "_" . $o["id"] . "'
							name='ans" . $q["id"] . "' value='" . $q["id"] . "_" . $o["id"] . "'>
							<label for=\"square-radio-1\">" . $o["option"] . "</label></div></li>";
                  }
               } else {
                  if ($q["answer_field"] != 1 && $q["answer_field"] != 5)
                     $str .= "<li><div class=\"rightlabel\"><label for=\"square-radio-1\">No Option found</label></div></li>";
               }
            }
            $str .= "</ul>
						</div>";
         }
      } else {
         $str .= "<div class=\"rightlabel\"><label for=\"square-radio-1\">No Option found</label></div>";
      }
      echo $str;
      exit;
   }
   public function action_add_questionoptions()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post               = $this->request->post();
         $insert["option"]   = $post["option"];
         $insert["sqid"]     = $post["sqid"];
         $insert["sequence"] = $post["sequence"];
         $sqoptid            = $questionmodel->insertQuestionOptions($insert);
         echo ($sqoptid) ? $sqoptid : false;
         //$this->session->set('success', 'Your Option was updated Successfully!!!');
      }
      exit;
   }
   public function action_getansweredquestions()
   {
      $questionmodel = ORM::factory('admin_commonquestions');
      if ($this->request->method() == HTTP_Request::POST) {
         $post            = $this->request->post();
         $ans             = $questionmodel->answers($post);
         if (isset($ans) && is_array($ans) && count($ans) > 0) {
            $qno = 1;
            $str = "";
            foreach ($ans as $k => $v) {
               $question    = $v["question"];
               $v["answer"] = str_replace("###,", "###", $v["answer"]);
               $ans         = explode("###", $v["answer"]);
               $ans         = array_values(array_filter($ans));
               $str .= "<div class=\"panel-heading\">" . $qno++ . ". " . $question . "</div>";
               $str .= "<div class=\"panel-body\">
								<ul class=\"questionlist\">";
               foreach ($ans as $op => $o) {
                  $str .= "<li><div class=\"rightlabel\"><label for=\"square-radio-1\">" . $o . "</label></div></li>";
               }
               $str .= "</ul>
						</div>";
            }
            echo $str;
         } else {
            $str = "<div class=\"rightlabel\"><label for=\"square-radio-1\">No Data Found</label></div>";
         }
         exit;
      }
   }
}

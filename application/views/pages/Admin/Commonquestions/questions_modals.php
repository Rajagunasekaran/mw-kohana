 <!-- more Modal  -->
<div class="modal fade" id="questionsModal" tabindex="-1" role="dialog" aria-labelledby="questionsModal">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog modal-xs" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
				<h4 class="modal-title" id="exampleModalLabel">Common Questions</h4>
			</div>
			<div class="modal-body">
			  <form>
					<div class="form-group">
						<label for="workout-name" class="control-label">Questions: </label>
						<textarea id='questions' name='questions' class="form-control" onkeypress="return addQevent(event,this)"></textarea>
					</div>
					<div class="form-group">
						<label for="workout-name" class="control-label">Custom Answer: </label>
						<select name='answer_field' id='answer_field' class='selectAction' onchange='call_child(this)'>
							<option value=''>Choose Answer Field</option>
							<option value='1'>Text Box</option>
							<option value='2'>Select Box</option>
							<option value='3'>Check box</option>
							<option value='4'>Radio Button</option>
							<option value='5'>Input Slider</option>
						</select>
					</div>
					<div class="form-group placeholder" style='display:none;'>
						<label for="workout-name" class="control-label">Placeholder Text: </label>
						<textarea id='placeholder_text' name='placeholder_text' class="form-control" autocomplete='off' onkeypress="return addQevent(event,this)"></textarea>
					</div>
					<div class="form-group">
						<label for="workout-name" class="control-label">Is Required: </label>
						<input type='checkbox' id='isrequired' name='isrequired' >
					</div>
					<div class="form-group input_slider" style='display:none;'>
						<div class="row">
							<div class="col-xs-3 ">
								<label for="workout-name" class="control-label">Min: </label>
							</div>
							<div class="col-xs-3">
								<input type='text' id='min_val' name='min_val' size=5 class="form-control"  onkeypress="return numbersonly(event)">
							</div>
							<div class="col-xs-3">
								<label for="workout-name" class="control-label">Max: </label>
							</div>
							<div class="col-xs-3">
								<input type='text' id='max_val' name='max_val' size=5 class="form-control"  onkeypress="return numbersonly(event)">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<input type='hidden' id='sqid' name='' value=''>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="addquestions()">Save</button>
			</div>
		</div>
	</div>
	</div>
</div>



 <!-- more Modal  -->
<style>
#sTreequestion { list-style-type: none; margin: 0; padding: 0; width: 100%; }
#sTreequestion li i{ font-size:25px; }
.form-group textarea{ resize: none;}

#psTree2 { list-style-type: none; margin: 0; padding: 0; width: 100%; }
#psTree2 li { background:#F5F5F5;padding:5px 5px 5px 10px;margin-bottom:3px;cursor:auto;vertical-align: middle; }
.err { color:red }
#psTree2 li i{ font-size:25px; }
</style>
<div class="modal fade" id="questionoptionsModal" tabindex="-1" role="dialog" aria-labelledby="questionoptionsModal">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog modal-xs" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
				<h4 class="modal-title" id="titleLabel">Common Question Options</h4>
			</div>
			<div class="modal-body">
				<div class="row"><div class="banner success orderoption" style="text-align:center;color:green;"></div></div>
			 
					<div class="form-group">
						<label for="workout-name" class="control-label">Questions: </label>
						<p id=qid></p>
					</div>
					<div class="form-group">
						<label for="workout-name" class="control-label">Options: </label>
						<ul id="sTreequestion">
						</ul>
						<input autocomplete="off" class="form-control input-sm" id='option' name='option' onkeypress="return optionevent(event,this)" type="text" data-items="8" style='width:90%'/>
						<button id="b1" class="btn add-more-qualifications" type="button" onclick="addquestionoptions()" style='margin-left: 90%;margin-top: -50px'>+</button>
						<!--textarea id='option' name='option' class="form-control" onkeypress="return optionevent(event,this)"></textarea-->
					</div>
				
			</div>
			<div class="modal-footer">
				<input type='hidden' id='sqid' name='' value=''>
				<input type='hidden' id='seq' name='' value='0'>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<!--button type="button" class="btn btn-primary" onclick="addquestionoptions()">Save</button-->
			</div>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="editoptionModal" tabindex="-1" role="dialog" aria-labelledby="editoptionModal">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog modal-xs" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
				<h4 class="modal-title" id="titleLabel">Edit Question Option</span></h4>
			</div>
			<div class="modal-body">
			  <form>
					<div class="form-group">
						<textarea id='editoption' class="form-control" onkeypress="return editoptionevent(event,this)"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<input type='hidden' id='oid' name='' value=''>
				<input type='hidden' id='sqid' name='' value=''>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updatequestionoptions()">Update</button>
			</div>
		</div>
	</div>
	</div>
</div>


<div class="modal fade" id="questionpreviewModal" tabindex="-1" role="dialog" aria-labelledby="questionpreviewModal">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog modal-xs" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
				<h4 class="modal-title" id="titleLabel">Common Question Preview</span></h4>
			</div>
			<div class="modal-body">
			  <form>
					<div class="form-group">
						<label for="workout-name" class="control-label">Questions: </label>
						<p id=pqid></p>
					</div>
					<div class="form-group">
						<label for="workout-name" class="control-label">Options: </label>
						<ul id="psTree2">
							<li class='err'>No Records found</li>
						</ul>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>				
			</div>
		</div>
	</div>
	</div>
</div>

<?php /**************************Preview Question Moadl***************/ ?>
<style type="text/css">
#public li label { padding-left: 10px; }
.iradio_square-blue { float: left; max-width: 15%; width: 24px; }
.questionlist li { clear: both; }
.questionlist { list-style-type: none; padding-left: 0px; font-weight:bold; }
#public h2 { font-size: 24px; }
.iradio_square-blue { top: 3px; }
@media only screen and (max-width :480px) {
	.main-wrapper { padding: 0px; }
}
.previewallbody{
	height:350px;
	overflow-x: hidden;
}
</style>
<div class="modal fade" id="previewquestionsModal" tabindex="-1" role="dialog" aria-labelledby="previewquestionsModal">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
				<h4 class="modal-title" id="titleLabel">Common Question Preview</span></h4>
			</div>
			<div class="modal-body row ">
				<div class="col-sm-12 previewallbody">
					<div id='previewall'>
						
						<!--div class="panel-heading">1. Choose the sentence that incorrectly uses a nonstandard idiom or expression. Choose one answer.</div>
                  <div class="panel-body">
                  	<ul class="questionlist">
								<li><div class="rightlabel"><label for="square-radio-1">Her coat kept falling off the hook.</label></div></li>
							</ul>
						</div-->
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>				
			</div>
		</div>
	</div>
	</div>
</div>
<input type='hidden' id='q_type' name='q_type' value='1'>
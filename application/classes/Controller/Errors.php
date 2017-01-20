<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Website {
	
	public function before()
	{
		parent::before();
		$this->render();
		// Internal request only!
		if ( ! Request::current()->is_initial())
		{
			if ($message = rawurldecode($this->request->param('message')))
			{
				$this->template->content->message = $message;
			}
		}
		else
		{
			$this->request->action(404);
		}
		$this->response->status((int) $this->request->action());
	}
	
	public function action_404()
	{
		$this->template->title = '404 Not Found';
		$this->response->status(404);
	}
	 
	public function action_503()
	{
		$this->template->title = 'Maintenance Mode';
		$this->response->status(503);
	}
	 
	public function action_500()
	{
		$this->template->title = 'Internal Server Error';
		$this->response->status(500);
	}
} // End Error

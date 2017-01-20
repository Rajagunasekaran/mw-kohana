<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_Exception extends Kohana_Kohana_Exception {
	public static function response(Exception $e)
    {
		if (Kohana::$environment === Kohana::DEVELOPMENT)
		{
			parent::handler($e);
		}
		else
		{
			try
			{
				Kohana::$log->add(Log::ERROR, parent::text($e));
				// Get the exception information
				$class   = get_class($e);
				$code    = $e->getCode();
				$message = $e->getMessage();
				$file    = $e->getFile();
				$line    = $e->getLine();
				$trace   = $e->getTrace();
				/** error tracking **/
				$user = Auth::instance()->get_user();
				$error['error_text'] = $message;
				$error['error_file'] = $file;
				$error['error_line'] = $line;
				$error['user_id'] 	 = (isset($user) && $user->pk() ? $user->pk() : '0');
				$error['site_id'] 	 = '1';
				$error['error_type'] = ($class == 'Database_Exception' ? '2' : '1');
				$error['modified_date'] = $error['created_date'] = date('Y-m-d H:i:s');
				Model::instance('Model/error')->insert($error);
				/** end error tracking **/
				/**
				 * HTTP_Exceptions are constructed in the HTTP_Exception::factory()
				 * method. We need to remove that entry from the trace and overwrite
				 * the variables from above.
				 */
				if ($e instanceof HTTP_Exception AND $trace[0]['function'] == 'factory')
				{
					extract(array_shift($trace));
				}


				if ($e instanceof ErrorException)
				{
					/**
					 * If XDebug is installed, and this is a fatal error,
					 * use XDebug to generate the stack trace
					 */
					if (function_exists('xdebug_get_function_stack') AND $code == E_ERROR)
					{
						$trace = array_slice(array_reverse(xdebug_get_function_stack()), 4);

						foreach ($trace as & $frame)
						{
							/**
							 * XDebug pre 2.1.1 doesn't currently set the call type key
							 * http://bugs.xdebug.org/view.php?id=695
							 */
							if ( ! isset($frame['type']))
							{
								$frame['type'] = '??';
							}

							// Xdebug returns the words 'dynamic' and 'static' instead of using '->' and '::' symbols
							if ('dynamic' === $frame['type'])
							{
								$frame['type'] = '->';
							}
							elseif ('static' === $frame['type'])
							{
								$frame['type'] = '::';
							}

							// XDebug also has a different name for the parameters array
							if (isset($frame['params']) AND ! isset($frame['args']))
							{
								$frame['args'] = $frame['params'];
							}
						}
					}

					if (isset(Kohana_Exception::$php_errors[$code]))
					{
						// Use the human-readable error name
						$code = Kohana_Exception::$php_errors[$code];
					}
				}
				$params = array
				(
					'action'  => 500,
					'message' => rawurlencode($e->getMessage())
				);
 
				if ($e instanceof HTTP_Exception)
				{
					$params['action'] = $e->getCode();
				}
				echo Request::factory(Route::url('error', $params))
						->execute()
						->send_headers()
						->body();
				die();
			}
			catch (Exception $e)
			{
				// Clean the output buffer if one exists
				ob_get_level() and ob_clean();
 
				// Display the exception text
				echo parent::text($e);
 
				// Exit with an error status
				exit(1);
			}
		}
    }
}

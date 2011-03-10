<?php

/**
 *
 * @Module class
 *
 * @package Core
 *
 */

class Module
{

	public static function run($request) 
	{
		if(!$request instanceof Request)
			$request = new Request($request);
			
		$file   = $request->getFile();
		$class  = $request->getClass();
		$method = $request->getMethod();
		$args   = $request->getArgs();

		$front = FrontController::getInstance();
		$registry = $front->getRegistry();
		$registry->oRequest = $request;
		$front->setRegistry($registry);
		
		if (file_exists($file)) 
		{		
			require_once($file);
			
			$rc = new ReflectionClass($class);
			// if the controller exists and implements IController
			if( $rc->implementsInterface( 'IController' ) )
			{
				try {
					$controller = $rc->newInstance();
					$classMethod = $rc->getMethod($method);			
					return $classMethod->invokeArgs($controller,$args);
				}
				catch (ReflectionException $e)
				{
					throw new MvcException($e->getMessage());
				}
			}
			else
			{
				throw new MvcException("Interface iController must be implemented");
			}
		}
		else 
		{
			throw new MvcException("Controller file not found");
		}
	}		


} // end of class

<?php
namespace Simply\Route;

use ReflectionClass;


/**
 * @author André Teles <andre.teletp@gmail.com>
 *
 * @package SyRoute
 */


class SyRoute
{
	private $resources=[];

	private $method=[];

	private $controller=[];


	/**
	 * @todo
	 */
	private $app;

	private $methodsAllowed = ['GET','POST'];



	public function __construct($app='')
	{
		$this->app = $app;
	}

	/**
	 * 
	 */
	private function addRoute($methodHttp,$resource,$controller)
	{
		if(!$this->methodValid($methodHttp)){
			throw new Exception("Method Not Allowed this request");
		}
		
		$this->sanitizeController($controller);

		$this->resources['resource'][]= [
							'route'      => $resource,
							'controller' => $this->controller,
							'method'     => $this->method
						];
	}

	/**
	 * 
	 * @method void Do request with method GET
	 * @param string $resource Resource the be access
	 * @param string $controller Controller
	 * 
	 */

	public function get($resource, $controller)
	{
		$this->addRoute("GET",$resource,$controller);
	}

	/**
	 * @method void Do request with method POST
	 * @param string $resource Resource the be access
	 * @param string $controller Controller
	 */

	public function post($resource, $controller)
	{
		$this->addRoute("POST",$resource,$controller);
	}

	/**
	 * @method void Run Request current and match with routes defined previous 
	 */

	public function run()
	{
		$uri = $this->uri();
	
		
		array_walk($this->resources['resource'],function($resource)use($uri){
			
			$params=[];
			
			if($resource['route'] == $uri['path']){
			
				return call_user_func([$this->makeObject($this->controller[0]),$this->method[0]]);
				
			}

			if(preg_match($this->matchUriParams($resource['route']), $uri['path'], $params)){
			
				array_shift($params);
				
				return call_user_func_array([$this->makeObject($this->controller[0]),$this->method[0]], $params);
			}

				array_shift($this->controller);
				array_shift($this->method);
		});
	}

	/**
	 * @method boolean Check if is method is allowed and is method from the request current
	 * @param string $method
	 */

	private function methodValid($method)
	{	
		if(!(in_array($method, $this->methodsAllowed) && $_SERVER['REQUEST_METHOD'] == $method) ){

			return false;
		}

		return true;
	}

	/**
	 * @method void get URI current
	 * @return nothing
	*/

	private function uri()
	{
		return parse_url($_SERVER['REQUEST_URI']);
	}

	/**
	 * @param string $controller It is split data to controller and method
	 * @return nothing
	 */

	private function sanitizeController($controller)
	{
		$controller = explode('@',$controller);

		$this->controller[] = $controller[0];

		$this->method[] = $controller[1];

	}

	/**
	 * @param string $class get name of class or namespace and return Object
	 * @return Object
	 */
	
	private function makeObject($class)
	{

		
		$obj = new ReflectionClass($class);

		if($obj->isInstantiable()){
			return $obj->newInstance();
		}
	}

	/**
	 * @param string $pattern Receive a pattern from the URI
	 * @return string
	 */

	private function matchUriParams($pattern)
	{
		$pattern = '/^'.str_replace('/', '\/', $pattern) . '$/';

		return $pattern;
	}

}
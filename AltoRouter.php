<?php

class AltoRouter {
	private $routes = array();
	private $namedRoutes = array();
	private $basePath = '';

	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}
	/**
	* Map a route to a target
	*/
	public function map($method, $route, $target, array $args = array()) {
		
		$route = $this->basePath . $route;
		$this->routes[] = array($method, $route, $target);
		
		if(isset($args['name'])) {
			$this->namedRoutes[$args['name']] = $route;
		}
	}

	/**
	* Reversed routing
	*
	* Generate the URL for a named route. Replace regexes with supplied parameters
	*/
	public function generate($routeName, array $params = array()) {

		// Check if named route exists
		if(!isset($this->namedRoutes[$routeName])) {
			throw new \Exception("Route '{$routeName}' does not exist.");
		}

		// Replace named parameters
		$route = $this->namedRoutes[$routeName];
		$url = $route;

		if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
			
			foreach($matches as $match) {
				list($block, $pre, $type, $param, $optional) = $match;

				if(isset($params[$param])) {
					$url = str_replace(substr($block,1), $params[$param], $url);
				}
			}
			

		}

		return $url;
	}

	/**
	* Match a given Request Url against stored routes
	*/
	public function match($requestUrl = null, $requestMethod = null) {

		// set Request Url if it isn't passed as parameter
		if($requestUrl === null) {
			$requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		}

		// Strip query string (?a=b) from Request Url
		if (false !== strpos($requestUrl, '?')) {
        	$requestUrl = strstr($requestUrl, '?', true);
   		 }

		// set Request Method if it isn't passed as a parameter
		if($requestMethod === null) {
			$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}

		// Force request_order to be GP
    	// http://www.mail-archive.com/internals@lists.php.net/msg33119.html
    	$_REQUEST = array_merge($_GET, $_POST);

    	foreach($this->routes as $handler) {
    		list($method, $_route, $target) = $handler;

    		$methods = explode('|', $method);
    		$method_match = false;

    		// Check if request method matches. If not, abandon early. (CHEAP)
    		foreach($methods as $method) {
    			if (strcasecmp($requestMethod, $method) === 0) {
    				$method_match = true;
    				break;
    			}
    		}

    		// Method did not match, continue to next route.
    		if(!$method_match) continue;

    		// Check for a wildcard (matches all)
        	if ($_route === '*') {
        		$match = true;
        	} elseif (isset($_route[0]) && $_route[0] === '@') {
            	$match = preg_match('`' . substr($_route, 1) . '`', $requestUrl, $params);
      		} else {
      			$route = null;
	            $regex = false;
	            $j = 0;
	            $n = isset($_route[0]) ? $_route[0] : null;
	            $i = 0;

	            // Find the longest non-regex substring and match it against the URI
	            while (true) {
	                if (!isset($_route[$i])) {
	                    break;
	                } elseif (false === $regex) {
	                    $c = $n;
	                    $regex = $c === '[' || $c === '(' || $c === '.';
	                    if (false === $regex && false !== isset($_route[$i+1])) {
	                        $n = $_route[$i + 1];
	                        $regex = $n === '?' || $n === '+' || $n === '*' || $n === '{';
	                    }
	                    if (false === $regex && $c !== '/' && (!isset($requestUrl[$j]) || $c !== $requestUrl[$j])) {
	                        continue 2;
	                    }
	                    $j++;
	                }
	                $route .= $_route[$i++];
	            }

	            $regex = $this->compileRoute($route);
      			$match = preg_match($regex, $requestUrl, $params);
      		}

      		

      		if(isset($match) && $match > 0) {
      			if($params) {
      				foreach($params as $key => $value) {
      					if(is_numeric($key)) unset($params[$key]);
      				}
      			}
      			return array(
      				'target' => $target,
      				'params' => $params
      			);
      		}

    	}

    	return false;

	}

	/**
	* Compile the regex for a given route (EXPENSIVE)
	*/
	private function compileRoute($route) {
		if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {

	        $match_types = array(
	            'i'  => '[0-9]++',
	            'a'  => '[0-9A-Za-z]++',
	            'h'  => '[0-9A-Fa-f]++',
	            '*'  => '.+?',
	            '**' => '.++',
	            ''   => '[^/]++'
	        );

        	foreach ($matches as $match) {
	            list($block, $pre, $type, $param, $optional) = $match;

	            if (isset($match_types[$type])) {
	                $type = $match_types[$type];
	            }
	            if ($pre === '.') {
	                $pre = '\.';
	            }

	            //Older versions of PCRE require the 'P' in (?P<named>)
	            $pattern = '(?:'
	                     . ($pre !== '' ? $pre : null)
	                     . '('
	                     . ($param !== '' ? "?P<$param>" : null)
	                     . $type
	                     . '))'
	                     . ($optional !== '' ? '?' : null);

	            $route = str_replace($block, $pattern, $route);
	        }

	    }
	    return "`^$route$`";
	}
}
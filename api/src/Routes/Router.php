<?php
namespace Src\Routes;

class Router
{
    private array $routes = [];
    
    public function addRoute($method, $path, $callback): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ];
    }
    
    public function dispatch($requestMethod, $requestUri)
    {
        // Loại bỏ query string
        $requestUri = strtok($requestUri, '?');
        
        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($requestMethod)) {
                // Chuyển đổi route pattern thành regex
                $pattern = $this->convertToRegex($route['path']);
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    // Loại bỏ phần tử đầu tiên (full match)
                    array_shift($matches);
                    
                    if (is_callable($route['callback'])) {
                        return call_user_func_array($route['callback'], $matches);
                    } elseif (is_array($route['callback'])) {
                        $controller = new $route['callback'][0]();
                        $method = $route['callback'][1];
                        return call_user_func_array([$controller, $method], $matches);
                    }
                }
            }
        }
        
        // Không tìm thấy route
        http_response_code(404);
        echo "404 - Không tìm thấy trang";
        
        return false;
    }
    
    private function convertToRegex($path): string
    {
        // Thay thế {id} bằng ([^/]+)
        $pattern = preg_replace('/\{([^}]+)}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
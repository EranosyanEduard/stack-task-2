<?php

class Router
{
  private array $routes;

  public function __construct()
  {
    $this->routes = [];
  }

  public function all($route, $controller)
  {
    $this->addRoute('*', $route, $controller);
  }

  public function delete($route, $controller)
  {
    $this->addRoute('DELETE', $route, $controller);
  }

  public function get($route, $controller)
  {
    $this->addRoute('GET', $route, $controller);
  }

  public function patch($route, $controller)
  {
    $this->addRoute('PATCH', $route, $controller);
  }

  public function post($route, $controller)
  {
    $this->addRoute('POST', $route, $controller);
  }

  public function use()
  {
    foreach ($this->routes as $route) {
      if ($this->checkRoute($route['method'], $route['route'])) {
        $route['controller']();
        break;
      }
    }
  }

  private function addRoute($req_method, $route, $controller)
  {
    $this->routes[] = [
      'method' => $req_method,
      'route' => $route,
      'controller' => $controller
    ];
  }

  private function checkRoute($req_method, $route): bool
  {
    $is_matched_req_methods = $req_method === '*'
      || $req_method === $_SERVER['REQUEST_METHOD'];
    $is_matched_url_paths = $route === '*'
      || $route === parse_url($_SERVER['REQUEST_URI'])['path'];
    return $is_matched_req_methods && $is_matched_url_paths;
  }
}

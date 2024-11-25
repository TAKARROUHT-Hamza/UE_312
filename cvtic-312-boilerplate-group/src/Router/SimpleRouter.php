<?php declare(strict_types=1);

namespace Framework312\Router;

use Framework312\Router\Exception as RouterException;
use Framework312\Template\Renderer;
use Symfony\Component\HttpFoundation\Response;

class Route {
    private const VIEW_CLASS = 'Framework312\Router\View\BaseView';
    private const VIEW_USE_TEMPLATE_FUNC = 'use_template';
    private const VIEW_RENDER_FUNC = 'render';

    private string $view;

    public function __construct(string|object $class_or_view) {
        $reflect = new \ReflectionClass($class_or_view);
        $view = $reflect->getName();
        if (!$reflect->isSubclassOf(self::VIEW_CLASS)) {
            throw new RouterException\InvalidViewImplementation($view);
        }
        $this->view = $view;
    }

    public function call(Request $request, ?Renderer $engine): Response {
	    // TODO
        $view = new $this->view();
        
        if ($this->view::use_template() && is_null($engine)) {
            throw new RouterException\InvalidViewImplementation(
                $this->view,
                'View requires template engine but none provided'
            );
        }
        
        return $view->render($request);
    }
}

class SimpleRouter implements Router {
    //
    private array $routes = [];
    //
    private Renderer $engine;

    public function __construct(Renderer $engine) {
        $this->engine = $engine;
        // TODO
        $this->engine = $engine;
        $this->routes = [];
    }

    public function register(string $path, string|object $class_or_view) {
	    // TODO
        try {
            $this->routes[$path] = new Route($class_or_view);
        } catch (\ReflectionException $e) {
            throw new RouterException\InvalidViewImplementation($class_or_view);
        }
    }

    public function serve(mixed ...$args): void {
	    // TODO
        $request = Request::createFromGlobals();
        $path = $request->getPathInfo();

        if (!isset($this->routes[$path])) {
            $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
        } else {
            try {
                $response = $this->routes[$path]->call($request, $this->engine);
            } catch (\Exception $e) {
                $response = new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        $response->send();
    }
}

?>

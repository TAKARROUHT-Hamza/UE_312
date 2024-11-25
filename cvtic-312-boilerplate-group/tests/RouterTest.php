<?php declare(strict_types=1);

namespace Tests\Framework312;

use PHPUnit\Framework\TestCase;
use Framework312\Router\SimpleRouter;
use Framework312\Router\View\JSONView;
use Framework312\Template\TwigRenderer;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends TestCase {
    private SimpleRouter $router;
    private TwigRenderer $renderer;

    protected function setUp(): void {
        $this->renderer = new TwigRenderer(__DIR__ . '/templates');
        $this->router = new SimpleRouter($this->renderer);
    }

    public function testRouteRegistration(): void {
        $this->router->register('/test', JSONView::class);
        $this->assertTrue(true);
    }

    public function testJSONViewResponse(): void {
        $path = '/api';
        $this->router->register($path, JSONView::class);
        
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $path;
        
        ob_start();
        $this->router->serve();
        $response = ob_get_clean();

        $this->assertJson($response);
        $data = json_decode($response, true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('status', $data);
    }
}
<?php declare(strict_types=1);

namespace Framework312\Router\View;

use Framework312\Router\Request;
use Symfony\Component\HttpFoundation\Response;

class JSONView extends BaseView {
    static public function use_template(): bool {
        return false;
    }

    protected function get(Request $request): array {
        return [
            'status' => 'success'
        ];
    }

    public function render(Request $request): Response {
        $data = $this->get($request);
        return new Response(
            json_encode($data),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}
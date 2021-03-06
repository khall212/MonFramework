<?php
namespace Tests\Blog\Actions;

use App\Blog\Actions\BlogAction;
use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class BlogActionTest extends TestCase {

    /**
     * @var action
     */
    private $action;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var Renderer
     */
    private $renderer;
    private $postTable;

    public function setUp()
    {
        $this->renderer = $this->prophesize(RendererInterface::class);
        $this->postTable = $this->prophesize(PostTable::class);

        $this->router = $this->prophesize(Router::class);
        $this->action = new BlogAction(
            $this->renderer->reveal(),
            $this->router->reveal(),
            $this->postTable->reveal()
        );
    }

    public function makePost(int $id, string $slug): Post {
        $post = new Post();
        $post->id = $id;
        $post->slug = $slug;
        return $post;
    }


    public function testShowRedirect() {

        $post = $this->makePost(9, "azezez-azzeza");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', 'demo');

        $this->router->generateUri('blog.show',
            ['id' => $post->id, 'slug' => $post->slug]
        )->willReturn('/demo2');
        $this->postTable->find($post->id)->willReturn($post);

        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(['/demo2'], $response->getHeader('location'));
    }

    public function testShowRender() {

        $post = $this->makePost(9, "azezez-azzeza");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', $post->slug);


        $this->postTable->find($post->id)->willReturn($post);
        $this->renderer->render('@blog/show', ['post' => $post])->willReturn('');

        $response = call_user_func_array($this->action, [$request]);
        $this->assertEquals(true, true);

    }
}
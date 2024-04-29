<?php

namespace App\Tests\Unit;

use App\Entity\Post;
use App\Transformers\PostTransformer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class PostTransformerTest extends TestCase
{
    private $entityManager;
    private $transformer;

    protected function setUp(): void
    {
        $this->entityManager = m::mock(EntityManagerInterface::class);
        $this->transformer = new PostTransformer($this->entityManager);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testTransformPostsAndPersist()
    {
        $postData = [
            ['title' => 'Title 1', 'body' => 'Body 1', 'userId' => 1]
        ];
        $userMap = [1 => 'Test user 1'];

        $this->entityManager->shouldReceive('persist')
            ->once()
            ->with(m::on(function ($post) use ($postData, $userMap) {
                $this->assertInstanceOf(Post::class, $post);
                $this->assertEquals($postData[0]['title'], $post->getTitle());
                $this->assertEquals($userMap[$postData[0]['userId']], $post->getAuthorName());
                return true;
            }))
            ->andReturnNull();

        $this->transformer->transformPostsAndPersist($postData, $userMap);

        $this->assertTrue(true);
    }

    public function testMapUsers()
    {
        $users = [
            ['id' => 1, 'name' => 'User One'],
            ['id' => 2, 'name' => 'User Two']
        ];

        $expected = [1 => 'User One', 2 => 'User Two'];
        $result = $this->transformer->mapUsers($users);

        $this->assertEquals($expected, $result);
    }
}
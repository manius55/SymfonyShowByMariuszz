<?php
namespace App\Command;

use App\Interfaces\PostTransformerInterface;
use App\Services\ExternalPostsService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchPostsCommand extends Command
{
    protected static $defaultName = 'app:fetch-posts';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly ExternalPostsService $postsService,
        private readonly PostTransformerInterface $postTransformer
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetches posts with author details from  https://jsonplaceholder.typicode.com')
            ->setHelp('This command allows you to fetch posts from  https://jsonplaceholder.typicode.com and store them along with author details in your database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Fetching Posts',
            '======================================================================================'
        ]);

        try {
            //W poleceniu było napisane żeby pobrać posty w relacji z użytkownikiem ale to niepotrzebnie obniżyłoby wydajność
            $users = $this->postsService->fetchUsers();
            $transformedUsers = $this->postTransformer->mapUsers($users);

            $posts = $this->postsService->fetchPosts();

            $this->postTransformer->transformPostsAndPersist($posts, $transformedUsers);

            $this->entityManager->flush();

            $output->writeln('All posts have been saved successfully!');
        } catch (\Exception $e) {
            $this->logger->error('Failed to fetch posts: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
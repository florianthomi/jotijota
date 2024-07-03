<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:make:superadmin',
    description: 'Create the first user with role ROLE_SUPER_ADMIN',
)]
class CreateSuperadminUserCommand extends Command
{
    public function __construct(private readonly UserRepository $userRepository, private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Create a new Super Admin User');

        if ($this->userRepository->findByRole('ROLE_SUPER_ADMIN')) {
            $io->error('A user with this role already exist');
            return Command::FAILURE;
        }

        $helper = $this->getHelper('question');

        $user = new User();
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $question = new Question("Username ?\n> ");
        $user->setUsername($helper->ask($input, $output, $question));

        $question = new Question("Password ?\n> ");
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            $helper->ask($input, $output, $question)
        ));

        $question = new Question("First name ?\n> ");
        $user->setFirstname($helper->ask($input, $output, $question));

        $question = new Question("Last name ?\n> ");
        $user->setLastname($helper->ask($input, $output, $question));

        $this->userRepository->save($user);

        $io->success('The user has been successfully created');


        return Command::SUCCESS;
    }
}

<?php
declare(strict_types=1);

namespace App\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;

#[AsCommand(name: 'app:create:user')]
class CreateUser extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ){
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $user = new User();
        $user->firstName = $input->getArgument('firstName');
        $user->lastName = $input->getArgument('lastName');
        $user->email = $input->getArgument('email');
        $user->phoneNum = $input->getArgument('phone');

        $this->em->persist($user);
        $this->em->flush();
        return Command::SUCCESS;
    }

    protected function configure(): void{
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('does what the can says!')
            ->addArgument(
                name: 'firstName',
                mode: InputArgument::REQUIRED,
                description: "The user's first name."
            )
            ->addArgument(
                name: 'lastName',
                mode: InputArgument::REQUIRED,
                description: "The user's last name."
            )
            ->addArgument(
                name: 'phone',
                mode: InputArgument::REQUIRED,
                description: "The user's phone number."
            )
            ->addArgument(
                name: 'email',
                mode: InputArgument::REQUIRED,
                description: "The user's email address."
            );;
    }
}

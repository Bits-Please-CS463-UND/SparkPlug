<?php
declare(strict_types=1);

namespace App\Command;
use App\Entity\Vehicle;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create:vehicle')]
class CreateVehicle extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
    ){
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $user = $this->userRepository->findOneByEmail($input->getArgument('owner'));
        if (!$user) {
            $output->writeln([
                '<error>',
                '                                                         ',
                '  the user does not exist! what the heck!!!!!!!!!!!!!!!! ',
                '                                                         ',
                '</error>'
            ]);
            return Command::FAILURE;
        }

        $vehicle = new Vehicle();
        $vehicle->make = $input->getArgument('make');
        $vehicle->model = $input->getArgument('model');
        $vehicle->year = (int)$input->getArgument('year');
        $vehicle->color = $input->getArgument('color');
        $vehicle->vin = 'X';
        $vehicle->licensePlates = 'X';
        $vehicle->owner = $user;
        $vehicle->drivers->add($user);
        $user->sharedVehicles->add($vehicle);

        $this->em->persist($vehicle);
        $this->em->flush();
        return Command::SUCCESS;
    }

    protected function configure(): void{
        $this
            ->setDescription('Creates a new vehicle.')
            ->setHelp('does what the can says!')
            ->addArgument(
                name: 'make',
                mode: InputArgument::REQUIRED,
                description: "The vehicle's make."
            )
            ->addArgument(
                name: 'model',
                mode: InputArgument::REQUIRED,
                description: "The vehicle's model."
            )
            ->addArgument(
                name: 'year',
                mode: InputArgument::REQUIRED,
                description: "The vehicle's year."
            )
            ->addArgument(
                name: 'color',
                mode: InputArgument::REQUIRED,
                description: "The vehicle's color."
            )
            ->addArgument(
                name: 'owner',
                mode: InputArgument::REQUIRED,
                description: "The owner's email address."
            );
    }
}

<?php
declare(strict_types=1);

namespace App\Command;
use App\Entity\Notification;
use App\Enum\NotificationPriority;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:create:notification')]
class CreateNotification extends Command
{
    public function __construct(
        private readonly VehicleRepository $vehicleRepository,
        private EntityManagerInterface $em,
    ){
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $vehicle = $this->vehicleRepository->find($input->getArgument('vehicle'));
        if (!$vehicle) {
            $output->writeln([
                '<error>',
                '                                                     ',
                '  the vehicle does not exist! you are the soyjack!!  ',
                '                                                     ',
                '</error>'
            ]);
            return Command::FAILURE;
        }

        $notification = new Notification();
        $notification->title = $input->getArgument('title');
        $notification->message = $input->getArgument('message');
        $notification->priority = NotificationPriority::tryFrom($input->getArgument('priority'));
        $notification->vehicle = $vehicle;

        $this->em->persist($notification);
        $this->em->flush();
        return Command::SUCCESS;
    }

    protected function configure(): void{
        $this
            ->setDescription('Creates a new notification.')
            ->setHelp('does what the can says!')
            ->addArgument(
                name: 'vehicle',
                mode: InputArgument::REQUIRED,
                description: "The vehicle's UUID."
            )
            ->addArgument(
                name: 'title',
                mode: InputArgument::REQUIRED,
                description: "The notification title."
            )
            ->addArgument(
                name: 'message',
                mode: InputArgument::REQUIRED,
                description: "The notification message."
            )
            ->addArgument(
                name: 'priority',
                mode: InputArgument::REQUIRED,
                description: "The notification's urgency.",
                suggestedValues: NotificationPriority::cases()
            );
    }
}

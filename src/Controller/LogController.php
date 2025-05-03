<?php
namespace App\Controller;

use App\Entity\LogEntry;
use App\Repository\LogEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/v1/vehicle/{vehicleId}/logs')]
class LogController extends AbstractController
{
    private LogEntryRepository $logEntryRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;

    public function __construct(LogEntryRepository $logEntryRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->logEntryRepository = $logEntryRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'get_logs', methods: ['GET'])]
    public function getLogs(): JsonResponse
    {
        $logEntries = $this->logEntryRepository->findAll();
        $data = $this->serializer->normalize($logEntries, null, [AbstractNormalizer::IGNORED_ATTRIBUTES => ['vehicle']]);
        return new JsonResponse($data);
    }

    #[Route('', name: 'add_log_entry', methods: ['POST'])]
    public function addLogEntry(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $logEntry = new LogEntry($data['title'], $data['message']);
        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $logEntry->getId(),
            'issuedAt' => $logEntry->getIssuedAt()->format('c'),
        ], JsonResponse::HTTP_CREATED);
    }
}

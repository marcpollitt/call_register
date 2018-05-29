<?php

namespace App\Services;

use App\Entity\Answer4You;
use App\Repository\Answer4YouRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CloseOIA4UProcessService
{
    private $closeIOA4UImportService;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, CloseIOA4UImportService $closeIOA4UImportService)
    {
        $this->entityManager = $entityManager;
        $this->closeIOA4UImportService = $closeIOA4UImportService;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \Exception
     */
    public function A4UDataProcessor(InputInterface $input, OutputInterface $output): void
    {
        /** @var Answer4YouRepository $repository */
        $repository = $this->entityManager->getRepository(Answer4You::class);

        $createdDate = new \DateTime($input->getArgument('date'));

        if ($repository->findBy(['createdDate' => $createdDate])) {
            throw new \Exception('<info>File has already been upload by someone</info>');
        }

        $data = $this->closeIOA4UImportService->importFile($input, $output);

        foreach ($data[0] as $key => $datum) {
            $a4UEntity = new Answer4You();
            $a4UEntity->setDepartment($datum)
                ->setCallsMissed($data[1][$key])
                ->setCreatedDate($createdDate)
                ->setFilename(pathinfo($input->getArgument('filename'))['basename']);

            $this->entityManager->persist($a4UEntity);
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
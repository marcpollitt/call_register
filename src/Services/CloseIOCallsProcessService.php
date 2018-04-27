<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 13/04/2018
 * Time: 12:13
 */

namespace App\Services;

use App\Entity\Calls;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CloseIOCallsProcessService
 * @package App\Services
 */
class CloseIOCallsProcessService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CloseIOCallsAutomaticallyMapper
     */
    private $closeOICallsMapperService;
    /**
     * @var CloseIOApiService
     */
    private $closeIOApiService;
    /**
     * @var ContainerInterface
     */
    private $container;

    /** @var integer */
    private $skip;

    /** @var integer */
    private $limit;

    /** @var array */
    private $skipKeys;

    /**
     * CloseIOCallsProcessService constructor.
     * @param CloseIOApiService $closeIOApiService
     * @param EntityManagerInterface $entityManager
     * @param CloseIOCallsAutomaticallyMapper $closeOICallsMapperService
     * @param ContainerInterface $container
     */
    public function __construct(CloseIOApiService $closeIOApiService, EntityManagerInterface $entityManager, CloseIOCallsAutomaticallyMapper $closeOICallsMapperService, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->closeOICallsMapperService = $closeOICallsMapperService;
        $this->closeIOApiService = $closeIOApiService;
        $this->container = $container;

        $this->skip = $this->container->getParameter('close_io_skip');
        $this->limit = $this->container->getParameter('close_io_limit');
        $this->skipKeys = $this->container->getParameter('close_io_skipKeys');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function closeIOCallProcessor(InputInterface $input, OutputInterface $output)
    {
        $fromDateTime = $this->setFromDateTime($input);
        $toDateTime = $this->setToDateTime($input);

        $from = $fromDateTime->format('Y-m-d\T00:00:01.00+00:00');
        $to = $toDateTime->format('Y-m-d\T23:59:59.00+00:00');

        $output->writeln(sprintf('<comment>Call data for dates from %s to %s</comment>', $from, $to));

        do {
            $data = $this->setCloseIOApiRequest($from, $to);

            foreach ($data['data'] as $callData) {

                if (!$this->entityManager->getRepository(Calls::class)->findBy(['closeId' => $callData['id']])) {
                    $calls = $this->closeOICallsMapperService->setCalls($callData, $this->skipKeys)->getCalls();
                    $this->entityManager->persist($calls);
                }
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
            $this->skip += $this->limit;
            $output->write('<bg=green;fg=green>.</>');
        } while ($data['has_more']);

        $output->writeln('');
    }

    /**
     * @param InputInterface $input
     * @return \DateTime
     */
    public function setFromDateTime(InputInterface $input): \DateTime
    {
        $fromDateTime = new \DateTime(
            sprintf('%s-%s-%s',
                $input->getArgument('from_year'),
                $input->getArgument('from_month'),
                $input->getArgument('from_day')
            )
        );
        return $fromDateTime;
    }

    /**
     * @param InputInterface $input
     * @return \DateTime
     */
    public function setToDateTime(InputInterface $input): \DateTime
    {
        $toDateTime = new \DateTime(
            sprintf('%s-%s-%s',
                $input->getArgument('to_year'),
                $input->getArgument('to_month'),
                $input->getArgument('to_day')
            ));
        return $toDateTime;
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    private function setCloseIOApiRequest($from, $to): array
    {
        $data = $this->closeIOApiService->getCalls([
            'date_created__gt' => $from,
            'date_created__lt' => $to,
            '_skip' => $this->skip,
            '_limit' => $this->limit,
        ]);
        return $data;
    }

}
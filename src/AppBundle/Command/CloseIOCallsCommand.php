<?php
namespace AppBundle\Command;

use AppBundle\Services\CloseIOCallsProcessService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CloseIOCallsCommand
 * @package AppBundle\Command
 */
class CloseIOCallsCommand extends Command
{
    /**
     * @var CloseIOCallsProcessService
     */
    private $closeOICallsProcessService;

    /**
     * CloseIOCallsCommand constructor.
     * @param CloseIOCallsProcessService $closeOICallsProcessService
     */
    public function __construct(CloseIOCallsProcessService $closeOICallsProcessService)
    {
        $this->closeOICallsProcessService = $closeOICallsProcessService;
        parent::__construct();
    }

    protected function configure()
    {
        $fromDateTime = new \DateTime();
        $toDateTime = new \DateTime('+1 Day');


        $this
            // the name of the command (the part after "bin/console")
            ->setName('close:io:calls:export')
            // the short description shown while running "php bin/console list"
            ->setDescription('Load call data into Database')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Loads data from close.io for calls into database.')
            ->addArgument('from_year', InputArgument::OPTIONAL, 'From Year', $fromDateTime->format('Y'))
            ->addArgument('from_month', InputArgument::OPTIONAL, 'From Month', $fromDateTime->format('m'))
            ->addArgument('from_day', InputArgument::OPTIONAL, 'From Day', $fromDateTime->format('d'))
            ->addArgument('to_year', InputArgument::OPTIONAL, 'To Year', $fromDateTime->format('Y'))
            ->addArgument('to_month', InputArgument::OPTIONAL, 'To Month', $fromDateTime->format('m'))
            ->addArgument('to_day', InputArgument::OPTIONAL, 'To Day', $toDateTime->format('d'));

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->closeOICallsProcessService->closeIOCallProcessor($input,$output);
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }
    }
}
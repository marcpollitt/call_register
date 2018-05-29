<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 23/05/2018
 * Time: 10:00
 */

namespace App\Command;

use App\Services\CloseIOA4UImportService;
use App\Services\CloseOIA4UProcessService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportA4UCommand extends Command
{
    private $closeOIA4UProcessService;

    /**
     * ImportA4UCommand constructor.
     * @param CloseIOA4UImportService $closeIOA4UImportService
     */
    public function __construct(CloseOIA4UProcessService $closeOIA4UProcessService)
    {
        parent::__construct();
        $this->closeOIA4UProcessService = $closeOIA4UProcessService;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->inputArgs();

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?void
    {
        try {
            $this->closeOIA4UProcessService->A4UDataProcessor($input, $output);
            $output->writeln('Imported Answer for you excel file successfully');
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }
    }

    private function inputArgs()
    {

        $this
            // the name of the command (the part after "bin/console")
            ->setName('close:io:a4u:import')
            // the short description shown while running "php bin/console list"
            ->setDescription('Import answer for you data into database')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Import answer for you data into database')
            ->addArgument('filename', InputArgument::REQUIRED, 'filename')
            ->addArgument('date', InputArgument::REQUIRED, 'date');
    }

}
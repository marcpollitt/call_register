<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 24/05/2018
 * Time: 13:19
 */

namespace Tests\Command;

use App\Command\ImportA4UCommand;
use App\Entity\Answer4You;
use App\Repository\Answer4YouRepository;
use App\Services\CloseIOA4UImportService;
use App\Services\CloseOIA4UProcessService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportA4UCommandTest extends KernelTestCase
{

    /** @var EntityManager | KernelTestCase */
    private $entityManager;

    /** @var Answer4YouRepository | KernelTestCase */
    private $a4URepository;

    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $this->entityManager = $this->createMock(EntityManager::class);
        $this->a4URepository = $this->createMock(Answer4YouRepository::class);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Answer4You::class)
            ->willReturn($this->a4URepository);

        $this->a4URepository->expects($this->once())
            ->method('findBy')
            ->willReturn(true);

        $service = new CloseOIA4UProcessService($this->entityManager, new CloseIOA4UImportService());

        $application->add(new ImportA4UCommand($service));

        $command = $application->find('close:io:a4u:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'filename' => 'ddd.xls',
            'date' => '2018-04-01'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('File has already been upload by someone', $output);
    }

    public function testExecuteFileDoesNotExist()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $this->entityManager = $this->createMock(EntityManager::class);
        $this->a4URepository = $this->createMock(Answer4YouRepository::class);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Answer4You::class)
            ->willReturn($this->a4URepository);

        $this->a4URepository->expects($this->once())
            ->method('findBy')
            ->willReturn(false);

        $service = new CloseOIA4UProcessService($this->entityManager, new CloseIOA4UImportService());

        $application->add(new ImportA4UCommand($service));

        $command = $application->find('close:io:a4u:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'filename' => 'ddd.xls',
            'date' => '2018-04-01'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('File "ddd.xls" does not exist', $output);
    }


    public function testExecuteFileExist()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $this->entityManager = $this->createMock(EntityManager::class);
        $this->a4URepository = $this->createMock(Answer4YouRepository::class);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Answer4You::class)
            ->willReturn($this->a4URepository);

        $this->a4URepository->expects($this->once())
            ->method('findBy')
            ->willReturn(false);

        $service = new CloseOIA4UProcessService($this->entityManager, new CloseIOA4UImportService());

        $application->add(new ImportA4UCommand($service));

        $command = $application->find('close:io:a4u:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'filename' => '/Users/marc/Downloads/32063575.xls',
            'date' => '2018-05-23'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Imported Answer for you excel file successfully', $output);
    }
}
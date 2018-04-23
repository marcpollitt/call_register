<?php

namespace Tests\AppBundle\Service;


use AppBundle\Entity\Calls;
use AppBundle\Repository\CallsRepository;
use AppBundle\Repository\Interfaces\CallsRepositoryInterface;
use AppBundle\Services\CloseIOApiService;
use AppBundle\Command\CloseIOCallsCommand;
use AppBundle\Services\CloseIOCallsAutomaticallyMapper;
use AppBundle\Services\CloseIOCallsProcessService;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

class CloseIOServiceTest extends KernelTestCase
{
    const ERROR_MESSAGE = '{"error":"%s"}';

    /**
     * @var GuzzleClient
     */
    protected $guzzleClient;

    /**
     * @var CloseIOApiService
     */
    protected $closeIOService;

    /**
     * @var string
     */
    protected $credentials;

    /**
     * @var []
     */
    protected $header;

    /**
     * @var string
     */
    protected $host;

    /** @var CloseIOApiService */
    private $closeIOAPiService;

    /** @var EntityManager */
    private $entityManager;

    /** @var CloseIOCallsAutomaticallyMapper */
    private $closeOICallsMapperService;

    /** @var Application */
    private $application;

    /** @var CallsRepositoryInterface */
    private $callsRepository;

    /** @var Container */
    private $container;

    /**
     *
     */
    public function setup()
    {
        $this->entityManager = $this->createMock(EntityManager::class);

        $this->callsRepository = $this->createMock(CallsRepository::class);

        $this->closeOICallsMapperService = new CloseIOCallsAutomaticallyMapper();

        $this->container = new Container();

        $this->container->setParameter('close_io_skip', 0);
        $this->container->setParameter('close_io_limit', 100);
        $this->container->setParameter('close_io_skipKeys', ['remote_phone', 'phone', 'remote_phone_formatted']);

        $kernel = self::bootKernel();

        $this->application = new Application($kernel);
    }

    protected function getGuzzleClient(array $responses = [], array &$container = []): Client
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $history = Middleware::history($container);
        $handler->push($history);
        return new Client([
            'base_uri' => $this->host,
            'handler' => $handler,
        ]);
    }

    public function test_that_it_returns_an_array_of_data_on_success_from_close()
    {
        $success1stResponse = <<<JSON
{ "has_more": false, "data": [{"voicemail_url": null, "date_updated": "2018-04-18T15:37:49.766000+00:00", "created_by_name": "Germaine Spence", "_type": "Call", "contact_id": "cont_dqKIVUoybevdouWkIMKS0ODwDzwpnwiCQJPehwN65zu", "duration": 0, "remote_phone_formatted": "+44 7841 420264", "id": "acti_VT3epgixQRk78WcbJoDy6QiZzBOKbDL9DXbwUl5TBwI", "updated_by_name": "Germaine Spence", "users": [], "user_id": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "voicemail_duration": 0, "transferred_from": null, "created_by": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "note": "", "source": "Close.io", "has_recording": false, "dialer_id": null, "user_name": "Germaine Spence", "status": "created", "direction": "outbound", "local_phone_formatted": null, "updated_by": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "remote_phone": "+447841420264", "organization_id": "orga_B7XdlAK4v7A4FSWGBiQUD5ykPdISZh4kViOK767d7RM", "phone": "+447841420264", "local_phone": null, "lead_id": "lead_podP7pHMJO9JPjO4fmGUHJMbSFUHArH3GgZVJFygKCD", "transferred_to": null, "date_created": "2018-04-18T15:37:49.766000+00:00", "recording_url": null}]}
JSON;

        $container = [];
        $client = $this->getGuzzleClient([
            new \GuzzleHttp\Psr7\Response(200, [], $success1stResponse)
        ], $container);

        $this->closeIOAPiService = new CloseIOApiService($client);

        /** @var CloseIOCallsProcessService $closeAPI */
        $closeAPI = $this->getMockBuilder(CloseIOCallsProcessService::class)->setMethods(['__construct'])
            ->setConstructorArgs([$this->closeIOAPiService, $this->entityManager, $this->closeOICallsMapperService, $this->container])
            ->getMock();

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Calls::class)
            ->willReturn($this->callsRepository);

        $this->callsRepository->expects($this->once())
            ->method('findBy')
            ->with(['closeId' => 'acti_VT3epgixQRk78WcbJoDy6QiZzBOKbDL9DXbwUl5TBwI'])
            ->willReturn(false);

        $this->application->add(new CloseIOCallsCommand($closeAPI));
        $this->application->setAutoExit(false);
        $command = $this->application->find( 'close:io:calls:export');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' =>  $command->getName(),
            'from_year' => 2018,
            'from_month' => 2,
            'from_day' => 1,
            'to_year' => 2018,
            'to_month' => 2,
            'to_day' => 1
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Call data for dates from 2018-02-01T00:00:01.00+00:00 to 2018-02-01T23:59:59.00+00:00', $output);
        $this->assertContains('.', $output);
    }


    public function test_that_it_returns_an_array_of_data_on_success_from_close_but_only_one_is_new()
    {
        $success1stResponse = <<<JSON
{ "has_more": true, "data": [{"voicemail_url": null, "date_updated": "2018-04-18T15:37:49.766000+00:00", "created_by_name": "Germaine Spence", "_type": "Call", "contact_id": "cont_dqKIVUoybevdouWkIMKS0ODwDzwpnwiCQJPehwN65zu", "duration": 0, "remote_phone_formatted": "+44 7841 420264", "id": "acti_VT3epgixQRk78WcbJoDy6QiZzBOKbDL9DXbwUl5TBwI", "updated_by_name": "Germaine Spence", "users": [], "user_id": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "voicemail_duration": 0, "transferred_from": null, "created_by": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "note": "", "source": "Close.io", "has_recording": false, "dialer_id": null, "user_name": "Germaine Spence", "status": "created", "direction": "outbound", "local_phone_formatted": null, "updated_by": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "remote_phone": "+447841420264", "organization_id": "orga_B7XdlAK4v7A4FSWGBiQUD5ykPdISZh4kViOK767d7RM", "phone": "+447841420264", "local_phone": null, "lead_id": "lead_podP7pHMJO9JPjO4fmGUHJMbSFUHArH3GgZVJFygKCD", "transferred_to": null, "date_created": "2018-04-18T15:37:49.766000+00:00", "recording_url": null}]}
JSON;
        $success2ndResponse = <<<JSON
{ "has_more": false, "data": [{"voicemail_url": null, "date_updated": "2018-04-18T15:37:49.766000+00:00", "created_by_name": "Germaine Spence", "_type": "Call", "contact_id": "cont_dqKIVUoybevdouWkIMKS0ODwDzwpnwiCQJPehwN65zu", "duration": 0, "remote_phone_formatted": "+44 7841 420264", "id": "acti_dddafll;daj;lfsdpoudfpoiuofdpsapusdjvnalndd", "updated_by_name": "Germaine Spence", "users": [], "user_id": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "voicemail_duration": 0, "transferred_from": null, "created_by": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "note": "", "source": "Close.io", "has_recording": false, "dialer_id": null, "user_name": "Germaine Spence", "status": "created", "direction": "outbound", "local_phone_formatted": null, "updated_by": "user_ZKoTHcHj9YD0YTKT2hJJtsrg3SN2Zv45XxWd3gqVLaM", "remote_phone": "+447841420264", "organization_id": "orga_B7XdlAK4v7A4FSWGBiQUD5ykPdISZh4kViOK767d7RM", "phone": "+447841420264", "local_phone": null, "lead_id": "lead_podP7pHMJO9JPjO4fmGUHJMbSFUHArH3GgZVJFygKCD", "transferred_to": null, "date_created": "2018-04-18T15:37:49.766000+00:00", "recording_url": null}]}
JSON;

        $container = [];
        $client = $this->getGuzzleClient([
            new \GuzzleHttp\Psr7\Response(200, [], $success1stResponse),
            new \GuzzleHttp\Psr7\Response(200, [], $success2ndResponse)
        ], $container);

        $this->closeIOAPiService = new CloseIOApiService($client);


        $closeAPI = $this->getMockBuilder(CloseIOCallsProcessService::class)->setMethods(['__construct'])
            ->setConstructorArgs([$this->closeIOAPiService, $this->entityManager, $this->closeOICallsMapperService,$this->container])
            ->getMock();

        $this->entityManager->expects($this->exactly(2))
            ->method('getRepository')
            ->with(Calls::class)
            ->willReturn($this->callsRepository);

        $this->callsRepository->expects($this->at(0))
            ->method('findBy')
            ->with(['closeId' => 'acti_VT3epgixQRk78WcbJoDy6QiZzBOKbDL9DXbwUl5TBwI'])
            ->willReturn(false);

        $this->callsRepository->expects($this->at(1))
            ->method('findBy')
            ->with(['closeId' => 'acti_dddafll;daj;lfsdpoudfpoiuofdpsapusdjvnalndd'])
            ->willReturn(true);

        $this->application->add(new CloseIOCallsCommand($closeAPI));
        $this->application->setAutoExit(false);
        $command = $this->application->find( 'close:io:calls:export');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' =>  $command->getName(),
            'from_year' => 2018,
            'from_month' => 2,
            'from_day' => 1,
            'to_year' => 2018,
            'to_month' => 2,
            'to_day' => 1
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Call data for dates from 2018-02-01T00:00:01.00+00:00 to 2018-02-01T23:59:59.00+00:00', $output);
        $this->assertContains('.', $output);
    }

    public function test_that_it_returns_exception_400()
    {
        $error = '{"error": {"message": "API call Bad Request", "rate_reset": 0.870663, "rate_limit": 40, "rate_window": 1, "rate_limit_type": "key", "rate_endpoint_group": "99ad0b85407fbfce6882152c4cd0b86d"}}';

        $container = [];
        $client = $this->getGuzzleClient([
            new \GuzzleHttp\Psr7\Response(400, [], $error)
        ], $container);

        $this->closeIOAPiService = new CloseIOApiService($client);

        /** @var CloseIOCallsProcessService $closeAPI */
        $closeAPI = $this->getMockBuilder(CloseIOCallsProcessService::class)->setMethods(['__construct'])
            ->setConstructorArgs([$this->closeIOAPiService, $this->entityManager, $this->closeOICallsMapperService, $this->container])
            ->getMock();

        $this->application->add(new CloseIOCallsCommand($closeAPI));
        $this->application->setAutoExit(false);
        $command = $this->application->find( 'close:io:calls:export');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' =>  $command->getName(),
            'from_year' => 2018,
            'from_month' => 2,
            'from_day' => 1,
            'to_year' => 2018,
            'to_month' => 2,
            'to_day' => 1
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Call data for dates from 2018-02-01T00:00:01.00+00:00 to 2018-02-01T23:59:59.00+00:00', $output);
        $this->assertContains('Client error:', $output);
        $this->assertContains('400 Bad Request', $output);
    }

    public function test_that_it_returns_exception_500_from_close_but_only_one_is_new()
    {
        $error = '{"error": {"message": "API call Internal Server Error", "rate_reset": 0.870663, "rate_limit": 40, "rate_window": 1, "rate_limit_type": "key", "rate_endpoint_group": "99ad0b85407fbfce6882152c4cd0b86d"}}';

        $container = [];
        $client = $this->getGuzzleClient([
            new \GuzzleHttp\Psr7\Response(500, [], $error)
        ], $container);

        $this->closeIOAPiService = new CloseIOApiService($client);

        /** @var CloseIOCallsProcessService $closeAPI */
        $closeAPI = $this->getMockBuilder(CloseIOCallsProcessService::class)->setMethods(['__construct'])
            ->setConstructorArgs([$this->closeIOAPiService, $this->entityManager, $this->closeOICallsMapperService, $this->container])
            ->getMock();

        $this->application->add(new CloseIOCallsCommand($closeAPI));
        $this->application->setAutoExit(false);
        $command = $this->application->find( 'close:io:calls:export');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' =>  $command->getName(),
            'from_year' => 2018,
            'from_month' => 2,
            'from_day' => 1,
            'to_year' => 2018,
            'to_month' => 2,
            'to_day' => 1
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Call data for dates from 2018-02-01T00:00:01.00+00:00 to 2018-02-01T23:59:59.00+00:00', $output);
        $this->assertContains('Server error:', $output);
        $this->assertContains('500 Internal Server Error', $output);
    }
}
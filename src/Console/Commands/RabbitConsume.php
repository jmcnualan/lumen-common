<?php

namespace Dmn\Cmn\Console\Commands;

use Anik\Amqp\ConsumableMessage;
use Anik\Amqp\Facades\Amqp;
use Illuminate\Console\Command;
use RuntimeException;

class RabbitConsume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:consume
                        {queue : queue name}
                        {routingKey : routing key}
                        {event : event from event_maps.php }
                        {--connection= : connection, default will be from AMQP_CONNECTION }
                        {--declare=1 : declare, create queue if not existing, default: 1 }
                        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a rabbitmq consumer.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routingKey = $this->argument('routingKey');
        $eventClass = $this->getEvent();

        $this->consume($routingKey, $eventClass);
    }

    /**
     * Consume
     *
     * @param string $routingKey
     * @param string $eventClass
     *
     * @return void
     */
    public function consume(
        string $routingKey,
        string $eventClass
    ): void {
        $configuration = $this->getConfiguration();
        Amqp::consume(
            function (ConsumableMessage $message) use ($eventClass) {
                $rawMessage = $message->getStream() . PHP_EOL;
                $content    = json_decode($rawMessage, true);
                try {
                    event(new $eventClass($content));
                    $message->getDeliveryInfo()->acknowledge();
                    $this->info(
                        'Acknowledged['
                        . $this->argument('event')
                        . ']: '
                        .  $rawMessage
                    );
                } catch (\Exception $e) {
                    $message->getDeliveryInfo()->reject();
                    $this->error(
                        'Rejected['
                        . $this->argument('event')
                        . ']: '
                        .  $rawMessage
                    );
                }
            },
            $routingKey,
            $configuration
        );
    }

    /**
     * Get configuration
     *
     * @return array
     */
    protected function getConfiguration(): array
    {
        $queue      = $this->argument('queue');
        $connection = $this->getConnection();
        $declare    = $this->option('declare') === '1' ? true : false;

        return [
            'connection' => $connection,
            'queue' => [
                'name' => $queue,
                'declare' => $declare
            ],
        ];
    }

    /**
     * Get event
     *
     * @return string
     */
    protected function getEvent(): string
    {
        $event      = $this->argument('event');
        $eventClass = config('event_maps.' . $event);

        if (true === is_null($eventClass)) {
            throw new RuntimeException('Event [' . $event . '] not found in event maps.');
        }

        return config('event_maps.' . $event);
    }

    /**
     * Get Connection
     *
     * @return string
     */
    public function getConnection(): string
    {
        $config = config('amqp');

        $connection = $this->option('connection');

        if (true === is_null($connection)) {
            return config('amqp.default');
        }

        if (true === isset($config['connections'][$connection])) {
            return $connection;
        }

        throw new RuntimeException('Connection [' . $connection . '] not found in config.');
    }
}

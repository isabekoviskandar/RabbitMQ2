<?php
namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );
        
        $this->channel = $this->connection->channel();
        
        // Declare the queue
        $this->channel->queue_declare(
            'my_queue',    // queue name
            false,         // passive
            true,          // durable
            false,         // exclusive
            false          // auto delete
        );
    }

    public function publishMessage($message)
    {
        $msg = new AMQPMessage(
            json_encode($message),
            ['content_type' => 'application/json']
        );
        
        $this->channel->basic_publish($msg, '', 'my_queue');
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
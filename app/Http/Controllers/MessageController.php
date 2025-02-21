<?php
namespace App\Http\Controllers;

use App\Services\RabbitMQService;

class MessageController extends Controller
{
    private $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
    }

    public function sendMessage()
    {
        $message = [
            'type' => 'notification',
            'content' => 'Iskandar',
            'timestamp' => now()
        ];

        $this->rabbitMQService->publishMessage($message);
        
        return response()->json(['message' => 'Iskandar']);
    }
}

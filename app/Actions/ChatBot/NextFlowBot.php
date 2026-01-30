<?php
namespace App\Actions\ChatBot;

use App\Services\CallnChat\ChatBotService;

class NextFlowBot
{
     public function __construct(
          private $chatBotService = new ChatBotService
     ) {
     }
     public function handle($botId)
     {
          $chatBot = $this->chatBotService->findById($botId);
          $message = $chatBot['next_scene'];
          $nextFlow = $message['next'];
          unset($message['next']);


          $currentStepIndex = $chatBot->current_step['index'];
          $chatBot->update([
               'current_scene' => $message,
               'next_scene' => $nextFlow,
               'current_step' => [
                    'index' => $currentStepIndex + 1,
                    'type' => $message['type']
               ],
          ]);

          $this->chatBotService->message::create([
               'chatbot_id' => $chatBot->id,
               'sender' => 'bot',
               'message' => json_encode($message),
               'message_type' => "bot_" . $message['type'],
          ]);

          return [
               'message' => [
                    'message' => $this->chatBotService->handleMessageOption($message),
                    'message_type' => "bot_" . $message['type'],
                    'sender' => 'bot',
                    'time' => date('H:i')
               ],
               'type' => $message['type'],
          ];
     }
}
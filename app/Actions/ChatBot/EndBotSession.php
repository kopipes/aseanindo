<?php
namespace App\Actions\ChatBot;

use Illuminate\Http\Request;
use App\Services\CallnChat\ChatBotService;

class EndBotSession
{
     public function __construct(
          private $chatBotService = new ChatBotService
     ) {
     }
     public function handle($botId)
     {
          $chatBot = $this->chatBotService->findById($botId);
          $chatBot->update([
               'status' => 'end'
          ]);
     }
}
<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChatRequest;
use App\Repository\reporter\ReportChatRepository;

class ReportChatController extends Controller
{

      protected $reportChat;
      public function __construct(ReportChatRepository $reportChat)
      {

            $this->reportChat = $reportChat;
      }

      //store  report chat between reporter and admin

      public function store(ChatRequest $request)
      {
            return $this->reportChat->store($request);
      }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChatRequest;
use App\Repository\admin\ReportChatRepository;

class ReportChatController extends Controller
{

protected $report_chat;
public function __construct(ReportChatRepository $report_chat)
{
    $this->report_chat=$report_chat;
}

public function store(ChatRequest $request)
{
    return $this->report_chat->store($request);
}


}

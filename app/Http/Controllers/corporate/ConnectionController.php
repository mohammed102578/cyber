<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Corporate\ConnectionRequest;
use App\Repository\corporate\ConnectionRepository;

class ConnectionController extends Controller
{
  
    protected $connection;
    public function __construct(ConnectionRepository $connection)
    {
  
      $this->connection = $connection;
    }
  
    public function store(ConnectionRequest $request)
    {
      return $this->connection->store($request);
    }
  
    public function destroy($id)
    {
      return $this->connection->destroy($id);
    }
}

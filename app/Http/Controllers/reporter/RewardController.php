<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reporter\TimeRequest;
use App\Repository\reporter\RewardRepository;

class RewardController extends Controller
{

  protected $reward;
  public function __construct(RewardRepository $reward)
  {

      $this->reward = $reward;
  }
  public function reward(TimeRequest $request)
 {
  return $this->reward->reward($request);

 }
}

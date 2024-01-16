<?php

namespace App\Http\Controllers\corporate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reporter\TimeRequest;
use App\Repository\corporate\RewardRepository;


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

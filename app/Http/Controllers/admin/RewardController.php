<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repository\admin\RewardRepository;


class RewardController extends Controller
{
 
  public $reward;
  public function __construct(RewardRepository $reward)
  {
    $this->reward = $reward;
  }

public function corporate_reward()
{
return $this->reward->corporate_reward();
}

public function reporter_reward()
{
  return $this->reward->reporter_reward();

}






}

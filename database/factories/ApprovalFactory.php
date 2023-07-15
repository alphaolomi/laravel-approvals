<?php

namespace Alphaolomi\LaravelApprovals\Database\Factories;

use Alphaolomi\LaravelApprovals\Models\Approval;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalFactory extends Factory
{
    protected $model = Approval::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'approvable_id' => 1,
            'approvable_type' => 'Alphaolomi\LaravelApprovals\Approval',
            'approved' => 1,
            'approved_at' => now(),
            'approved_by' => 1,
            'rejected' => 0,
            'rejected_at' => null,
            'rejected_by' => null,
            'reason' => null,
        ];
    }
}

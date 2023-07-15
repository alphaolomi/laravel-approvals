<?php

namespace Alphaolomi\LaravelApprovals;

use Alphaolomi\LaravelApprovals\Models\Approval;

class Approvals
{
    // approve
    public function approve($model, $type, $approver, $comment = null)
    {
        $model->approvals()->create([
            'approver_id' => $approver->id,
            'comment' => $comment,
            'state' => $model->getApprovalWorkflowStates()[$type]['next_state'],
        ]);
    }

    // approvals
    public function approvals($model, $type)
    {
        return $model->approvals()->where('type', $type)->get();
    }

    // allApprovals
    public function allApprovals()
    {
        return Approval::all();
    }

    // getApprovalTypes
    public function getApprovalTypes($model)
    {
        return $model->getApprovalTypes();
    }
}

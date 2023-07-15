<?php

namespace Alphaolomi\LaravelApprovals;

use Alphaolomi\LaravelApprovals\Models\Approval;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait HasApprovals
 *
 * @package Alphaolomi\LaravelApprovals
 *
 * @property MorphMany $approvals
 * @method MorphMany approvals()
 * @method approve(string $type, null|User|int $user = null, string|null $comment = null)
 * @method hasApprovalFrom(User|int $user)
 * @method getLatestApproval()
 */
trait HasApprovals
{
    /**
     * @return MorphMany
     */
    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable')->latest();
    }

    /**
     * @param string $type
     * @param null|User|int $user
     * @param string|null $comment
     * @return Approval
     */
    public function approve($type, $user = null, $comment = null)
    {
        if ($user instanceof Authenticatable) {
            $user = $user->id;
        }

        $workflowStates = static::getApprovalWorkflowStates();

        // check if type is defined
        if (!isset($workflowStates[$type])) {
            throw new \InvalidArgumentException("Approval state {$type} is not defined");
        }

        // check if user has already approved this
        // for the given type
        if ($this->hasApprovalFrom($user, $type)) {
            throw new \InvalidArgumentException("User {$user} has already approved this");
        }

        // get latest approval
        $prevApproval = $this->getLatestApproval();

        //   if is not the first approval
        if ($prevApproval) {

            // check if user is allowed to approve this
            if ($prevApproval->next_approver_role !== $workflowStates[$type]['prev_approver_role']) {
                throw new \InvalidArgumentException("User {$user} is not allowed to approve this");
            }

            // check if next state is valid
            if (!in_array($type, $workflowStates[$prevApproval->approval_type]['next_possible_state'])) {
                // Cannot approve this state
                throw new \InvalidArgumentException("Approval state {$type} is not valid");
            }
        }
        $prev_approver_role = $workflowStates[$type]['prev_approver_role'];

        $approval = new Approval([
            'approved_by' => $user,
            'approval_type' => $type,
            'comment' => $comment,
            'prev_approval_id' => $prevApproval ? $prevApproval->id : null,
            'prev_approver_role' => $workflowStates[$type]['prev_approver_role'],
            'next_approver_role' => $workflowStates[$type]['next_approver_role'],
        ]);

        return $this->approvals()->save($approval);
    }

    /**
     * @param User|int $user
     * @param string|null $type
     * @return bool
     */
    public function hasApprovalFrom($user, $type = null): bool
    {
        if ($user instanceof Authenticatable) {
            $user = $user->id;
        }

        return $this->approvals()
            ->where('approved_by', $user)
            ->when($type, function ($query) use ($type) {
                $query->where('approval_type', $type);
            })
            ->exists();
    }

    public function getLatestApproval(): ?Approval
    {
        return $this->approvals()->latest()->first();
    }

    /**
     * This method should return an array of approval types
     * and their workflow states
     *
     * @example
     * [
     * 'approved' => [
     * 'prev_approver_role' => 'manager',
     * 'next_approver_role' => 'director',
     * 'next_possible_state' => ['rejected', 'approved'],
     * ],
     * 'rejected' => [
     * 'prev_approver_role' => 'manager',
     * 'next_approver_role' => 'manager',
     * 'next_possible_state' => ['rejected'],
     * ],
     *
     * @return array
     */
    public abstract static function getApprovalTypes(): array;

    public abstract static function getApprovalWorkflowStates(): array;
}

<?php

use Alphaolomi\LaravelApprovals\HasApprovals;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\Factories\UserFactory;

/**
 * @property mixed id
 *
 * @mixins \Alphaolomi\LaravelApprovals\HasApprovals
 */
class Project extends Model
{
    use HasApprovals;

    protected $guarded = [];

    // getApprovalTypes
    public static function getApprovalTypes(): array
    {
        return [
            'project_submission',
            'project_approval',
        ];
    }

    // getApprovalStates
    public static function getApprovalWorkflowStates(): array
    {
        return [
            'project_submission' => [
                'prev_approver_role' => 'editor', // 'project_manager
                'next_approver_role' => 'project_manager',
                'next_possible_state' => ['project_approval', 'request_changes'],
                'next_state' => 'project_approval',
            ],
            'project_approval' => [
                'prev_approver_role' => 'project_manager',
                'next_approver_role' => 'project_director',
                'next_possible_state' => ['project_director_approval', 'request_changes'],
                'next_state' => 'project_director_approval',
            ],
            'project_director_approval' => [
                'prev_approver_role' => 'project_director',
                'next_approver_role' => null,
                'next_possible_state' => ['approved', 'request_changes'],
                'next_state' => 'approved',
            ],
            'approved' => [
                'prev_approver_role' => 'project_director',
                'next_approver_role' => null,
                'next_possible_state' => [],
                'next_state' => null,
            ],
        ];
    }
}

it('can approve a project', function () {
    // Migrate Project using SChema
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    // Create user table
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });

    $user = UserFactory::new()->create();

    // Create a project
    $project = Project::create(['name' => 'Project 1']);

    $project->approve('project_submission', $user);

    // expect($project->approvals)->dd();
    expect($project->approvals()->count())->toBe(1);
    expect($project->approvals()->first()->created_at)->toBeInstanceOf(Carbon\Carbon::class);
    expect($project->approvals()->first()->comment)->toBe(null);
    expect($project->approvals()->first()->approved_by)->toBe($user->id);

    // Create another user
    $user2 = UserFactory::new()->create();
    $project->approve('project_approval', $user2);

    expect($project->approvals()->count())->toBe(2);
    // expect((string)$project->approvals()
    // expect($project->approvals()->first()->created_at)->toBeInstanceOf(Carbon\Carbon::class);
    // expect($project->approvals()->first()->comment)->toBe(null);
    // expect($project->approvals()->first()->approved_by)->toBe($user2->id);

});

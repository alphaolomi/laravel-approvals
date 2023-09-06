<?php

use Alphaolomi\LaravelApprovals\Models\Approval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_approval()
    {
        $approval = Approval::factory()->create([
            'type' => 'review',
            'comment' => 'Please review this document',
        ]);

        $this->assertNotNull($approval->approval_id);
        $this->assertEquals('review', $approval->type);
        $this->assertEquals('Please review this document', $approval->comment);
    }

    /** @test */
    public function it_can_retrieve_the_approvable_model()
    {
        $post = Post::factory()->create();
        $approval = Approval::factory()->create([
            'approvable_type' => 'App\Models\Post',
            'approvable_id' => $post->id,
        ]);

        $this->assertInstanceOf(Post::class, $approval->approvable);
        $this->assertEquals($post->id, $approval->approvable->id);
    }

    /** @test */
    public function it_can_retrieve_the_user_that_approved_the_approval()
    {
        $user = User::factory()->create();
        $approval = Approval::factory()->create([
            'approved_by' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $approval->approvedBy);
        $this->assertEquals($user->id, $approval->approvedBy->id);
    }

    /** @test */
    public function it_can_retrieve_the_previous_approval()
    {
        $prevApproval = Approval::factory()->create();
        $approval = Approval::factory()->create([
            'prev_approval_id' => $prevApproval->id,
        ]);

        $this->assertInstanceOf(Approval::class, $approval->prevApproval);
        $this->assertEquals($prevApproval->id, $approval->prevApproval->id);
    }

    /** @test */
    public function it_can_retrieve_the_subsequent_approvals()
    {
        $prevApproval = Approval::factory()->create();
        $approval1 = Approval::factory()->create([
            'prev_approval_id' => $prevApproval->id,
        ]);
        $approval2 = Approval::factory()->create([
            'prev_approval_id' => $approval1->id,
        ]);

        $subSequentApprovals = $prevApproval->subSequentApprovals;

        $this->assertInstanceOf(Collection::class, $subSequentApprovals);
        $this->assertCount(2, $subSequentApprovals);
        $this->assertEquals($approval1->id, $subSequentApprovals[0]->id);
        $this->assertEquals($approval2->id, $subSequentApprovals[1]->id);
    }
}

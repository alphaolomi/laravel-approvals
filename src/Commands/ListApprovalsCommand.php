<?php

namespace Alphaolomi\LaravelApprovals\Commands;

use Illuminate\Console\Command;

class ListApprovalsCommand extends Command
{
    public $signature = 'approvals';

    public $description = 'List all approvals';

    public function handle(): int
    {
        $this->comment('All approvals:');

        return self::SUCCESS;
    }
}

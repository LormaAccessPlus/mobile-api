<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateApiToken extends Command
{
    protected $signature = 'access:token {email} {--name=API Token}';
    protected $description = 'Generate an API token for a user';

    public function handle()
    {
        $email = $this->argument('email');
        $tokenName = $this->option('name');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        $token = $user->createToken($tokenName)->plainTextToken;

        $this->info("API Token generated for {$user->name} ({$email}):");
        $this->line($token);
        $this->newLine();
        $this->comment("Use this token in the Authorization header:");
        $this->line("Authorization: Bearer {$token}");

        return 0;
    }
}
<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the onboarding status
$users = \App\Models\User::all();

echo "Testing onboarding status:\n";
echo "========================\n\n";

foreach ($users as $user) {
    echo "User ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Raw onboarding_completed: " . var_export($user->getAttributes()['onboarding_completed'] ?? null, true) . "\n";
    echo "Casted onboarding_completed: " . var_export($user->onboarding_completed, true) . "\n";
    echo "Type: " . gettype($user->onboarding_completed) . "\n";
    echo "Boolean check: " . var_export((bool)$user->onboarding_completed, true) . "\n";
    echo "Strict check (=== true): " . var_export($user->onboarding_completed === true, true) . "\n";
    echo "Strict check (=== 1): " . var_export($user->onboarding_completed === 1, true) . "\n";
    echo "Onboarding completed at: " . ($user->onboarding_completed_at ? $user->onboarding_completed_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo str_repeat('-', 50) . "\n";
}
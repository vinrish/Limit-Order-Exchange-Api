<?php

declare(strict_types=1);

use App\Actions\User\CreateUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

beforeEach(function (): void {
    User::query()->delete();
});

it('creates a user successfully', function (): void {
    Event::fake();

    $action = new CreateUser();

    $user = $action->handle(
        ['name' => 'John Doe', 'email' => 'john@example.com'],
        'secret123'
    );

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->balance)->toBe('0.00')
        ->and(Hash::check('secret123', $user->password))->toBeTrue();

    Event::assertDispatched(Registered::class);
});

it('runs inside a database transaction and rolls back on failure', function (): void {
    Event::fake();

    try {
        DB::transaction(function (): void {
            $user = User::create([
                'name' => 'Rollback User',
                'email' => 'rollback@example.com',
                'password' => bcrypt('password'),
                'balance' => '0.00',
            ]);

            event(new Registered($user));

            throw new Exception('Force rollback');
        });

        $this->fail('Expected exception was not thrown.');
    } catch (Throwable $e) {
        expect($e)->toBeInstanceOf(Exception::class)
            ->and($e->getMessage())->toBe('Force rollback');
    }

    expect(User::count())->toBe(0);

    Event::assertDispatched(Registered::class);
});

<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Http\Services\AccountService;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $accountService;

    public function setUp(): void
    {
        parent::setUp();
        $this->accountService = Mockery::mock(AccountService::class);
        $this->app->instance(AccountService::class, $this->accountService);
    }

    public function test_get_all_accounts_returns_account_collection()
    {
        $accounts = Account::factory()->count(3)->make();
        $this->accountService->shouldReceive('getAllAccounts')
            ->once()
            ->andReturn($accounts);

        $response = $this->getJson('/allAccounts');

        $response->assertOk()
                 ->assertJsonCount(3, 'data');
    }

    public function test_get_balance_return_decimal()
    {
        $this->accountService->shouldReceive('getBalance')->with(1234)->once()->andReturn(4321);
        $response = $this->getJson('/balance?account_id=1234');

        $response->assertOk()->assertSee(4321);
    }

    public function test_post_reset_account_returns_ok()
    {
        $this->accountService->shouldReceive('resetAccounts');
        $response = $this->postJson('/reset');
        
        $response->assertOk()
                 ->assertSee('OK');
    }

    public function test_post_event_deposit_returns_json()
    {
        $this->accountService->shouldReceive('deposit')
        ->with(1234,4321)->once()
        ->andReturn(['status' => 'success', 'payload' => ['destination' => 1234, 'balance' => 4321]]);
        
        $response = $this->postJson('/event', [
            'type' => 'deposit',
            'destination' => 1234,
            'amount' => 4321
        ]);

        $response->assertCreated()
        ->assertJson([
            'destination' => '1234',
            'balance' => 4321
         ]);
    }

    public function test_post_event_withdraw_returns_json()
    {
        $this->accountService->shouldReceive('withdraw')
        ->with(1234,1000)->once()
        ->andReturn(['status' => 'success', 'payload' => ['origin' => 1234, 'balance' => 9000]]);
        
        $response = $this->postJson('/event', [
            'type' => 'withdraw',
            'origin' => 1234,
            'amount' => 1000
        ]);

        $response->assertCreated()
        ->assertJson([
            'origin' => '1234',
            'balance' => 9000
         ]);
    }

    public function test_post_event_transfer_returns_json()
    {
        $this->accountService->shouldReceive('transfer')
            ->with(1234, 4567, 500)
            ->once()
            ->andReturn([
                'status' => 'success',
                'payload' => [
                    'origin' => ['id' => '4567', 'balance' => 500],
                    'destination' => ['id' => '1234', 'balance' => 1500]
                ]
            ]);

        $response = $this->postJson('/event', [
            'type' => 'transfer',
            'origin' => 4567,
            'destination' => 1234,
            'amount' => 500
        ]);

        $response->assertCreated()
            ->assertJson([
                'origin' => ['id' => '4567', 'balance' => 500],
                'destination' => ['id' => '1234', 'balance' => 1500]
            ]);
    }
}

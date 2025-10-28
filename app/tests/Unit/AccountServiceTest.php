<?php

namespace Tests\Unit;

use App\Http\Services\AccountService;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use RefreshDatabase;
    private AccountService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AccountService();
    }

    
    public function test_it_returns_all_accounts()
    {
        Account::factory()->count(3)->create();
        $accounts = $this->service->getAllAccounts();

        $this->assertCount(3, $accounts);
    }

    
    public function test_it_returns_null_if_balance_not_found()
    {
        $balance = $this->service->getBalance('999');
        $this->assertNull($balance);
    }

    
    public function test_it_returns_balance_of_existing_account()
    {
        $account = Account::factory()->create([
            'account_id' => '100',
            'balance' => 200.0
        ]);

        $balance = $this->service->getBalance('100');
        $this->assertEquals(200.0, $balance);
    }

    
    public function test_it_resets_all_accounts()
    {
        Account::factory()->count(5)->create();

        $this->service->resetAccounts();
        $this->assertDatabaseCount('accounts', 0);
    }

    
    public function test_it_deposits_in_existing_account()
    {
        $account = Account::factory()->create([
            'account_id' => 'abc',
            'balance' => 50
        ]);

        $result = $this->service->deposit('abc', 25);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(75, $result['balance']);
        $this->assertDatabaseHas('accounts', [
            'account_id' => 'abc',
            'balance' => 75
        ]);
    }

    
    public function test_it_deposits_in_new_account()
    {
        $result = $this->service->deposit('xyz', 100);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(100, $result['balance']);
        $this->assertDatabaseHas('accounts', [
            'account_id' => 'xyz',
            'balance' => 100
        ]);
    }

    
    public function test_it_withdraws_from_existing_account()
    {
        $account = Account::factory()->create([
            'account_id' => 'abc',
            'balance' => 100
        ]);

        $result = $this->service->withdraw('abc', 30);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals(70, $result['balance']);
        $this->assertDatabaseHas('accounts', [
            'account_id' => 'abc',
            'balance' => 70
        ]);
    }

    
    public function test_it_returns_error_when_withdrawing_from_non_existing_account()
    {
        $result = $this->service->withdraw('naoexiste', 10);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals(0, $result['payload']);
    }

    
    public function test_it_transfers_between_accounts_successfully()
    {
        $origin = Account::factory()->create([
            'account_id' => 'o1',
            'balance' => 100
        ]);

        $destination = Account::factory()->create([
            'account_id' => 'd1',
            'balance' => 50
        ]);

        $result = $this->service->transfer('d1', 'o1', 30);

        $this->assertEquals('success', $result['status']);
        $this->assertDatabaseHas('accounts', [
            'account_id' => 'o1',
            'balance' => 70
        ]);
        $this->assertDatabaseHas('accounts', [
            'account_id' => 'd1',
            'balance' => 80
        ]);
    }

    
    public function test_it_fails_transfer_if_origin_does_not_exist()
    {
        $result = $this->service->transfer('d1', 'naoexiste', 50);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals(0, $result['payload']);
    }
}
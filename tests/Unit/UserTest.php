<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    public function testBalanceWithoutUserError()
    {
        $response = $this->get('/balance');
        $response->assertStatus(422);
    }

    public function testBalanceNoValidIdUserError()
    {
        $response = $this->get('/balance?user=-101');
        $response->assertStatus(422);
    }

    public function testBalanceSuccess()
    {
        $response = $this->get('/balance?user=101');
        $response->assertStatus(200);
    }

    public function testDepositWithoutAmountError()
    {
        $response = $this->call('POST', 'deposit', array(
            'user' => '101',
        ));
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testDepositSuccess()
    {
        $response = $this->call('POST', 'deposit', array(
            'user' => '101',
            'amount' => '100',
        ));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testWithdrawError()
    {
        $response = $this->call('POST', 'withdraw', array(
            'user' => '101',
            'amount' => '10000',
        ));
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testWithdrawSuccess()
    {
        $response = $this->call('POST', 'withdraw', array(
            'user' => '101',
            'amount' => '50',
        ));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testTransferToError()
    {
        $response = $this->call('POST', 'transfer', array(
            'from' => '101',
            'amount' => '10000',
        ));
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testTransferFromError()
    {
        $response = $this->call('POST', 'transfer', array(
            'to' => '101',
            'amount' => '10000',
        ));
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testTransferAmountError()
    {
        $response = $this->call('POST', 'transfer', array(
            'to' => '102',
            'from' => '101',
            'amount' => '-25',
        ));
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testTransferSuccess()
    {
        $response = $this->call('POST', 'transfer', array(
            'to' => '102',
            'from' => '101',
            'amount' => '25',
        ));
        $this->assertEquals(200, $response->getStatusCode());
    }
}

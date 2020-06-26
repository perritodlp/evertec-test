<?php


namespace App\Tests;
use PHPUnit\Framework\TestCase;

//use Monolog\Test\TestCase;

class OrderModelTest extends TestCase
{
    public function test(){

        $orderModel = new Order(
            'Fernando Rengifo',
            'fer@fer.com',
            '3008987000',
            '12900',
            '2451',
            '1',
            '1',
            '1',
            '12900',
            '9522',
            '15',
            '3');

        $this->assertEquals(
            'Fernando Rengifo',
            $orderModel->getCustomerName()
        );
    }
}
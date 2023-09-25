<?php
declare(strict_types=1);

namespace Tests\Api;

use Symfony\Component\HttpFoundation\Response;
use Tests\Support\ApiTester;
use \Codeception\Attribute\DataProvider;
use \Codeception\Example;

class RegisterUserCest
{
    public function _before(ApiTester $I)
    {
    }

    public function registerUserHappyPathTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');
    }

    #[DataProvider('nameTestProvider')]
    public function registerWrongNameTest(ApiTester $I, Example $example): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => $example[0],
            'email' => 'test@example.com',
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }

    protected function nameTestProvider(): array
    {
        return [
            ['te'],
            [''],
            [' '],
            ['test123456789012'],
        ];
    }

    #[DataProvider('emailTestProvider')]
    public function registerWrongEmailTest(ApiTester $I, Example $example): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'test',
            'email' => $example[0],
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }

    protected function emailTestProvider(): array
    {
        return [
            [''],
            [' '],
            ['test.example.com'],
        ];
    }

    #[DataProvider('passwordTestProvider')]
    public function registerWrongPasswordTest(ApiTester $I, Example $example): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => $example[0],
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }

    protected function passwordTestProvider(): array
    {
        return [
            [''],
            [' '],
            ['test123'],
        ];
    }

    public function registerUserUniqueTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'test678',
            'email' => 'test678@example.com',
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'test678',
            'email' => 'test789@example.com',
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }

    public function registerEmailUniqueTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'teste678',
            'email' => 'teste678@example.com',
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/registration', [
            'name' => 'teste789',
            'email' => 'teste678@example.com',
            'password' => 'test12345678',
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }
}

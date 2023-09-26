<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */
    public function loginIntoApiAsAdmin(): string
    {
        $this->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $this->sendPost('/login', [
            'email' => 'testadmin@example.com',
            'password' => 'test1234567890',
        ]);
        $this->seeResponseCodeIsSuccessful();
        $this->seeResponseIsJson();
        $this->seeResponseContains('"status":"ok"');
        $this->seeResponseContains('"token_type":"bearer"');
        $this->seeResponseMatchesJsonType([
                                              'access_token' => 'string',
                                              'token_type' => 'string',
                                              'expires_in' => 'integer',
                                              'status' => 'string',
                                          ]);

        $token = $this->grabDataFromResponseByJsonPath('$.access_token');
        $this->amBearerAuthenticated($token[0] ?? '');

        return $token[0] ?? '';
    }

    public function grabCurrentUserUuid($token): string
    {
        $this->amBearerAuthenticated($token);

        $this->sendGet('/me');
        $this->seeResponseIsJson();

        return $this->grabDataFromResponseByJsonPath('$uuid')[0];
    }
}

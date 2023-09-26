<?php
declare(strict_types=1);

namespace Tests\Api;

use Symfony\Component\HttpFoundation\Response;
use Tests\Support\ApiTester;

class AdminLoginCest
{
    public function loginWithCorrectDataAdminTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testadmin@example.com',
            'password' => 'test1234567890',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"token_type":"bearer"');
        $I->seeResponseMatchesJsonType([
                                           'access_token' => 'string',
                                           'token_type' => 'string',
                                           'expires_in' => 'integer',
                                           'status' => 'string',
                                       ]);

        $this->checkSuccessMeWithBearerToken($I);
    }

    public function loginWithCorrectDataEditorTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testeditor@example.com',
            'password' => '0987654321test',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"token_type":"bearer"');
        $I->seeResponseMatchesJsonType([
                                           'access_token' => 'string',
                                           'token_type' => 'string',
                                           'expires_in' => 'integer',
                                           'status' => 'string',
                                       ]);

        $this->checkSuccessMeWithBearerToken($I);
    }

    public function loginWithWrongDataTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testwrong@example.com',
            'password' => 'test666',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);

        $this->checkFailedMeWithBearerToken($I);
    }

    public function loginWithWrongPasswordTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testeditor@example.com',
            'password' => 'test666',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
        $I->seeResponseCodeIs(Response::HTTP_UNAUTHORIZED);

        $this->checkFailedMeWithBearerToken($I);
    }

    public function loginWithWrongUserTypeDataTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testuser@example.com',
            'password' => 'test0987654321',
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);

        $this->checkFailedMeWithBearerToken($I);
    }

    public function loginWithCorrectDataAdminAndLogoutTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testadmin@example.com',
            'password' => 'test1234567890',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"token_type":"bearer"');
        $I->seeResponseMatchesJsonType([
                                           'access_token' => 'string',
                                           'token_type' => 'string',
                                           'expires_in' => 'integer',
                                           'status' => 'string',
                                       ]);

        $I->sendPost('/logout');
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');

        $this->checkFailedMeWithBearerToken($I);
    }

    private function checkSuccessMeWithBearerToken(ApiTester $I)
    {
        $token = $I->grabDataFromResponseByJsonPath('$.access_token');
        $I->amBearerAuthenticated($token[0]);

        $I->sendGet('/me');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_OK);
    }

    private function checkFailedMeWithBearerToken(ApiTester $I)
    {
        $token = $I->grabDataFromResponseByJsonPath('$.access_token');
        $I->amBearerAuthenticated($token[0] ?? '');

        $I->sendGet('/me');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(Response::HTTP_FORBIDDEN);
    }

    public function renewTokenTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/login', [
            'email' => 'testadmin@example.com',
            'password' => 'test1234567890',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"token_type":"bearer"');
        $I->seeResponseMatchesJsonType([
                                           'access_token' => 'string',
                                           'token_type' => 'string',
                                           'expires_in' => 'integer',
                                           'status' => 'string',
                                       ]);

        $token = $I->grabDataFromResponseByJsonPath('$.access_token');

        $this->checkSuccessMeWithBearerToken($I);

        $I->sendPost('/refresh');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"token_type":"bearer"');
        $I->seeResponseMatchesJsonType([
                                           'access_token' => 'string',
                                           'token_type' => 'string',
                                           'expires_in' => 'integer',
                                           'status' => 'string',
                                       ]);

        $newToken = $I->grabDataFromResponseByJsonPath('$.access_token');

        if ($token == $newToken) {
            $I->fail('Token is this same as previous!');
        }

        $this->checkSuccessMeWithBearerToken($I);
    }
}

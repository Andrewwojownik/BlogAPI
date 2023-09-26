<?php
declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

class PublicPostCest
{
    public function fetchWithoutPageTeset(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGet('/posts');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"page":1');
        $I->seeResponseContains('"data":');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'page' => 'integer',
                                           'data' => [
                                               ['uuid' => 'string',
                                                   'title' => 'string',
                                                   'content' => 'string',
                                                   'author_uuid' => 'string',
                                                   'created_at' => 'string',
                                                   'updated_at' => 'null|string',]
                                           ]
                                       ]);
    }

    public function fetchPageTest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGet('/posts/3');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"page":3');
        $I->seeResponseContains('"data":');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'page' => 'integer',
                                           'data' => [
                                               ['uuid' => 'string',
                                                   'title' => 'string',
                                                   'content' => 'string',
                                                   'author_uuid' => 'string',
                                                   'created_at' => 'string',
                                                   'updated_at' => 'null|string',]
                                           ]
                                       ]);

    }
}

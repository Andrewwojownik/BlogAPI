<?php
declare(strict_types=1);

namespace Tests\Api;

use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Symfony\Component\HttpFoundation\Response;
use Tests\Support\ApiTester;

class AdminPostCest
{
    public function getPostListTest(ApiTester $I)
    {
        $I->loginIntoApiAsAdmin();

        $I->sendGet('/admin/posts');
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
        $I->loginIntoApiAsAdmin();

        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGet('/admin/posts?page=3');
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

    public function getListAndShowFirstPostTest(ApiTester $I): void
    {
        $I->loginIntoApiAsAdmin();

        $I->sendGet('/admin/posts');
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

        $firstPostUuid = (string)$I->grabDataFromResponseByJsonPath('$..data[0].uuid')[0];

        $I->sendGet('/admin/posts/' . $firstPostUuid);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"data":');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'data' => [
                                               'uuid' => 'string',
                                               'title' => 'string',
                                               'content' => 'string',
                                               'author_uuid' => 'string',
                                               'created_at' => 'string',
                                               'updated_at' => 'null|string',
                                           ]
                                       ]);
    }

    public function getShowWithNotExistsPostTest(ApiTester $I): void
    {
        $I->loginIntoApiAsAdmin();

        $I->sendGet('/admin/posts/06c84d09-a902-4186-bb24-42786f906e14');
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }

    public function addPostHappyPathTest(ApiTester $I): void
    {
        $token = $I->loginIntoApiAsAdmin();
        $uuid = $I->grabCurrentUserUuid($token);

        $I->amBearerAuthenticated($token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/admin/posts', [
            'title' => 'test title',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sit amet arcu non purus consectetur efficitur ullamcorper nec sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Pellentesque condimentum massa sed ipsum sodales mattis. Mauris cursus erat dolor, auctor commodo ligula suscipit eget. Ut sollicitudin ligula eget velit porta congue. Nam fringilla nibh id blandit pellentesque. Aenean consequat nisi vitae mollis facilisis. Suspendisse iaculis blandit elit eu finibus. Nullam urna eros, pellentesque ac facilisis nec, tincidunt et lorem. ',
            'author_uuid' => $uuid,
        ]);
        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'data' => 'string'
                                       ]);

        $newUuid = $I->grabDataFromResponseByJsonPath('$data.uuid')[0] ?? '';

        $I->amBearerAuthenticated($token);
        $I->sendGet('/admin/posts/' . $newUuid);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"data":');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'data' => [
                                               [
                                                   'uuid' => 'string',
                                                   'title' => 'string',
                                                   'content' => 'string',
                                                   'author_uuid' => 'string',
                                                   'created_at' => 'string',
                                                   'updated_at' => 'null|string',
                                               ]
                                           ]
                                       ]);
    }

    #[DataProvider('requiredTestProvider')]
    public function addPostRequiredData(ApiTester $I, Example $example): void
    {
        $token = $I->loginIntoApiAsAdmin();
        $uuid = $I->grabCurrentUserUuid($token);

        $I->amBearerAuthenticated($token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/admin/posts', [
            'title' => $example[0],
            'content' => $example[1],
            'author_uuid' => $uuid,
        ]);
        $I->seeResponseCodeIs(Response::HTTP_UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"error"');
    }

    protected function requiredTestProvider(): array
    {
        return [
            ['', 'content'],
            ['title', ''],
        ];
    }

    public function addPostAndDeleteHappyPathTest(ApiTester $I): void
    {
        $token = $I->loginIntoApiAsAdmin();
        $uuid = $I->grabCurrentUserUuid($token);

        $I->amBearerAuthenticated($token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/admin/posts', [
            'title' => 'test title',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sit amet arcu non purus consectetur efficitur ullamcorper nec sem. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Pellentesque condimentum massa sed ipsum sodales mattis. Mauris cursus erat dolor, auctor commodo ligula suscipit eget. Ut sollicitudin ligula eget velit porta congue. Nam fringilla nibh id blandit pellentesque. Aenean consequat nisi vitae mollis facilisis. Suspendisse iaculis blandit elit eu finibus. Nullam urna eros, pellentesque ac facilisis nec, tincidunt et lorem. ',
            'author_uuid' => $uuid,
        ]);
        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'data' => 'string'
                                       ]);

        $newUuid = $I->grabDataFromResponseByJsonPath('$data')[0] ?? '';

        $I->amBearerAuthenticated($token);
        $I->sendGet('/admin/posts/' . $newUuid);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
        $I->seeResponseContains('"data":');
        $I->seeResponseMatchesJsonType([
                                           'status' => 'string',
                                           'data' => [
                                               'uuid' => 'string',
                                               'title' => 'string',
                                               'content' => 'string',
                                               'author_uuid' => 'string',
                                               'created_at' => 'string',
                                               'updated_at' => 'null|string',
                                           ]
                                       ]);

        $I->amBearerAuthenticated($token);
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendDelete('/admin/posts/' . $newUuid);
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"status":"ok"');
    }
}

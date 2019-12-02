<?php

declare(strict_types=1);

namespace GhostZero\BitinflowAccounts\Tests;

use GhostZero\BitinflowAccounts\Enums\DocumentType;
use GhostZero\BitinflowAccounts\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
class ApiDocumentsTest extends ApiTestCase
{

    public function testCreateDocument(): void
    {
        $this->getClient()->withToken($this->getToken());

        $result = $this->getClient()->createDocument([
            'branding' => [
                'primary_color' => '#8284df',
                'watermark_url' => 'https://fbs.streamkit.gg/img/pdf/wm.png',
                'logo_url' => 'https://fbs.streamkit.gg/img/pdf/logo_dark_small.png',
            ],
            'locale' => 'de',
            'type' => DocumentType::TYPE_PDF_INVOICE,
            'data' => $this->createDummyInvoiceData(),
            'receipt_email' => 'rene+unittest@bitinflow.com',
        ]);

        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('id', $result->data());
        $this->assertArrayHasKey('download_url', $result->data());
        $this->assertEquals(
            'rene+unittest@bitinflow.com',
            $result->data()->receipt_email
        );
    }

    public function testGenerateDocumentStoragePath(): void
    {
        $this->getClient()->withToken($this->getToken());

        $expiresAt = now()->addHours(2);

        $result = $this->getClient()->createDocumentDownloadUrl('1', $expiresAt);

        $this->registerResult($result);
        $this->assertTrue($result->success());
        $this->assertArrayHasKey('download_url', $result->data());
        $this->assertEquals(
            $expiresAt->toDateTimeString(),
            $result->data()->expires_at
        );
    }

    private function createDummyInvoiceData(): array
    {
        return [
            'id' => 'FBS-IN-1337',
            'customer' => [
                'name' => 'GhostZero',
                'email' => 'rene@preuss.io',
                'address' => [
                    'Example Street 123',
                    '50733 Cologne',
                    'GERMANY',
                ],
            ],
            'line_items' => [
                [
                    'name' => 'T-shirt',
                    'description' => 'Comfortable cotton t-shirt',
                    'unit' => 'T-shirt', // optional unit name
                    'amount' => 1500,
                    'currency' => 'usd',
                    'quantity' => 2,
                ],
            ],
            'legal_notice' => 'According to the German §19 UStG no sales tax is calculated. However, the product is a digital good delivered via Internet we generally offer no refunds. The delivery date corresponds to the invoice date.',
            'already_paid' => true,
            'created_at' => now()->format('d.m.Y'),
        ];
    }
}
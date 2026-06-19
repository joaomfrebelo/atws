<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rebelo\ATWs\ATWsException;
use Rebelo\Base;

/**
 * Class LineTest
 *
 * @author João Rebelo
 */
class ResponseTest extends TestCase
{

    /**
     * @return void
     */
    #[Test]
    public function testReflection(): void
    {
        (new Base(Response::class))->testReflection(Response::class);
        $this->assertTrue(true);
    }

    /**
     * @return array[]
     */
    public static function responsesProvider(): array
    {
        $baseDir = ATWS_INVOICE_RESPONSE_DIR . DIRECTORY_SEPARATOR;
        return [
            [$baseDir . "ReturnCodeError.xml", 0, "Operação efetuada com sucesso."],

            [
                $baseDir . "AuthenticationError.xml",
                16,
                "Created: Chave de sessão inválida. Não foi possível decifrar o campo Created",
            ],
        ];
    }

    /**
     *
     * @param string $filePath
     * @param int    $code
     * @param string $message
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Exception
     */
    #[Test]
    #[DataProvider("responsesProvider")]
    public function testResponses(string $filePath, int $code, string $message): void
    {
        $xml = \file_get_contents($filePath);
        $response = Response::factory($xml ?: throw new \Exception("Fail to load file " . $filePath));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame($code, $response->getCode());
        $this->assertSame(
            \str_replace(["\r", "\n", "\t"], "", $message),
            str_replace(["\r", "\n", "\t"], "", $response->getMessage())
        );
    }

    /***
     * @throws \Rebelo\ATWs\ATWsException
     */
    #[Test]
    public function testWrongXmlResponses(): void
    {
        $this->expectException(ATWsException::class);
        $xml = "<Test></Test>";
        $response = Response::factory($xml);
        $this->assertInstanceOf(Response::class, $response);
    }

}

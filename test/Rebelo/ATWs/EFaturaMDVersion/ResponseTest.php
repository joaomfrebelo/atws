<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\EFaturaMDVersion;

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
     * @test
     * @return void
     */
    public function testReflection(): void
    {
        (new Base())->testReflection(Response::class);
        $this->assertTrue(true);
    }

    /**
     * @return array[]
     */
    public function responsesProvider(): array
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
     * @test
     * @dataProvider responsesProvider
     * @param string $filePath
     * @param int    $code
     * @param string $message
     * @throws \Rebelo\ATWs\ATWsException
     * @throws \Exception
     */
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
    public function testWrongXmlResponses(): void
    {
        $this->expectException(ATWsException::class);
        $xml = "<Test></Test>";
        $response = Response::factory($xml);
        $this->assertInstanceOf(Response::class, $response);
    }

}

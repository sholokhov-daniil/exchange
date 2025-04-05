<?php

namespace Sholokhov\Exchange\Source;

use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @param string $json
     * @param bool $multiple
     * @param int $count
     * @return void
     * @dataProvider iterableProvider
     */
    public function testIterable(string $json, bool $multiple, int $count): void
    {
        $i = 0;
        $source = new Json($json);
        $source->setMultiple($multiple);

        foreach ($source as $key => $value) {
            $i++;
        }

        $this->assertEquals($count, $i);
    }

    /**
     * @param string $json
     * @param string $sourceKey
     * @param mixed $actual
     * @param bool $multiple
     * @return void
     * @dataProvider sourceKeyProvider
     */
    public function testSourceKey(string $json, mixed $sourceKey, mixed $actual, bool $multiple): void
    {
        $source = new Json($json, $sourceKey);
        $source->setMultiple($multiple);

        $this->assertSame($actual, $source->current());
    }

    public static function sourceKeyProvider(): array
    {
        return [
            [
                json_encode([
                    'test' => 12,
                    'doc' => [
                        'hello' => 'world',
                    ]
                ]),
                null,
                [
                    'test' => 12,
                    'doc' => [
                        'hello' => 'world',
                    ]
                ],
                false,
            ],
            [
                json_encode(['key1' => 'test', 'key2' => 'doc']),
                'key1',
                'test',
                false,
            ],
            [
                json_encode(15),
                'testKey',
                null,
                false,
            ],
            [
                json_encode(['key1' => 'test']),
                777,
                null,
                false,
            ],
            [
                json_encode(['55', 'test', 'as']),
                2,
                'as',
                false,
            ],
        ];
    }

    public static function itemsProvider(): array
    {
        return [
            [json_encode(12), 12],
            [json_encode(false), false],
            [json_encode(['zz', 'ss']), ['zz', 'ss']],
            [json_encode(['zz', 'ss' => ['asas' => ['sdddd' => 12]]]), ['zz', 'ss' => ['asas' => ['sdddd' => 12]]]],
            [json_encode('hello'), 'hello'],
            [json_encode(true), true],
            [json_encode(33.56), 33.56],
        ];
    }

    public static function iterableProvider(): array
    {
       return [
           [
               json_encode(12),
               false,
               1,
           ],
           [
               json_encode(['ss', 'qqqq']),
               true,
               2
           ],
           [
               json_encode(['sss', '12' => ['asas' => ['sdddd' => 12]]]),
               true,
               2,
           ],
           [
               json_encode(['sss', '12' => ['asas' => ['sdddd' => 12]]]),
               false,
               1,
           ],
       ];
    }
}
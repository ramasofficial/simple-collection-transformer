<?php

declare(strict_types=1);

namespace Ramasdev\SimpleCollectionTransformer\Tests\Unit;

use Carbon\Carbon;
use Generator;
use PHPUnit\Framework\TestCase;
use Ramasdev\SimpleCollectionTransformer\CollectionTransformer;
use Ramasdev\SimpleCollectionTransformer\Tests\Unit\Data\Collections\ChannelCollection;
use Ramasdev\SimpleCollectionTransformer\Tests\Unit\Data\Models\Channel;
use ReflectionException;

class CollectionTransformerTest extends TestCase
{
    private CollectionTransformer $collectionTransformer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collectionTransformer = new CollectionTransformer();
    }

    /**
     * @dataProvider transformerDataProvider
     * @throws ReflectionException
     */
    public function testTransformerBuildCollectionProperly(array $data): void
    {
        $actualCollection = $this->collectionTransformer->transform($data, Channel::class, function ($item) {
            return new Channel($item['channel_id'], $item['channel_type'], new Carbon($item['last_registration']));
        });

        $this->assertInstanceOf(ChannelCollection::class, $actualCollection);
        $this->assertCount(count($data), $actualCollection);
    }

    /**
     * @dataProvider transformerDataProvider
     * @throws ReflectionException
     */
    public function testTransformerBuildCollectionWithConditionsProperly(array $data, int $conditionalCount): void
    {
        $actualCollection = $this->collectionTransformer->transform($data, Channel::class, function ($item) {
            if ($item['channel_id'] === 'b') {
                return false;
            }

            return new Channel($item['channel_id'], $item['channel_type'], new Carbon($item['last_registration']));
        });

        $this->assertInstanceOf(ChannelCollection::class, $actualCollection);
        $this->assertCount($conditionalCount, $actualCollection);
    }

    public function transformerDataProvider(): Generator
    {
        $data = [
            0 => [
                'channel_id' => 'a',
                'channel_type' => 'ios',
                'last_registration' => '2022-01-01 16:00:00',
            ],
            1 => [
                'channel_id' => 'b',
                'channel_type' => 'android',
                'last_registration' => '2022-01-04 16:00:00',
            ],
        ];

        $dataSecond = $data;
        $dataSecond[] = [
            'channel_id' => 'c',
            'channel_type' => 'android',
            'last_registration' => '2022-01-04 16:00:00',
        ];

        yield 'First simple example' => [
            'data' => $data,
            'conditional_count' => 1,
        ];

        yield 'Second simple example' => [
            'data' => $dataSecond,
            'conditional_count' => 2,
        ];
    }
}

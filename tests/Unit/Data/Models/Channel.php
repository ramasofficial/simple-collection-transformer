<?php

declare(strict_types=1);

namespace Ramasdev\SimpleCollectionTransformer\Tests\Unit\Data\Models;

use Carbon\Carbon;
use Ramasdev\SimpleCollectionTransformer\Attributes\CollectionAttribute;
use Ramasdev\SimpleCollectionTransformer\Tests\Unit\Data\Collections\ChannelCollection;

#[CollectionAttribute(ChannelCollection::class)]
class Channel
{
    public function __construct(
        private string $channelId,
        private string $channelType,
        private Carbon $lastRegistration
    ) {
    }

    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public function getChannelType(): string
    {
        return $this->channelType;
    }

    public function getLastRegistration(): Carbon
    {
        return $this->lastRegistration;
    }
}

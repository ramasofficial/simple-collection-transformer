# Simple Collection Transformer

Simple data to Laravel collection transformer, don't write Collection transformers anymore!
- Lets you utilize filters through callback function.

## Install
```
composer require ramasdev/simple-collection-transformer
```

## Usage

Create DTO/Model class and provide collection class through attributes to which need to transform:

```php
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
```

Transform to collection with filters:

```php
use Ramasdev\SimpleCollectionTransformer\CollectionTransformer;

$actualCollection = $this->collectionTransformer->transform($data, function ($item) {
    if ($item['channel_id'] === 'b') {
        return false;
    }

    return new Channel($item['channel_id'], $item['channel_type'], new Carbon($item['last_registration']));
}); // Returns ChannelCollection
```

You can utilize it with you deserializer component also, for example:
```php
use Ramasdev\SimpleCollectionTransformer\CollectionTransformer;

$actualCollection = $this->collectionTransformer->transform($data, function ($item) {
    return $this->decoder->decodeArray((array) $item, Channel::class);
}); // Returns ChannelCollection
```

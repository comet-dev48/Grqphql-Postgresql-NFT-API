<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class Event
{
    public int $id;

    public string $url_event;

    public string $event_name;

    public string $image_link;

    public bool $verified;

    public string $start_date;

    public string $end_date;

    public string $website_link;

    public string $twitter_link;

    public string $discord_link;

    public string $marketplace;

    public string $marketplace_link;

    public string $blockchain;

    public string $blockchain_link;

    public string $description;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }

    public function populate($source){
        //$short_description = substr($source["description"],0 ,30);
        return new Event([
            'id' => $source["id"],
            'url_event' => $source["url_event"],
            'event_name' => $source["event_name"],
            'image_link' => $source["image_link"],
            'start_date' => $source["start_date"],
            'end_date' => $source["end_date"],
            'website_link' => $source["website_link"],
            'twitter_link' => $source["twitter_link"],
            'discord_link'=> $source["discord_link"],
            'marketplace' => $source["marketplace"],
            'marketplace_link' => $source["marketplace_link"],
            'blockchain' => $source["blockchain"],
            'blockchain_link' => $source["blockchain_link"],
            'description' => $source["description"]
        ]);
    }
}
<?php

namespace App\Story;

use App\Entity\Item;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

use function Zenstruck\Foundry\Persistence\persist;

#[AsFixture(name: 'items', groups: ['dev'])]
final class ItemStory extends Story
{
    public function build(): void
    {
        $this->addState('almanac', persist(Item::class, [
            'name' => 'Almanac',
            'image' => 'images/almanac.png',
            'colorClass' => 'bg-[#2C4A52]',
            'foundAt' => '1955, Hill Valley, CA, Earth',
            'description' => 'Biff Tannen has made a rather large fortune from the information in this almanac. If this is yours, please claim it as soon as possible, and take care where you park your DeLorean.',
        ]), pool: 'items');

        $this->addState('book', persist(Item::class, [
            'name' => 'Book',
            'image' => 'images/book.png',
            'colorClass' => 'bg-[#5B3256]',
            'foundAt' => '2268, Sigma Iotia II',
            'description' => 'The cultural development of the inhabitants of Sigma Iotia has been severely altered by the contents of this book, leading to a boom in fedora manufacturing. Everyone sounds like they’re on the set of The Godfather…',
        ]), pool: 'items');

        $this->addState('phone', persist(Item::class, [
            'name' => 'Smart Phone',
            'image' => 'images/phone.png',
            'colorClass' => 'bg-[#8B4A3C]',
            'foundAt' => '17,000 BCE, Nouvelle-Aquitaine, Earth',
            'description' => 'Earth’s earliest inhabitants are in an uproar after discovering this smartphone in a cave in the Nouvelle-Aquitaine region of France. When it ran out of battery, the locals built a large stone circle where it is now displayed.',
        ]), pool: 'items');

        $this->addState('cdplayer', persist(Item::class, [
            'name' => 'Portable CD Player',
            'image' => 'images/cdplayer.png',
            'colorClass' => 'bg-[#A67C3D]',
            'foundAt' => '2387, Mare Tranquillitatis, Luna',
            'description' => 'Crews working to terraform the moon have discovered this portable CD player tucked away in a crater. It contains a copy of Now That’s What I Call Music 5, and even though it has “Skip Protection”, it definitely skips…',
        ]), pool: 'items');

        $this->addState('phaser', persist(Item::class, [
            'name' => 'Phaser',
            'image' => 'images/phaser.png',
            'colorClass' => 'bg-[#35533F]',
            'foundAt' => '1986, Alameda, CA, Earth',
            'description' => 'This phaser was collected by officials aboard the USS Enterprise in Alameda, CA after an intruder tossed it during a chase. This intruder later disappeared from a nearby hospital under mysterious circumstances.',
        ]), pool: 'items');

        $this->addState('newspaper', persist(Item::class, [
            'name' => 'Newspaper',
            'image' => 'images/newspaper.png',
            'colorClass' => 'bg-[#2E3F5C]',
            'foundAt' => '2016, Chicago, IL, Earth',
            'description' => 'A copy of the Chicago Tribune, dated September 3rd 2016, was confiscated from a gentleman a few days prior to the game as he was attempting to place a suspiciously large bet in favor of the Cubs.',
        ]), pool: 'items');
    }
}

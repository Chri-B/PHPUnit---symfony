<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testItCanGetAndSetData(): void
    {
        $dino = new Dinosaur(
            name: "Dinos",
            genus: "Triceratops",
            length: 15,
            enclosure: "Paddock A",
        );


        self::assertSame("Dinos", $dino->getName());
        self::assertSame("Triceratops", $dino->getGenus());
        self::assertSame(15, $dino->getLength());
        self::assertSame("Paddock A", $dino->getEnclosure());
    }

    /** @dataProvider sizeDescriptionProvider */
    public function testDinoHasCorrectSizeDescriptionGivenLength(int $length, string $expectedSize): void
    {
        $dino = new Dinosaur(name: 'Big Eaty', length:$length);

        self::assertSame($expectedSize, $dino->getSizeDescription());
    }

    // public function testDinosaurBetween5And9MetersIsMedium(): void
    // {
    //     $dino = new Dinosaur(name: 'Big Eaty', length:5);

    //     self::assertSame('Medium', $dino->getSizeDescription(), 'This is supposed to be a medium dinosaur');
    // }

    // public function testDinosaurUnder5MetersIsSmall(): void
    // {
    //     $dino = new Dinosaur(name: 'Big Eaty', length:1);

    //     self::assertSame('Small', $dino->getSizeDescription(), 'This is supposed to be a small dinosaur');
    // }

    public function sizeDescriptionProvider(): \Generator
    {
        yield '10 meters Large Dino' => [10, 'Large'];
        yield '5 meters Medium Dino' => [5, 'Medium'];
        yield '1 meters Small Dino' => [1, 'Small'];
    }

    public function testIsAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur(name: 'Dennis');

        self::assertTrue($dino->isAcceptingVisitors());
    }

    /**
     * @dataProvider healthStatusProvider
     */
    public function testIsAcceptingVisitorsBasedOnHealthStatus(HealthStatus $healthStatus, bool $expectedVisitorStatus): void
    {
        $dino = new Dinosaur(name: 'Bumpy');
        $dino->setHealth($healthStatus);

        self::assertSame($expectedVisitorStatus, $dino->isAcceptingVisitors());
    }

    public function healthStatusProvider(): \Generator
    {
        yield 'Sick dino is not accepting visitors' => [HealthStatus::SICK, false];
        yield 'Hungry dino is accepting visitors' => [HealthStatus::HUNGRY, true];
        yield 'Healthy dino is accepting visitors' => [HealthStatus::HEALTHY, true];
    }
}
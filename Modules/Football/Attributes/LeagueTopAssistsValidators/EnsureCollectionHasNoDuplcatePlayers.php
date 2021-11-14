<?php

declare(strict_types=1);

namespace Module\Football\Attributes\LeagueTopAssistsValidators;

use Attribute;
use Module\Football\Collections\LeagueTopAssistsCollection;
use App\Contracts\AfterMakingValidatorInterface;
use Module\Football\DTO\Player;

#[Attribute(Attribute::TARGET_CLASS)]
final class EnsureCollectionHasNoDuplcatePlayers implements AfterMakingValidatorInterface
{
    /**
     * @param LeagueTopAssistsCollection $collection
     */
    public function validate(Object $collection): void
    {
        $collection
            ->players()
            ->toLaravelCollection()
            ->duplicates(fn (Player $player): int => $player->getId()->toInt())
            ->whenNotEmpty(fn () => throw new \LogicException('Duplicate players found in top assists collection'));
    }
}
<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\ValueObjects\Url;
use App\ValueObjects\Country;
use App\DTO\DataTransferObject;
use Module\Football\ValueObjects\CoachId;
use Module\Football\ValueObjects\CoachAge;

final class Coach extends DataTransferObject
{
    protected Country $nationality;
    protected CoachAge $age;
    protected Team $team;
    protected bool $hasCurrentTeam;
    protected CoachId $id;
    protected Url $photo_url;
    protected string $name;

    public function id(): CoachId
    {
        return $this->id;
    }

    public function age(): CoachAge
    {
        return $this->age;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function photoUrl(): Url
    {
        return $this->photo_url;
    }

    public function hasCurrentTeam(): bool
    {
        return $this->hasCurrentTeam;
    }

    /**
     * Check if coach has current team with the hasCurrentTeam method
     * before calling this method to avoid initialization Exception
     */
    public function currentTeam(): Team
    {
        return $this->team;
    }

    public function nationality(): Country
    {
        return $this->nationality;
    }
}

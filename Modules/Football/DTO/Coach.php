<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\ValueObjects\Country;
use App\DTO\DataTransferObject;
use Module\Football\Media\ImageUrl;
use Module\Football\ValueObjects\CoachId;
use Module\Football\ValueObjects\CoachAge;
use App\ValueObjects\NonEmptyString as CoachName;

final class Coach extends DataTransferObject
{
    protected Country $nationality;
    protected CoachAge $age;
    protected Team $team;
    protected bool $hasCurrentTeam;
    protected CoachId $id;
    protected ImageUrl $photo_url;
    protected CoachName $name;

    public function id(): CoachId
    {
        return $this->id;
    }

    public function age(): CoachAge
    {
        return $this->age;
    }

    public function name(): CoachName
    {
        return $this->name;
    }

    public function photoUrl(): ImageUrl
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

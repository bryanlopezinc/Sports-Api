<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football\Cache;

use Module\User\ValueObjects\UserId;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FixturePredictionsResult;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface as BaseRepository;

final class FixturePredictionsCacheRepository implements BaseRepository
{
    private const KEY = 'Fixture:user:predictions';

    /**
     * Array of fixtures ids that have been predicted by different users.
     * Each value in the array is represented as
     * [
     *      fixtureId (int) => [
     *          userId (int) => hasPredictedFixture (bool)
     *          userId (int) => hasPredictedFixture (bool)
     *          userId (int) => hasPredictedFixture (bool)
     *          ...etc
     *      ],
     *      ...etc
     * ]
     * @var array<int, array<int, bool>>
     */
    private array $predictions;

    public function __construct(private BaseRepository $baseRepository, private Repository $repository)
    {
        $this->predictions = $this->repository->get(self::KEY, []);
    }

    public function userHasPredictedFixture(UserId $userId, FixtureId $fixtureId): bool
    {
        $value = $this->predictions[$fixtureId->toInt()][$userId->toInt()] ?? null;

        if ($value !== null) {
            return $value;
        }

        $hasPredicted = $this->baseRepository->userHasPredictedFixture($userId, $fixtureId);

        $this->predictions[$fixtureId->toInt()][$userId->toInt()] = $hasPredicted;

        $this->save();

        return $hasPredicted;
    }

    public function forget(UserId $userId, FixtureId $fixtureId): void
    {
        unset($this->predictions[$fixtureId->toInt()][$userId->toInt()]);

        $this->save();
    }

    private function save(): void
    {
        $this->repository->put(self::KEY, $this->predictions, now()->addDay());
    }

    public function fetchPredictionsResultFor(FixtureId $fixtureId): FixturePredictionsResult
    {
        return $this->baseRepository->fetchPredictionsResultFor($fixtureId);
    }
}

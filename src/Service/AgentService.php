<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\AgentDto;
use App\Entity\Agent;
use App\Entity\User;
use App\Exception\Agent\AgentResourceNotFoundException;
use App\Exception\Agent\CantRemoveUniqueAgentFromUserException;
use App\Exception\ValidatorException;
use App\Repository\Interface\AgentRepositoryInterface;
use App\Repository\Interface\OpportunityRepositoryInterface;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\FileServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class AgentService extends AbstractEntityService implements AgentServiceInterface
{
    private const string DIR_AGENT_PROFILE = 'app.dir.agent.profile';

    public function __construct(
        private AgentRepositoryInterface $repository,
        private FileServiceInterface $fileService,
        private OpportunityRepositoryInterface $opportunityRepository,
        private ParameterBagInterface $parameterBag,
        private Security $security,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct(
            $this->security,
            $this->serializer,
            $this->validator,
            $this->entityManager,
            Agent::class,
            $this->fileService,
            $this->parameterBag,
            self::DIR_AGENT_PROFILE,
        );
    }

    public function count(?User $user = null): int
    {
        $criteria = $this->getDefaultParams();

        if ($user) {
            $criteria['user'] = $user;
        }

        return $this->repository->count($criteria);
    }

    public function create(array $agent): Agent
    {
        $agent = $this->validateInput($agent, AgentDto::class, AgentDto::CREATE);

        $agentObj = $this->serializer->denormalize($agent, Agent::class);

        return $this->repository->save($agentObj);
    }

    public function createFromUser(array $user, ?array $extraFields = null): Agent
    {
        $agent = $this->organizeDefaultAgentData($user);
        $agent['extraFields'] = $extraFields;
        $agent['main'] = true;
        $agent = $this->validateInput($agent, AgentDto::class, AgentDto::CREATE);

        $agentObj = $this->serializer->denormalize($agent, Agent::class);

        return $this->repository->save($agentObj);
    }

    public function findBy(array $params = [], int $limit = 50): array
    {
        $userParams = $this->getDefaultParams();

        if (null !== $this->security->getUser()) {
            $user = $this->security->getUser();
            $userParams['user'] = $user;
        }

        return $this->repository->findBy(
            [...$params, ...$userParams],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    public function findOneBy(array $params): ?Agent
    {
        return $this->repository->findOneBy(
            [...$params, ...$this->getDefaultParams()]
        );
    }

    public function get(Uuid $id): Agent
    {
        $agent = $this->repository->findOneBy([
            ...['id' => $id],
            ...$this->getDefaultParams(),
        ]);

        if (null === $agent) {
            throw new AgentResourceNotFoundException();
        }

        return $agent;
    }

    public function getMainAgentByEmail(string $email): ?Agent
    {
        return $this->repository->getMainAgentByEmail($email);
    }

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getDefaultParams()],
            ['createdAt' => $order],
            $limit
        );
    }

    private function organizeDefaultAgentData(array $user): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => "{$user['firstname']} {$user['lastname']}",
            'shortBio' => 'Agente criado automaticamente',
            'longBio' => 'Este agente foi criado automaticamente pelo sistema',
            'culture' => false,
            'user' => $user['id'],
        ];
    }

    public function remove(Uuid $id): void
    {
        $agent = $this->repository->findOneBy([
            ...['id' => $id],
            ...$this->getDefaultParams(),
        ]);

        $agents = $this->security->getUser()->getAgents()->count();

        if (null === $agent) {
            throw new AgentResourceNotFoundException();
        }

        if (1 === $agents) {
            throw new CantRemoveUniqueAgentFromUserException();
        }

        foreach ($agent->getOpportunities() as $opportunity) {
            $opportunity->setDeletedAt(new DateTime());
            $this->opportunityRepository->save($opportunity);
        }

        $agent->setDeletedAt(new DateTime());

        if ($agent->getImage()) {
            $this->fileService->deleteFileByUrl($agent->getImage());
        }

        $this->repository->save($agent);
    }

    public function update(Uuid $id, array $agent): Agent
    {
        $agentObj = $this->get($id);

        $agent = $this->validateInput($agent, AgentDto::class, AgentDto::UPDATE);

        $agentObj = $this->serializer->denormalize($agent, Agent::class, context: [
            'object_to_populate' => $agentObj,
        ]);

        $agentObj->setUpdatedAt(new DateTime());

        return $this->repository->save($agentObj);
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Agent
    {
        $agent = $this->get($id);

        $agentDto = new AgentDto();
        $agentDto->image = $uploadedFile;

        $violations = $this->validator->validate($agentDto, groups: [AgentDto::UPDATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        if ($agent->getImage()) {
            $this->fileService->deleteFileByUrl($agent->getImage());
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get(self::DIR_AGENT_PROFILE),
            $uploadedFile
        );

        $relativePath = '/uploads'.$this->parameterBag->get(self::DIR_AGENT_PROFILE).'/'.$uploadedImage->getFilename();
        $agent->setImage($relativePath);

        $agent->setUpdatedAt(new DateTime());

        $this->repository->save($agent);

        return $agent;
    }
}

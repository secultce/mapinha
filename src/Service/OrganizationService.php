<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\OrganizationDto;
use App\Entity\Agent;
use App\Entity\Organization;
use App\Enum\OrganizationTypeEnum;
use App\Exception\Organization\OrganizationResourceNotFoundException;
use App\Repository\Interface\OrganizationRepositoryInterface;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\OrganizationServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class OrganizationService extends AbstractEntityService implements OrganizationServiceInterface
{
    private const string DIR_ORGANIZATION_PROFILE = 'app.dir.organization.profile';
    private const string DIR_ORGANIZATION_COVER = 'app.dir.organization.cover';

    public function __construct(
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private OrganizationRepositoryInterface $repository,
        private Security $security,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private AgentServiceInterface $agentService,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
    ) {
        parent::__construct(
            $this->security,
            $this->serializer,
            $this->validator,
            $this->entityManager,
            Organization::class,
            $this->fileService,
            $this->parameterBag,
            self::DIR_ORGANIZATION_PROFILE,
        );
    }

    public function count(?Agent $createdBy = null): int
    {
        $criteria = $this->getDefaultParams();

        if ($createdBy) {
            $criteria['createdBy'] = $createdBy;
        }

        return $this->repository->count($criteria);
    }

    public function create(array $organization): Organization
    {
        $organization = $this->validateInput($organization, OrganizationDto::class, OrganizationDto::CREATE);

        $organizationObj = $this->serializer->denormalize($organization, Organization::class);

        return $this->repository->save($organizationObj);
    }

    public function findBy(array $params = [], int $limit = 50): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getUserParams()],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    public function findOneBy(array $params): ?Organization
    {
        return $this->repository->findOneBy(
            [...$params, ...$this->getDefaultParams()]
        );
    }

    public function get(Uuid $id): Organization
    {
        $organization = $this->repository->findOneBy([
            ...['id' => $id],
            ...$this->getDefaultParams(),
        ]);

        if (null === $organization) {
            throw new OrganizationResourceNotFoundException();
        }

        return $organization;
    }

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getDefaultParams()],
            ['createdAt' => $order],
            $limit
        );
    }

    public function remove(Uuid $id): void
    {
        $organization = $this->findOneBy(
            [...['id' => $id], ...$this->getUserParams()]
        );

        if (null === $organization) {
            throw new OrganizationResourceNotFoundException();
        }

        $organization->setDeletedAt(new DateTime());

        if ($organization->getImage()) {
            $this->fileService->deleteFileByUrl($organization->getImage());
        }

        $this->repository->save($organization);
    }

    public function update(Uuid $identifier, array $organization): Organization
    {
        $organizationFromDB = $this->get($identifier);

        $organizationDto = $this->validateInput($organization, OrganizationDto::class, OrganizationDto::UPDATE);

        $organizationObj = $this->serializer->denormalize($organizationDto, Organization::class, context: [
            'object_to_populate' => $organizationFromDB,
        ]);

        $organizationObj->setUpdatedAt(new DateTime());

        return $this->repository->save($organizationObj);
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Organization
    {
        return $this->processFileUpload(
            id: $id,
            uploadedFile: $uploadedFile,
            dtoClass: OrganizationDto::class,
            dtoProperty: 'image',
            directoryParam: self::DIR_ORGANIZATION_PROFILE,
            getterMethod: 'getImage',
            setterMethod: 'setImage',
            validationGroups: [OrganizationDto::UPDATE]
        );
    }

    public function updateCoverImage(Uuid $id, UploadedFile $uploadedFile): Organization
    {
        return $this->processFileUpload(
            id: $id,
            uploadedFile: $uploadedFile,
            dtoClass: OrganizationDto::class,
            dtoProperty: 'coverImage',
            directoryParam: self::DIR_ORGANIZATION_COVER,
            getterMethod: 'getCoverImage',
            setterMethod: 'setCoverImage',
            validationGroups: [OrganizationDto::UPDATE]
        );
    }

    public function removeAgent(Uuid $agentId, Uuid $organizationId): void
    {
        $organization = $this->get($organizationId);
        $agent = $this->agentService->get($agentId);

        $organization->removeAgent($agent);
        $this->repository->save($organization);
    }

    public function getCsvHeaders(?string $type): array
    {
        if ($type === OrganizationTypeEnum::MUNICIPIO->value) {
            return [
                'ID',
                'Código da Cidade',
                'Nome',
                'Descrição',
                'Região',
                'Estado',
                'Status do Termo de Adesão',
                'Termo de Adesão',
                'Versão do Termo de Adesão',
                'Email',
                'Telefone',
                'Site',
                'Experiência Habitacional',
                'Possui PLHIS',
                'Criado Por',
                'Criado Em',
            ];
        }

        return [
            'Nome da Organização',
            'Tipo',
            'Equadramento',
            'CNPJ',
            'E-mail',
            'Site',
            'Telefone',
            'Responsável',
            'Criado em',
            'Criado por',
        ];
    }

    public function getCsvRow(object $entity, ?string $type): array
    {
        if (!$entity instanceof Organization) {
            throw new InvalidArgumentException('Expected Organization entity.');
        }

        if ($type === OrganizationTypeEnum::MUNICIPIO->value) {
            $extraFields = $entity->getExtraFields();

            $formExists = isset($extraFields['form']);
            $documentLink = $formExists
                ? $this->urlGenerator->generate(
                    'regmel_municipality_document_file',
                    ['id' => $entity->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
                : '';
            $status = match ($extraFields['term_status'] ?? '') {
                'awaiting' => $this->translator->trans('awaiting'),
                'accepted' => $this->translator->trans('accepted'),
                'rejected' => $this->translator->trans('rejected'),
                default => $this->translator->trans('unknown'),
            };

            return [
                $entity->getId(),
                $extraFields['cityCode'] ?? '',
                $entity->getName(),
                $entity->getDescription(),
                $extraFields['region'] ?? '',
                $extraFields['state'] ?? '',
                $status ?? '',
                $documentLink,
                $extraFields['term_version'] ?? '',
                $extraFields['email'] ?? '',
                $extraFields['telefone'] ?? '',
                $extraFields['site'] ?? '',
                isset($extraFields['hasHousingExperience'])
                    ? ($extraFields['hasHousingExperience'] ? 'Sim' : 'Não')
                    : '',
                isset($extraFields['hasPlhis'])
                    ? ($extraFields['hasPlhis'] ? 'Sim' : 'Não')
                    : '',
                $entity->getCreatedBy() ? $entity->getCreatedBy()->getName() : '-',
                $entity->getCreatedAt()->format('d/m/Y H:i:s'),
            ];
        }

        return [
            $entity->getName(),
            $entity->getExtraFields()['tipo'] ?? '',
            $entity->getExtraFields()['framework'] ?? '',
            $entity->getExtraFields()['cnpj'] ?? '',
            $entity->getExtraFields()['email'] ?? '',
            $entity->getExtraFields()['site'] ?? '',
            $entity->getExtraFields()['telefone'] ?? '',
            $entity->getOwner()->getName() ?? '',
            $entity->getCreatedAt()->format('d/m/Y H:i:s'),
            $entity->getCreatedBy() ? $entity->getCreatedBy()->getName() : '-',
        ];
    }
}

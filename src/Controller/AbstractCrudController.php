<?php

declare(strict_types = 1);

namespace User\Controller;

use Doctrine\Common\Collections\Criteria;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\OffsetRepresentation;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use User\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use User\Component\Security\Core\Authorization\Voter\CrudVoter;
use User\Service\CrudServiceInterface;

/**
 * Abstract controller class that helps to create a simple CRUD.
 *
 * @package User\Controller
 * @author  Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
abstract class AbstractCrudController
{
    const ACTION_CREATE = 'create';
    const ACTION_GET = 'get';
    const ACTION_GET_LIST = 'getList';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    /**
     * @var string
     */
    protected $action;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var CrudServiceInterface
     */
    private $service;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AbstractCrudController constructor.
     *
     * @param SerializerInterface  $serializer
     * @param ValidatorInterface   $validator
     * @param CrudServiceInterface $service
     * @param LoggerInterface      $logger
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CrudServiceInterface $service,
        LoggerInterface $logger
    ) {
        $this->service = $service;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * Creates a resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $this->action = self::ACTION_CREATE;

        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $this->getDtoType(),
            'json',
            $this->getDeserializationContext()
        );

        $errors = $this->validator->validate($dto, null, $this->getValidationGroups());
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException($errors);
        }

        $this->checkAccess([CrudVoter::CREATE]);

        $dto = $this->service->create($dto);

        return new JsonResponse(
            $this->serializer->serialize(
                $dto,
                'json',
                $this->getSerializationContext()
            ),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * Get a resource by its ID.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $this->action = self::ACTION_GET;

        $this->beforeAction($this->action);

        $dto = $this->service->get(
            $request->attributes->get('id')
        );

        if (null === $dto) {
            throw new NotFoundHttpException();
        }

        $this->checkAccess([CrudVoter::GET], $dto);

        return new JsonResponse(
            $this->serializer->serialize(
                $dto,
                'json',
                $this->getSerializationContext()
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Get a list of resources.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $this->action = self::ACTION_GET_LIST;

        $this->beforeAction($this->action);

        $this->checkAccess([CrudVoter::GET_LIST]);

        $offset = $request->attributes->get('_offset');
        $limit = $request->attributes->get('_limit');

        $criteria = Criteria::create()
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $collection = $this->service->getList($criteria);

        $total = count($collection);

        $offsetRepresentation = new OffsetRepresentation(
            new CollectionRepresentation(
                $collection
            ),
            $request->attributes->get('_route'),
            [],
            $offset,
            $limit,
            $total
        );

        return new JsonResponse(
            $this->serializer->serialize(
                $offsetRepresentation,
                'json',
                $this->getSerializationContext()
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Update a resource by its ID.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $this->getDtoType(),
            'json',
            $this->getDeserializationContext()
        );

        $dto->setId(
            $request->attributes->get('id')
        );

        $errors = $this->validator->validate($dto, null, $this->getValidationGroups());
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException($errors);
        }

        $this->checkAccess([CrudVoter::UPDATE], $dto);

        $dto = $this->service->update($dto);

        return new JsonResponse(
            $this->serializer->serialize(
                $dto,
                'json',
                $this->getSerializationContext()
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Delete a resource by its id.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        // Create an empty object.
        $dto = $this->serializer->deserialize(
            '{}',
            $this->getDtoType(),
            'json',
            $this->getDeserializationContext()
        );

        $dto->setId(
            $request->attributes->get('id')
        );

        $this->checkAccess([CrudVoter::DELETE], $dto);

        $this->service->delete($dto);

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Called before each action.
     *
     * @param string $action
     */
    protected function beforeAction(string $action): void
    {
    }

    /**
     * @see AuthorizationCheckerInterface::isGranted()
     *
     * @param array       $attributes
     * @param object|null $object
     *
     * @throws AccessDeniedHttpException
     */
    protected function checkAccess(array $attributes, object $object = null): void
    {
    }

    /**
     * Returns DTO object type which is used for deserialization.
     *
     * @return string
     */
    abstract protected function getDtoType(): string;

    /**
     * @return DeserializationContext
     */
    protected function getDeserializationContext(): DeserializationContext
    {
        return new DeserializationContext();
    }

    /**
     * @return array
     */
    protected function getValidationGroups(): array
    {
        return [];
    }

    /**
     * @return SerializationContext
     */
    protected function getSerializationContext(): SerializationContext
    {
        return new SerializationContext();
    }
}
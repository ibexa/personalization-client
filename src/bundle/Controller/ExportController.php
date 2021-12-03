<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Controller;

use Ibexa\PersonalizationClient\Authentication\AuthenticatorInterface;
use Ibexa\PersonalizationClient\Exception\ExportInProgressException;
use Ibexa\PersonalizationClient\File\FileManagerInterface;
use Ibexa\PersonalizationClient\Helper\ExportProcessRunnerHelper;
use Ibexa\PersonalizationClient\Value\ExportRequest;
use Ibexa\Rest\Server\Controller;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ExportController extends Controller
{
    /** @var \Ibexa\PersonalizationClient\Authentication\AuthenticatorInterface */
    private $authenticator;

    /** @var \Ibexa\PersonalizationClient\File\FileManagerInterface */
    private $fileManager;

    /** @var \Ibexa\PersonalizationClient\Helper\ExportProcessRunnerHelper */
    private $exportProcessRunner;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(
        AuthenticatorInterface $authenticator,
        FileManagerInterface $fileManager,
        ExportProcessRunnerHelper $exportProcessRunner,
        LoggerInterface $logger
    ) {
        $this->authenticator = $authenticator;
        $this->fileManager = $fileManager;
        $this->exportProcessRunner = $exportProcessRunner;
        $this->logger = $logger;
    }

    /**
     * @throws \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException
     */
    public function downloadAction(string $filePath): Response
    {
        $response = new Response();

        if (!$this->authenticator->authenticateByFile($filePath) || $this->authenticator->authenticate()) {
            return $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $content = $this->fileManager->load($filePath);

        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filePath);
        $fileSize = filesize($this->fileManager->getDir() . $filePath);

        if (is_int($fileSize)) {
            $response->headers->set('Content-Length', (string)$fileSize);
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * @ParamConverter("export_request_converter")
     *
     * @throws \Ibexa\PersonalizationClient\Exception\ExportInProgressException
     */
    public function exportAction(ExportRequest $request): JsonResponse
    {
        $response = new JsonResponse();

        if (!$this->authenticator->authenticate()) {
            return $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        if ($this->fileManager->isLocked()) {
            $this->logger->warning('Export is running.');
            throw new ExportInProgressException('Export is running');
        }

        $this->exportProcessRunner->run($request->getExportRequestParameters());

        return $response->setData([sprintf(
            'Export started at %s',
            date('Y-m-d H:i:s')
        )]);
    }
}

class_alias(ExportController::class, 'EzSystems\EzRecommendationClientBundle\Controller\ExportController');

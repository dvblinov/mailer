<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 18.08.16
 * Time: 15:33
 */

namespace Mailer\Controller;

use Mailer\Service\AttachmentService;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;

class AttachmentController extends AbstractActionController
{
    public function downloadAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();
        $id = $this->params()->fromRoute('id', null);
        /** @var AttachmentService $attachmentService */
        $attachmentService = $this->getServiceLocator()->get('Mailer\Service\Attachment');
        $attachment = $attachmentService->getAttachment($id);
        if ($attachment) {
            $content = $attachmentService->getAttachmentContent($attachment);
            if ($content !== null) {
                $response->setContent($content);
                $headers = $response->getHeaders();
                $headers->clearHeaders()
                    ->addHeaderLine('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                    ->addHeaderLine('Pragma', 'public')
                    ->addHeaderLine('Content-Type', 'application/download; charset=UTF-8')
                    ->addHeaderLine('Accept-Ranges', 'bytes')
                    ->addHeaderLine('Content-Disposition',
                        'attachment; filename="' . rawurlencode($attachment->getFileName()) . '"; filename*=utf-8\'\'' . rawurlencode($attachment->getFileName()) . '')
                    ->addHeaderLine('Content-Transfer-Encoding: binary');
                return $response;
            }
        }
        $headers = $response->getHeaders();
        $headers->clearHeaders();
        $response->setStatusCode(Response::STATUS_CODE_404);
        return $response;
    }

    public function uploadAction()
    {
        $files = $this->params()->fromFiles();
        /** @var AttachmentService $attachmentService */
        $attachmentService = $this->getServiceLocator()->get('Mailer\Service\Attachment');
        $result = $attachmentService->uploadFile($files['attachment']);
        return new JsonModel($result);
    }
}
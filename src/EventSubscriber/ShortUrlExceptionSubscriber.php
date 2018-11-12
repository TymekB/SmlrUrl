<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.11.18
 * Time: 14:03
 */

namespace App\EventSubscriber;


use App\ShortUrl\Exception\ShortUrlDataNotFound;
use App\ShortUrl\Exception\ShortUrlIsNotValidException;
use App\ShortUrl\Exception\ShortUrlNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ShortUrlExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException']
            ]
        ];
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if($exception instanceof ShortUrlNotFoundException || $exception instanceof ShortUrlDataNotFound) {

            $response = new JsonResponse(['success' => false]);
            $response->setStatusCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

            $event->setResponse($response);
        }

        if($exception instanceof ShortUrlIsNotValidException) {

            $errorMessages = explode(",", $exception->getMessage());

            $response = new JsonResponse(['success' => false, 'error_messages' => $errorMessages]);
            $response->setStatusCode(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

            $event->setResponse($response);
        }

        return;
    }
}
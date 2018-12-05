<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 02.12.18
 * Time: 20:31
 */

namespace App\EventSubscriber;

use App\ApiToken\Exception\ApiTokenNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiTokenExceptionSubscriber implements EventSubscriberInterface
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
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException']
            ]
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function processException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        if($exception instanceof ApiTokenNotFoundException) {
            $notFoundException = new NotFoundHttpException($exception->getMessage());
            $event->setException($notFoundException);
        }
    }
}
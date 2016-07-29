<?php

namespace Acme\DemoBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CRUDController extends Controller
{

    public function validerAction()
    {
        $object = $this->admin->getSubject();

        $object->setValide(true);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($object);
        $em->flush();

        $this->addFlash('sonata_flash_success', 'Le questionnaire a été validé !');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function devaliderAction()
    {
        $object = $this->admin->getSubject();

        $object->setValide(false);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($object);
        $em->flush();

        $this->addFlash('sonata_flash_success', 'Le questionnaire a été dévalidé !');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function cloneAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        // Be careful, you may need to overload the __clone method of your object
        // to set its id to null !
        $clonedObject = clone $object;

        $clonedObject->setName($object->getName().' (Clone)');

        $this->admin->create($clonedObject);

        $this->addFlash('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));

        // if you have a filtered list and want to keep your filters after the redirect
        // return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

}

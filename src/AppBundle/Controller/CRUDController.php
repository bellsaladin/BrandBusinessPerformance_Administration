<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CRUDController extends Controller
{
    public function batchActionValider(ProxyQueryInterface $selectedModelQuery)
    {
      if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false)
      {
          throw new AccessDeniedException();
      }

      $request = $this->get('request');
      $modelManager = $this->admin->getModelManager();

      /*$target = $modelManager->find($this->admin->getClass(), $request->get('targetId'));

      if( $target === null){
          $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_info', "Veuillez selectionner des éléments avant d'effectuer l'opération");

          return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
      }*/

      $selectedModels = $selectedModelQuery->execute();

      // do the work here

      try {
          foreach ($selectedModels as $model){
              $model->setValide(true);
          }
          $modelManager->update($model);
      } catch (\Exception $e) {
          $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_error', $e->getMessage());

          return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
      }

      $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_success', "Les elements choisis ont été validés !");

      return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
   }

   public function batchActionDevalider(ProxyQueryInterface $selectedModelQuery)
   {
     if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false)
     {
         throw new AccessDeniedException();
     }

     $request = $this->get('request');
     $modelManager = $this->admin->getModelManager();

     /*$target = $modelManager->find($this->admin->getClass(), $request->get('targetId'));

     if( $target === null){
         $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_info', "Veuillez selectionner des éléments avant d'effectuer l'opération");

         return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
     }*/

     $selectedModels = $selectedModelQuery->execute();

     // do the work here

     try {
         foreach ($selectedModels as $model){
             $model->setValide(false);
         }
         $modelManager->update($model);
     } catch (\Exception $e) {
         $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_error', $e->getMessage());

         return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
     }

     $this->getRequest()->getSession()->getFlashBag()->add('sonata_flash_success', "Les elements choisis ont été dévalidés !");

     return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
  }
}

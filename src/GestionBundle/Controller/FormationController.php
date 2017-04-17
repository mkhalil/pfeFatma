<?php

namespace GestionBundle\Controller;

use GestionBundle\Entity\Employee;
use GestionBundle\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Formation controller.
 *
 * @Route("formation")
 */
class FormationController extends Controller
{
    /**
     * Lists all formation entities.
     *
     * @Route("/", name="formation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $formations = $em->getRepository('GestionBundle:Formation')->findAll();

     if($formations){

           foreach($formations as $formation){


           if ($formation-> getDateDebut()->format('Y-m-d') < date('Y-m-d') && $formation->getDateFin()->format('Y-m-d') > date('Y-m-d'))
                    $formation->setEtat("En cours");

                else if ($formation-> getDateDebut()->format('Y-m-d') > date('Y-m-d'))
                    $formation->setEtat("Programmée");
                else
                    $formation->setEtat("Terminée");
                //$this->debug_to_console($formation->getDateDebut()->format('Y-m-d'));
              }
         $em->persist($formation);
         $em->flush();
        }


           return $this->render('formation/index.html.twig', array(
            'formations' => $formations,
        ));
    }

    /**
     * Creates a new formation entity.
     *
     * @Route("/new", name="formation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $formation = new Formation();

        $em = $this->getDoctrine()->getManager();

        $employees=$em->getRepository('GestionBundle:Employee')->findAll();
        

        $form = $this->createForm('GestionBundle\Form\FormationType', $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formation->setEtat("en attente");
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush($formation);

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new.html.twig', array(
            'formation' => $formation,
            'employees'=>$employees,
            'form' => $form->createView(),

        ));
    }

    /**
     * Creates a new formation entity.
     *
     * @Route("/new1", name="formation_new1")
     * @Method({"GET", "POST"})
     */
    public function new1Action(Request $request)
    {
        $formation = new Formation();
        $form = $this->createForm('GestionBundle\Form\FormationType', $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id=$formation->getDemandeFormation();
            $em = $this->getDoctrine()->getManager();
            $demandeformation=$em->getRepository('GestionBundle:Demande_Formation')->findOneBy(array('id'=>$id))->SetEtat("accepter");
            $em->persist($demandeformation);
            $em->flush($demandeformation);

            $em->persist($formation);
            $em->flush($formation);

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new1.html.twig', array(
            'formation' => $formation,
            'form' => $form->createView(),
        ));
    }
    




    /**
     * Finds and displays a formation entity.
     *
     * @Route("show/{id}", name="formation_show")
     * @Method("GET")
     */
    public function showAction(Formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);

        return $this->render('formation/show.html.twig', array(
            'formation' => $formation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing formation entity.
     *
     * @Route("/{id}/edit", name="formation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);
        $editForm = $this->createForm('GestionBundle\Form\FormationType', $formation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/edit.html.twig', array(
            'formation' => $formation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a formation entity.
     *
     * @Route("/{id}", name="formation_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $demandeFormation = $em->getRepository('GestionBundle:Formation')->find($id);

        $em->remove($demandeFormation);
        $em->flush($demandeFormation);
        $this->addFlash(
            'deletesuccess',
            ', la formation a été supprimé!'
        );
        return $this->redirectToRoute('formation_index');
    }

    /**
     * Creates a form to delete a formation entity.
     *
     * @param Formation $formation The formation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Formation $formation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formation_delete', array('id' => $formation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

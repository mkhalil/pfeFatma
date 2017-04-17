<?php

namespace GestionBundle\Controller;

use GestionBundle\Entity\Qualification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Qualification controller.
 *
 * @Route("qualification")
 */
class QualificationController extends Controller
{
    /**
     * Lists all qualification entities.
     *
     * @Route("/", name="qualification_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qualifications = $em->getRepository('GestionBundle:Qualification')->findAll();

        return $this->render('qualification/index.html.twig', array(
            'qualifications' => $qualifications,
        ));
    }

    /**
     * Creates a new qualification entity.
     *
     * @Route("/new", name="qualification_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qualification = new Qualification();
        $form = $this->createForm('GestionBundle\Form\QualificationType', $qualification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qualification);
            $em->flush($qualification);

            return $this->redirectToRoute('qualification_index');
        }

        return $this->render('qualification/new.html.twig', array(
            'qualification' => $qualification,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qualification entity.
     *
     * @Route("show/{id}", name="qualification_show")
     * @Method("GET")
     */
    public function showAction(Qualification $qualification)
    {
        $deleteForm = $this->createDeleteForm($qualification);

        return $this->render('qualification/show.html.twig', array(
            'qualification' => $qualification,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qualification entity.
     *
     * @Route("/{id}/edit", name="qualification_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Qualification $qualification)
    {
        $deleteForm = $this->createDeleteForm($qualification);
        $editForm = $this->createForm('GestionBundle\Form\QualificationType', $qualification);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('qualification_index');
        }

        return $this->render('qualification/edit.html.twig', array(
            'qualification' => $qualification,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qualification entity.
     *
     * @Route("/{id}", name="qualification_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qualification = $em->getRepository('GestionBundle:Qualification')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($qualification);
        $em->flush($qualification);
        $this->addFlash(
            'deletesuccess',
            ', la formation a été supprimé!'
        );

        return $this->redirectToRoute('qualification_index');
    }
    
    /**
     * Creates a form to delete a qualification entity.
     *
     * @param Qualification $qualification The qualification entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Qualification $qualification)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('qualification_delete', array('id' => $qualification->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index($entity_name='User', Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository('App\\Entity\\' . $entity_name);
        $rows = $repository->findAll();
        //$model =
        $form_config = $this->getParameter('form');
        //$model = $model->get($entity_name);
        //dd($rows);
        $form = $$this->createForm(CollectionType::class);
        foreach()
        {

        }
        return $this->render('index/index.html.twig', [
            'rows' => $rows,
            //'model' => $model
        ]);
    }
   
    /**
     * @Route("/form/{entity_name}/{id}", name="form")
     */
    public function form($entity_name, $id=0, EntityManagerInterface $entityManager): Response
    {
        $entity_fullname = 'App\\Entity\\' . $entity_name;
        if($id){
            $row = $entityManager->getRepository($entity_fullname)->find($id);
        }else{
            $row = new $entity_fullname;
        }
        $form_config = $this->getParameter('form');
        $form = $this->createForm(FormType::class, $row);
        foreach ($form_config[$entity_name]['fields'] as $field_name => $field_config)
        {
            $form->add($field_name, $field_config['namespace'] . '\\' . $field_config['type'], $field_config['options']);
        }
        $form->add('Submit', SubmitType::class);

        if ($form->isSubmitted()) {
            $entityManager->persist($entity_fullname);
            $entityManager->flush();
        }
        return $this->render('form/form.html.twig', [
            'form' => $form->createView(),
            'form_config' => $form_config,
        ]);
    } 
}


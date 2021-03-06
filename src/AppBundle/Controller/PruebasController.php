<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Curso;
use AppBundle\Form\CursoType;
use Symfony\Component\Validator\Constraints as Assert;

class PruebasController extends Controller {

    /**
     * @Route("/indexpruebas", name="indexpruebas")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('@App/Pruebas/index.html.twig');
    }

    /**
     * @Route("/createCurso", name="createCurso")
     */
    public function createAction() {
        $curso = new Curso();
        $curso->setTitulo("Curso de Symfony Victor Robles");
        $curso->setDescripcion("Curso completo de Symfony 3");
        $curso->setPrecio(80);

        $em = $this->getDoctrine()->getManager();
        $em->persist($curso);
        $flush = $em->flush();

        if ($flush == null) {
            echo "Curso creado";
        } else {
            echo "Error al crear el curso";
        }

        die();
    }

    /**
     * @Route("/readCurso", name="readCurso")
     */
    public function readAction() {
        $em = $this->getDoctrine()->getManager();

        $cursos = $em->getRepository("AppBundle:Curso")->findAll();

        foreach ($cursos as $curso) {
            echo $curso->getId() . "<br/>";
            echo $curso->getTitulo() . "<br/>";
            echo $curso->getDescripcion() . "<br/>";
            echo $curso->getPrecio() . "<br/><hr/>";
        }

        die();
    }

    /**
     * @Route("/updateCurso/{id}/{titulo}/{descripcion}/{precio}", name="updateCurso")
     */
    public function updateAction($id, $titulo, $descripcion, $precio) {
        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository("AppBundle:Curso")->find($id);

        if (count($curso) > 0) {
            $curso->setTitulo($titulo);
            $curso->setDescripcion($descripcion);
            $curso->setPrecio($precio);

            $em->persist($curso);
            $flush = $em->flush();

            if ($flush == null) {
                echo "Curso actualizado";
            } else {
                echo "Error al actualizar el curso";
            }
        } else {
            echo "Cambie los datos 'id', 'titulo', 'descripcion' y 'precio' en la url";
        }

        die();
    }

    /**
     * @Route("/deleteCurso/{id}", name="deleteCurso")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository("AppBundle:Curso")->find($id);

        if (count($curso) > 0) {

            $em->remove($curso);
            $flush = $em->flush();

            if ($flush == null) {
                echo "Curso eliminado";
            } else {
                echo "Error al eliminar el curso";
            }
        } else {
            echo "Cambie el dato 'id', en la url para eliminar un curso";
        }

        die();
    }

    /**
     * @Route("/ConsultaSql", name="consultasql")
     */
    public function nativeSqlAction() {
        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();

        $query = "SELECT * FROM cursos";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $cursos = $stmt->fetchAll();

        foreach ($cursos as $curso) {
            echo $curso["id"] . "<br/>";
            echo $curso["titulo"] . "<br/>";
            echo $curso["descripcion"] . "<br/>";
            echo $curso["precio"] . "<br/><hr/>";
        }

        die();
    }

    /**
     * @Route("/consultadql", name="consultadql")
     */
    public function consultaDqlAction() {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT c FROM AppBundle:Curso c WHERE c.precio > :precio")
                ->setParameter('precio', 79);

        $cursos = $query->getResult();

        foreach ($cursos as $curso) {
            echo $curso->getId() . "<br/>";
            echo $curso->getTitulo() . "<br/>";
            echo $curso->getDescripcion() . "<br/>";
            echo $curso->getPrecio() . "<br/><hr/>";
        }

        die();
    }

    /**
     * @Route("/queryBuilder", name="queryBuilder")
     */
    public function queryBuilderAction() {
        $em = $this->getDoctrine()->getManager();

        $cursos_repo = $em->getRepository("AppBundle:Curso");

        $cursos = $cursos_repo->getCursos();

        foreach ($cursos as $curso) {
            echo $curso->getId() . "<br/>";
            echo $curso->getTitulo() . "<br/>";
            echo $curso->getDescripcion() . "<br/>";
            echo $curso->getPrecio() . "<br/><hr/>";
        }

        die();
    }

    /**
     * @Route("/formulario", name="formulario")
     */
    public function formAction(Request $request) {

        $curso = new Curso();
        $form = $this->createForm(CursoType::class, $curso);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $status = "Formulario válido";
            $data = array(
                "titulo" => $form->get("titulo")->getData(),
                "descripcion" => $form->get("descripcion")->getData(),
                "precio" => $form->get("precio")->getData(),
            );
        } else {
            $status = null;
            $data = null;
        }

        return $this->render('@App/Pruebas/from.html.twig', [
                    'form' => $form->createView(),
                    'status' => $status,
                    'data' => $data
        ]);
    }

    /**
     *  @Route("/validaremail/{email}", name="validaremail")
     */
    public function validarEmailAction($email) {
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = "Pasame un buen correo";

        $error = $this->get("validator")->validate(
            $email,
            $emailConstraint
        );

        if(count($error) == 0) {
            echo "Correo valido";
        } else {
            echo "Correo invalido " . $error[0]->getMessage();
        }        

        die();
    }

}

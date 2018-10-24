<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Curso;

class PruebasController extends Controller
{
    /**
     * @Route("/indexpruebas", name="indexpruebas")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/Pruebas/index.html.twig');
    }
    
    /**
     * @Route("/createCurso", name="createCurso")
     */
    public function createAction()
    {
        $curso = new Curso();
        $curso->setTitulo("Curso de Symfony Victor Robles");
        $curso->setDescripcion("Curso completo de Symfony 3");
        $curso->setPrecio(80);

        $em = $this->getDoctrine()->getManager();
        $em->persist($curso);
        $flush = $em->flush();

        if($flush == null) {
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

        foreach($cursos as $curso){
            echo $curso->getId(). "<br/>";
            echo $curso->getTitulo(). "<br/>";
            echo $curso->getDescripcion(). "<br/>";
            echo $curso->getPrecio(). "<br/><hr/>";
        }

        die();        
    }

    /**
     * @Route("/updateCurso/{id}/{titulo}/{descripcion}/{precio}", name="updateCurso")
     */
    public function updateAction($id, $titulo, $descripcion, $precio){
        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository("AppBundle:Curso")->find($id);

        if(count($curso) > 0){
            $curso->setTitulo($titulo);
            $curso->setDescripcion($descripcion);
            $curso->setPrecio($precio);

            $em->persist($curso);
            $flush = $em->flush();

            if($flush == null) {
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
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository("AppBundle:Curso")->find($id);

        if(count($curso) > 0){

            $em->remove($curso);
            $flush = $em->flush();
            
            if($flush == null) {
                echo "Curso eliminado";
            } else {
                echo "Error al eliminar el curso";
            }
        } else {
            echo "Cambie el dato 'id', en la url para eliminar un curso";
        }

        die();
    }
}
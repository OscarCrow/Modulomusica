<?php

namespace UltimosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Estrenos
 * 
 * Esta clase contiene las funciones para la consulta del API Web de Spotify
 * y las funciones que cargan las vistas para mostrar los datos consultados.
 * 
 * @package     UltimosBundle
 * @author      Oscar Ordoñez <crowbeyar@gmail.com>
 * @version     0.1.0
 */
class EstrenosController extends Controller {

    /**
     * Inicio
     * 
     * Se carga la vista de la lista consultada a través del API
     * 
     * @author Oscar Ordoñez <oscar@riusoftbrnad.com>
     * @return render twig
     */
    public function indexAction() {
        $datos = $this->listaCanciones();

        $this->get('logger')->info('ingreso');
        return $this->render('@Ultimos/Estrenos/index.html.twig', array(
                    'datos' => $datos
        ));
    }

    /**
     * Crear token
     * 
     * Generación del token para las solisitudes de información en el API
     * 
     * @author Oscar Ordoñez <oscar@riusoftbrnad.com>
     * @return string access token
     */
    public function crearToken() {
        /* Spotify Application Client ID y Secret Key */
        $client_id = '4ecf27cd4c9e43ea8eb026d55fb4b46f';
        $client_secret = '32f0373c38714e4a8eda878a54362952';

        /* Get Spotify Authorization Token */
        $conexion = curl_init();
        curl_setopt($conexion, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conexion, CURLOPT_POST, 1);
        curl_setopt($conexion, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret)));

        $result = curl_exec($conexion);
        $json = json_decode($result, true);

        return $json['access_token'];
    }

    /**
     * Lista de canciones
     * 
     * Se realiza la consulta para la lista de canciones con el token generado
     * a través de la API para obtener los datos.
     * 
     * @author Oscar Ordoñez <oscar@riusoftbrnad.com>
     * @return array
     */
    public function listaCanciones() {
        $url = "https://api.spotify.com/v1/browse/new-releases?country=SE";
        $token = $this->crearToken();
        $conexion = curl_init();

        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_HTTPGET, TRUE);
        curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Authorization: "Bearer ' . $token, "Accept: application/json; charset=utf-8", "Content-Type: application/json; charset=utf-8"));
        curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);

        $respuesta = curl_exec($conexion);
        $json = json_decode($respuesta, true);

        curl_close($conexion);

        return $json;
    }

    /**
     * Artista
     * 
     * Se realiza la consulta de la información con el parámetro $id, para 
     * mostrar la vista del artista.
     * 
     * @author Oscar Ordoñez <oscar@riusoftbrnad.com>
     * @param type $id
     * @return type
     */
    public function artistaAction($id) {
        $url = "https://api.spotify.com/v1/artists/" . $id;
        $token = $this->crearToken();
        $conexion = curl_init();

        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_HTTPGET, TRUE);
        curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Authorization: "Bearer ' . $token, "Accept: application/json; charset=utf-8", "Content-Type: application/json; charset=utf-8"));
        curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);

        $respuesta = curl_exec($conexion);
        $datos = json_decode($respuesta, true);

        curl_close($conexion);

        $albumes = $this->albumesArtista($id);

        return $this->render('@Ultimos/Estrenos/artista.html.twig', array(
                    'datos' => $datos,
                    'albumes' => $albumes
        ));
    }

    /**
     * Albumes Artista
     * 
     * Se realiza la consulta al API, con el parámetro $id para obtener la información 
     * de los albumes de un artista especifico. 
     * 
     * @author Oscar Ordoñez <oscar@riusoftbrnad.com>
     * @param type $id
     * @return render twig
     */
    public function albumesArtista($id) {

        $url = "https://api.spotify.com/v1/artists/" . $id . "/albums";
        $token = $this->crearToken();
        $conexion = curl_init();

        curl_setopt($conexion, CURLOPT_URL, $url);
        curl_setopt($conexion, CURLOPT_HTTPGET, TRUE);
        curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Authorization: "Bearer ' . $token, "Accept: application/json; charset=utf-8", "Content-Type: application/json; charset=utf-8"));
        curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);

        $respuesta = curl_exec($conexion);
        $json = json_decode($respuesta, true);

        curl_close($conexion);

        return $json;
    }

}

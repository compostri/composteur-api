<?php


namespace App\Controller;


use App\Entity\Composter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

class ComposterController extends AbstractController
{

    private $urlHelper;

    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @Route("/composters.geojson", name="composters-geojson")
     * @param Request $request
     * @return Response
     */
    public function getCompostersGeojson(Request $request): Response
    {
        $composters =  $this->getDoctrine()
            ->getRepository(Composter::class)
            ->findAllForFrontMap();

        // On prépare un GeoJSON de centre formater le l'affichage sur la carte
        $features = [];
        /** @var Composter $c */
        foreach ($composters as $c) {
            $features[] = [
                'type'  => 'Feature',
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => [$c->getLng(), $c->getLat()]
                ),
                'properties' => [
                    'commune'       => $c->getCommune() ? $c->getCommune()->getId() : null,
                    'communeName'   => $c->getCommune() ? $c->getCommune()->getName() : null,
                    'categorie'     => $c->getCategorie() ? $c->getCategorie()->getId() : null,
                    'categorieName' => $c->getCategorie() ? $c->getCategorie()->getName() : null,
                    'id'            => $c->getId(),
                    'slug'          => $c->getSlug(),
                    'name'          => $c->getName(),
                    'status'        => $c->getStatus(),
                    'acceptNewMembers' => $c->getAcceptNewMembers(),
                    'image'         => $c->getImage() ? $this->getImageUrl( $c->getImage()->getImageName() ) : null
                ]
            ];
        }
        $geojson = [
            'type'      => 'FeatureCollection',
            'features'  => $features,
        ];

        return $this->json($geojson);
    }

    private function getImageUrl( string $imageName ) : string
    {

        $dir = str_replace( $this->getParameter('kernel.project_dir') . '/public', '',  $this->getParameter('upload_destination') );
        return $this->urlHelper->getAbsoluteUrl( $dir . $imageName  );
    }
}
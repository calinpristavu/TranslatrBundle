<?php

namespace Evozon\TranslatrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TranslationController
 *
 * @author Ovidiu Enache
 */
class TranslationController extends Controller
{
    /**
     * Extracts all translations from application and creates translations files
     *
     * @Route(name="extract_translations", path="/translations/extract")
     *
     * @param Request $request
     */
    public function extractAction(Request $request)
    {
        return $this->render('test.html.twig', array());
    }

    /**
     * Uploads translations to adapter
     *
     * @Route(name="upload_translations", path="/translations/upload")
     *
     * @param Request $request
     */
    public function uploadAction(Request $request)
    {
        return $this->render('test.html.twig', array());
    }

    /**
     * Downloads translations from adapter
     *
     * @Route(name="download_translations", path="/translations/download")
     *
     * @param Request $request
     */
    public function downloadAction(Request $request)
    {
        return $this->render('test.html.twig', array());
    }
}
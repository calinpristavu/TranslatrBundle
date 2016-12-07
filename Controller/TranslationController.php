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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function extractAction(Request $request)
    {
    }

    /**
     * Uploads translations to adapter
     *
     * @Route(name="upload_translations", path="/translations/upload")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadAction(Request $request)
    {
        $uploader = $this->container->get('uploader');
        $uploader->upload();

        //Mai trebuie sa returnez ceva response si sa fac ajaxul
    }

    /**
     * Downloads translations from adapter
     *
     * @Route(name="download_translations", path="/translations/download")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Request $request)
    {
        $downloader = $this->container->get('downloader');
        $downloader->download();

        //Mai trebuie sa returnez ceva response si sa fac ajaxul
    }
}
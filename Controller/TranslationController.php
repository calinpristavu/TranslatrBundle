<?php

namespace Evozon\TranslatrBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class TranslationController
 *
 * @package     Evozon\TranslatrBundle\Controller
 * @author      Ovidiu Enache <i.ovidiuenache@yahoo.com>
 */
class TranslationController extends Controller
{
    /**
     * Extracts all translations from app and creates .po translations files
     *
     * @Route(name="extract_translations", path="/translations/extract")
     *
     * @return JsonResponse
     */
    public function extractAction()
    {
        $kernel = $this->get('kernel');

        $availableLocales = $this->getParameter('available_locales');
        $availableLocales = array_map(
            function ($locale) {
                return strtolower(substr($locale, 0, 2));
            },
            $availableLocales
        );

        $application = new Application($kernel);
        $application->setAutoExit(false);
        $output = new NullOutput();

        foreach ($availableLocales as $locale) {
            $input = new ArrayInput(array(
                'command' => 'translation:update',
                'locale' => $locale,
                '--force' => true,
                '--output-format' => 'po'
            ));

            try {
                $application->run($input, $output);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false
                ]);
            }
        }

        return new JsonResponse([
            'success' => true
        ]);
    }

    /**
     * Uploads translations to adapter
     *
     * @Route(name="upload_translations", path="/translations/upload")
     *
     * @return JsonResponse
     */
    public function uploadAction()
    {
        try {
            $uploader = $this->container->get('uploader');
            $uploader->upload();
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false
            ]);
        }

        return new JsonResponse([
            'success' => true
        ]);
    }

    /**
     * Downloads translations from adapter
     *
     * @Route(name="download_translations", path="/translations/download")
     *
     * @return JsonResponse
     */
    public function downloadAction()
    {
        try {
            $downloader = $this->container->get('downloader');
            $downloader->download();
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false
            ]);
        }

        return new JsonResponse([
            'success' => true
        ]);
    }
}
<?php


namespace Aecxms\Controller;


use Aecxms\Exception\CmsException;

abstract class AbstractController
{
    private const LAYOUT = 'layout.php';

    /**
     * @var string
     */
    private string $viewDirectory;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->setViewDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $view
     * @param array $params
     * @throws CmsException
     */
    private function loadTemplate(string $view, array $params = [])
    {
        $file   = $this->viewDirectory . $view;
        $layout = $this->viewDirectory . self::LAYOUT;

        if (file_exists($file)) {
            ob_start();
            $content = require($this->viewDirectory . $view);
            $content = ob_get_clean();
            if (file_exists($layout)) {
                require($this->viewDirectory . self::LAYOUT);
            } else {
                throw new CmsException(sprintf('The file %s does not exist in %s', self::LAYOUT, $layout));
            }
        } else {
            throw new CmsException(sprintf('The file %s does not exist in %s', $view, $file));
        }
    }

    /**
     * @param string $view
     * @param array $params
     */
    public function render(string $view, array $params = [])
    {
        try {
            $this->loadTemplate($view, $params);
        } catch (CmsException $e) {
            $errorMessage  = $e->getMessage();
            $errorTemplate = $this->viewDirectory . 'error' . DIRECTORY_SEPARATOR . 'errorView.php';
            $errorViewFile = 'errorView.php';
            require($errorTemplate);
        }
    }

    /**
     * @param string $viewDirectory
     */
    private function setViewDirectory(string $viewDirectory)
    {
        $this->viewDirectory = $viewDirectory;
    }
}
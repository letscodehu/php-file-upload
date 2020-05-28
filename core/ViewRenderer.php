<?php

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ViewRenderer {

    private $basePath;
    private $csrfManager;

    public function __construct(string $basePath, CsrfTokenManagerInterface $csrfManager) {
        $this->basePath = $basePath;
        $this->csrfManager = $csrfManager;
    }

    public function render(ModelAndView $modelAndView) {
        extract($modelAndView->getModel());
        ob_clean();
        $view = $modelAndView->getViewName();
        $_csrfToken = $this->csrfManager->getToken('_csrf')->getValue();
        $_csrf = "<input type='hidden' name='_csrf' value='${_csrfToken}' />";
        require_once $this->basePath."/templates/layout.php";
        return ob_get_clean();
    }

}
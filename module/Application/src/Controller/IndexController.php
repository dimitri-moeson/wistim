<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Translate\TranslateAction;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function localeAction()
    {
        $locale = $this->params("locale");
        
        TranslateAction::getInstance()->setLocale($locale);
        
        return $this->redirect()->toRoute("profile");
    }
}

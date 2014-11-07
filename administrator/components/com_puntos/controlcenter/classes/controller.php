<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class ControlCenterController extends JControllerLegacy {
    private $jversion = '15';

    
    public function __construct($config = array())
    {
        parent::__construct();

  
        if( version_compare( JVERSION, '1.6.0', 'ge' ) ) {
            $this->jversion = '16';
        }

        $basePath = dirname(__FILE__);
        if($this->jversion == '15') {
            $this->_basePath = $basePath;
        } else {
            $this->basePath = $basePath;
        }

        $this->registerDefaultTask('overview');
    }

    public function overview()
    {
        $this->display();
    }

	
    public final function display($cachable = false, $urlparams = array())
    {
        $viewLayout	= JRequest::getCmd( 'layout', 'overview' );

        $view = $this->getThisView();

       
        $view->setLayout($viewLayout);

        $view->display();
    }

    public final function getThisView()
    {
        static $view = null;

        if(is_null($view))
        {
            $basePath = ($this->jversion == '15') ? $this->_basePath : $this->basePath;
            $tPath = dirname(__FILE__).'/tmpl';

            require_once('view.php');
            $view = new ControlCenterView(array('base_path'=>$basePath, 'template_path'=>$tPath));
        }

        return $view;
    }

}

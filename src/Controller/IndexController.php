<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 *
 */
namespace MSBios\CPanel\Controller;

use MSBios\Authentication\AuthenticationServiceAwareTrait;
use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Guard\GuardInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\Redirect;

/**
 * Class IndexController
 * @package MSBios\CPanel\Controller
 */
class IndexController extends AbstractActionController implements
    ActionControllerInterface,
    GuardInterface
{
    use AuthenticationServiceAwareTrait;

    /**
     * IndexController constructor.
     *
     * @param AuthenticationServiceInterface $authenticationService
     */
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->setAuthenticationService($authenticationService);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function loginAction()
    {
        /** @var Redirect $redirect */
        $redirect = $this->redirect();

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->getAuthenticationService();

        if (! $authenticationService->hasIdentity() && $this->getRequest()->isPost()) {

            /** @var  $adapter */
            $adapter = $authenticationService->getAdapter();

            /** @var array $params */
            $params = $this->params()->fromPost();

            $adapter->setIdentity($params['username']);
            $adapter->setCredential($params['password']);

            /** @var Result $authenticationResult */
            $authenticationResult = $authenticationService->authenticate();

            if ($authenticationResult->isValid()) {
                if (! empty($params['redirect'])) {
                    return $redirect->toUrl(base64_decode($params['redirect']));
                }
            }
        }

        return $redirect->toRoute('cpanel');
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();
        return $this->redirect()->toRoute('cpanel');
    }
}

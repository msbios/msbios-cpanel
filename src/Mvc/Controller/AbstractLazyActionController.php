<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use MSBios\CPanel\Exception\RecordNotFoundException;
use MSBios\CPanel\Initializer\LazyControllerAwareInterface;
use MSBios\CPanel\Initializer\LazyControllerAwareTrait;
use Zend\Form\Form;
use Zend\Http\PhpEnvironment\Request;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractLazyActionController
 * @package MSBios\CPanel\Mvc\Controller
 */
abstract class AbstractLazyActionController extends AbstractActionController implements
    ActionControllerInterface,
    LazyControllerAwareInterface
{
    /** @const EVENT_PRE_PERSIST_DATA */
    const EVENT_PRE_PERSIST_DATA = 'pre.persist.data';

    /** @const EVENT_POST_PERSIST_OBJECT */
    const EVENT_POST_PERSIST_DATA = 'post.persist.data';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_PRE_MERGE_DATA = 'pre.merge.data';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_POST_MERGE_DATA = 'post.merge.data';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_PRE_REMOVE_DATA = 'pre.remove.data';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_POST_REMOVE_DATA = 'post.remove.data';

    /** @const EVENT_VALIDATE_ERROR */
    const EVENT_VALIDATE_ERROR = 'validate.error';

    use LazyControllerAwareTrait;

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        // TODO: in future
        // if ($this->getRequest()->isPost()) {
        //
        //     /**
        //      * @var int $id
        //      * @var int $check
        //      */
        //     foreach ($this->params()->fromPost('items') as $id => $check) {
        //         if ($check) {
        //             $this->getEntityManager()->remove(
        //                 $this->getEntityManager()->find($this->getResourceClassName(), $id)
        //             );
        //         }
        //     }
        //
        //     $this->getEntityManager()->flush();
        //     $this->flashMessenger()->addSuccessMessage('Entities has been removed');
        //
        //     return $this->redirect()->toRoute($this->getRouteName());
        // }

        /** @var Paginator $paginator */
        $paginator = (new Paginator($this->getPaginatorAdapter()))
            ->setItemCountPerPage($this->getOptions()->get('item_count_per_page'));

        if ($page = (int)$this->params()->fromQuery('page')) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel([
            'paginator' => $paginator,
            'config' => $this->getOptions()
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        /** @var int $id */
        if ($id = $this->params()->fromRoute('id')) {
            return $this->redirect()->toRoute(
                $this->getRouteName(),
                ['action' => 'add']
            );
        }

        /** @var Form $form */
        $form = $this->getFormElement();

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var Parameters $data */
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getEventManager()->trigger(
                    self::EVENT_PRE_PERSIST_DATA,
                    $this,
                    ['data' => $data]
                );
                $this->persistData($data);

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()->toRoute(
                    $this->getRouteName()
                );
            } else {
                // fire event
                $this->getEventManager()->trigger(
                    self::EVENT_VALIDATE_ERROR,
                    $this,
                    ['form' => $form]
                );
            }
        }

        $form->setAttribute(
            'action',
            $this->url()->fromRoute($this->getRouteName(), ['action' => 'add'])
        );

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var int $id */
        if (! $id = (int)$this->params()->fromRoute('id', 0)) {
            return $this->redirect()->toRoute(
                $this->getRouteName(),
                ['action' => 'add']
            );
        }

        try {
            /** @var array|mixed $row */
            $row = $this->current($id);
        } catch (RecordNotFoundException $ex) {
            return $this->redirect()->toRoute(
                $this->getRouteName(),
                ['action' => 'index']
            );
        }

        /** @var Form $form */
        $form = $this->formElementBindData($row);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var Parameters $parameters */
            $parameters = $request->getPost();
            $form->setData($parameters);

            if ($form->isValid()) {
                $this->getEventManager()->trigger(
                    self::EVENT_PRE_MERGE_DATA,
                    $this,
                    ['data' => $parameters]
                );

                $this->mergeData($parameters, $row);

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()->toRoute(
                    $this->getRouteName()
                );
            } else {
                // fire event
                $this->getEventManager()->trigger(
                    self::EVENT_VALIDATE_ERROR,
                    $this,
                    ['form' => $form, 'object' => $row]
                );
            }
        }

        $form->setAttribute(
            'action',
            $this->url()->fromRoute($this->getRouteName(), ['action' => 'edit', 'id' => $id])
        );

        return new ViewModel([
            'object' => $row, 'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function dropAction()
    {
        /** @var int $id */
        if ($id = $this->params()->fromRoute('id', 0)) {
            $this->getEventManager()->trigger(self::EVENT_PRE_REMOVE_DATA, $this, ['id' => $id]);
            $this->dropData($id);

            $this->flashMessenger()
                ->addSuccessMessage('Entity has been removed');
        }

        return $this->redirect()->toRoute($this->getRouteName());
    }
}

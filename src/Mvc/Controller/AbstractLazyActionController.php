<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Mvc\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use MSBios\CPanel\Exception\RecordNotFoundException;
use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\Form\FormInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractLazyActionController
 * @package MSBios\CPanel\Mvc\Controller
 */
abstract class AbstractLazyActionController extends AbstractActionController implements
    LazyActionControllerInterface,
    EntityManagerAwareInterface,
    FormElementManagerAwareInterface,
    OptionsAwareInterface
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
    const EVENT_PRE_REMOVE_DATA = 'pre.remove.object';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_POST_REMOVE_DATA = 'post.remove.object';

    /** @const EVENT_VALIDATE_ERROR */
    const EVENT_VALIDATE_ERROR = 'validate.error';

    use EntityManagerAwareTrait;
    use FormElementManagerAwareTrait;
    use OptionsAwareTrait;

    /**
     * @return mixed
     */
    protected function getResourceClassName()
    {
        return $this->getOptions()->get('resource_class');
    }

    /**
     * @return mixed
     */
    protected function getRouteName()
    {
        return $this->getOptions()->get('route_name');
    }

    /**
     * @return mixed
     */
    protected function getFormElementName()
    {
        return $this->getOptions()->get('form_element');
    }

    /**
     * @return ElementInterface
     */
    protected function getFormElement()
    {
        return $this->getFormElementManager()
            ->get($this->getFormElementName());
    }

    /**
     * @return DoctrineAdapter
     */
    protected function getPaginatorAdapter()
    {
        // /** @var EntityRepository $entityRepository */
        // $entityRepository = $this->getEntityManager()
        //     ->getRepository($this->getResourceClassName());
        //
        // /** @var QueryBuilder $queryBuilder */
        // $queryBuilder = $entityRepository->createQueryBuilder('resource');
        //
        // return new DoctrineAdapter(new ORMPaginator($queryBuilder));
    }

    /**
     * @param array $values
     */
    protected function persistData(array $values)
    {
        // Do persist data

        ///** @var Entity $entity */
        //$entity = $this->getFormElement()
        //    ->getObject();
        //
        //// fire event
        //$this->getEventManager()
        //    ->trigger(self::EVENT_PERSIST_OBJECT, $this, ['entity' => $entity, 'data' => $data]);
        //
        //$this->getEntityManager()->persist($entity);
        //$this->getEntityManager()->flush();

        $this->getEventManager()->trigger(self::EVENT_POST_PERSIST_DATA, $this, [
            'values' => $values,
            'data' => $this->getFormElement()
                ->getData()
        ]);
    }

    /**
     * @param $row
     * @param Parameters $values
     */
    protected function mergeData($row, Parameters $values)
    {
        // Do merge data

        ///** @var Entity $entity */
        //$entity = $this->getFormElement()
        //    ->getObject();
        //
        //// fire event
        //$this->getEventManager()->trigger(self::EVENT_MERGE_OBJECT, $this, [
        //    'object' => $object,
        //    'entity' => $entity,
        //    'data' => $data
        //]);
        //
        //$this->getEntityManager()->merge($entity);
        //$this->getEntityManager()->flush();

        $this->getEventManager()->trigger(self::EVENT_POST_MERGE_DATA, $this, [
            'row' => $row,
            'values' => $values,
            'data' => $this->getFormElement()
                ->getData()
        ]);
    }

    /**
     * @param $id
     */
    protected function dropData($id)
    {
        // Do remove data
        // // fire event
        // $this->getEventManager()
        //     ->trigger(self::EVENT_REMOVE_OBJECT, $this, ['object' => $row,]);
        //
        // $this->getEntityManager()->remove($row);
        // $this->getEntityManager()->flush();

        $this->getEventManager()->trigger(self::EVENT_POST_REMOVE_DATA, $this, [
            'id' => $id
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    protected function current($id)
    {
        //return $this->getEntityManager()->find(
        //    $this->getResourceClassName(),
        //    (int)$this->params()->fromRoute('id', 0)
        //);

        return [];
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function formElementBindData($data)
    {
        /** @var FormInterface $form */
        return $this->getFormElement()
            ->setData($data);
    }

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
                $this->getRouteName(), ['action' => 'add']
            );
        }

        /** @var Form $form */
        $form = $this->getFormElement();

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var array $data */
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {

                $this->getEventManager()->trigger(self::EVENT_PRE_PERSIST_DATA, $this, ['data' => $data]);
                $this->persistData($data);

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()->toRoute($this->getRouteName());
            } else {
                // fire event
                $this->getEventManager()
                    ->trigger(self::EVENT_VALIDATE_ERROR, $this, ['form' => $form]);
            }
        }

        $form->setAttribute(
            'action', $this->url()->fromRoute($this->getRouteName(), ['action' => 'add'])
        );

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var int $id */
        if (!$id = (int)$this->params()->fromRoute('id', 0)) {
            return $this->redirect()->toRoute(
                $this->getRouteName(), ['action' => 'add']
            );
        }

        try {
            /** @var array|mixed $row */
            $row = $this->current($id);
        } catch (RecordNotFoundException $ex) {
            return $this->redirect()->toRoute(
                $this->getRouteName(), ['action' => 'index']
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

                $this->getEventManager()->trigger(self::EVENT_PRE_MERGE_DATA, $this, ['data' => $parameters]);
                $this->mergeData($row, $parameters);

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()->toRoute($this->getRouteName());
            } else {
                // fire event
                $this->getEventManager()
                    ->trigger(self::EVENT_VALIDATE_ERROR, $this, [
                        'form' => $form, 'object' => $row
                    ]);
            }
        }

        $form->setAttribute(
            'action', $this->url()->fromRoute(
                $this->getRouteName(), ['action' => 'edit', 'id' => $id]
            )
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

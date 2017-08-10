<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Mvc\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use MSBios\CPanel\Config\ControllerInterface as ControllerOptions;
use MSBios\Resource\Entity;
use Zend\Form\Form;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController as DefaultAbstractActionController;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class LazyAbstractActionController
 * @package MSBios\CPanel\Mvc\Controller
 */
abstract class LazyAbstractActionController extends DefaultAbstractActionController implements
    LazyActionControllerInterface
{
    /** @const PERSIST_OBJECT */
    const PERSIST_OBJECT = 'persist.object';

    /** @const MERGE_OBJECT */
    const MERGE_OBJECT = 'merge.object';

    /** @const REMOVE_OBJECT */
    const REMOVE_OBJECT = 'remove.object';

    /** @var EntityManager */
    protected $entityManager;

    /** @var  FormElementManagerV3Polyfill */
    protected $formElementManager;

    /** @var  ControllerOptions */
    protected $options;

    /**
     * @param EntityManager $dem
     * @return $this
     */
    public function setEntityManager(EntityManager $dem)
    {
        $this->entityManager = $dem;
        return $this;
    }

    /**
     * @param FormElementManagerV3Polyfill $formElementManager
     * @return $this
     */
    public function setFormElement(FormElementManagerV3Polyfill $formElementManager)
    {
        $this->formElementManager = $formElementManager;
        return $this;
    }

    /**
     * @param ControllerOptions $options
     * @return $this
     */
    public function setOptions(ControllerOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {

            /**
             * @var int $id
             * @var int $check
             */
            foreach ($this->params()->fromPost('items') as $id => $check) {
                if ($check) {
                    $this->entityManager->remove($this->entityManager->find($this->options->getResourceClass(), $id));
                }
            }

            $this->entityManager->flush();
            $this->flashMessenger()
                ->addSuccessMessage('Entities has been removed');

            return $this->redirect()
                ->toRoute($this->options->getRouteName());
        }

        /** @var EntityRepository $entityRepository */
        $entityRepository = $this->entityManager
            ->getRepository($this->options->getResourceClass());

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityRepository->createQueryBuilder('resource');

        /** @var Paginator $paginator */
        $paginator = (new Paginator(
            new DoctrineAdapter(
                new ORMPaginator($queryBuilder)
            )
        ))->setItemCountPerPage($this->options->getItemCountPerPage());

        /** @var int $page */
        $page = (int)$this->params()->fromQuery('page');
        if ($page) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel([
            'paginator' => $paginator, 'config' => $this->options
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        /** @var int $id */
        if ($id = $this->params()->fromRoute('id')) {
            return $this->redirect()
                ->toRoute($this->options->getRouteName(), ['action' => 'add']);
        }

        /** @var Form $form */
        $form = $this->formElementManager
            ->get($this->options->getFormElement());

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var array $data */
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {

                /** @var Entity $entity */
                $entity = $form->getObject();

                // fire event
                $this->getEventManager()
                    ->trigger(self::PERSIST_OBJECT, $this, ['entity' => $entity, 'data' => $data]);

                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()
                    ->toRoute($this->options->getRouteName());
            }
        }

        $form->setAttribute(
            'action',
            $this->url()
                ->fromRoute($this->options->getRouteName(), ['action' => 'add'])
        );

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        /** @var int $id */
        $id = (int)$this->params()->fromRoute('id', 0);

        if (! $object = $this->entityManager->find(
            $this->options->getResourceClass(),
            $id
        )
        ) {
            return $this->redirect()->toRoute(
                $this->options->getRouteName()
            );
        }

        /** @var Form $form */
        $form = $this->formElementManager->get(
            $this->options->getFormElement()
        )->bind(clone $object);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {

            /** @var array $data */
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {

                /** @var Entity $entity */
                $entity = $form->getObject();

                // fire event
                $this->getEventManager()->trigger(self::MERGE_OBJECT, $this, [
                    'object' => $object,
                    'entity' => $entity,
                    'data' => $data
                ]);

                $this->entityManager->merge($entity);
                $this->entityManager->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()
                    ->toRoute($this->options->getRouteName());
            }
        }

        $form->setAttribute(
            'action',
            $this->url()->fromRoute($this->options->getRouteName(), [
                'action' => 'edit',
                'id' => $id
            ])
        );

        return new ViewModel([
            'object' => $object,
            'form' => $form
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function dropAction()
    {
        /** @var int $id */
        if ($object = $this->entityManager->find(
            $this->options->getResourceClass(),
            $this->params()->fromRoute('id', 0)
        )
        ) {
            // fire event
            $this->getEventManager()->trigger(self::REMOVE_OBJECT, $this, [
                'object' => $object,
            ]);

            $this->entityManager->remove($object);
            $this->entityManager->flush();

            $this->flashMessenger()
                ->addSuccessMessage('Entity has been removed');

            return $this->redirect()
                ->toRoute($this->options->getRouteName());
        }

        return $this->redirect()->toRoute(
            $this->options->getRouteName()
        );
    }
}

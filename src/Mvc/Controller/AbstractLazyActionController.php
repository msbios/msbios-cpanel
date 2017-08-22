<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Mvc\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use MSBios\Resource\Entity;
use MSBios\Stdlib\Object;
use Zend\Form\Form;

use Zend\Http\PhpEnvironment\Request;
use Zend\Paginator\Paginator;
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
    /** @const EVENT_PERSIST_OBJECT */
    const EVENT_PERSIST_OBJECT = 'persist.object';

    /** @const EVENT_MERGE_OBJECT */
    const EVENT_MERGE_OBJECT = 'merge.object';

    /** @const EVENT_REMOVE_OBJECT */
    const EVENT_REMOVE_OBJECT = 'remove.object';

    use EntityManagerAwareTrait;
    use FormElementManagerAwareTrait;
    use OptionsAwareTrait;

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
                    $this->getEntityManager()
                        ->remove($this->getEntityManager()->find(
                            $this->getOptions()->get('resource_class'), $id
                        ));
                }
            }

            $this->getEntityManager()->flush();
            $this->flashMessenger()
                ->addSuccessMessage('Entities has been removed');

            return $this->redirect()
                ->toRoute($this->getOptions()->get('route_name'));
        }

        /** @var EntityRepository $entityRepository */
        $entityRepository = $this->getEntityManager()
            ->getRepository(
                $this->getOptions()
                    ->get('resource_class')
            );

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $entityRepository->createQueryBuilder('resource');

        /** @var Paginator $paginator */
        $paginator = (new Paginator(
            new DoctrineAdapter(
                new ORMPaginator($queryBuilder)
            )
        ))->setItemCountPerPage($this->getOptions()->get('item_count_per_page'));

        /** @var int $page */
        $page = (int)$this->params()->fromQuery('page');
        if ($page) {
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
            return $this->redirect()
                ->toRoute($this->getOptions()->get('route_name'), ['action' => 'add']);
        }

        /** @var Form $form */
        $form = $this->getFormElementManager()
            ->get($this->getOptions()->get('form_element'));

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
                    ->trigger(self::EVENT_PERSIST_OBJECT, $this, ['entity' => $entity, 'data' => $data]);

                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been create');

                return $this->redirect()
                    ->toRoute($this->getOptions()->get('route_name'));
            }
        }

        $form->setAttribute(
            'action',
            $this->url()
                ->fromRoute($this->getOptions()->get('route_name'), ['action' => 'add'])
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

        /** @var Object $object */
        $object = $this->getEntityManager()
            ->find($this->getOptions()->get('resource_class'), $id);

        if (! $object) {
            return $this->redirect()->toRoute(
                $this->getOptions()->get('route_name')
            );
        }

        /** @var Form $form */
        $form = $this->getFormElementManager()->get(
            $this->getOptions()->get('form_element')
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
                $this->getEventManager()->trigger(self::EVENT_MERGE_OBJECT, $this, [
                    'object' => $object,
                    'entity' => $entity,
                    'data' => $data
                ]);

                $this->getEntityManager()->merge($entity);
                $this->getEntityManager()->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Entity has been update');

                return $this->redirect()
                    ->toRoute($this->getOptions()->get('route_name'));
            }
        }

        $form->setAttribute(
            'action',
            $this->url()->fromRoute($this->getOptions()->get('route_name'), ['action' => 'edit', 'id' => $id])
        );

        return new ViewModel([
            'object' => $object, 'form' => $form
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
            $this->getEventManager()->trigger(self::EVENT_REMOVE_OBJECT, $this, [
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

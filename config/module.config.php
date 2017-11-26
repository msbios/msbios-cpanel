<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

$CPANEL = getenv('APPLICATION_CPANEL') ? : 'cpanel';
$CTHEME = getenv('APPLICATION_CTHEME') ? : 'limitless';

return [

    'router' => [
        'routes' => [
            'cpanel' => [
                'type' => Segment::class,
                'options' => [
                    'route' => "/[:locale/]{$CPANEL}[/]",
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',

                        // MSBios\Theme
                        'theme_identifier' => $CTHEME,
                        // 'layout_identifier' => 'limitless'
                    ],
                    'constraints' => [
                        'locale' => '(?i)[a-z]{2,3}(?:_[a-z]{2})?',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'authentication' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => ':action[/]',
                            'constraints' => [
                                'action' => 'login|logout'
                            ]
                        ]
                    ],
                    'layout' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'layout[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\LayoutController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'module' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'module[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\ModuleController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'page-type' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'page-type[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\PageTypeController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'route' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'route[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\RouteController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'setting' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'setting[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\SettingController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ],
                        ]
                    ],
                    'theme' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'theme[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\ThemeController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                Factory\IndexControllerFactory::class,
            Controller\LayoutController::class =>
                InvokableFactory::class,
            Controller\ModuleController::class =>
                InvokableFactory::class,
            Controller\PageTypeController::class =>
                InvokableFactory::class,
            Controller\RouteController::class =>
                InvokableFactory::class,
            Controller\SettingController::class =>
                InvokableFactory::class,
            Controller\ThemeController::class =>
                InvokableFactory::class,
        ],
        'initializers' => [
            new Initializer\LazyControllerInitializer
        ]
    ],

    'form_elements' => [
        'aliases' => [
            Controller\LayoutController::class =>
                \MSBios\Resource\Form\LayoutForm::class,
            Controller\ModuleController::class =>
                \MSBios\Resource\Form\ModuleForm::class,
            Controller\PageTypeController::class =>
                \MSBios\Resource\Form\PageTypeForm::class,
            Controller\RouteController::class =>
                \MSBios\Resource\Form\RouteForm::class,
            Controller\ThemeController::class =>
                \MSBios\Resource\Form\ThemeForm::class,
        ]
    ],

    'service_manager' => [

        'factories' => [
            // // Authentication CPanel Service
            // Authentication\Adapter\ResourceAdapter::class =>
            //     InvokableFactory::class,
            // Authentication\Storage\ResourceStorage::class =>
            //     InvokableFactory::class,
            // Authentication\AuthenticationService::class =>
            //     Factory\AuthenticationServiceFactory::class,

            Module::class =>
                Factory\ModuleFactory::class,
            Navigation\Sidebar::class =>
                Factory\NavigationFactory::class,

            // Listeners
            Listener\ForbiddenListener::class =>
                InvokableFactory::class,
            Listener\TranslatorListener::class =>
                InvokableFactory::class,

            // Widgets
            Widget\AreYouSureDropWidget::class =>
                InvokableFactory::class
        ]
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            ],
        ],
    ],

    'navigation' => [
        Navigation\Sidebar::class => [
            'dashboard' => [
                'label' => _('Dashboard'),
                'route' => 'cpanel',
                'class' => 'icon-home',
                'order' => 100
            ],
            'setting' => [
                'label' => _('System'),
                'uri' => '#',
                'class' => 'icon-gear',
                'order' => 100500,
                'pages' => [
                    'layout' => [
                        'label' => _('Layouts'),
                        'route' => 'cpanel/layout',
                    ],
                    'module' => [
                        'label' => _('Modules'),
                        'route' => 'cpanel/module',
                    ],
                    'page-type' => [
                        'label' => _('Page Types'),
                        'route' => 'cpanel/page-type',
                    ],
                    'route' => [
                        'label' => _('Routes'),
                        'route' => 'cpanel/route',
                    ],
                    'setting' => [
                        'label' => _('Setting'),
                        'route' => 'cpanel/setting',
                    ],
                    'theme' => [
                        'label' => _('Themes'),
                        'route' => 'cpanel/theme',
                    ],
                ]
            ]
        ],
    ],

    \MSBios\Theme\Module::class => [
        'themes' => [
            'limitless' => [
                'identifier' => 'limitless',
                'title' => 'Limitless Application Theme',
                'description' => 'Limitless Application Theme Description',
                'template_map' => [
                    'error/403' => __DIR__ . '/../themes/limitless/view/error/403.phtml'
                ],
                'template_path_stack' => [
                    __DIR__ . '/../themes/limitless/view/',
                ],
                'controller_map' => [
                ],
                'translation_file_patterns' => [
                    [
                        'type' => 'gettext',
                        'base_dir' => __DIR__ . '/../themes/limitless/language/',
                        'pattern' => '%s.mo',
                    ],
                ],
                'widget_manager' => [
                    'template_map' => [
                    ],
                    'template_path_stack' => [
                        __DIR__ . '/../themes/limitless/widget/'
                    ],
                ],
            ],

            'paper' => [
                'identifier' => 'paper',
                'title' => 'Paper CPanel Theme',
                'description' => 'Paper CPanel Theme',
                'template_map' => [
                ],
                'template_path_stack' => [
                    __DIR__ . '/../themes/paper/view/',
                ],
                'controller_map' => [
                ],
                'translation_file_patterns' => [
                    [
                        'type' => 'gettext',
                        'base_dir' => __DIR__ . '/../themes/paper/language/',
                        'pattern' => '%s.mo',
                    ],
                ],
            ]
        ]
    ],

    \MSBios\Guard\Module::class => [
        'resource_providers' => [
            \MSBios\Guard\Provider\ResourceProvider::class => [

                Controller\IndexController::class => [],
                Controller\LayoutController::class => [],
                Controller\ModuleController::class => [],
                Controller\PageTypeController::class => [],
                Controller\RouteController::class => [],
                Controller\SettingController::class => [],
                Controller\ThemeController::class => [],

                'DASHBOARD' => [
                    'SIDEBAR' => [],
                ],
            ],
        ],

        'rule_providers' => [
            \MSBios\Guard\Provider\RuleProvider::class => [
                'allow' => [
                    [['DEVELOPER'], Controller\IndexController::class],
                    [['DEVELOPER'], Controller\LayoutController::class],
                    [['DEVELOPER'], Controller\ModuleController::class],
                    [['DEVELOPER'], Controller\PageTypeController::class],
                    [['DEVELOPER'], Controller\RouteController::class],
                    [['DEVELOPER'], Controller\SettingController::class],
                    [['DEVELOPER'], Controller\ThemeController::class],
                    [['DEVELOPER'], 'SIDEBAR'],
                ],
                'deny' => []
            ]
        ],
    ],

    Module::class => [

        // Layout for authentication view
        'default_layout_authorized' => 'layout/login_simple',

        'listeners' => [
            Listener\TranslatorListener::class => [
                'listener' => Listener\TranslatorListener::class,
                'method' => 'onDispatch',
                'event' => \Zend\Mvc\MvcEvent::EVENT_DISPATCH,
                'priority' => 10,
            ],
            Listener\ForbiddenListener::class => [
                'listener' => Listener\ForbiddenListener::class,
                'method' => 'onDispatchError',
                'event' => \Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR,
                'priority' => -100900,
            ],
        ],
    ],
];

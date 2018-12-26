<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel;

use MSBios\Factory\ModuleFactory;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'cpanel' => [
                'type' => Segment::class,
                'options' => [
                    'route' => "/[:locale/]cpanel[/]",
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                        'theme_identifier' => 'limitless',
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
                Factory\ControllerFactory::class,
            Controller\ModuleController::class =>
                Factory\ControllerFactory::class,
            Controller\PageTypeController::class =>
                Factory\ControllerFactory::class,
            Controller\RouteController::class =>
                InvokableFactory::class,
            Controller\SettingController::class =>
                InvokableFactory::class,
            Controller\ThemeController::class =>
                InvokableFactory::class,
        ],
    ],

    'table_manager' => [
        'aliases' => [
            Controller\LayoutController::class =>
                \MSBios\Resource\Table\LayoutTableGateway::class,
            Controller\ModuleController::class =>
                \MSBios\Resource\Table\ModuleTableGateway::class
        ]
    ],

    'form_elements' => [
        'factories' => [
            Form\SearchForm::class =>
                InvokableFactory::class
        ],
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
            Module::class =>
                ModuleFactory::class,
            Navigation\Sidebar::class =>
                Factory\NavigationFactory::class,

            // Listeners
            ListenerAggregate::class =>
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
                'resource' => Mvc\Controller\SystemControllerInterface::class,
                'pages' => [
                    'layout' => [
                        'label' => _('Layouts'),
                        'route' => 'cpanel/layout',
                        'resource' => Controller\LayoutController::class
                    ],
                    'module' => [
                        'label' => _('Modules'),
                        'route' => 'cpanel/module',
                        'order' => 100,
                        'resource' => Controller\ModuleController::class
                    ],
                    'page-type' => [
                        'label' => _('Page Types'),
                        'route' => 'cpanel/page-type',
                        'order' => 200,
                        'resource' => Controller\PageTypeController::class
                    ],
                    'route' => [
                        'label' => _('Routes'),
                        'route' => 'cpanel/route',
                        'order' => 300,
                        'resource' => Controller\RouteController::class
                    ],
                    'setting' => [
                        'label' => _('Setting'),
                        'route' => 'cpanel/setting',
                        'order' => 400,
                        'resource' => Controller\SettingController::class
                    ],
                    'theme' => [
                        'label' => _('Themes'),
                        'route' => 'cpanel/theme',
                        'order' => 500,
                        'resource' => Controller\ThemeController::class
                    ]
                ]
            ]
        ]
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
                    // ...
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
                    // ...
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
                Mvc\Controller\ActionControllerInterface::class => [
                    Mvc\Controller\SystemControllerInterface::class => [
                        Controller\LayoutController::class,
                        Controller\ModuleController::class,
                        Controller\PageTypeController::class,
                        Controller\RouteController::class,
                        Controller\SettingController::class,
                        Controller\ThemeController::class
                    ]
                ]
            ]
        ],

        'rule_providers' => [
            \MSBios\Guard\Provider\RuleProvider::class => [
                'allow' => [
                    [['DEVELOPER'], Mvc\Controller\ActionControllerInterface::class],
                    // [['DEVELOPER'], Controller\IndexController::class],
                    // [['DEVELOPER'], Controller\LayoutController::class],
                    // [['DEVELOPER'], Controller\ModuleController::class],
                    // [['DEVELOPER'], Controller\PageTypeController::class],
                    // [['DEVELOPER'], Controller\RouteController::class],
                    // [['DEVELOPER'], Controller\SettingController::class],
                    // [['DEVELOPER'], Controller\ThemeController::class],
                ],
                'deny' => [
                    // ...
                ]
            ]
        ],
    ],

    'listeners' => [
        ListenerAggregate::class
    ],

    Module::class => [
        // Layout for authentication view
        'default_layout_authorized' => 'layout/login_simple',
    ],
];

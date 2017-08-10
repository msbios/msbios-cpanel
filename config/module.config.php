<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel;

use Zend\Router\Http\Segment;

return [

    'router' => [
        'routes' => [
            'cpanel' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:locale/]cpanel[/]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                        'locale' => 'en_US',

                        // MSBios\Theme
                        'theme_identifier' => 'limitless',
                        // 'theme_identifier' => 'paper',
                        // 'layout_identifier' => 'limitless'
                    ],
                    'constraints' => [
                        'locale' => '(?i)[a-z]{2,3}(?:_[a-z]{2})?',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
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
                    'resource' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'resource[/]',
                            'defaults' => [
                                'controller' => Controller\ResourceController::class,
                            ],
                        ]
                    ],
                    'role' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'role[/]',
                            'defaults' => [
                                'controller' => Controller\RoleController::class,
                            ],
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
                            'route' => 'setting[/]',
                            'defaults' => [
                                'controller' => Controller\SettingController::class,
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
                            ]
                        ]
                    ],
                    'widget' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'widget[/]',
                            'defaults' => [
                                'controller' => Controller\WidgetController::class,
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],

    'controllers' => [

        'abstract_factories' => [
            // Mvc\Controller\LazyControllerAbstractFactory::class,
        ],

        'factories' => [
            Controller\IndexController::class => Factory\LazyAbstractControllerFactory::class,
            Controller\LayoutController::class => Factory\LazyAbstractControllerFactory::class,
            Controller\ModuleController::class => Factory\LazyAbstractControllerFactory::class,
            Controller\PageTypeController::class => Factory\LazyAbstractControllerFactory::class,
            Controller\RouteController::class => Factory\LazyAbstractControllerFactory::class,
            Controller\ThemeController::class => Factory\LazyAbstractControllerFactory::class,
        ]
    ],

    'service_manager' => [
        'invokables' => [
            Listener\TranslatorListener::class
        ],
        'factories' => [
            Module::class => Factory\ModuleFactory::class,
            Navigation\Sidebar::class => Factory\NavigationFactory::class
        ],
        'aliases' => [
            'translator' => \Zend\I18n\Translator\TranslatorInterface::class
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
                    [
                        'label' => _('Layouts'),
                        'route' => 'cpanel/layout',
                    ], [
                        'label' => _('Modules'),
                        'route' => 'cpanel/module',
                    ], [
                        'label' => _('Page Types'),
                        'route' => 'cpanel/page-type',
                    ], [
                        'label' => _('Routes'),
                        'route' => 'cpanel/route',
                    ], [
                        'label' => _('Setting'),
                        'uri' => '#',
                    ], [
                        'label' => _('Themes'),
                        'route' => 'cpanel/theme',
                    ], [
                        'label' => _('Widgets'),
                        'uri' => '#',
                    ],
                ]
            ]
        ],
    ],

    \MSBios\Theme\Module::class => [
        'themes' => [
            'limitless' => [
                'identifier' => 'limitless',
                'title' => 'Default Application Theme',
                'description' => 'Default Application Theme Descritpion',
                'template_map' => [
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

        'role_providers' => [
            \MSBios\Guard\Provider\RoleProvider::class => [
            ]
        ],

        'resource_providers' => [
            \MSBios\Guard\Provider\ResourceProvider::class => [
                'SIDEBAR' => [
                    'DASHBOARD' => [

                    ],
                ]
            ]
        ],

        'rule_providers' => [
            \MSBios\Guard\Provider\RuleProvider::class => [
                'allow' => [
                    [['DEVELOPER'], 'SIDEBAR'],
                ],
                'deny' => [
                ]
            ]
        ],

        'guard_listeners' => [
            \MSBios\Guard\Listener\ControllerListener::class => [
                [
                    'controller' => Controller\IndexController::class,
                    'action' => 'index',
                    'roles' => ['DEVELOPER']
                ], [
                    'controller' => Controller\LayoutController::class,
                    // 'action' => ['index', 'add', 'drop'],
                    'roles' => ['DEVELOPER']
                ]
            ]
        ]
    ],

    Module::class => [

        'listeners' => [
            [
                'listener' => Listener\TranslatorListener::class,
                'method' => 'onDispatch',
                'event' => \Zend\Mvc\MvcEvent::EVENT_DISPATCH,
                'priority' => 100500,
            ],
        ],

        Config\Config::CONTROLLER => [ // key controller
            Controller\LayoutController::class => [
                'route_name' => 'cpanel/layout',
                'resource_class' => \MSBios\Resource\Entity\Layout::class,
                'form_element' => \MSBios\Resource\Form\LayoutForm::class
            ],
            Controller\ModuleController::class => [
                'route_name' => 'cpanel/module',
                'resource_class' => \MSBios\Resource\Entity\Module::class,
                'form_element' => \MSBios\Resource\Form\ModuleForm::class
            ],
            Controller\PageTypeController::class => [
                'route_name' => 'cpanel/page-type',
                'resource_class' => \MSBios\Resource\Entity\PageType::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\RouteController::class => [
                'route_name' => 'cpanel/route',
                'resource_class' => \MSBios\Resource\Entity\PageType::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\ThemeController::class => [
                'route_name' => 'cpanel/theme',
                'resource_class' => \MSBios\Resource\Entity\Theme::class,
                'form_element' => \MSBios\Resource\Form\ThemeForm::class
            ]
        ]
    ],
];

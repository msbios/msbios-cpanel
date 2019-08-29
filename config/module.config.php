<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel;

use MSBios\Factory\ModuleFactory;
use MSBios\Navigation\Factory\NavigationableFactory;
use Zend\Router\Http\Regex;
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
                    'navigation' => [
                        'type' => Regex::class,
                        'options' => [
                            'regex' => 'navigation.(?<format>(json|xml)?)',
                            'spec' => 'navigation.%format%',
                            'defaults' => [
                                'controller' => Controller\SidebarController::class,
                                'action' => 'index'
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
            Controller\SidebarController::class =>
                Factory\SidebarControllerFactory::class,
        ],
    ],

    'table_manager' => [
        'aliases' => [
            // ...
        ]
    ],

    'form_elements' => [
        'aliases' => [
            // ...
        ]
    ],

    'service_manager' => [

        'factories' => [
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
            //'setting' => [
            //    'label' => _('System'),
            //    'uri' => '#',
            //    'class' => 'icon-gear',
            //    'order' => 100500,
            //    'resource' => Mvc\Controller\SystemControllerInterface::class,
            //    'pages' => [
            //        'layout' => [
            //            'label' => _('Layouts'),
            //            'route' => 'cpanel/layout',
            //            'resource' => Controller\LayoutController::class
            //        ],
            //        'module' => [
            //            'label' => _('Modules'),
            //            'route' => 'cpanel/module',
            //            'order' => 100,
            //            'resource' => Controller\ModuleController::class
            //        ],
            //        'page-type' => [
            //            'label' => _('Page Types'),
            //            'route' => 'cpanel/page-type',
            //            'order' => 200,
            //            'resource' => Controller\PageTypeController::class
            //        ],
            //        'route' => [
            //            'label' => _('Routes'),
            //            'route' => 'cpanel/route',
            //            'order' => 300,
            //            'resource' => Controller\RouteController::class
            //        ],
            //        'setting' => [
            //            'label' => _('Setting'),
            //            'route' => 'cpanel/setting',
            //            'order' => 400,
            //            'resource' => Controller\SettingController::class
            //        ],
            //        'theme' => [
            //            'label' => _('Themes'),
            //            'route' => 'cpanel/theme',
            //            'order' => 500,
            //            'resource' => Controller\ThemeController::class
            //        ]
            //    ]
            //]
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
                Controller\SidebarController::class => [],
                Mvc\Controller\ActionControllerInterface::class => [
                    // Mvc\Controller\SystemControllerInterface::class => [
                    //     Controller\LayoutController::class,
                    //     Controller\ModuleController::class,
                    //     Controller\PageTypeController::class,
                    //     Controller\RouteController::class,
                    //     Controller\SettingController::class,
                    //     Controller\ThemeController::class
                    // ]
                ]
            ]
        ],

        'rule_providers' => [
            \MSBios\Guard\Provider\RuleProvider::class => [
                'allow' => [
                    [['DEVELOPER'], Controller\IndexController::class],
                    [['DEVELOPER'], Controller\SidebarController::class],
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

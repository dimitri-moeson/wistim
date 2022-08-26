<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 25/08/2022
     * Time: 13:08
     */
    
    declare(strict_types=1);
    
    namespace User ;
    
    use Laminas\Router\Http\Literal;
    use Laminas\Router\Http\Segment;
    use Laminas\ServiceManager\Factory\InvokableFactory;

    return [
        
        'router' => [
            'routes' => [
                'sign-up' => [
                   'type' => Literal::class,
                    'options' => [
                        'route' => '/sign-up',
                        'defaults' => [
                            'controller' => Controller\AuthController::class,
                            'action' => 'create'
                        ]
                    ]
                ],
                'sign-in' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/sign-in',
                        'defaults' => [
                            'controller' => Controller\LoginController::class,
                            'action' => 'index'
                        ]
                    ]
                ],
                'profile' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/profile[/:id[/:firstname]]',
                        "constraints" =>[
                            'id' => "[0-9]+",
                            "firstname" => "[a-zA-z][a-zA-z0-9]+",
                        ],
                        'defaults' => [
                            'controller' => Controller\ProfileController::class,
                            'action' => 'index'
                        ]
                    ]
                ],
                'sign-out' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/sign-out',
                        'defaults' => [
                            'controller' => Controller\LogoutController::class,
                            'action' => 'index'
                        ]
                    ]
                ],
            ]
        ],
        
        'controllers' => [
            'factories'=>[
                 Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
                Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
                Controller\LogoutController::class => InvokableFactory::class,
                Controller\ProfileController::class => InvokableFactory::class,
            ]
        ],
        
        'view_manager' => [
            'template_map' =>[
                'auth/create' => __DIR__ . '/../view/user/auth/create.phtml',
                'login/index' => __DIR__ . '/../view/user/auth/login.phtml',
                'profile/index' => __DIR__ . '/../view/user/profile/index.phtml',
            ],
            'template_path_stack' =>[
                'user' => __DIR__.'/../view'
            ]
        ]
    ];
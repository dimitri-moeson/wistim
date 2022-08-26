<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 17:34
     */
    declare(strict_types=1);
    
    namespace User\Controller\Factory;
    
    
    use Laminas\Db\Adapter\Adapter;
    use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
    use Laminas\ServiceManager\Exception\ServiceNotFoundException;
    use Laminas\ServiceManager\Factory\FactoryInterface;
    use Psr\Container\ContainerExceptionInterface;
    use Psr\Container\ContainerInterface;

    use User\Controller\LoginController;
    use User\Model\Table\UsersTable;

    class LoginControllerFactory implements FactoryInterface
    {
    
        /**
         * Create an object
         *
         * @param  string $requestedName
         * @param  null|array<mixed>  $options
         *
         * @return object
         * @throws ServiceNotFoundException If unable to resolve the service.
         * @throws ServiceNotCreatedException If an exception is raised when creating a service.
         * @throws ContainerExceptionInterface If any other error occurs.
         */
        public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
        {
            return new LoginController(
                $container->get(Adapter::class),
                $container->get(UsersTable::class)
            );
        }
    }
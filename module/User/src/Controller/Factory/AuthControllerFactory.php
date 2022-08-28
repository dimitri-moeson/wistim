<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 26/08/2022
     * Time: 13:29
     */
    
    declare(strict_types=1);
    
    namespace User\Controller\Factory;
    
    
    use Interop\Container\ContainerInterface;
    use Interop\Container\Exception\ContainerException;
    use Laminas\Db\Adapter\Adapter;
    use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
    use Laminas\ServiceManager\Exception\ServiceNotFoundException;
    use Laminas\ServiceManager\Factory\FactoryInterface;
    use User\Controller\AuthController;
    use User\Model\Table\UsersTable;

    class AuthControllerFactory implements FactoryInterface
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
         * @throws ContainerException If any other error occurs.
         */
        public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
        {
            return new AuthController(
                $container->get(Adapter::class),
                $container->get(UsersTable::class)
            );
        }
    }
<?php
    /**
     * Created by PhpStorm.
     * User: admin
     * Date: 27/08/2022
     * Time: 12:02
     */
    
    namespace User\Controller\Factory;
    
    
    use Laminas\Db\Adapter\Adapter;
    use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
    use Laminas\ServiceManager\Exception\ServiceNotFoundException;
    use Laminas\ServiceManager\Factory\FactoryInterface;
    use Psr\Container\ContainerExceptionInterface;
    use Psr\Container\ContainerInterface;
    use User\Controller\ProfileController;
    use User\Model\Table\ClassesTable;
    use User\Model\Table\MedicamentsTable;
    use User\Model\Table\MedicamentsUsersTable;
    use User\Model\Table\UsersTable;

    class ProfileControllerFactory implements FactoryInterface
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
            return new ProfileController(
                $container->get(Adapter::class),
                $container->get(UsersTable::class),
                $container->get(MedicamentsUsersTable::class),
                $container->get(MedicamentsTable::class),
                $container->get(ClassesTable::class)
            );
        }
    }
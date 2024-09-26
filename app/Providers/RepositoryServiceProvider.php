<?php

namespace App\Providers;

use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Menu\MenuRepository;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Repositories\AccessControl\AccessControlRepository;
use App\Repositories\AccessControl\AccessControlRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Bill\BillRepository;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Storage\StorageRepository;
use App\Repositories\Storage\StorageRepositoryInterface;
use App\Repositories\Visitor\VisitorRepository;
use App\Repositories\Visitor\VisitorRepositoryInterface;
use App\Repositories\TransaksiBooking\TransaksiBookingRepository;
use App\Repositories\TransaksiBooking\TransaksiBookingRepositoryInterface;
use App\Repositories\Poliklinik\PoliklinikRepository;
use App\Repositories\Poliklinik\PoliklinikRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    /**
     * Bind the interface to an implementation repository class
     */
    public function register() {
        $this->app->bind(
                RoleRepositoryInterface::class,
                RoleRepository::class
        );

        $this->app->bind(
                MenuRepositoryInterface::class,
                MenuRepository::class
        );

        $this->app->bind(
                AccessControlRepositoryInterface::class,
                AccessControlRepository::class
        );

        $this->app->bind(
                UserRepositoryInterface::class,
                UserRepository::class
        );

        $this->app->bind(
                CompanyRepositoryInterface::class,
                CompanyRepository::class
        );

        $this->app->bind(
                CustomerRepositoryInterface::class,
                CustomerRepository::class
        );

        $this->app->bind(
                BillRepositoryInterface::class,
                BillRepository::class
        );

        $this->app->bind(
                StorageRepositoryInterface::class,
                StorageRepository::class
        );
        
        $this->app->bind(
                VisitorRepositoryInterface::class,
                VisitorRepository::class
        );
        
        $this->app->bind(
                TransaksiBookingRepositoryInterface::class,
                TransaksiBookingRepository::class
        );
        
        $this->app->bind(
                PoliklinikRepositoryInterface::class,
                PoliklinikRepository::class
        );
    }

}

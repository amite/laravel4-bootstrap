<?php 

namespace Security;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for permissions
 */
class PermissionsFacade extends Facade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor(){ return 'permissions'; }

}
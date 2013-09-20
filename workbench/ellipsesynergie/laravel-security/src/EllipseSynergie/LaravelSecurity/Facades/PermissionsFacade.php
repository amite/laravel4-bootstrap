<?php namespace EllipseSynergie\LaravelSecurity\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for permission library
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
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
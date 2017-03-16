<?php
namespace Bluforce\StreamchartzApi\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Marko Ellermaa <m.ellermaa@bluforce.at>, bluforce
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Wall
 */
class Wall extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * wallId
     *
     * @var string
     */
    protected $wallId = '';
    
    /**
     * viewId
     *
     * @var string
     */
    protected $viewId = '';
    
    /**
     * Returns the wallId
     *
     * @return string $wallId
     */
    public function getWallId()
    {
        return $this->wallId;
    }
    
    /**
     * Sets the wallId
     *
     * @param string $wallId
     * @return void
     */
    public function setWallId($wallId)
    {
        $this->wallId = $wallId;
    }
    
    /**
     * Returns the viewId
     *
     * @return string $viewId
     */
    public function getViewId()
    {
        return $this->viewId;
    }
    
    /**
     * Sets the viewId
     *
     * @param string $viewId
     * @return void
     */
    public function setViewId($viewId)
    {
        $this->viewId = $viewId;
    }

}
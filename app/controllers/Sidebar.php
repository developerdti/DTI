<?php

declare(strict_types = 1);

namespace app\controllers;

use Throwable;
use app\models\Sidebar as modelSidebar;


/**
 * Performs the side bar generation
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Sidebar{

    /**
     * Generates sidebar
     * @access  public
     * @throws  DataBaseException   If an error occurs during the data base interaction
     * @param   string      $group  belonging customer group
     * @return  string      side bar template      
     */
    public static function creatSidebar(int $kind,?string $group): string
    {
        $template = '';
        
        try {
            if(isset($group) || $kind>1){
                $options = modelSidebar::Sidebar($kind,$group);
                foreach ($options as $key){
                    $template .= <<<EOD
                    <div class = "offcanvas-body__child">
                        <a href="{$key['moduleName']}">
                            {$key['icon']}
                            <p>{$key['descriptionName']}</p>
                        </a>
                    </div>
                    EOD;
                }
                return $template;
            }

            $catalogue = modelSidebar::SidebarCatalogue($kind,$group);
            
            $template = '<div class="accordion accordion-flush" id="sidebar">';
            foreach ($catalogue as $key){
                $template .= <<<EOD
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#sidebar__{$key['sectionName']}" aria-expanded="false" aria-controls="collapseThree">
                                {$key['icon']}
                                {$key['sectionName']}
                            </button>
                        </h2>
                        <div id="sidebar__{$key['sectionName']}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                EOD;
                
                $module = modelSidebar::SidebarCatalogueModules($kind,$key['sectionName']);

                foreach($module as $value){
                    $template .= <<<EOD
                        <div class = "offcanvas-body__child">
                            <a href="{$value['moduleName']}">
                                {$value['icon']}
                                <p>{$value['descriptionName']}</p>
                            </a>
                        </div>
                        EOD;
                }
                $template .= <<<EOD
                            </div>
                        </div>
                    </div>
                EOD;
            }
            
            $template .= '</div>';
        return $template;
        } catch (Throwable $th) {
            echo $th->getmessage();
            echo $th->getPrevious();
        }
    }
}
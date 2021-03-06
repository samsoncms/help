<?php
namespace samsoncms\app\help;

/**
 * SamsonCMS generic help application
 * This application serves basic help system and approach to
 * all other possible applications and services.
 *
 * @package samson\cms\web\help
 */
class Application extends \samsoncms\Application
{
    /** Application name */
    public $name = 'Помощь';

    /** Application icon*/
    public $icon = 'question';

    /** Identifier */
    protected $id = 'help';

    /**
     * Event handler for rendering main menu sub menu
     * @param string $html Main menu HTML
     * @param string $subMenu Sub menu HTML
     */
    public function subMenuHandler(&$html, &$subMenu, $category, $subCategory, $subSubCategory)
    {
        $subMenu = '<li><a href="#top">'.t('В начало', true).'</a></li>';

        // Fire event when application help is rendered
        \samsonphp\event\Event::fire(
            'help.submenu.rendered',
            array(&$subMenu, $this, $category, $subCategory, $subSubCategory)
        );
    }

    /** Universal controller action */
    public function __handler($category = null, $subCategory = null, $subSubCategory = null)
    {
        // Render menu and current controller parameters
        \samsonphp\event\Event::subscribe(
            'template.menu.rendered',
            array($this, 'subMenuHandler'),
            array($category, $subCategory, $subSubCategory)
        );

        $html = '';

        // Fire event when application help is rendered
        \samsonphp\event\Event::fire(
            'help.content.rendered',
            array(&$html, $category, $subCategory, $subSubCategory, $this)
        );

        // Prepare view
        $this->view('index')
            ->title(t($this->name, true))
        ->set('content', $html);
    }
}
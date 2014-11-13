<?php
/**
 *
 * @package Quickedit
 * @copyright (c) 2014 Marc Alexander ( www.m-a-styles.de )
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace marc\quickedit\tests\functional;

/**
* @group functional
*/
class quickedit_acp_test extends \phpbb_functional_test_case
{
	/**
	* Define the extensions to be tested
	*
	* @return array vendor/name of extension(s) to test
	* @access static
	*/
	static protected function setup_extensions()
	{
		return array('marc/quickedit');
	}

	public function setUp()
	{
		parent::setUp();

		$this->add_lang_ext('marc/quickedit', array('quickedit_acp'));
	}

	public function test_quickedit_not_enabled()
	{
		$this->login();
		$this->admin_login();
		$crawler = self::request('GET', 'adm/index.php?icat=6&mode=manage&parent_id=1&f=2&action=edit&sid=' . $this->sid);
		$form = $crawler->selectButton('Submit')->form();
		$form_values = $form->getValues();
		$this->assertArrayHasKey('enable_quick_edit', $form_values);
		$this->assertEquals('0', $form_values['enable_quick_edit']);
	}

	/**
	* Test if quickedit settings are available
	*
	* @dependsOn test_quickedit_not_enabled
	* @access public
	*/
	public function test_board_features_quickedit()
	{
		$this->login();
		$this->admin_login();
		$crawler = self::request('GET', 'adm/index.php?i=acp_board&mode=features&sid=' . $this->sid);
		$this->assertContainsLang('ALLOW_QUICK_EDIT', $crawler->text());

		$form = $crawler->selectButton('allow_quick_edit_enable')->form();
		$form_values = $form->getValues();
		$this->assertArrayHasKey('config[allow_quick_edit]', $form_values);
		$this->assertEquals('0', $form_values['config[allow_quick_edit]']);
		$form->setValues(array(
			'config[allow_quick_edit]'	=> true,
		));
		$crawler = self::submit($form);
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->text());

		$crawler = self::request('GET', 'adm/index.php?i=acp_board&mode=features&sid=' . $this->sid);
		$form = $crawler->selectButton('allow_quick_edit_enable')->form();
		$form_values = $form->getValues();
		$this->assertArrayHasKey('config[allow_quick_edit]', $form_values);
		$this->assertEquals('1', $form_values['config[allow_quick_edit]']);
	}

	/**
	 * @dependsOn test_board_features_quickedit
	 */
	public function test_quickedit_is_enabled()
	{
		$this->login();
		$this->admin_login();
		$crawler = self::request('GET', 'adm/index.php?icat=6&mode=manage&parent_id=1&f=2&action=edit&sid=' . $this->sid);
		$form = $crawler->selectButton('Submit')->form();
		$form_values = $form->getValues();
		$this->assertArrayHasKey('enable_quick_edit', $form_values);
		$this->assertEquals('1', $form_values['enable_quick_edit']);
	}
}

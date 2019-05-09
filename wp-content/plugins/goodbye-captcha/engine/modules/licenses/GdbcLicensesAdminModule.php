<?php
/**
 * Copyright (C) 2015 Mihai Chelaru
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class GdbcLicensesAdminModule extends GdbcBaseAdminModule
{
	protected function __construct()
	{
		parent::__construct();
		
		add_action( 'admin_init', array($this, 'checkForModuleUpdates'), 0 );
		
		//set_site_transient( 'update_plugins', null );
		
	}
	
	
	public function checkForModuleUpdates()
	{
		foreach(GdbcModulesController::getLicensedModuleNames() as $moduleName)
		{
			if(!GdbcModulesController::isModuleRegistered($moduleName))
				continue;
			
			if(GdbcModulesController::isModuleIncludedInProBundle($moduleName)){
				continue;
			}
			
			$moduleMainClassName = GdbcModulesController::getModuleStandAloneClassName($moduleName);
			
			if( !defined("$moduleMainClassName::MODULE_VERSION") )
				continue;
			
			$classReflector = new ReflectionClass($moduleMainClassName);
			
			$licenseKey = $this->getOption($moduleName);
			$pluginUpdater = new MchGdbcPluginUpdater(GoodByeCaptcha::PLUGIN_SITE_URL, $classReflector->getFileName(), array(
				'version' 	=> constant("$moduleMainClassName::MODULE_VERSION"),
				'item_name' => GdbcModulesController::getModuleDisplayName($moduleName),
				'license' 	=> $licenseKey,
				'url'       => home_url(), 'author' => 'MihChe'
			));
			
			
			add_action( 'in_plugin_update_message-' . plugin_basename( $classReflector->getFileName() ), function($pluginData, $versionInfo) use($licenseKey, $pluginUpdater){
				
				if(empty($licenseKey))
				{
					echo '&nbsp;<strong><a href="' . esc_url( admin_url( 'admin.php?page=wp-bruiser-licenses' ) ) . '">' . __( 'Enter valid license key for automatic updates.', 'goodbye-captcha' ) . '</a></strong>';
				}
				else
				{
					$arrCachedVersionInfo = (array)$pluginUpdater->get_cached_version_info();
					if(!empty($arrCachedVersionInfo['license_checked']) && !empty($arrCachedVersionInfo['license_status']) && $licenseKey === $arrCachedVersionInfo['license_checked'])
					{
						if(!empty($arrCachedVersionInfo['license_renew_url']) && 'expired' === $arrCachedVersionInfo['license_status'])
						{
							echo '&nbsp;<strong><a style="color: #a00" href="' . esc_url( $arrCachedVersionInfo['license_renew_url'] ) . '">' . __( 'Your license is expired. Renew your license', 'goodbye-captcha' ) . '</a></strong>';
						}
					}
				}
				
			}, 10, 2 );
			
			
			
		}
		
	}
	
	public function getDefaultOptions()
	{
		static $arrDefaultSettingOptions = null;
		if(null !== $arrDefaultSettingOptions)
			return $arrDefaultSettingOptions;
		
		$arrDefaultSettingOptions = array();
		
		foreach(GdbcModulesController::getLicensedModuleNames() as $moduleName)
		{
			if(!GdbcModulesController::isModuleRegistered($moduleName)) {
				continue;
			}
			
			if(GdbcModulesController::isModuleIncludedInProBundle($moduleName)){
				continue;
			}
			$arrDefaultSettingOptions[$moduleName] = array(
				//'Id' => ++$modulesCounter,
				'Value' => null,
				'LabelText' => GdbcModulesController::getModuleDisplayName($moduleName) . ' ' . __('License', GoodByeCaptcha::PLUGIN_SLUG),
				'InputType'  => MchGdbcHtmlUtils::FORM_ELEMENT_INPUT_TEXT
			);
		}
		
		return $arrDefaultSettingOptions;
		
	}
	
	
	public  function validateModuleSettingsFields($arrSettingOptions)
	{
		
		$arrSettingOptions = array_map('sanitize_text_field', (array)$arrSettingOptions);
		$arrSettingOptions = array_map('trim', (array)$arrSettingOptions);
		$arrSettingOptions = array_filter((array)$arrSettingOptions);
		
		$errorEncountered = false;
		foreach($arrSettingOptions as $moduleName => $licenseKey)
		{
			if(!GdbcModulesController::isModuleRegistered($moduleName))
				continue;
			
			if( ! $this->activateLicense($moduleName, $licenseKey) )
			{
				$errorEncountered = true;
				//unset($arrSettingOptions[$moduleName]);
			}
			
		}
		
		set_site_transient( 'update_plugins', null );
		
		if($errorEncountered || empty($arrSettingOptions))
		{
			$this->registerErrorMessage(__('There was an error while activating your license!', GoodByeCaptcha::PLUGIN_SLUG));
		}
		else
		{
			$this->registerSuccessMessage(__('Your license was successfully activated!', GoodByeCaptcha::PLUGIN_SLUG));
		}
		
		
		return $arrSettingOptions;
	}
	
	
	private function activateLicense($moduleName, $licenseKey)
	{
		$moduleName = GdbcModulesController::getModuleDisplayName($moduleName, true);
		$licenseRequestParams = array(
			'edd_action' => 'activate_license',
			'license'    => $licenseKey,
			'item_name'  => urlencode($moduleName),
			'url'        => home_url()
		);
		
		$response = wp_remote_post( GoodByeCaptcha::PLUGIN_SITE_URL, array('timeout'   => 15, 'sslverify' => false, 'body' => $licenseRequestParams));
		
		if ( is_wp_error( $response ) ) {
			return false;
		}
		
		$licenseInfo = @json_decode( @wp_remote_retrieve_body( $response ) );
		
		return !empty($licenseInfo->success) && !empty($licenseInfo->license) && $licenseInfo->license === 'valid';
		
	}
	
	public  function renderModuleSettingsSectionHeader(array $arrSectionInfo)
	{
		//echo '<h4>' . __('Activate licenses for the following extensions:', GoodByeCaptcha::PLUGIN_SLUG) . '</h4>';
	}
	
	public function getFormattedBlockedContent(GdbcAttemptEntity $attemptEntity)
	{
		return null;
	}
	
	public static function getInstance()
	{
		static $adminInstance = null;
		return null !== $adminInstance ? $adminInstance : $adminInstance = new self();
	}
	
}
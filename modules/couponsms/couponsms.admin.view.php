<?php

class couponsmsAdminView extends couponsms
{
	function init()
	{
		$this->setTemplatePath($this->module_path . 'tpl');
		$this->setTemplateFile(strtolower(str_replace('dispCouponsmsAdmin', '', $this->act)));
	}

	function dispCouponsmsAdminIndex()
	{
		$oCouponsmsModel = getModel('couponsms');
		$output = $oCouponsmsModel->getCouponList();
		$couponsms_list = $output->data;

		Context::set('coupon_list', $couponsms_list);
	}

	function dispCouponsmsAdminCouponUser()
	{
		$couponsms_srl = Context::get('couponsms_srl');
		$coupon_config = getModel('couponsms')->getCouponConfig($couponsms_srl);

		Context::set('coupon_config', $coupon_config->data);
	}

	function dispCouponsmsAdminCouponDelete()
	{
		$couponsms_srl = Context::get('couponsms_srl');
		$coupon_config = getModel('couponsms')->getCouponConfig($couponsms_srl);

		Context::set('coupon_config', $coupon_config->data);
	}

	function dispCouponsmsAdminCouponInsert()
	{
		$couponsms_srl = Context::get('couponsms_srl');

		$oCouponsmsModel = getModel('couponsms');

		$oMemberModel = getModel('member');
		$group_list = $oMemberModel->getGroups();

		$db_info = Context::getDBInfo();
		$default_url = $db_info->default_url;

		if($couponsms_srl)
		{
			$couponsms_config = $oCouponsmsModel->getCouponConfig($couponsms_srl)->data;
			$couponsms_config_group = unserialize($couponsms_config->group_srl);
		}

		Context::set('default_url', $default_url);
		Context::set('couponsms_config_group', $couponsms_config_group);
		Context::set('couponsms_config', $couponsms_config);
		Context::set('group_list', $group_list);
	}

	function dispCouponsmsAdminSetting()
	{
		$oModuleModel = getModel('module');
		$oLayoutModel = getModel('layout');
		$oCouponsmsModel = getModel('couponsms');

		$member_config = getModel('member')->getMemberConfig();
		$variable_name = array();
		foreach($member_config->signupForm as $item)
		{
			if($item->type == 'tel')
			{
				$variable_name[] = $item->name;
			}
		}
		Context::set('variable_name', $variable_name);

		$config = $oCouponsmsModel->getConfig();
		Context::set('config', $config);
		$layout_list = $oLayoutModel->getLayoutList();
		Context::set('layout_list', $layout_list);

		$mobile_layout_list = $oLayoutModel->getLayoutList(0, 'M');
		Context::set('mlayout_list', $mobile_layout_list);

		$skin_list = $oModuleModel->getSkins($this->module_path);
		Context::set('skin_list', $skin_list);

		$mskin_list = $oModuleModel->getSkins($this->module_path, "m.skins");
		Context::set('mskin_list', $mskin_list);
	}

	function dispCouponsmsAdminCouponList()
	{
		$output = executeQueryArray('couponsms.getCouponUserList');
		$coupon_user = $output->data;

		Context::set('page', $output->page);
		Context::set('total_page', $output->total_page);
		Context::set('total_count', $output->total_count);
		Context::set('coupon_user', $coupon_user);
		Context::set('page_navigation', $output->page_navigation);
	}

	function dispCouponsmsAdminHistory()
	{
		$args = new stdClass();
		$args->page = Context::get('page');
		$args->list_count = '20';
		$args->page_count = '10';
		$output = executeQueryArray('couponsms.getHistory', $args);

		Context::set('history_data', $output->data);
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);
	}
}

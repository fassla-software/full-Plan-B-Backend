<?php

use App\Helper\ModuleMetaDataForPlugin;
use Modules\PluginManage\Http\Helpers\PluginJsonFileHelper;

function pluginObject()
{
    return (new ModuleMetaDataForPlugin());
}

function getAllExternalMenu()
{
    return (new ModuleMetaDataForPlugin())->getAllExternalMenu();
}

function getAllExternalPaymentGatewayMenu()
{
    return pluginObject()->getAllExternalPaymentGatewayMenu();
}

function getExternalPaymentGateway()
{
    return pluginObject()->getExternalPaymentGateway();
}

function getAllPaymentGatewayList()
{
    return pluginObject()->getAllPaymentGatewayList();
}

function getAllPaymentGatewayListWithImage()
{
    return pluginObject()->getAllPaymentGatewayListWithImage();
}

/**
 * @param $imageName
 * @param $moduleName
 * @return string
 */
function renderPaymentGatewayImage($imageName, $moduleName): string
{
    return pluginObject()->renderPaymentGatewayImage($imageName, $moduleName);
}

function getPaymentGatewayImagePath($gateway_slug)
{
    return pluginObject()->getPaymentGatewayImagePath($gateway_slug);
}

function renderAllPaymentGatewayExtraInfoBlade()
{
    return pluginObject()->renderAllPaymentGatewayExtraInfoBlade();
}

/**
 * @param $payment_gateway_name
 * @return mixed
 */
function getChargeCustomerMethodNameByPaymentGatewayNameSpace($payment_gateway_name): mixed
{
    return pluginObject()->getChargeCustomerMethodNameByPaymentGatewayNameSpace($payment_gateway_name);
}

/**
 * @param $payment_gateway_name
 * @return mixed
 */
function getChargeCustomerMethodNameByPaymentGatewayName($payment_gateway_name): mixed
{
    return pluginObject()->getChargeCustomerMethodNameByPaymentGatewayName($payment_gateway_name);
}


function loadPaymentGatewayLogo($moduleName, $gatewayName)
{
    return route('payment.gateway.logo', [$moduleName, $gatewayName]);
}

function isPluginActive($moduleName)
{
    return (new PluginJsonFileHelper($moduleName))->isPluginActive();
}

function renderHeadStartHooks()
{
    return pluginObject()->renderHeadStartHooks();
}
function renderHeadEndHooks()
{
    return pluginObject()->renderHeadEndHooks();
}
function renderBodyStartHooks()
{
    return pluginObject()->renderBodyStartHooks();
}
function renderBodyEndHooks()
{
    return pluginObject()->renderBodyEndHooks();
}

<?php

namespace App\Helper;
// "settingsBlade": "AzulPaymentGateway::backend.azulpaymentgateway",
class ModuleMetaData
{
    public function __construct(public ?string $moduleName = null)
    {

    }

    public function paymentGatewayData(){
        $allMetaData = $this->getMetaData();
        if (property_exists($allMetaData,'xilancerMetaData')){
            // check payment meta is available or not
            $metaInstance = $allMetaData->xilancerMetaData;
            return $this->getPaymentMetaInfo($metaInstance);
        }
        return null;
    }

    private function getMetaData()
    {
        if (moduleExists($this->moduleName)){
            return $this->getIndividualModuleMetaData($this->moduleName);
        }

        return null;
    }

    public function renderAllPaymentGatewaySettingsBlade(){
        // return blade partials to render it in
        $outputMarkup = '';
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo){
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            foreach ($paymentMeta as $inPay){
                if (property_exists($inPay,'settingsBlade')){
                    if (view()->exists($inPay->settingsBlade)){
                        $outputMarkup .= view($inPay->settingsBlade)->render();
                    }
                }
            }
        }
        return $outputMarkup;
    }
    public function getChargeCustomerMethodNameByPaymentGatewayName($gateway){
        // return blade partials to render it in
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo){
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            if ($gateway !== current($paymentMeta)->name){
                continue;
            }
            return current($paymentMeta)->chargeCustomerMethod;
        }
        return '';
    }

    public function renderAllPaymentGatewayExtraInforBlade(){
        // return blade partials to render it in
        $outputMarkup = '';
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo){
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            foreach ($paymentMeta as $inPay){
                if (property_exists($inPay,'extraInfoMarkupBlade')){
                    if (view()->exists($inPay->extraInfoMarkupBlade)){
                        $outputMarkup .= view($inPay->extraInfoMarkupBlade)->render();
                    }
                }
            }
        }
        return $outputMarkup;
    }

    public function saveAllPaymentGatewaySettings(){
        $outputMarkup = [];
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo){
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            foreach ($paymentMeta ?? [] as $inPay){
                if (property_exists($inPay,'settingsData')){
                    $outputMarkup[] = $inPay->settingsData;
                }
            }
        }
        return $outputMarkup;
    }

    public function getAllPaymentGatewayList(){
        $outputMarkup = [];
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo){
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
                foreach ($paymentMeta as $inPay){
                    if (property_exists($inPay,'name')){
                        $outputMarkup[] = $inPay->name;
                    }
                }
        }
        return $outputMarkup;
    }

    private function getPaymentMetaInfo($metaInstance){
        if(property_exists($metaInstance,'paymentGateway')){
            return $metaInstance->paymentGateway;
        }
    }

//    public function getAllMetaData(){
//        $allModuleMeta = [];
//        $allDirectories = glob(base_path().'/Modules/*',GLOB_ONLYDIR);
//        foreach ($allDirectories as $dire){
//            // scan all the json file
//            $currFolderName = pathinfo($dire,PATHINFO_BASENAME);
//            $metaInformation = $this->getIndividualModuleMetaData($currFolderName);
//            if (property_exists($metaInformation,'xilancerMetaData')){
//                $allModuleMeta[$currFolderName] = $metaInformation->xilancerMetaData;
//            }
//        }
//        return $allModuleMeta;
//    }

    public function getAllMetaData()
    {
        $allModuleMeta = [];
        $allDirectories = glob(base_path() . '/Modules/*', GLOB_ONLYDIR);
        $modules_status_data = [];
        if (file_exists(base_path() . "/modules_statuses.json") && !is_dir(base_path() . "/modules_statuses.json")) {
            $modules_status_data = json_decode(file_get_contents(base_path() . "/modules_statuses.json"), true);
        }

        foreach ($allDirectories as $dire) {
            //todo scan all the json file
            $currFolderName = pathinfo($dire, PATHINFO_BASENAME);
            $metaInformation = $this->getIndividualModuleMetaData($currFolderName);

            //did not collect  meta info of the module which is disabled from module_status.json file
            if (!array_key_exists($currFolderName, $modules_status_data)) {
                continue;
            }

            if (
                array_key_exists($metaInformation->name, $modules_status_data)
                && isset($modules_status_data[$metaInformation->name])
                && $modules_status_data[$metaInformation->name] === false
            ) {
                continue;
            }

            if (property_exists($metaInformation, 'xilancerMetaData')) {
                $allModuleMeta[$currFolderName] = $metaInformation->xilancerMetaData;
                $allModuleMeta[$currFolderName]->alias = $metaInformation->alias;
            }
        }

        return $allModuleMeta;
    }


    private function getIndividualModuleMetaData(string $moduleName,bool $returnType = false){
        $filePath =  module_path($moduleName).'/module.json';
        if (file_exists($filePath) && !is_dir($filePath)){
            return json_decode(file_get_contents($filePath),$returnType);
        }
    }

    //for external menu
    public function getAllExternalMenu()
    {
        $allModuleMeta = $this->getAllMetaData();
        return $this->getEachMenu($allModuleMeta);
    }

    private function getEachMenu($allModuleMeta)
    {
        $menuList = [];
        if (!empty($allModuleMeta)) {
            foreach ($allModuleMeta ?? [] as $metaData) {

                $adminSettings = $this->getAdminSettings($metaData);
                $adminSettings = is_array($adminSettings) ? (object)$adminSettings : $adminSettings;
                $menuItem = $this->getAdminMenuSettings($adminSettings);
                if (!empty((array)$menuItem)) {
                    $menuList[] = $menuItem;
                }
            }
        }
        return $menuList;
    }

    public function getAdminSettings($metaData)
    {
        $adminSettings = [];
        if (property_exists($metaData, 'admin_settings')) {
            $adminSettings = $metaData->admin_settings;
        }

        return $adminSettings;
    }

    public function getAdminMenuSettings($adminSettings)
    {
        $menuItem = [];
        $adminSettings = is_array($adminSettings) ? (object)$adminSettings : $adminSettings;
        if (property_exists($adminSettings, 'menu_item') && !empty($adminSettings->menu_item)) {
            $menuItem = $adminSettings->menu_item;
        }

        return $menuItem;
    }

    public function getCustomPageBuilderAddonList(): array
    {
        $addonNames = [];
        $addonList = $this->getPageBuilderAddonList();
        if (!empty($addonList)) {
            foreach ($addonList as $item) {
                $addonNames[] = $item['addon'];
            }
        }

        return $addonNames;
    }

    public function getPageBuilderAddonList()
    {
        $addonNames = [];
        $allModuleMeta = $this->getAllMetaData();

        if (!empty($allModuleMeta)) {
            $addonNames = $this->getOnlyPageBuilder($allModuleMeta);
        }

        return $addonNames;
    }

    private function getOnlyPageBuilder($allModuleMeta)
    {
        $classList = [];
        foreach ($allModuleMeta as $eachModuleMeta) {
            if (property_exists($eachModuleMeta, 'pageBuilderAddon')) {
                $pageBuilderAddon = $eachModuleMeta->pageBuilderAddon;
                if (!empty($pageBuilderAddon)) {
                    foreach ($pageBuilderAddon as $key => $item) {
                        if (!empty($item)) {
                            if (!property_exists($item, 'addon')) {
                                continue;
                            }

                            $classList[][$key] = [
                                "addon" => $this->addonPath($item->addon),
                            ];
                        }
                    }
                }
            }
        }

        return array_merge(...$classList);
    }

    private function addonPath($widgetBuilderAddon)
    {
        $addonNames = "";
        if (file_exists(str_replace('\\', '/', base_path($widgetBuilderAddon)) . '.php')) {
            $addonNames = $widgetBuilderAddon;
        }

        return $addonNames;
    }
}


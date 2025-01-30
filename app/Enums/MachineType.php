<?php

namespace App\Enums;

enum MachineType: string
{
    // Heavy Equipment Rental (تأجير المعدات الثقيلة)
    case heavyEquipment = 'heavyEquipment'; // تأجير المعدات الثقيلة
    case loader = 'loader'; // لودر
    case excavator = 'excavator'; // حفار
    case backhoeLoader = 'backhoeLoader'; // باك لودر
    case bulldozer = 'bulldozer'; // بلدوزر
    case grader = 'grader'; // جريدر
    case harrow = 'harrow'; // هراس
    case asphaltScraper = 'asphaltScraper'; // مكشطة أسفلت
    case bitumenSprayerTruck = 'bitumenSprayerTruck'; // شاحنة رش البيتومين
    case finisher = 'finisher'; // فنشر
    case telehandler = 'telehandler'; // تليهندلر
    case forklift = 'forklift'; // فورك ليفت
    case agriculturalTractor = 'agriculturalTractor'; // جرار زراعي
    case equipmentTransportFlatbed = 'equipmentTransportFlatbed'; // سطحة لنقل المعدات

    // Vehicle Rental (تأجير السيارات)
    case vehicleRental = 'vehicleRental'; // تأجير السيارات
    case trailer = 'trailer'; // تريللا
    case oneEighthTonTruck = 'oneEighthTonTruck'; // سيارة نقل 1/8
    case oneQuarterTonTruck = 'oneQuarterTonTruck'; // سيارة نقل 1/4
    case oneThirdTonTruck = 'oneThirdTonTruck'; // سيارة نقل 1/3 (جامبو)
    case oneHalfTonTruck = 'oneHalfTonTruck'; // سيارة نقل 1/2 (شاسيه)
    case potableWaterTanker = 'potableWaterTanker'; // سيارة تانك نقل مياه شرب
    case constructionWaterTanker = 'constructionWaterTanker'; // سيارة تانك نقل مياه لأعمال الإنشاءات
    case petroleumMaterialTanker = 'petroleumMaterialTanker'; // سيارة تانك نقل مواد بترولية
    case soilTipper = 'soilTipper';

    // Cranes and Lifting Equipment Rental (تأجير الأوناش والرافعات)
    case craneRental = 'craneRental'; // تأجير الأوناش والرافعات
    case telescopicCrane = 'telescopicCrane'; // ونش تلسكوبي
    case truckMountedCrane = 'truckMountedCrane'; // شاحنة مزودة بونش
    case towerCrane = 'towerCrane'; // ونش أبراج
    case forkliftCrane = 'forkliftCrane'; // ونش سلّة
    case furnitureMovingCrane = 'furnitureMovingCrane'; // ونش رفع الأثاث

    // Accommodation and Living Services (خدمات الإعاشة)
    case accommodationServices = 'accommodationServices'; // خدمات الإعاشة
    case furnishedAccommodation = 'furnishedAccommodation'; // تأجير سكن مفروش للعمال والموظفين
    case mealCatering = 'mealCatering'; // توريد وجبات
    case potableWaterSupply = 'potableWaterSupply'; // توريد مياه شرب
    case constructionWaterSupply = 'constructionWaterSupply'; // توريد مياه لأعمال الإنشاءات
    case solidWasteDisposal = 'solidWasteDisposal'; // رفع مخلفات صلبة
    case sewageDisposalSanitary = 'sewageDisposalSanitary'; // رفع مياه صرف صحي
    case sewageDisposalIndustrial = 'sewageDisposalIndustrial'; // رفع مياه صرف صناعي

    // Generators and Tools Rental (تأجير المولدات والمعدات الخفيفة)
    case generatorRental = 'generatorRental'; // تأجير المولدات والمعدات الخفيفة
    case electricGenerator = 'electricGenerator'; // مولد كهرباء
    case soilCompactor = 'soilCompactor'; // دكاك تربة
    case concreteMixer = 'concreteMixer'; // خلاطة
    case concreteVibrator = 'concreteVibrator'; // هزاز خرسانة
    case airCompressor = 'airCompressor'; // ماكينة ضغط الهواء (كومبريسور)
    case buildingMaterialCrane = 'buildingMaterialCrane'; // ونش رفع مواد البناء

    // Scaffolding and Construction Tools (أدوات سحب الكابلات)
    case scaffoldingTools = 'scaffoldingTools'; // تأجير السقالات وأدوات البناء
    case roofPouringScaffolds = 'roofPouringScaffolds'; // شدات صب السقف
    case wallPanelScaffolds = 'wallPanelScaffolds'; // سقالات الحوائط والجدران
    case cablePullingWinches = 'cablePullingWinches'; // بكرة سحب الكابلات
    case cablePullingRollers = 'cablePullingRollers'; // درفيل (Roller)
    case cableCutters = 'cableCutters'; // مقص كابلات
    case hydraulicCrimpers = 'hydraulicCrimpers'; // مكبس أكواس

    // Cable Tools and Miscellaneous (أدوات سحب الكابلات)
    case cableTools = 'cableTools'; // أدوات سحب الكابلات
    case cablePullingSockets = 'cablePullingSockets'; // سوستة سحب الكابلات داخل المواسير
    case cableRollers = 'cableRollers'; // درفيل
    case hydraulicPresses = 'hydraulicPresses'; // مكبس هيدروليكي

    // Add the values() method to get all enum values
    public static function values(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
